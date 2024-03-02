<?php

declare(strict_types=1);

use hulang\tool\Arr;

$arr = [1, 2, 3, 4, [6, 4, 5, 7]];

//Arr::dd(sort($arr),0);

Arr::dd(Arr::arrSort($arr));
