# Simple pattern matching for PHP 

[![Stable Version](https://img.shields.io/packagist/v/korchasa/matcho.svg?style=flat-square)](https://packagist.org/packages/korchasa/matcho)
[![Unstable Version](https://img.shields.io/packagist/vpre/korchasa/matcho.svg?style=flat-square)](https://packagist.org/packages/korchasa/matcho)
[![Build Status](https://travis-ci.org/korchasa/matcho.svg?style=flat-square)](https://travis-ci.org/korchasa/matcho)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.0-8892BF.svg?style=flat-square)](https://php.net/)

## Install:
```bash
composer require korchasa/matcho
```

## Usage:

Use *** for "any value".

Functions or class:
```php
match_array([ 'foo' => [ 'any' => '***' ] ], $someArray); 
Match::array([ 'foo' => [ 'any' => '***' ] ], $someArray);
```

Arrays:
```php
$someArray = [ 'foo' => [ 'any' => 11 ] ];
Match::array([ 'foo' => [ 'any' => '***' ]], $someArray); //true
Match::array([ 'foo' => [], $someArray); //true
Match::array([ 'foo' => [ 'not_any' => 13 ]], $someArray); //false (missed key foo.not_any)
Match::array([ 'foo' => [ 'any' => 12 ]], $someArray); //false (not equals values foo.any)
```

JSON:
```php
$someJson = '{
    "foo": "bar",
    "baz": { "value": 1 },
    "items": [
        { "a": "b", "c": 2 },
        { "z": "x", "c": 3 }    
    ]
}';

Match::json('{
        "foo": "bar",
        "baz": "***",
        "items": [
            "***",
            { "z": "x", "c": 3 }    
        ]
    }',
    $someJson
); //true
```

String:
```php
Match::string('12345***0ab***f', '1234567890abcdef'); //true          
```

Custom "any symbol":
```php
Match::string('12345***0ab***f', '1234567890abcdef', '%%%'); //false
```

Case:
```php
$processingResult = Match::case('user@company2.com')
    ->string('***@company1.com', function($val) { return $this->processCompany1Email($val); })
    ->string('***@company2.com', function($val) { return $this->processCompany2Email($val); })
    ->default(function ($val) { return $this->processUsualEmails($val); });
```