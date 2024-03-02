<?php

declare(strict_types=1);

use hulang\tool\Arr;

$array1 = array(1, 2, 3, 4, 5, 6);
$array2 = array(3, 1, 5, 6, 7, 8);

echo '<pre>';

Arr::dd(Arr::delVal($array1, [1, 2, 3]));
Arr::dd(Arr::delVal($array2, [6, 10]));
