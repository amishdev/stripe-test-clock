<?php

namespace Amish\StripeTestClock;

class StripeTestClock
{

    public function stripeOptions(array $options = [])
    {
        if(config('stripe-test-clock.enabled', false)) {
            $clock = TestClock::first();

            if($clock !== null && $clock->enabled) {
                $options['test_clock'] = $clock->stripe_id;
            }
        }

        return $options;
    }

}