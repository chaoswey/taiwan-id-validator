# taiwan-id-validator

## Features

* 台灣身分證字號驗證
* 舊版臺灣地區無戶籍國民、外國人、大陸地區人民及香港或澳門居民之專屬代號
* 新版臺灣地區無戶籍國民、外國人、大陸地區人民及香港或澳門居民之專屬代號
* 公司統一編號驗證 (支援新/舊版統一編號檢查)
* 自然人憑證編號驗證
* 電子發票手機條碼驗證
* 電子發票捐贈碼驗證
* 信用卡號碼驗證

## Quick start
使用 composer 安裝

```
composer require chaoswey/taiwan-id-validator
```

## Usage

使用 laravel

```php
use Chaoswey\TaiwanIdValidator\Facades\TaiwanIdValidator;

TaiwanIdValidator::isGuiNumberValid('04595252', true); // 新版統一編號

TaiwanIdValidator::isGuiNumberValid('12345675'); // 統一編號
TaiwanIdValidator::isNationalIdentificationNumberValid('A123456789'); // 身分證字號
TaiwanIdValidator::isResidentCertificateNumberValid('AA00000009'); // 居留證編號 (舊式與新式)
TaiwanIdValidator::isNewResidentCertificateNumberValid('A800000014'); // 新式居留證編號
TaiwanIdValidator::isOriginalResidentCertificateNumberValid('AA00000009'); // 舊式居留證編號
TaiwanIdValidator::isCitizenDigitalCertificateNumberValid('AA12345678901234'); // 自然人憑證
TaiwanIdValidator::isEInvoiceCellPhoneBarcodeValid('/U.5+A33'); // 手機條碼
TaiwanIdValidator::isEInvoiceDonateCodeValid('001'); // 捐贈碼
TaiwanIdValidator::isCreditCardNumberValid('5105105105105100'); // 信用卡
```

使用 laravel validation

```php
public function rules(): array
{
    return [
        'gui'                => 'required|isGUI', //中華民國統一編號
        'ni'                 => 'required|isNI', //中華民國身分證字號
        'rc'                 => 'required|isRC', //臺灣地區無戶籍國民、外國人、大陸地區人民及香港或澳門居民之專屬代號
        'new_rc'             => 'required|isNewRC', //臺灣地區無戶籍國民、外國人、大陸地區人民及香港或澳門居民之新專屬代號
        'original_rc'        => 'required|isOriginalRC', //臺灣地區無戶籍國民、外國人、大陸地區人民及香港或澳門居民之舊專屬代號
        'cdc'                => 'required|isCDC', //自然人憑證
        'cell_phone_barcode' => 'required|isCellPhoneBarcode', //電子發票手機條碼
        'donate_code'        => 'required|isDonateCode', //電子發票捐贈碼
        'is_credit_card'     => 'required|isCreditCard', //信用卡卡號
    ];
}
```
純 php 使用
```php
use Chaoswey\TaiwanIdValidator\TaiwanIdValidator;

$valid = new TaiwanIdValidator();
$valid->isGuiNumberValid('04595252', true); // 新版統一編號

$valid->isGuiNumberValid('12345675'); // 統一編號
$valid->isNationalIdentificationNumberValid('A123456789'); // 身分證字號
$valid->isResidentCertificateNumberValid('AA00000009'); // 居留證編號 (舊式與新式)
$valid->isNewResidentCertificateNumberValid('A800000014'); // 新式居留證編號
$valid->isOriginalResidentCertificateNumberValid('AA00000009'); // 舊式居留證編號
$valid->isCitizenDigitalCertificateNumberValid('AA12345678901234'); // 自然人憑證
$valid->isEInvoiceCellPhoneBarcodeValid('/U.5+A33'); // 手機條碼
$valid->isEInvoiceDonateCodeValid('001'); // 捐贈碼
$valid->isCreditCardNumberValid('5105105105105100'); // 信用卡
```


## 補充資料
參考以下網站 轉成PHP版本

[enylin/taiwan-id-validator](https://github.com/enylin/taiwan-id-validator)
