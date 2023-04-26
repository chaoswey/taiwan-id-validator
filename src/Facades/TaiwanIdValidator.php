<?php namespace Chaoswey\TaiwanIdValidator\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Chaoswey\TaiwanIdValidator\TaiwanIdValidator
 */
class TaiwanIdValidator extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'TaiwanIdValidator';
    }
}
