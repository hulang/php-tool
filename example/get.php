<?php

declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use hulang\tool\Arr;

$arr = [
    ['name' => ['ddf', 'emd', 'dd' => ['test']]],
    'sex' => '女'
];

Arr::dd(Arr::getData($arr, '0.name.dd', 0));
Arr::dd(Arr::getData($arr, 'sex', 0));
