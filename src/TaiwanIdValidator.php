<?php

namespace Chaoswey\TaiwanIdValidator;

use Chaoswey\TaiwanIdValidator\Contracts\ValidatorFactoryInterface;
use Chaoswey\TaiwanIdValidator\Factories\ValidatorFactory;

class TaiwanIdValidator
{
    private ValidatorFactoryInterface $factory;

    public function __construct(?ValidatorFactoryInterface $factory = null)
    {
        $this->factory = $factory ?? new ValidatorFactory();
    }

    public function useFactory(ValidatorFactoryInterface $factory): void
    {
        $this->factory = $factory;
    }

    public function isBan(mixed $value, array $options = []): bool
    {
        return $this->factory->make('ban')->validate($value, $options);
    }

    public function isIdCardNumber(mixed $value, array $options = []): bool
    {
        return $this->factory->make('id_card')->validate($value, $options);
    }

    public function isMobileBarcode(mixed $value): bool
    {
        return $this->factory->make('mobile_barcode')->validate($value);
    }

    public function isCitizenDigitalCertificate(mixed $value): bool
    {
        return $this->factory->make('citizen_certificate')->validate($value);
    }

    public function isDonationCode(mixed $value): bool
    {
        return $this->factory->make('donation_code')->validate($value);
    }

    public function isCreditCardNumberValid(mixed $value, array $options = []): bool
    {
        return $this->factory->make('credit_card')->validate($value, $options);
    }

    /**
     * @deprecated 改用 isBan()，若需舊版驗證邏輯請傳入 ['applyOldRules' => true]
     */
    public function isGuiNumberValid(mixed $input, bool $extended = false): bool
    {
        $options = $extended ? ['applyOldRules' => true] : [];

        return $this->isBan($input, $options);
    }

    /**
     * @deprecated 改用 isIdCardNumber($input, ['scope' => 'citizen'])
     */
    public function isNationalIdentificationNumberValid(mixed $input): bool
    {
        return $this->isIdCardNumber($input, ['scope' => 'citizen']);
    }

    /**
     * @deprecated 改用 isIdCardNumber($input, ['scope' => 'resident'])
     */
    public function isResidentCertificateNumberValid(mixed $input): bool
    {
        return $this->isIdCardNumber($input, ['scope' => 'resident']);
    }

    /**
     * @deprecated 改用 isIdCardNumber($input, ['scope' => 'resident-new'])
     */
    public function isNewResidentCertificateNumberValid(mixed $input): bool
    {
        return $this->isIdCardNumber($input, ['scope' => 'resident-new']);
    }

    /**
     * @deprecated 改用 isIdCardNumber($input, ['scope' => 'resident-original'])
     */
    public function isOriginalResidentCertificateNumberValid(mixed $input): bool
    {
        return $this->isIdCardNumber($input, ['scope' => 'resident-original']);
    }

    /**
     * @deprecated 改用 isCitizenDigitalCertificate()
     */
    public function isCitizenDigitalCertificateNumberValid(mixed $input): bool
    {
        return $this->isCitizenDigitalCertificate($input);
    }

    /**
     * @deprecated 改用 isMobileBarcode()
     */
    public function isEInvoiceCellPhoneBarcodeValid(mixed $input): bool
    {
        return $this->isMobileBarcode($input);
    }

    /**
     * @deprecated 改用 isDonationCode()
     */
    public function isEInvoiceDonateCodeValid(mixed $input): bool
    {
        return $this->isDonationCode($input);
    }
}
