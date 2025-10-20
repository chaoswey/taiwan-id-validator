# Taiwan ID Validator

一組 PHP / Laravel 共用的驗證工具，支援台灣營利事業統一編號、國民身分證、新舊式外來人口統一證號與常見證號格式。2025 版以策略模式重構，可依需求替換或擴充驗證器，同時保留舊有 API 以利升級。

## 特色

- `isBan`：營利事業統一編號，新版預設（2023.04 後）並可選擇套用舊規則。
- `isIdCardNumber`：統一處理國民身分證、新／舊式外來人口統一證號，提供細緻的開關與族群限制。
- `isMobileBarcode`、`isDonationCode`、`isCitizenDigitalCertificate`、`isCreditCardNumberValid` 等常用格式驗證。
- Laravel Service Provider 內建驗證規則（含新舊命名），可直接於驗證器或 Request 使用。
- 以工廠模式封裝，各驗證器獨立成物件，易於繼承或覆寫。

## 安裝

```bash
composer require chaoswey/taiwan-id-validator
```

## 快速開始

### Laravel Facade

```php
use Chaoswey\TaiwanIdValidator\Facades\TaiwanIdValidator;

TaiwanIdValidator::isBan('04595257');
TaiwanIdValidator::isBan('04595252', ['applyOldRules' => true]); // 舊版演算法

// IdCard 基礎用法
TaiwanIdValidator::isIdCardNumber('A123456789');
TaiwanIdValidator::isIdCardNumber('A800000014', [
    'nationalId' => false,
    'uiNumber' => [
        'oldFormat' => false,
        'newFormat' => [
            'foreignOrStateless' => true,
            'statelessResident' => false,
            'hkMacaoResident' => false,
            'mainlandChinaResident' => false,
        ],
    ],
]);
TaiwanIdValidator::isMobileBarcode('/AAA33AA');

// 更複雜的 IdCard 組合
TaiwanIdValidator::isIdCardNumber('AA00000009', ['scope' => 'resident-original']); // 僅舊式統一證號
TaiwanIdValidator::isIdCardNumber('A870000015', [
    'scope' => 'resident-new',
    'nationalId' => false,
    'uiNumber' => [
        'oldFormat' => false,
        'newFormat' => [
            'enabled' => true,
            'categories' => [
                'foreignOrStateless' => false,
                'statelessResident' => true,
                'hkMacaoResident' => false,
                'mainlandChinaResident' => false,
            ],
        ],
    ],
]);
TaiwanIdValidator::isIdCardNumber('A880000018', [
    'scope' => ['resident-new'],
    'uiNumber' => [
        'oldFormat' => false,
        'newFormat' => [
            'categories' => [
                'foreignOrStateless' => false,
                'statelessResident' => false,
                'hkMacaoResident' => true,
                'mainlandChinaResident' => false,
            ],
        ],
    ],
]);
TaiwanIdValidator::isIdCardNumber('A123456789', [
    'scope' => 'citizen|resident-original', // 同時允許某範圍
    'uiNumber' => ['newFormat' => false],
]);

TaiwanIdValidator::isDonationCode('10001');
TaiwanIdValidator::isCitizenDigitalCertificate('AB12345678901234');
TaiwanIdValidator::isCreditCardNumberValid('5105105105105100', ['checkIssuerRegexes' => true]);
```

### 純 PHP

```php
use Chaoswey\TaiwanIdValidator\TaiwanIdValidator;

$validator = new TaiwanIdValidator();
$validator->isBan('12345675');
$validator->isIdCardNumber('AA00000009', ['scope' => 'resident-original']);
$validator->isIdCardNumber('A800000014', [
    'nationalId' => false,
    'uiNumber' => [
        'oldFormat' => false,
        'newFormat' => [
            'categories' => [
                'foreignOrStateless' => true,
                'statelessResident' => false,
                'hkMacaoResident' => false,
                'mainlandChinaResident' => false,
            ],
        ],
    ],
]);
$validator->isMobileBarcode('/+.-++..');
```

## Laravel 驗證規則

| 規則名稱 | 說明 | 範例 |
| --- | --- | --- |
| `isBan` | 統一編號。參數：`old`/`oldRules` 套用舊演算法 | `isBan:old` |
| `isIdCardNumber` | 身分證/外來人口證號。支援複數參數（逗號分隔）。<br>`scope=a\|b` 指定驗證範圍（詳見下方說明）。<br>`nationalId=false` 關閉身分證驗證。<br>`ui=true/false` 或 `uiOld=true`、`uiNew=false` 控制新舊格式。<br>`uiNewCategories=foreignOrStateless,statelessResident` 指定可接受族群。 | `isIdCardNumber:scope=citizen`,<br>`isIdCardNumber:nationalId=false,uiOld=false,uiNewCategories=hkMacaoResident`,<br>`isIdCardNumber:scope=resident-new,uiNewCategories=foreignOrStateless`,<br>`isIdCardNumber:scope=resident`,<br>`citizen,ui=false` |
| `isMobileBarcode` | 電子發票手機條碼 | - |
| `isCitizenDigitalCertificate` | 自然人憑證 | - |
| `isDonationCode` | 電子發票捐贈碼 | - |
| `isCreditCard` | 信用卡號，參數加上 `issuer` 代表同時檢查發卡組織 | `isCreditCard:issuer` |

