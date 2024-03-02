<?php

declare(strict_types=1);

use hulang\tool\Arr;

$arr = [
    ['name' => 'TOP1', 'cid' => 0, 'id' => 1],
    ['name' => 'TOP1', 'cid' => 1, 'id' => 20],
    ['name' => 'TOP1', 'cid' => 0, 'id' => 2],
    ['name' => 'TOP1', 'cid' => 2, 'id' => 40],
    ['name' => 'TOP1', 'cid' => 2, 'id' => 5],
];

Arr::dd(Arr::getDataTree($arr, 0, 'cid'));
