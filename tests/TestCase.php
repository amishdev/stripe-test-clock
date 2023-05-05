<?php
namespace Amish\StripeTestClock\Tests;

use Amish\StripeTestClock\StripeTestClockServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected $loadEnvironmentVariables = true;

    protected function getPackageProviders($app): array
    {
        return [
            StripeTestClockServiceProvider::class,
        ];
    }
}