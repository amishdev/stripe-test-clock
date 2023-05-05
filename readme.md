# StripeTestClock

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

Work with Stripe test clocks in laravel.

## Installation

Via Composer

``` bash
composer require amish/stripe-test-clock
```

### Publish config
```bash
php artisan vendor:publish --tag "stripe-test-clock.config"
```

### Run migrations
```bash
php artisan migrate
```

## Usage

Create a test clock:
```bash
php artisan test-clock:create "Test 1 week trial"
```

### Assigning test clocks to your customers.

To assign a clock to a customer for testing use the `StripeTestClock::stripeOptions()` method.
This will add the 'test_clock' key to your array if there is an active clock.
It will not add the key if 'enabled' is set to false in your config, or if you don't have a clock active.

```php
use Amish\StripeTestClock\Facades\StripeTestClock;

// using the StripeClient
$stripe->customers->create(StripeTestClock::stripeOptions([
    'email' => 'email@example.com',
    //...
]));

// Or using cashier
$user->createAsStripeCustomer(StripeTestClock::options());

```

From there you can work with the clock from the stripe dashboard or using the cli.

```bash
php artisan test-clock:advance -w 1 # advance the current clock by 1 week
php artisan test-clock:advance -m 1 -d 4 # advance the current clock by 1 month & 5 days
php artisan test-clock:advance "2023-07-19" # advance the clock to a specific date.
```

If a test clock is deleted in stripe or expires you can prune using the `artisan test-clock:prune` command to delete the models. 


When testing locally I recommend using the stripe cli to forward webhook events to your app.
```bash
stripe listen -f http://localhost/stripe/webhook
```



## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author@email.com instead of using the issue tracker.

## Credits

- [Author Name][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/amish/stripe-test-clock.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/amish/stripe-test-clock.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/amish/stripe-test-clock/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/amish/stripe-test-clock
[link-downloads]: https://packagist.org/packages/amish/stripe-test-clock
[link-travis]: https://travis-ci.org/amish/stripe-test-clock
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/amish
[link-contributors]: ../../contributors
