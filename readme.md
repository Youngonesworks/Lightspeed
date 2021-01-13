<p align="center">
    <img title="Lightspeed" height="200" src="https://raw.githubusercontent.com/Youngonesworks/Lightspeed/master/docs/images/logo-readme.png" />
</p>
<p align="center">
      <a href="https://packagist.org/packages/laravel-zero/framework"><img src="https://img.shields.io/packagist/dt/youngones/lightspeed.svg" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/laravel-zero/framework"><img src="https://img.shields.io/packagist/v/youngones/lightspeed.svg?label=stable" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/laravel-zero/framework"><img src="https://img.shields.io/packagist/l/youngones/lightspeed.svg" alt="License"></a>
</p>

Lightspeed is a lightweight communication solution for distributed Laravel applications inspired by gRPC. Without Protocol buffers.

What Lightspeed essentially does is run your normal routes, but streams the output over a TCP socket. The data is encoded as [CBOR](https://cbor.io/). 

## Installation

Via Composer

``` bash
$ composer require youngones/lightspeed
```

## Usage

### Server side:
1. Define the route you wish to request via Lightspeed:
```php
// ./routes/web.php
Route::lightspeed('/test', 'TestController');
```
2. Start the Lightspeed server:

```
$ php artisan lightspeed:server start
```

### Client side:
```php
// Create an instance of `\YoungOnes\Lightspeed\Client\Client`
$client = new \YoungOnes\Lightspeed\Client\Client();
// Create a request
$request = new \YoungOnes\Lightspeed\Requests\Request('127.0.0.1:9810', '/api/test', ['Authorization' => 'Bearer'])
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits

- [author name][link-author]
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/youngones/lightspeed.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/youngones/lightspeed.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/youngones/lightspeed/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/youngones/lightspeed
[link-downloads]: https://packagist.org/packages/youngones/lightspeed
[link-travis]: https://travis-ci.org/youngones/lightspeed
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/youngones
[link-contributors]: ../../contributors
