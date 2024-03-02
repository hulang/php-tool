<?php

declare(strict_types=1);

use hulang\tool\Arr;

$array = [100, 200, 300];

$value = Arr::arrayLast($array, function ($value, $key) {
    return $value >= 300;
});
Arr::dd($value);
