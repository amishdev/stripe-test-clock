<?php

namespace Amish\StripeTestClock\Commands;

use Amish\StripeTestClock\TestClock;
use Illuminate\Console\Command;
use Stripe\Exception\ApiErrorException;

class PruneClocks extends Command
{
    protected $signature = "test-clock:prune";

    protected $description = "removes any test-clock models that have been deleted from stripe";


    public function handle()
    {
        $deleteCount = 0;

        TestClock::each(function (TestClock $clock) use (&$deleteCount) {
            try {
                $clock->stripeClock;
            } catch (ApiErrorException) {
                $this->line("deleted test clock {$clock->stripe_id}");
                $deleteCount++;
                $clock->delete();
            }
        });

        $this->info("Deleted {$deleteCount} ".str('clock')->plural($deleteCount));

        return 0;
    }

}