<?php

namespace Ipe\Sdk;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Log;
use Ipe\Sdk\Exceptions\SmsException;

class SmsIr
{
    protected $client;

    public function __construct($apiKey, $baseUri)
    {
        $this->client = new Client([
            'base_uri' => rtrim($baseUri, '/') . '/',
            'headers' => [
                'X-API-KEY' => $apiKey,
                'Accept' => 'application/json',
            ]
        ]);
    }

    protected function handleResponse($response)
    {
        $body = json_decode($response->getBody(), true);
        $statusCode = $response->getStatusCode();
        $status = $body['status'] ?? null;
        $message = $body['message'] ?? null;

        switch ($statusCode) {
            case 200:
            case 400:
                return new SmsIrResult($status, $message, $body['data'] ?? null);
            case 401:
                $message = $body['message'] ?? 'Unauthorized access';
                throw new SmsException($message, $statusCode);
            case 403:
                $message = $body['message'] ?? 'Access Denied';
                throw new SmsException($message, $statusCode);
            case 429:
                $message = $body['message'] ?? 'Too Many Requests';
                throw new SmsException($message, $statusCode);
            case 500:
                $message = $body['message'] ?? 'Internal Server Error';
                throw new SmsException($message, $statusCode);
            default:
                throw new SmsException('An unexpected error occurred', $statusCode);
        }
    }

    protected function handleException(RequestException $e)
    {
        $response = $e->getResponse();
        $message = $e->getMessage();
        $statusCode = $response ? $response->getStatusCode() : 500; 

        if ($response) {
            $errorBody = json_decode($response->getBody(), true);
            $message = $errorBody['message'] ?? $message;
        }

        if ($statusCode === 400) {
            return $this->handleResponse($response);
        }

        throw new SmsException($message, $statusCode);
    }

    private function sendRequest($method, $uri, $options = [])
    {
        try {
            $response = $this->client->request($method, $uri, $options);

            return $this->handleResponse($response);
        } catch (RequestException $e) {
            return $this->handleException($e);
        } catch (Exception $e) {
            throw new SmsException("An unexpected error occurred: " . $e->getMessage(), 500);
        }
    }

    /**
     * Retrieves the available SMS credit.
     *
     * @return SmsIrResult The result object containing credit information.
     */
    public function getCredit()
    {
        return $this->sendRequest('GET', 'credit');
    }

    /**
     * Retrieves the list of SMS lines.
     *
     * @return SmsIrResult The result object containing available lines.
     */
    public function getLines()
    {
        return $this->sendRequest('GET', 'line');
    }

    /**
     * Retrieves the latest received messages.
     *
     * @param int $count The number of messages to retrieve.
     * @return SmsIrResult The result object containing received messages.
     */
    public function getLatestReceives($count = 100)
    {
        return $this->sendRequest('GET', 'receive/latest', [
            'query' => ['count' => $count],
        ]);
    }

    /**
     * Retrieves received messages for the current day.
     *
     * Fetches a list of messages that were received today, with optional pagination
     * and sorting parameters.
     *
     * @param int  $pageNumber   The page number for pagination (default is 1).
     * @param int  $pageSize     The number of messages per page (default is 100).
     * @param bool $sortByNewest Whether to sort the messages by newest first (default is false).
     *
     * @return SmsIrResult
     * @throws SmsException
     */
    public function getLiveReceives($pageNumber = 1, $pageSize = 100, $sortByNewest = false)
    {
        return $this->sendRequest('GET', 'receive/live', [
            'query' => [
                'pageNumber' => $pageNumber,
                'pageSize' => $pageSize,
                'sortByNewest' => $sortByNewest,
            ]
        ]);
    }

