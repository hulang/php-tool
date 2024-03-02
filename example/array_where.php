<?php

declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use hulang\tool\Arr;

$array = [100, '200', 300, '400', 500];

$array = Arr::arrayWhere($array, function ($value, $key) {
    return is_string($value);
});
Arr::dd($array);
