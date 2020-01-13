# Laravel SMS
[![CircleCI](https://circleci.com/gh/djunehor/laravel-sms.svg?style=svg)](https://circleci.com/gh/djunehor/laravel-sms)
[![Latest Stable Version](https://poser.pugx.org/djunehor/laravel-sms/v/stable)](https://packagist.org/packages/djunehor/laravel-sms)
[![Total Downloads](https://poser.pugx.org/djunehor/laravel-sms/downloads)](https://packagist.org/packages/djunehor/laravel-sms)
[![License](https://poser.pugx.org/djunehor/laravel-sms/license)](https://packagist.org/packages/djunehor/laravel-sms)
[![StyleCI](https://github.styleci.io/repos/224398453/shield?branch=master)](https://github.styleci.io/repos/224398453)
[![Build Status](https://scrutinizer-ci.com/g/djunehor/laravel-sms/badges/build.png?b=master)](https://scrutinizer-ci.com/g/djunehor/laravel-sms/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/djunehor/laravel-sms/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/djunehor/laravel-sms/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/djunehor/laravel-sms/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/djunehor/laravel-sms/?branch=master)

Laravel VTU allows you to buy airtime and data plan, as well as pay for utility bill from your Laravel application using one of over 3 vtu providers, or your own vtu provider.

- [Laravel VTU](#laravel-vtu)
    - [Installation](#installation)
        - [Laravel 5.5 and above](#laravel-55-and-above)
        - [Laravel 5.4 and older](#laravel-54-and-older)
        - [Lumen](#lumen)
        - [Env Keys](#env-keys)
    - [Usage](#usage)
        - [All parts of speech](#using-helper-function)
    - [Available VTU Providers](#available-vtu-providers)
    - [Creating custom VTU Provider](#creating-custom-vtu-provider)
    - [Contributing](#contributing)

## Installation

### Step 1
You can install the package via composer:

```shell
composer require djunehor/laravel-vtu
```

#### Laravel 5.5 and above

The package will automatically register itself, so you can start using it immediately.

#### Laravel 5.4 and older

In Laravel version 5.4 and older, you have to add the service provider in `config/app.php` file manually:

```php
'providers' => [
    // ...
    Djunehor\Vtu\VtuServiceProvider::class,
];
```
#### Lumen

After installing the package, you will have to register it in `bootstrap/app.php` file manually:
```php
// Register Service Providers
    // ...
    $app->register(Djunehor\Vtu\VtuServiceProvider::class);
];
```

#### Env Keys
```dotenv

BETASMS_USERNAME=
BETASMS_PASSWORD=


```


### Step 2 - Publishing files
Run:
`php artisan vendor:publish --tag=laravel-vtu`
This will move the migration file, seeder file and config file to your app. You can set your sms details in the config file or via env

### Step 3 - Adding SMS credentials
- Add the env keys to your `.env` file
- Or edit the config/laravel-vtu.php file


## Usage
```php
//using VoiceAndText
use Djunehor\Vtu\Concrete\VoiceAndText;

$vtu = new VoiceAndText();
$amount = 100;
$mobileNumber = '08149659347';
$mobileNetwork = '01';
$callBackUrl = 'http://www.your-website.com';
$send = $vtu->buyAirtime($amount, $mobileNumber, $mobileNetwork, $callBackUrl);
```

### Using Helper function
```php
//VoiceAndtext
$send = buy_airtime($amount, $mobileNumber, $mobileNetwork, $callBackUrl, $token = 121231112, \Djunehor\Vtu\Concrete\VoiceAndText::class);
```
The default VTU provider is VoiceAndText. You can set the default SMS provider in `config/laravel-vtu.php` e.g ` 'default' => \Djunehor\Vtu\Concrete\VoiceAndText::class,`, so you can use the helper function like this:
```php
$send = buy_airtime($amount, $mobileNumber, $mobileNetwork);
//$token, $callbackUrl and $class are optional and better set in the config
```

### Available VTU Providers
|Provider|URL|Tested|
|:--------- | :-----------------: | :------: |
|VoiceAndText|https://www.voiceandtext.com/vtuapi.pdf|Yes|
|CowrieSys|https://github.com/cowriesys/airtime|No|
|FpeVtu|https://fpevtu.com/|No|
|MobileAirtimeNig|https://mobileairtimeng.com/api|No|
|UnikMobileNig|https://unikmobileng.com/client/api|No|
|VTPass|https://www.vtpass.com/documentation/buying-services/|No|

### Creating custom SMS Provider
- Create a class that extends `Djunehor\Vtu\Concrete\Vtu` class
- Implement the required methods (buyAirtime, buyData, PayUtility) which makes the request and return bool
- (Optional) You can add the provider keys to the config/laravel-vtu.php

## Contributing
- Fork this project
- Clone your forked repo
- Run `composer install`
- Make your changes and run tests `composer test`
- Push and create Pull request
