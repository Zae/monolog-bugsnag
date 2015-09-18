# monolog-bugsnag

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

Monolog Handler for sending errors to bugsnag. This way not only crashes will be send but you can easily send other
debug information to bugsnag by reusing your monolog error handling.

## Install

Via Composer

``` bash
$ composer require zae/monolog-bugsnag
```

## Usage

``` php
$bugsnag = new Bugsnag_Client('YOUR_API_KEY');

$logger = new Logger('my_logger');
$logger->pushHandler(new BugsnagHandler(Logger::WARNING, true, 'BugsnagMonolog', $bugsnag));
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email ezra@tsdme.nl instead of using the issue tracker.

## Credits

- [Ezra Pool][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/zae/monolog-bugsnag.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/zae/monolog-bugsnag.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/zae/monolog-bugsnag
[link-downloads]: https://packagist.org/packages/zae/monolog-bugsnag
[link-author]: https://github.com/zae
