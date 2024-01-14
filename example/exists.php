<?php

declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use hulang\tool\Arr;

$data = [
    'k0' => 'v0',
    'k1' => [
        'k1-1' => 'v1-1'
    ],
    'complex_[name]_!@#$&%*^' => 'complex',
    'k2' => 'string'
];

var_dump(Arr::pf_exists('k0', $data));
// returns: true
var_dump(Arr::pf_exists('k9', $data));
// returns: false
var_dump(Arr::pf_exists('[k1][k1-1]', $data));
// returns: true
Arr::pf_exists('[k1][k1-2]', $data); // returns: false
Arr::pf_exists('["complex_[name]_!@#$&%*^"]', $data); // returns: true
Arr::pf_exists('[k2][2]', $data); // returns: false