    /**
     * Retrieves archived received messages based on date range.
     *
     * @param int $pageNumber The page number.
     * @param int $pageSize The number of messages per page.
     * @param int|null $fromDate    (Optional) The start date for filtering reports.
     *                              This must be a Unix timestamp (numeric format).
     *                              Example: 1609459200 (equivalent to 2021-01-01 00:00:00 UTC).
     * @param int|null $toDate      (Optional) The end date for filtering reports.
     *                              This must be a Unix timestamp (numeric format).
     *                              Example: 1612137600 (equivalent to 2021-02-01 00:00:00 UTC).
     * @return SmsIrResult The result object containing archived received messages.
     */
    public function getArchivedReceives($pageNumber = 1, $pageSize = 100, $fromDate = null, $toDate = null)
    {
        $queryParams = [
            'pageNumber' => $pageNumber,
            'pageSize' => $pageSize
        ];

        if ($fromDate) {
            $queryParams['fromDate'] = $fromDate;
        }
        if ($toDate) {
            $queryParams['toDate'] = $toDate;
        }

        return $this->sendRequest('GET', 'receive/archive', [
            'query' => $queryParams,
        ]);
    }

    /**
     * Sends bulk SMS messages.
     *
     * @param string $lineNumber The sender's line number.
     * @param string $messageText The message content.
     * @param array $mobiles The list of mobile numbers to send to.
     * @param int|null $sendDateTime  (Optional) The scheduled time for sending the message.
     *                               This must be a Unix timestamp (numeric format).
     *                               Example: 1609459200 (equivalent to 2021-01-01 00:00:00 UTC).
     * @return SmsIrResult The result object containing the send status.
     */
    public function bulkSend($lineNumber, $messageText, $mobiles, $sendDateTime = null)
    {
        return $this->sendRequest('POST', 'send/bulk', [
            'json' => [
                'lineNumber' => $lineNumber,
                'messageText' => $messageText,
                'mobiles' => $mobiles,
                'sendDateTime' => $sendDateTime,
            ]
        ]);
    }

    /**
     * Sends like-to-like SMS messages where each mobile number gets a unique message.
     *
     * @param string $lineNumber The sender's line number.
     * @param array $messageTexts The array of message contents.
     * @param array $mobiles The list of mobile numbers to send to.
     * @param int|null $sendDateTime  (Optional) The scheduled time for sending the message.
     *                               This must be a Unix timestamp (numeric format).
     *                               Example: 1609459200 (equivalent to 2021-01-01 00:00:00 UTC).
     * @return SmsIrResult The result object containing the send status.
     */
    public function likeToLikeSend($lineNumber, $messageTexts, $mobiles, $sendDateTime = null)
    {
        return $this->sendRequest('POST', 'send/likeTolike', [
            'json' => [
                'lineNumber' => $lineNumber,
                'messageTexts' => $messageTexts,
                'mobiles' => $mobiles,
                'sendDateTime' => $sendDateTime,
            ]
        ]);
    }

    /**
     * Sends a verification message using a template.
     *
     * This method is used to send verification messages (e.g., OTP codes) to a mobile number
     * using a pre-defined template. The template includes dynamic parameters that are replaced
     * with provided values.
     *
     * @param string $mobile      The recipient's mobile number.
     * @param int    $templateId  The ID of the template used for sending the message.
     * @param array  $parameters  An array of parameters to be injected into the template.
     *                            Each parameter should be an associative array with:
     *                            - "name"  (string) : The name of the variable in the template.
     *                            - "value" (string) : The value to replace the variable with.
     *                            Example:
     *                            "parameters": [
     *                              {
     *                                "name": "Code",
     *                                "value": "12345"
     *                              }
     *                            ]
     *
     * @return SmsIrResult
     * @throws SmsException
     */
    public function verifySend($mobile, $templateId, $parameters)
    {
        return $this->sendRequest('POST', 'send/verify', [
            'json' => [
                'mobile' => $mobile,
                'templateId' => $templateId,
                'parameters' => $parameters,
            ]
        ]);
    }

    /**
     * Removes a scheduled message.
     *
     * This function is used to remove a scheduled message that has not yet been sent.
     * The scheduled message is identified by its pack ID.
     *
     * @param string $packId  The ID of the scheduled message pack to remove.
     *
     * @return SmsIrResult
     * @throws SmsException
     */
    public function removeScheduledMessages($packId)
    {
        return $this->sendRequest('DELETE', "send/scheduled/{$packId}");
    }

