<?php

declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

$datas = \pf\arr\Arr::CreateDateArray("2014-01-01", "2014-01-20", "+1 day", "m/d/Y");

echo '<pre>';
print_r($datas);

$dates_a = \pf\arr\Arr::CreateDateArray("01:00:00", "23:00:00", "+1 hour", "H:i:s");

print_r($dates_a);

$dates_b = \pf\arr\Arr::CreateCorrelationArray("2014-01-01", "2014-01-20", 0, "+1 day", "m/d/Y");

print_r($dates_b);
