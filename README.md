# CustomerIO PHP SDK

[![latest version](https://img.shields.io/github/release/shapintv/customerio.svg?style=flat-square)](https://github.com/shapintv/customerio/releases)
[![build status](https://img.shields.io/travis/shapintv/customerio.svg?style=flat-square)](https://travis-ci.com/shapintv/customerio)
[![code coverage](https://img.shields.io/scrutinizer/coverage/g/shapintv/customerio.svg?style=flat-square)](https://scrutinizer-ci.com/g/shapintv/customerio)
[![quality score](https://img.shields.io/scrutinizer/g/shapintv/customerio.svg?style=flat-square)](https://scrutinizer-ci.com/g/shapintv/customerio)
[![total downloads](https://img.shields.io/packagist/dt/shapin/customerio.svg?style=flat-square)](https://packagist.org/packages/shapin/customerio)


## Install

Via Composer

``` bash
$ composer require shapintv/customerio
```

## Usage

``` php
$customerIOClient = CustomerIOClient::create($customerIOSiteId, $customerIOApiKey);
// Create a customer
$apiClient->customers()->createOrUpdate('my_custom_id', [
    'email' => 'georges@abitbol.com',
]);
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
