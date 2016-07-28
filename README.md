Geocoder for Zend Framework 2
===

This package allows you to use [**Geocoder**](http://geocoder-php.org/Geocoder/) in [**Zend Framework 2**](http://framework.zend.com/)

Requirements
------------
  
Please see the [composer.json](composer.json) file.

Installation
------------

Run the following `composer` command:

```console
$ composer require "jguittard/zf-geocoder:~1.0-dev"
```

Alternately, manually add the following to your `composer.json`, in the `require` section:

```javascript
"require": {
    "jguittard/zf-geocoder": "~1.0-dev"
}
```

And then run `composer update` to ensure the module is installed.

Finally, add the module name to your project's `config/application.config.php` under the `modules`
key:

```php
return array(
    /* ... */
    'modules' => array(
        /* ... */
        'ZF\Geocoder',
    ),
    /* ... */
);
```

Configuration
-------------
Copy the `config/zf.geocoder.local.php.dist` to the `config/autoload` directory and remove the `dist` extension to jump start configuration.

Usage
-----
You can retrieve Geocoder documentation

The following will handle setup and service management within this Zend Framework 2 module.

First, make sure you have set up your configuration file by commenting out the provider(s) you'd like to use.

For example, in `config/autoload/zf.geocoder.local.php`:
```php
return [
    'geocoder' => [
        'httpAdapter' => '<SERVICE_KEY_OF_HTTP_ADAPTER>',
        'providers' => [
            'google_maps' => [
                'locale' => 'fr_FR',
                'region' => 'Île-de-France',
                'useSsl' => false,
            ],
        ],
    ],
];
```

Every provider is exposed as a **service** in the main Service Manager following the convention: `Geocoder\<PROVIDER_NAME_IN_CAMELCASE>`
Such service will be an instance of corresponding `Geocoder\Provider\<PROVIDER_NAME_IN_CAMELCASE>` with appropriate configuration passed to its constructor

For example, in your controller:
```php
public function localeAction()
{
    /* ... */
    $geocoder = $this->getServiceLocator()->get('Geocoder\GoogleMaps');
    $addressCollection = $geocoder->geocode('10 avenue Gambetta, Paris, France');
    /* ... */
}
```

_Obviously, such a call is bad practice. You should consider injecting your Geocoder service in a dedicated service, not call it from a controller_

Unit tests
---
Make sure `dev` dependencies are installed
```console
composer install --dev
```
Run the unit tests
```console
./vendor/bin/phpunit
```

Code styling check
```console
./vendor/bin/phpcs
```