<?php

namespace Chaoswey\TaiwanIdValidator\Factories;

use Chaoswey\TaiwanIdValidator\Contracts\ValidatorFactoryInterface;
use Chaoswey\TaiwanIdValidator\Contracts\ValidatorInterface;
use Chaoswey\TaiwanIdValidator\Validators\BanValidator;
use Chaoswey\TaiwanIdValidator\Validators\CitizenDigitalCertificateValidator;
use Chaoswey\TaiwanIdValidator\Validators\CreditCardValidator;
use Chaoswey\TaiwanIdValidator\Validators\DonationCodeValidator;
use Chaoswey\TaiwanIdValidator\Validators\IdCardValidator;
use Chaoswey\TaiwanIdValidator\Validators\MobileBarcodeValidator;
use InvalidArgumentException;

class ValidatorFactory implements ValidatorFactoryInterface
{
    /**
     * @var array<string, class-string<ValidatorInterface>>
     */
    private array $map;

    /**
     * @param array<string, class-string<ValidatorInterface>>|null $map
     */
    public function __construct(?array $map = null)
    {
        $this->map = $map ?? [
            'ban'                 => BanValidator::class,
            'id_card'             => IdCardValidator::class,
            'mobile_barcode'      => MobileBarcodeValidator::class,
            'citizen_certificate' => CitizenDigitalCertificateValidator::class,
            'donation_code'       => DonationCodeValidator::class,
            'credit_card'         => CreditCardValidator::class,
        ];
    }

    public function make(string $type): ValidatorInterface
    {
        $normalized = strtolower($type);

        if (!isset($this->map[$normalized])) {
            throw new InvalidArgumentException(sprintf('Validator for type [%s] is not registered.', $type));
        }

        $class = $this->map[$normalized];

        return new $class();
    }
}
