<?php

namespace Amish\StripeTestClock;

use Amish\StripeTestClock\Commands\AdvanceClock;
use Amish\StripeTestClock\Commands\CreateClock;
use Amish\StripeTestClock\Commands\PruneClocks;
use Illuminate\Support\ServiceProvider;

class StripeTestClockServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
         $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/stripe-test-clock.php', 'stripe-test-clock');

        // Register the service the package provides.
        $this->app->singleton('stripe-test-clock', function ($app) {
            return new StripeTestClock;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['stripe-test-clock'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/stripe-test-clock.php' => config_path('stripe-test-clock.php'),
        ], 'stripe-test-clock.config');


        // Registering package commands.
         $this->commands([CreateClock::class, AdvanceClock::class, PruneClocks::class]);
    }
}
