<?php

namespace Kavenegar\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return 'Kavenegar\KavenegarServiceProvider';
    }
    protected function getPackageAliases($app)
    {
        return [
            'Kavenegar' => 'Kavenegar\Facades\Kavenegar'
        ];
    }
}

