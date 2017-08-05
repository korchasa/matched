# Simple pattern matching for PHP 

[![Latest Stable Version](https://img.shields.io/packagist/v/korchasa/matched.svg?style=flat-square)](https://packagist.org/packages/korchasa/matched)
[![Build Status](https://travis-ci.org/korchasa/matched.svg?style=flat-square)](https://travis-ci.org/korchasa/matched)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.0-8892BF.svg?style=flat-square)](https://php.net/)

## Install:
```bash
composer require korchasa/matched
```

## Usage:

Use *** for "any value".

Functions or class:
```php
matched_array([ 'foo' => [ 'any' => '***' ] ], $someArray); 
Matched::array([ 'foo' => [ 'any' => '***' ] ], $someArray);
```

Arrays:
```php
$someArray = [ 'foo' => [ 'any' => 11 ] ];
matched_array([ 'foo' => [ 'any' => '***' ]], $someArray); //true
matched_array([ 'foo' => [], $someArray); //true
matched_array([ 'foo' => [ 'not_any' => 13 ]], $someArray); //false (missed key foo.not_any)
matched_array([ 'foo' => [ 'any' => 12 ]], $someArray); //false (not equals values foo.any)
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

matched_json('{
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
matched_string('12345***0ab***f', '1234567890abcdef'); //true          
```

Case:
```php
$processingResult = Matched::case('user@company2.com')
    ->string('***@company1.com', function($val) { return $this->processCompany1Email($val); })
    ->string('***@company2.com', function($val) { return $this->processCompany2Email($val); })
    ->default(function ($val) { return $this->processUsualEmails($val); });
```