### Hexlet tests and linter status
[![Actions Status](https://github.com/InfluxOW/php-oop-project-lvl1/workflows/hexlet-check/badge.svg)](https://github.com/InfluxOW/php-oop-project-lvl1/actions)
[![Maintainability](https://api.codeclimate.com/v1/badges/5d9414318e4493b2c958/maintainability)](https://codeclimate.com/github/InfluxOW/php-oop-project-lvl1/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/5d9414318e4493b2c958/test_coverage)](https://codeclimate.com/github/InfluxOW/php-oop-project-lvl1/test_coverage)

### Requirements

* PHP >= 8.0
* Composer

### Usage

```php
<?php

use Hexlet\Validator\Validator;

$v = new \Hexlet\Validator\Validator();

// strings
$schema = $v->required()->string();

$schema->isValid('what does the fox say'); // true
$schema->isValid(''); // false

// numbers
$schema = $v->required()->number()->positive();

$schema->isValid(-10); // false
$schema->isValid(10); // true

// array shape
$schema = $v->array()->sizeof(2)->shape([
    'name' => $v->string()->required(),
    'age' => $v->number()->positive(),
]);

$schema->isValid(['name' => 'kolya', 'age' => 100]); // true
$schema->isValid(['name' => '', 'age' => null]); // false

// custom validation rules for existing validators
$fn = fn($value, $start) => str_starts_with($value, $start);
$v->addValidator('string', 'startWith', $fn);

$schema = $v->string()->test('startWith', 'H');

$schema->isValid('exlet'); // false
$schema->isValid('Hexlet'); // true

// custom validators
$fn = fn($value, $start) => str_starts_with((string) $value, (string) $start);
$v->addValidator('customValidator', 'startWith', $fn);

$schema = $v->customValidator()->test('startWith', 5);

$schema->isValid(13); // false
$schema->isValid('test'); // false
$schema->isValid(55); // true
```