    /**
     * Retrieves the report of a sent message by its message ID.
     *
     * This function fetches the delivery report of a specific message that was sent.
     * The message is identified by its message ID.
     *
     * @param string $messageId  The ID of the sent message to retrieve the report for.
     *
     * @return SmsIrResult
     * @throws SmsException
     */
    public function getReportByMessageId($messageId)
    {
        return $this->sendRequest('GET', "send/{$messageId}");
    }

    /**
     * Retrieves the report of sent messages by pack ID.
     *
     * This function fetches the delivery report of a batch of messages that were sent together,
     * identified by the pack ID.
     *
     * @param string $packId  The ID of the message pack to retrieve the report for.
     *
     * @return SmsIrResult
     * @throws SmsException
     */
    public function getReportByPackId($packId)
    {
        return $this->sendRequest('GET', "send/pack/{$packId}");
    }

    /**
     * Retrieves live reports of current day sent messages.
     *
     * This function fetches real-time reports of messages that were recently sent.
     * It supports pagination and can return the reports sorted by newest or oldest.
     *
     * @param int  $pageNumber    The page number to retrieve.
     * @param int  $pageSize      The number of reports per page.
     * @param bool $sortByNewest  Whether to sort reports by newest first (true) or oldest first (false).
     *
     * @return SmsIrResult
     * @throws SmsException
     */
    public function getLiveReport($pageNumber = 1, $pageSize = 100, $sortByNewest = false)
    {
        return $this->sendRequest('GET', 'send/live', [
            'query' => [
                'pageNumber' => $pageNumber,
                'pageSize' => $pageSize,
                'sortByNewest' => $sortByNewest,
            ]
        ]);
    }

    /**
     * Retrieves archived reports of previously sent messages.
     *
     * This function fetches archived reports of messages that were sent in the past.
     * It supports pagination, date range filters, and sorting by newest or oldest.
     *
     * @param int    $pageNumber    The page number to retrieve.
     * @param int    $pageSize      The number of reports per page.
     * @param int|null $fromDate    (Optional) The start date for filtering reports.
     *                              This must be a Unix timestamp (numeric format).
     *                              Example: 1609459200 (equivalent to 2021-01-01 00:00:00 UTC).
     * @param int|null $toDate      (Optional) The end date for filtering reports.
     *                              This must be a Unix timestamp (numeric format).
     *                              Example: 1612137600 (equivalent to 2021-02-01 00:00:00 UTC).
     * @param bool   $sortByNewest  Whether to sort reports by newest first (true) or oldest first (false).
     *
     * @return SmsIrResult
     * @throws SmsException
     */
    public function getArchivedReport($pageNumber = 1, $pageSize = 100, $fromDate = null, $toDate = null, $sortByNewest = false)
    {
        $queryParams = [
            'pageNumber' => $pageNumber,
            'pageSize' => $pageSize,
            'sortByNewest' => $sortByNewest,
        ];

        if ($fromDate) {
            $queryParams['fromDate'] = $fromDate;
        }

        if ($toDate) {
            $queryParams['toDate'] = $toDate;
        }

        return $this->sendRequest('GET', 'send/archive', [
            'query' => $queryParams,
        ]);
    }

    /**
     * Retrieves a list of sent message packs.
     *
     * This function fetches a paginated list of sent message packs, where each pack
     * may contain multiple messages.
     *
     * @param int $pageNumber  The page number to retrieve.
     * @param int $pageSize    The number of packs per page.
     *
     * @return SmsIrResult
     * @throws SmsException
     */
    public function getSendPacks($pageNumber = 1, $pageSize = 100)
    {
        return $this->sendRequest('GET', 'send/pack', [
            'query' => [
                'pageNumber' => $pageNumber,
                'pageSize' => $pageSize,
            ]
        ]);
    }
}

class SmsIrResult
{
    public $status;
    public $message;
    public $data;

    public function __construct($status, $message, $data = null)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }
}
