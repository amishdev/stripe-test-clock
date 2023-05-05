<?php

namespace Amish\StripeTestClock\Commands;

use Amish\StripeTestClock\TestClock;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class AdvanceClock extends Command
{
    protected $signature = 'test-clock:advance {to?} {--d|day=0} {--w|week=0} {--m|month=0} {--id : the id of the clock to advance}';

    public function handle()
    {
        $clock = $this->getClock();

        if (!$clock) {
            $this->error('could not retrieve test clock');
            return 1;
        }

        $date = $this->getDate($clock);

        if (!$date) {
            return 2;
        }

        try {
            $clock->advanceClock($date);

            $this->info("Advancing the clock to {$date->format('m/d/y')}");
        } catch (Exception $exception) {
            $this->error("Failed with Exception: {$exception->getMessage()}");
        }

        return 0;
    }

    protected function getClock(): TestClock|null
    {
        if ($id = $this->option('id')) {
            // check if it's a stripe id
            if (str_starts_with($id, 'clock')) {
                return TestClock::where('stripe_id', $id)->first();
            }
            // otherwise try to find by primary key.
            return TestClock::find($id);
        }
        // by default find the first clock
        return TestClock::first();

    }

    protected function getDate(TestClock $clock)
    {
        if ($to = $this->argument('to')) {
            return Carbon::parse($to);
        }

        $days = (int) $this->option('day');
        $weeks = (int) $this->option('week');
        $months = (int) $this->option('month');

        return $clock->time->clone()->addDays($days)->addWeeks($weeks)->addMonths($months);
    }

}