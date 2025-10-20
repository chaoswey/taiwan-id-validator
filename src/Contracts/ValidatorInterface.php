<?php

namespace Chaoswey\TaiwanIdValidator\Contracts;

interface ValidatorInterface
{
    /**
     * @param mixed $value
     */
    public function validate(mixed $value, array $options = []): bool;
}
