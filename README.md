# Import Laravel Forge sites to Oh Dear!

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ohdearapp/laravel-forge-sync.svg?style=flat-square)](https://packagist.org/packages/ohdearapp/laravel-forge-sync)
[![Build Status](https://img.shields.io/travis/ohdearapp/laravel-forge-sync/master.svg?style=flat-square)](https://travis-ci.org/ohdearapp/laravel-forge-sync)
[![StyleCI](https://styleci.io/repos/117903870/shield?branch=master)](https://styleci.io/repos/117903870)
[![Quality Score](https://img.shields.io/scrutinizer/g/ohdearapp/laravel-forge-sync.svg?style=flat-square)](https://scrutinizer-ci.com/g/ohdearapp/laravel-forge-sync)
[![Total Downloads](https://img.shields.io/packagist/dt/ohdearapp/laravel-forge-sync.svg?style=flat-square)](https://packagist.org/packages/ohdearapp/laravel-forge-sync)

This package allows you to easily import your [Laravel Forge](https://forge.laravel.com) sites to your [Oh-Dear! App](https://ohdearapp.com) account.

![Screencast of Usage](https://ohdearapp.github.io/laravel-forge-sync/demo.gif)

At the moment it will only import sites from Forge to Oh Dear!

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

    /*
     * An Oh Dear! API token.
     *
     * Learn how to get an API token at the Oh Dear! docs
     * https://ohdearapp.com/docs/api/authentication
     */
    'ohdear_api_token' => '',

    /*
     * A Forge API token.
     *
     * You can create an API token here:
     * https://forge.laravel.com/user/profile#/api
     */
    'forge_api_token' => '',
    
];
````

## Usage

Run this command to start the import process. It will ask you which Forge sites should be imported to which Oh Dear! team.

``` bash
php artisan ohdear:forge-sync
```

Alternatively you could also run this piece of code:


``` php 
use OhDear\ForgeSync\ForgeSync;
use OhDear\ForgeSync\Site;

$forgeSync = new ForgeSync(
   $ohDearTeamId, 
   $ohDearApiToken,
   $forgeApiToken
);

$forgeSync->sites()->each(function(Site $site) {
    $forgeSync->addToOhDear($site);
});
```

### Skipping sites

If you have a site on Forge that you do not wish to import into Oh Dear! simply add this line to the Nginx config of that site. 

```
#OH-DEAR-DO-NOT-MONITOR
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
