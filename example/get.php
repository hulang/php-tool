<?php

declare(strict_types=1);

use hulang\tool\Arr;

$arr = [
    ['name' => ['ddf', 'emd', 'dd' => ['test']]],
    'sex' => '女'
];

Arr::dd(Arr::getData($arr, '0.name.dd', 0));
Arr::dd(Arr::getData($arr, 'sex', 0));
