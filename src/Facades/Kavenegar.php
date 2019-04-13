<?php


namespace Kavenegar\Facades;

/**
 * @method static void send ($receptor, string $message, array $options)
 *
 * @see \Kavenegar\Kavenegar
 */

use Illuminate\Support\Facades\Facade;

class Kavenegar extends Facade
{
    protected static function getFacadeAccessor() { return 'kavenegar'; }
}
