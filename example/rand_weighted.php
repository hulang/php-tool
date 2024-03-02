<?php

declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use hulang\tool\Arr;

$arr = [['dd', 1], ['ff', 2], ['cc', 3], ['ee', 4]];

//出现 ee的次数相对于其他的次数要多一点
Arr::dd(Arr::randWeighted($arr));
