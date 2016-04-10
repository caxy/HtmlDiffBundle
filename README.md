[![Packagist](https://img.shields.io/packagist/dt/caxy/htmldiff-bundle.svg)](https://packagist.org/packages/caxy/htmldiff-bundle)
[![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/caxy/HtmlDiffBundle.svg)](http://isitmaintained.com/project/caxy/HtmlDiffBundle "Average time to resolve an issue")
[![Percentage of issues still open](http://isitmaintained.com/badge/open/caxy/HtmlDiffBundle.svg)](http://isitmaintained.com/project/caxy/HtmlDiffBundle "Percentage of issues still open")

HtmlDiffBundle
==============

Symfony Bundle for [caxy/php-htmldiff][0].

## Requirements

- PHP 5.3.3 or higher
- [caxy/php-htmldiff][0]

## Installation

You can install this bundle using composer:

```
composer require caxy/htmldiff-bundle
```

or add the package to your composer.json file directly.

After you have installed the package, you just need to add the bundle to your AppKernel.php file:

```php
// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new Caxy\HtmlDiffBundle\CaxyHtmlDiffBundle(),
    // ...
);
```

## Usage

## Configuration

## Contributing

## Contributor Code of Conduct

Please note that this project is released with a [Contributor Code of
Conduct](http://contributor-covenant.org/). By participating in this project
you agree to abide by its terms. See CODE_OF_CONDUCT file.

## License

caxy/HtmlDiffBundle is released under the MIT License. See the bundled LICENSE file for
details.

[0]: https://github.com/caxy/php-htmldiff
