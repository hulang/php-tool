<?php

declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use hulang\tool\Arr;

$arr = [
    ['name' => ['ddf', 'emd', 'dd' => ['test']]],
    'sex' => '女'
];
Arr::dd(Arr::pf_set($arr, '0.name.dd', '大爷'));
