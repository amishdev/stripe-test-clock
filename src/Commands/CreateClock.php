<?php

namespace Amish\StripeTestClock\Commands;

use Amish\StripeTestClock\TestClock;
use Illuminate\Console\Command;

class CreateClock extends Command
{
    protected $signature = "test-clock:create {name?} {--enabled=true} {--stripe-id}";

    protected $description = "creates a new test clock in stripe";

    public function handle() : int
    {
        $clock = TestClock::create([
            'name' => $this->argument('name') ?? now()->format('Y-m-d'),
            'enabled' => $this->option('enabled') === 'true',
            'stripe_id' => $this->option('stripe-id'),
        ]);

        $this->info('Created TestClock');
        $this->table(
            ['id', 'name', 'stripe_id', 'enabled'],
            [[$clock->id, $clock->name, $clock->stripe_id, $clock->enabled]]
        );

        return 0;
    }

}