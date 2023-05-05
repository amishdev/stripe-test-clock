<?php

namespace Amish\StripeTestClock\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static stripeOptions($options = []): array
 */
class StripeTestClock extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'stripe-test-clock';
    }
}