### 相容舊版規則

原有的規則名稱仍可使用（`isGUI`、`isNI`、`isRC`、`isNewRC`、`isOriginalRC`、`isCDC`、`isCellPhoneBarcode`、`isDonateCode`），內部已改呼叫新方法，建議逐步調整為新命名。

## API 一覽

| 方法 | 說明與選項 |
| --- | --- |
| `isBan(mixed $value, array $options = [])` | 驗證統一編號。`applyOldRules` 選項開啟舊演算法。 |
| `isIdCardNumber(mixed $value, array $options = [])` | 統一的身分證／外來人口證號驗證。選項：<br>- `scope`：字串或陣列（`citizen`、`resident-new`、`resident-original`、`resident`）。<br>- `nationalId`：布林，預設 `true`。<br>- `uiNumber.oldFormat`：布林，預設 `true`。<br>- `uiNumber.newFormat.enabled`：布林，預設 `true`。<br>- `uiNumber.newFormat.categories`：陣列，預設全部 `true`，可設定 `foreignOrStateless`、`statelessResident`、`hkMacaoResident`、`mainlandChinaResident`。 |
| `isMobileBarcode(mixed $value)` | 電子發票手機條碼。 |
| `isCitizenDigitalCertificate(mixed $value)` | 自然人憑證碼。 |
| `isDonationCode(mixed $value)` | 電子發票捐贈碼。 |
| `isCreditCardNumberValid(mixed $value, array $options = [])` | 信用卡卡號。`checkIssuerRegexes` 控制是否驗證發卡組織。 |

> 舊版函式（例如 `isGuiNumberValid`、`isNationalIdentificationNumberValid` 等）仍可使用，但已標記為 `@deprecated`，會轉呼叫新 API。

### IdCard `scope` 與 `uiNumber` 參數說明

- `scope`：指定允許驗證的證號類別，可使用單一值或 `|` 分隔的多值。
  - `citizen`：只驗證國民身分證字號（第一碼英文字母、第二碼 1 或 2）。
  - `resident-new`：只驗證新式外來人口/居留證號（第一碼英文字、第二碼 8 或 9）。
  - `resident-original`：只驗證舊式統一證號（前兩碼英文字，第二碼限 A-D）。
  - `resident`：同時允許新舊式外來人口證號。
- `nationalId`：布林值（預設 `true`），是否允許國民身分證。
- `uiNumber.oldFormat`：布林值（預設 `true`），是否允許舊式外來人口統一證號。
- `uiNumber.newFormat.enabled`：布林值（預設 `true`），控制新式統一證號是否驗證。
- `uiNumber.newFormat.categories`：陣列，用於限制新式統一證號的族群，鍵值如下（預設皆為 `true`）：
  - `foreignOrStateless`：一般外國人或無國籍人士（第 3 碼 0-6）。
  - `statelessResident`：無戶籍國民（第 3 碼 7）。
  - `hkMacaoResident`：香港／澳門居民（第 3 碼 8）。
  - `mainlandChinaResident`：大陸地區人民（第 3 碼 9）。

範例：

```php
TaiwanIdValidator::isIdCardNumber('A800000014', [
    'nationalId' => false,
    'uiNumber' => [
        'oldFormat' => false,
        'newFormat' => [
            'enabled' => true,
            'categories' => [
                'foreignOrStateless' => true,
                'statelessResident' => false,
                'hkMacaoResident' => false,
                'mainlandChinaResident' => false,
            ],
        ],
    ],
]);
```

## 自訂驗證器

每一種驗證皆為獨立類別（位於 `src/Validators`）。若需擴充，實作 `Chaoswey\TaiwanIdValidator\Contracts\ValidatorInterface` 後，可透過建構 `TaiwanIdValidator` 時注入自訂工廠，或註冊自己的 Service Provider 覆寫容器綁定。

```php
use Chaoswey\TaiwanIdValidator\Factories\ValidatorFactory;
use Chaoswey\TaiwanIdValidator\TaiwanIdValidator;

$factory = new ValidatorFactory([
    'custom_ban' => App\Validators\MyBanValidator::class,
]);

$validator = new TaiwanIdValidator($factory);
```

## 升級指南

* `isGuiNumberValid()`、更名為 `isBan()`；切換舊版演算法改使用 `['applyOldRules' => true]`。
* `isNationalIdentificationNumberValid()`、`isResidentCertificateNumberValid()`、`isNewResidentCertificateNumberValid()`、`isOriginalResidentCertificateNumberValid()` 合併為 `isIdCardNumber()`；可透過 `scope` 與 `uiNumber` 控制行為。
* Laravel 驗證規則提供新命名（`isBan`、`isIdCardNumber` 等），舊名仍可相容。

## 參考來源

- [enylin/taiwan-id-validator](https://github.com/enylin/taiwan-id-validator)（原始 JS 實作）
