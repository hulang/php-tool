<?php

declare(strict_types=1);

use hulang\tool\Arr;

$array = [100, 200, 300, ['300', '400']];

$value = Arr::arrayFirst($array, function ($value, $key) {
    return $value >= 350;
});
Arr::dd($value);
