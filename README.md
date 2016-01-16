Gecoder for Zend Framework 2
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
=============
Copy the `config/zf.geocoder.local.php.dist` to the `config/autoload` directory and remove the `dist` extension to jump start configuration.