<div dir="rtl">

# پکیج PHP پنل پیامکی [sms.ir](https://www.sms.ir)

پکیج پنل پیامکی [sms.ir](https://www.sms.ir)، ابزاری کارآمد است که به شما این امکان را می‌دهد تا به سادگی از سرویس ارسال پیامک [sms.ir](https://www.sms.ir) در پروژه‌های PHP و Laravel خود بهره‌برداری کنید. با استفاده از این پکیج، می‌توانید پیامک‌های تکی، گروهی و زمان‌بندی‌شده را به راحتی ارسال و مدیریت کنید. این ابزار طراحی شده تا فرآیند ارسال پیامک را برای شما آسان و سریع کند.

## ویژگی‌ها

- ارسال پیامک‌های تکی و گروهی
- ارسال پیامک‌های نظیر به نظیر
- ارسال پیامک‌های تایید (VERIFY)
- امکان زمان‌بندی ارسال پیامک
- گزارش‌گیری از وضعیت پیامک‌های ارسال شده
- مشاهده پیامک‌های دریافت شده
- دریافت اعتبار فعلی و مدیریت خطوط

## نصب

برای نصب این پکیج می‌توانید از Composer استفاده کنید:

</div>

```bash
composer require ipe/sms.ir
```

<div dir="rtl">

پس از نصب موفقیت‌آمیز پکیج، شما می‌توانید از آن در پروژه‌های PHP خود استفاده کنید.

## تنظیمات

برای دریافت کلید وب سرویس، وارد پنل کاربری [sms.ir](https://www.sms.ir) خود شوید و از بخش "برنامه‌نویسان" اقدام به ایجاد کلید کنید. سپس از همان کلید در تنظیمات پکیج استفاده نمایید.

پس از تهیه کلید وب سرویس، فایل `.env` خود را با مقادیر زیر به‌روزرسانی کنید:

</div>

```bash
SMSIR_API_KEY= your_api_key_here
```

<div dir="rtl">

## استفاده از متدها

### 1. دریافت اعتبار فعلی

این متد به شما این امکان را می‌دهد تا مقدار اعتبار فعلی خود را از پنل پیامکی دریافت کنید.

</div>

```php
use Ipe\Sdk\Facades\SmsIr;

$response = SmsIr::getCredit();
```

<div dir="rtl">

### 2. دریافت لیست خطوط پیامکی

این متد به شما امکان می‌دهد تا لیست خطوط پیامکی فعال خود را از پنل پیامکی دریافت کنید.

</div>

```php
use Ipe\Sdk\Facades\SmsIr;

$response = SmsIr::getLines();
```

<div dir="rtl">

### 3. دریافت آخرین پیام‌های دریافتی

این متد به شما امکان می‌دهد تا آخرین پیام‌های دریافتی را از پنل پیامکی بازیابی کنید.

</div>

```php
use Ipe\Sdk\Facades\SmsIr;

// دریافت 100 پیام آخر (مقدار پیش‌فرض)
$response = SmsIr::getLatestReceives();

// یا دریافت تعداد مشخصی از آخرین پیام‌ها (مثلاً 50 پیام)
// $response = SmsIr::getLatestReceives(50);
```

<div dir="rtl">

### 4. دریافت پیام‌های دریافتی امروز

این متد به شما امکان می‌دهد تا پیام‌های دریافتی امروز را از پنل پیامکی با قابلیت صفحه‌بندی و مرتب‌سازی بازیابی کنید.

</div>

```php
use Ipe\Sdk\Facades\SmsIr;

// دریافت پیام‌های دریافتی امروز با تنظیمات پیش‌فرض
$response = SmsIr::getLiveReceives();

// یا با تنظیمات سفارشی
// $response = SmsIr::getLiveReceives(2, 50, true);
```

<div dir="rtl">

### 5. دریافت پیام‌های دریافتی آرشیو شده

این متد به شما امکان می‌دهد تا پیام‌های دریافتی آرشیو شده را بر اساس بازه زمانی مشخص از پنل پیامکی بازیابی کنید.

</div>

```php
use Ipe\Sdk\Facades\SmsIr;

// دریافت پیام‌های آرشیو شده با تنظیمات پیش‌فرض
$response = SmsIr::getArchivedReceives();

// یا با تنظیمات سفارشی
$pageNumber = 1;
$pageSize = 50;
$fromDate = 1672531200; // 2023-01-01 00:00:00 UTC
$toDate = 1704067199;   // 2023-12-31 23:59:59 UTC
$response = SmsIr::getArchivedReceives($pageNumber, $pageSize, $fromDate, $toDate);
```

<div dir="rtl">

### 6. ارسال پیامک‌های گروهی

این متد به شما این امکان را می‌دهد تا پیامک‌های گروهی را به چندین شماره موبایل ارسال کنید.

</div>

```php
use Ipe\Sdk\Facades\SmsIr;

$lineNumber = "1234567890"; // شماره خط فرستنده
$messageText = "این یک پیام آزمایشی است.";
$mobiles = ["09123456789", "09198765432"]; // لیست شماره‌های گیرنده
$sendDateTime = null;   // برای ارسال آنی، مقدار را نال قرار دهید

$response = SmsIr::bulkSend($lineNumber, $messageText, $mobiles, $sendDateTime);
```

<div dir="rtl">

### 7. ارسال پیامک‌های نظیر به نظیر

این متد به شما این امکان را می‌دهد تا پیامک‌هایی ارسال کنید که در آن هر شماره موبایل پیام منحصر به فرد خود را دریافت می‌کند.

</div>

```php
use Ipe\Sdk\Facades\SmsIr;

$lineNumber = "1234567890"; // شماره خط فرستنده
$messageTexts = [
    "پیام 1 برای شماره 1",
    "پیام 2 برای شماره 2"
];
$mobiles = ["09123456789", "09198765432"]; // لیست شماره‌های گیرنده
$sendDateTime = null;   // برای ارسال آنی، مقدار را نال قرار دهید

$response = SmsIr::likeToLikeSend($lineNumber, $messageTexts, $mobiles, $sendDateTime);
```

<div dir="rtl">

### 8. ارسال پیامک با استفاده از الگو

این متد به شما این امکان را می‌دهد تا پیامک‌های تأییدیه (مانند کدهای OTP) را به شماره موبایل ارسال کنید، با استفاده از یک الگوی از پیش تعریف شده. الگو شامل پارامترهای دینامیکی است که با مقادیر ارائه‌شده جایگزین می‌شوند.

</div>

```php
use Ipe\Sdk\Facades\SmsIr;

$mobile = "09120000000"; // شماره موبایل گیرنده
$templateId = 100000; // شناسه الگو
$parameters = [
    [
        "name" => "Code",
        "value" => "12345"
    ]
];

$response = SmsIr::verifySend($mobile, $templateId, $parameters);
```

<div dir="rtl">

### 9. حذف یک پیام زمان‌بندی‌شده

این متد برای حذف یک پیام زمان‌بندی‌شده که هنوز ارسال نشده است، استفاده می‌شود. پیام زمان‌بندی‌شده با استفاده از شناسه بسته (pack ID) شناسایی می‌شود.

</div>

```php
use Ipe\Sdk\Facades\SmsIr;

$packId = "your_pack_id"; // شناسه بسته پیام زمان‌بندی‌شده

$response = SmsIr::removeScheduledMessages($packId);
```

<div dir="rtl">

### 10. دریافت گزارش یک پیام ارسال شده بر اساس شناسه پیام

این متد برای دریافت گزارش تحویل یک پیام خاص که قبلاً ارسال شده است، استفاده می‌شود. پیام با استفاده از شناسه پیام (message ID) شناسایی می‌شود.

</div>

```php
use Ipe\Sdk\Facades\SmsIr;

$messageId = "your_message_id"; // شناسه پیام ارسال‌شده

$response = SmsIr::getReportByMessageId($messageId);
```

<div dir="rtl">

### 11. دریافت گزارش پیام‌های ارسال‌شده بر اساس شناسه بسته

این متد برای دریافت گزارش تحویل پیام‌هایی که به صورت دسته‌ای ارسال شده‌اند، استفاده می‌شود. بسته پیام‌ها با استفاده از شناسه بسته (pack ID) شناسایی می‌شود.

</div>

```php
use Ipe\Sdk\Facades\SmsIr;

$packId = "your_pack_id"; // شناسه بسته پیام

$response = SmsIr::getReportByPackId($packId);
```

<div dir="rtl">

### 12. دریافت گزارش زنده پیام‌های ارسال‌شده در روز جاری

این متد برای دریافت گزارش‌های زنده از پیام‌هایی که به تازگی ارسال شده‌اند، استفاده می‌شود. این متد قابلیت پشتیبانی از صفحه‌بندی را دارد و می‌تواند گزارش‌ها را بر اساس جدیدترین یا قدیمی‌ترین مرتب‌سازی کند.

</div>

```php
use Ipe\Sdk\Facades\SmsIr;

$pageNumber = 1; // شماره صفحه
$pageSize = 100; // تعداد گزارش‌ها در هر صفحه
$sortByNewest = true; // مرتب‌سازی بر اساس جدیدترین

$response = SmsIr::getLiveReport($pageNumber, $pageSize, $sortByNewest);
```

<div dir="rtl">

### 13. دریافت گزارش‌های آرشیو شده پیام‌های ارسال‌شده

این متد برای دریافت گزارش‌های آرشیو شده پیام‌هایی که در گذشته ارسال شده‌اند، استفاده می‌شود. این متد قابلیت پشتیبانی از صفحه‌بندی، فیلتر بر اساس بازه زمانی و مرتب‌سازی به ترتیب جدیدترین یا قدیمی‌ترین را دارد.

</div>

```php
use Ipe\Sdk\Facades\SmsIr;

$pageNumber = 1; // شماره صفحه
$pageSize = 100; // تعداد گزارش‌ها در هر صفحه
$fromDate = 1609459200; // تاریخ شروع
$toDate = 1612137600; // تاریخ پایان
$sortByNewest = true; // مرتب‌سازی بر اساس جدیدترین

$response = SmsIr::getArchivedReport($pageNumber, $pageSize, $fromDate, $toDate, $sortByNewest);
```

<div dir="rtl">

### 14. دریافت لیست پک‌های پیام‌های ارسال‌شده

این متد برای دریافت لیستی از پک‌های پیام‌هایی که ارسال شده‌اند، استفاده می‌شود. هر پک ممکن است شامل چندین پیام باشد و این متد قابلیت پشتیبانی از صفحه‌بندی را دارد.

</div>

```php
use Ipe\Sdk\Facades\SmsIr;

$pageNumber = 1; // شماره صفحه
$pageSize = 100; // تعداد پک‌ها در هر صفحه

$response = SmsIr::getSendPacks($pageNumber, $pageSize);
```

---

<div dir="rtl">

با استفاده از پکیج پنل پیامکی [sms.ir](https://www.sms.ir)، شما می‌توانید به راحتی و با کمترین تلاش، پیامک‌های مختلف را به صورت آنی یا زمان‌بندی شده ارسال کنید. این پکیج با ارائه متدهای متعدد برای ارسال پیامک‌های تکی، گروهی و نظیر به نظیر، به شما این امکان را می‌دهد که ارتباطات خود را بهبود بخشید و پیام‌های خود را به مخاطبان هدف برسانید. همچنین، از طریق متدهای گزارش‌گیری، می‌توانید وضعیت پیامک‌های ارسال‌شده را پیگیری کنید و با استفاده از متدهای مربوط به اعتبار و خطوط، مدیریت بهتری بر روی حساب کاربری خود داشته باشید.

اگر سوالی دارید یا به راهنمایی بیشتری نیاز دارید، لطفاً به مستندات رسمی وب‌سرویس سامانه پیامکی [sms.ir](https://www.sms.ir) مراجعه کنید یا با تیم پشتیبانی ما در تماس باشید.

</div>
