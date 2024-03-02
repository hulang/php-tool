<?php

declare(strict_types=1);

use hulang\tool\Arr;

$arr = [
    ['id' => 10, 'value' => 1],
    ['id' => 11, 'value' => 12],
    ['id' => 12, 'value' => 11],
    ['id' => 13, 'value' => 10],
    ['id' => 14, 'value' => 1],
    ['id' => 15, 'value' => 9],
    ['id' => 16, 'value' => 1],
];

Arr::dd(Arr::arrayRandByWeight($arr));
