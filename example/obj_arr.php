<?php

declare(strict_types=1);

use hulang\tool\Arr;

class obj
{
}

$obj = new obj();
$obj->body           = 'another post';
$obj->id             = 21;
$obj->approved       = true;
$obj->favorite_count = 1;
$obj->status         = NULL;
//echo json_encode($obj);
Arr::dd(Arr::objArr($obj));
