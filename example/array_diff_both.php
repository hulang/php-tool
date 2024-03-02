<?php

declare(strict_types=1);

use hulang\tool\Arr;

$arr = [1, 'a', 'c', 'e', 'f'];
$arr1 = [2, 6, 'c', 'f'];

Arr::dd(Arr::arrayFiffBoth($arr, $arr1));
