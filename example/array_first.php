<?php

declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use hulang\tool\Arr;

$array = [100, 200, 300, ['300', '400']];

$value = Arr::pf_array_first($array, function ($value, $key) {
    return $value >= 350;
});
Arr::dd($value);
