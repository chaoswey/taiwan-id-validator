<?php

namespace Chaoswey\TaiwanIdValidator\Validators;

use Chaoswey\TaiwanIdValidator\Contracts\ValidatorInterface;

class DonationCodeValidator implements ValidatorInterface
{
    public function validate(mixed $value, array $options = []): bool
    {
        if (!is_string($value) && !is_int($value)) {
            return false;
        }

        return (bool)preg_match('/^\d{3,7}$/', (string)$value);
    }
}
