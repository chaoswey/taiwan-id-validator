<?php

namespace Chaoswey\TaiwanIdValidator\Contracts;

interface ValidatorFactoryInterface
{
    public function make(string $type): ValidatorInterface;
}
