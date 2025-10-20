<?php

namespace Chaoswey\TaiwanIdValidator\Validators;

use Chaoswey\TaiwanIdValidator\Contracts\ValidatorInterface;

class MobileBarcodeValidator implements ValidatorInterface
{
    public function validate(mixed $value, array $options = []): bool
    {
        if (!is_string($value)) {
            return false;
        }

        return (bool)preg_match('/^\/[\dA-Z.\-+]{7}$/', $value);
    }
}
