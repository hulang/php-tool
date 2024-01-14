<?php

declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use hulang\tool\Arr;

$records = [
    [
        'state' => 0,
        'city' => '上海',
        'object' => '公交',
    ],
    [
        'state' => 1,
        'city' => '广州',
        'object' => '私家车',
    ],
    [
        'state' => 0,
        'city' => '山东',
        'object' => '公交',
    ],
    [
        'state' => 1,
        'city' => '上海',
        'object' => '私家车',
    ],
    [
        'state' => 0,
        'city' => '山东',
        'object' => '毛驴',
    ],
];
echo '<pre>';
print_r(Arr::pf_array_group_by($records, 'city'));

/*
 *  //返回结果
Array
(
    [上海] => Array
        (
            [0] => Array
                (
                    [state] => 0
                    [city] => 上海
                    [object] => 公交
                )

            [1] => Array
                (
                    [state] => 1
                    [city] => 上海
                    [object] => 私家车
                )

        )

    [广州] => Array
        (
            [0] => Array
                (
                    [state] => 1
                    [city] => 广州
                    [object] => 私家车
                )
        )
    [山东] => Array
        (
            [0] => Array
                (
                    [state] => 0
                    [city] => 山东
                    [object] => 公交
                )
            [1] => Array
                (
                    [state] => 0
                    [city] => 山东
                    [object] => 毛驴
                )
        )

)
 */

print_r(Arr::pf_array_group_by($records, 'city', 'state'));

/* 返回
Array
(
    [上海] => Array
        (
            [0] => Array
                (
                    [0] => Array
                        (
                            [state] => 0
                            [city] => 上海
                            [object] => 公交
                        )

                )

            [1] => Array
                (
                    [0] => Array
                        (
                            [state] => 1
                            [city] => 上海
                            [object] => 私家车
                        )

                )

        )

    [广州] => Array
        (
            [1] => Array
                (
                    [0] => Array
                        (
                            [state] => 1
                            [city] => 广州
                            [object] => 私家车
                        )

                )

        )

    [山东] => Array
        (
            [0] => Array
                (
                    [0] => Array
                        (
                            [state] => 0
                            [city] => 山东
                            [object] => 公交
                        )

                    [1] => Array
                        (
                            [state] => 0
                            [city] => 山东
                            [object] => 毛驴
                        )

                )

        )

)
*/