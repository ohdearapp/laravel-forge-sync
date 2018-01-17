# Sync Laravel Forge sites with Oh Dear!

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ohdearapp/laravel-forge-sync.svg?style=flat-square)](https://packagist.org/packages/ohdearapp/laravel-forge-sync)
[![Build Status](https://img.shields.io/travis/ohdearapp/laravel-forge-sync/master.svg?style=flat-square)](https://travis-ci.org/ohdearapp/laravel-forge-sync)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/xxxxxxxxx.svg?style=flat-square)](https://insight.sensiolabs.com/projects/xxxxxxxxx)
[![Quality Score](https://img.shields.io/scrutinizer/g/ohdearapp/laravel-forge-sync.svg?style=flat-square)](https://scrutinizer-ci.com/g/ohdearapp/laravel-forge-sync)
[![Total Downloads](https://img.shields.io/packagist/dt/ohdearapp/laravel-forge-sync.svg?style=flat-square)](https://packagist.org/packages/ohdearapp/laravel-forge-sync)

This package allows you to easily synchronize your [Laravel Forge](https://forge.laravel.com) Apps with the [Oh-Dear! App](https://ohdearapp.com).
## Installation

You can install the package via composer:

```bash
composer require ohdear/forge-sync
```

You must publish the configuration file with:

``` bash
php artisan vendor:publish --provider="OhDear\ForgeSync\ForgeSyncServiceProvider"
```

This is the content of the file that will be published at `config/forge-sync.php`. You should provide an Oh Dear! API token and a Forge API token.

```` php 
return [

    /**
     * An Oh Dear! API token.
     *
     * Learn how to get an API token at the Oh Dear! docs
     * https://ohdearapp.com/docs/api/authentication
     */
    'ohdear_api_token' => '',

    /**
     * A Forge API token.
     *
     * You can create an API token here:
     * https://forge.laravel.com/user/profile#/api
     */
    'forge_api_token' => '',
    
];
````

## Usage

When you edit the configuration values just type:
``` bash
php artisan ohdear:forge-sync
```
And you should be asked about the team and the sites that should be synced.

### Screencast
![Screencast of Usage](http://g.recordit.co/dPu0Ha2ErB.gif)

### Usage as Code
You can use this package as Code too, just use the ForgeSync::class:

``` php 
$forgeSync = new OhDear\ForgeSync\ForgeSync($ohdear_team_id, $ohdear_api_token = null, $forge_api_token = null);
foreach ($forgeSync->getSyncableSites() as $syncableSite) {
    $sync = $forgeSync->registerSiteAtOhDear($syncableSite->getUrl());
}
```
### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email support@ohdearapp.com instead of using the issue tracker.

## Credits

- [Lukas Kämmerling](https://github.com/LKDevelopment)
- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
