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

        $validator->extend('isBan', function ($attribute, $value, $parameters) {
            return $value !== null && $this->validator()->isBan($value, $this->buildBanOptions($parameters));
        });

        $validator->extend('isIdCardNumber', function ($attribute, $value, $parameters) {
            return $value !== null && $this->validator()->isIdCardNumber($value, $this->buildIdCardOptions($parameters));
        });

        $validator->extend('isMobileBarcode', function ($attribute, $value, $parameters) {
            return $value !== null && $this->validator()->isMobileBarcode($value);
        });

        $validator->extend('isCitizenDigitalCertificate', function ($attribute, $value, $parameters) {
            return $value !== null && $this->validator()->isCitizenDigitalCertificate($value);
        });

        $validator->extend('isDonationCode', function ($attribute, $value, $parameters) {
            return $value !== null && $this->validator()->isDonationCode($value);
        });

        $validator->extend('isCreditCard', function ($attribute, $value, $parameters) {
            return $value !== null && $this->validator()->isCreditCardNumberValid($value, ['checkIssuerRegexes' => in_array('issuer', $parameters, true)]);
        });

        // Backward compatible aliases
        $validator->extend('isGUI', fn($attribute, $value) => $value !== null && $this->validator()->isGuiNumberValid($value));
        $validator->extend('isNI', fn($attribute, $value) => $value !== null && $this->validator()->isNationalIdentificationNumberValid($value));
        $validator->extend('isRC', fn($attribute, $value) => $value !== null && $this->validator()->isResidentCertificateNumberValid($value));
        $validator->extend('isNewRC', fn($attribute, $value) => $value !== null && $this->validator()->isNewResidentCertificateNumberValid($value));
        $validator->extend('isOriginalRC', fn($attribute, $value) => $value !== null && $this->validator()->isOriginalResidentCertificateNumberValid($value));
        $validator->extend('isCDC', fn($attribute, $value) => $value !== null && $this->validator()->isCitizenDigitalCertificateNumberValid($value));
        $validator->extend('isCellPhoneBarcode', fn($attribute, $value) => $value !== null && $this->validator()->isEInvoiceCellPhoneBarcodeValid($value));
        $validator->extend('isDonateCode', fn($attribute, $value) => $value !== null && $this->validator()->isEInvoiceDonateCodeValid($value));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(\Chaoswey\TaiwanIdValidator\Contracts\ValidatorFactoryInterface::class, fn() => new \Chaoswey\TaiwanIdValidator\Factories\ValidatorFactory());

        $this->app->singleton(TaiwanIdValidator::class, function ($app) {
            return new TaiwanIdValidator($app->make(\Chaoswey\TaiwanIdValidator\Contracts\ValidatorFactoryInterface::class));
        });

        $this->app->alias(TaiwanIdValidator::class, 'TaiwanIdValidator');
    }

    private function validator(): TaiwanIdValidator
    {
        return $this->app->make(TaiwanIdValidator::class);
    }

    private function buildBanOptions(array $parameters): array
    {
        $options = [];

        foreach ($parameters as $parameter) {
            $normalized = strtolower(trim((string)$parameter));

            if ($normalized === 'old' || $normalized === 'oldrules') {
                $options['applyOldRules'] = true;
            }
        }

        return $options;
    }

    private function buildIdCardOptions(array $parameters): array
    {
        $options = [];
        $uiOptions = [];
        $newFormat = [];
        $categories = [];

        foreach ($parameters as $parameter) {
            $parts = explode('=', $parameter, 2);
            $key = strtolower(trim($parts[0]));
            $value = $parts[1] ?? null;

            switch ($key) {
                case 'scope':
                    if ($value !== null) {
                        $scopes = array_map('trim', explode('|', $value));
                        $options['scope'] = count($scopes) === 1 ? $scopes[0] : $scopes;
                    }
                    break;
                case 'nationalid':
                    $options['nationalId'] = $this->toBool($value);
                    break;
                case 'ui':
                case 'uinumber':
                    $uiOptions = $this->mergeArrayOptions($uiOptions, $this->buildUiSwitch($value));
                    break;
                case 'uiold':
                case 'uinumber.old':
                    $uiOptions['oldFormat'] = $this->toBool($value);
                    break;
                case 'uinew':
                case 'uinumber.new':
                    $newFormat['enabled'] = $this->toBool($value);
                    break;
                case 'uinewcategories':
                case 'uinumber.new.categories':
                    if ($value !== null) {
                        $selected = array_map('trim', explode(',', $value));
                        foreach (['foreignOrStateless', 'statelessResident', 'hkMacaoResident', 'mainlandChinaResident'] as $category) {
                            $categories[$category] = in_array($category, $selected, true);
                        }
                    }
                    break;
                default:
                    // ignore unknown
            }
        }

        if (!empty($categories)) {
            $newFormat['categories'] = array_merge([
                'foreignOrStateless'    => false,
                'statelessResident'     => false,
                'hkMacaoResident'       => false,
                'mainlandChinaResident' => false,
            ], $categories);
        }

        if (!empty($newFormat)) {
            $uiOptions['newFormat'] = array_merge(
                [
                    'enabled'    => $newFormat['enabled'] ?? true,
                    'categories' => $newFormat['categories'] ?? [
                            'foreignOrStateless'    => true,
                            'statelessResident'     => true,
                            'hkMacaoResident'       => true,
                            'mainlandChinaResident' => true,
                        ],
                ],
                $newFormat
            );
        }

        if (!empty($uiOptions)) {
            $options['uiNumber'] = $uiOptions;
        }

        return $options;
    }

    private function buildUiSwitch(?string $value): array
    {
        if ($value === null) {
            return [];
        }

        $bool = $this->toBool($value);

        return [
            'oldFormat' => $bool,
            'newFormat' => [
                'enabled'    => $bool,
                'categories' => [
                    'foreignOrStateless'    => $bool,
                    'statelessResident'     => $bool,
                    'hkMacaoResident'       => $bool,
                    'mainlandChinaResident' => $bool,
                ],
            ],
        ];
    }

    private function toBool(?string $value): bool
    {
        if ($value === null) {
            return true;
        }

        return !in_array(strtolower(trim($value)), ['false', '0', 'off', 'no', ''], true);
    }

    private function mergeArrayOptions(array $base, array $override): array
    {
        foreach ($override as $key => $value) {
            if (is_array($value) && isset($base[$key]) && is_array($base[$key])) {
                $base[$key] = $this->mergeArrayOptions($base[$key], $value);
                continue;
            }

            $base[$key] = $value;
        }

        return $base;
    }
}
