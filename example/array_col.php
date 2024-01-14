<?php

declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use hulang\tool\Arr;

$records = [
    [
        'city'  => '上海',
        'age'   => 18,
        'name'  => '马二'
    ],
    [
        'city'  => '上海',
        'age'   => 20,
        'name'  => '翠花'
    ]
];

Arr::dd(Arr::pf_array_col($records, 'city'));
