<?php

namespace Amish\StripeTestClock;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Stripe\StripeClient;
use Stripe\TestHelpers\TestClock as StripeTestClock;

/**
 * @property string $name
 * @property string $stripe_id
 * @property bool $enabled
 * @property StripeClient $client
 * @property Carbon $time
 * @property StripeTestClock $stripeClock
 */
class TestClock extends Model
{

    protected $fillable = ['stripe_id', 'enabled', 'name'];


    protected static function booted()
    {
        static::creating(function (TestClock $clock) {
            if ($clock->stripe_id !== null) {
                $clock->stripe_id = $clock->createClockInStripe()->id;
            }
        });

        static::addGlobalScope('enabled', function (Builder $query) {
            $query->latest()->where('enabled', true);
        });
    }

    public function createClockInStripe(): StripeTestClock
    {
        return $this->client->testHelpers->testClocks->create([
            'frozen_time' => time(),
            'name' => $this->name,
        ]);
    }

    public function advanceClock(Carbon $date)
    {
        if ($date->lessThan($this->time)) {
            throw new AdvanceClockException(
                "Tried to advance clock from {$this->time->format('m/d/y')} to time in the past: {$date->format('m/d/y')}"
            );
        }

        $this->client->testHelpers->testClocks->advance($this->stripe_id, ['frozen_time' => $date->timestamp]);
    }

    public function time(): Attribute
    {
        return Attribute::get(
            fn() => Carbon::createFromTimestamp($this->stripeClock->frozen_time),
        )->shouldCache();
    }


    public function client(): Attribute
    {
        return Attribute::get(fn() => new StripeClient(config('stripe-test-clock.stripe_secret')));
    }


    public function stripeClock(): Attribute
    {
        return Attribute::get(
            fn() => $this->client->testHelpers->testClocks->retrieve($this->stripe_id),
        )->shouldCache();
    }

}