<?php
// StripeTestClockTest

use Amish\StripeTestClock\TestClock;
use Illuminate\Foundation\Testing\RefreshDatabase;

// todo: write tests using testbench.

uses(RefreshDatabase::class);

it('creates a test clock from stripe', function () {
    $clock = TestClock::create(['enabled' => true, 'stripe_id' => 'foo']);

    expect($clock->exists())->toBeTrue();
});