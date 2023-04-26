<?php

namespace Chaoswey\TaiwanIdValidator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory;

class TaiwanIdValidatorServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot(): void
    {
        /* @var Factory $validator */
        $validator = $this->app['validator'];

        // Validator extensions
        // 中華民國統一編號
        $validator->extend('isGUI', function ($attribute, $value, $parameters) {
            return $value && $this->app['TaiwanIdValidator']->isGuiNumberValid($value);
        });
        // 中華民國身分證字號
        $validator->extend('isNI', function ($attribute, $value, $parameters) {
            return $value && $this->app['TaiwanIdValidator']->isNationalIdentificationNumberValid($value);
        });
        // 臺灣地區無戶籍國民、外國人、大陸地區人民及香港或澳門居民之專屬代號
        $validator->extend('isRC', function ($attribute, $value, $parameters) {
            return $value && $this->app['TaiwanIdValidator']->isResidentCertificateNumberValid($value);
        });
        // 臺灣地區無戶籍國民、外國人、大陸地區人民及香港或澳門居民之新專屬代號
        $validator->extend('isNewRC', function ($attribute, $value, $parameters) {
            return $value && $this->app['TaiwanIdValidator']->isNewResidentCertificateNumberValid($value);
        });
        // 臺灣地區無戶籍國民、外國人、大陸地區人民及香港或澳門居民之舊專屬代號
        $validator->extend('isOriginalRC', function ($attribute, $value, $parameters) {
            return $value && $this->app['TaiwanIdValidator']->isOriginalResidentCertificateNumberValid($value);
        });
        // 自然人憑證
        $validator->extend('isCDC', function ($attribute, $value, $parameters) {
            return $value && $this->app['TaiwanIdValidator']->isCitizenDigitalCertificateNumberValid($value);
        });
        // 電子發票手機條碼
        $validator->extend('isCellPhoneBarcode', function ($attribute, $value, $parameters) {
            return $value && $this->app['TaiwanIdValidator']->isEInvoiceCellPhoneBarcodeValid($value);
        });
        // 電子發票捐贈碼
        $validator->extend('isDonateCode', function ($attribute, $value, $parameters) {
            return $value && $this->app['TaiwanIdValidator']->isEInvoiceDonateCodeValid($value);
        });
        // 信用卡卡號
        $validator->extend('isCreditCard', function ($attribute, $value, $parameters) {
            return $value && $this->app['TaiwanIdValidator']->isCreditCardNumberValid($value);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind('TaiwanIdValidator', function ($app) {
            return new TaiwanIdValidator();
        });
    }
}
