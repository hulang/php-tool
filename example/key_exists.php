<?php

declare(strict_types=1);

use hulang\tool\Arr;

$arr = [
    'name' => 'pfinal',
    'sex' => 12,
    'ADDRESS' => '上海',
    'def' => [
        'a' => 'img',
        'size' => '12',
        'pfinal' => [
            'pf' => 'pf社区'
        ]
    ]
];

Arr::dd(Arr::keyExists($arr, 'pf'), 0);
