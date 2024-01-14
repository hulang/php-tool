<?php

declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use hulang\tool\Arr;

$arr = [1, 54, 'a', 45, 12, 'c', 1, 1, 12, [1, 1, 'a', ['a', 'b', 'a']]];

Arr::dd(Arr::pf_array_shuffle($arr));
var_dump($arr);
