<?php

namespace Chaoswey\TaiwanIdValidator\Validators;

use Chaoswey\TaiwanIdValidator\Contracts\ValidatorInterface;

class CitizenDigitalCertificateValidator implements ValidatorInterface
{
    public function validate(mixed $value, array $options = []): bool
    {
        if (!is_string($value)) {
            return false;
        }

        return (bool)preg_match('/^[A-Z]{2}\d{14}$/', $value);
    }
}
