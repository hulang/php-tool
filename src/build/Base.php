<?php

declare(strict_types=1);

namespace hulang\tool\build;

class Base
{
    use ArrFormat;
    use ArrToCsv;
    use ArrCheck;
    use DateArr;

    /**
     * 移除数组中的某个值 获取新数组
     * @param array $data
     * @param array $values
     * @return mixed|array
     */
    public function delVal(array $data, array $values)
    {
        $news = [];
        foreach ($data as $key => $v) {
            if (!in_array($v, $values)) {
                $news[$key] = $v;
            }
        }
        return $news;
    }

    /**
     * 根据键名获取值 支持点语法
     * @param array $data
     * @param $key
     * @param null $value
     * @return mixed|array|null
     */
    public function getData(array $data, $key, $value = null)
    {
        $exp = explode('.', $key);
        foreach ((array)$exp as $d) {
            if (isset($data[$d])) {
                $data = $data[$d];
            } else {
                return $value;
            }
        }
        return $data;
    }

    /**
     * 设置数组元素值支持点语法
     * @param array $data
     * @param $key
     * @param $value
     * @return mixed|array
     */
    public function setData(array $data, $key, $value)
    {
        $tmp = &$data;
        foreach (explode('.', $key) as $d) {
            if (!isset($tmp[$d])) {
                $tmp[$d] = [];
            }
            $tmp = &$tmp[$d];
        }
        $tmp = $value;
        return $data;
    }

    /**
     * 不区分大小写 检测数据数据键名
     * @param $arr
     * @param $key
     * @return mixed|bool
     */
    public function keyExists($arr, $key)
    {
        if (!is_array($arr)) return false;
        if (array_key_exists(strtolower($key), $arr)) {
            return true;
        } else {
            foreach ($arr as $value) {
                if (is_array($value)) {
                    return $this->keyExists($value, $key);
                }
            }
        }
    }

    /**
     * 过滤数组
     * @param array $data
     * @param array $keys
     * @param int $type
     * @return mixed|array
     */
    public function filterKeys(array $data, array $keys, $type = 1)
    {
        $tmp = $data;
        foreach ($data as $k => $v) {
            if ($type == 1) {
                // 存在时过滤
                if (in_array($k, $keys)) {
                    unset($tmp[$k]);
                }
            } else {
                // 不在时过滤
                if (!in_array($k, $keys)) {
                    unset($tmp[$k]);
                }
            }
        }
        return $tmp;
    }

    /**
     * 多维数组排序
     * @param $arr
     * @return mixed
     */
    public function arrSort($arr)
    {
        $len = count($arr);
        for ($i = 1; $i < $len; $i++) {
            for ($k = 0; $k < $len - $i; $k++) {
                if (is_array($arr[$k + 1])) {
                    $arr[$k + 1] = $this->arrSort($arr[$k + 1]);
                } else {
                    if ($arr[$k] > $arr[$k + 1]) {
                        $tmp = $arr[$k + 1];
                        $arr[$k + 1] = $arr[$k];
                        $arr[$k] = $tmp;
                    }
                }
            }
        }
        return $arr;
    }

    /**
     * 二级获取树形结构
     * @param $list
     * @param int $parent_id
     * @param string $field
     * @param string $field_key
     * @return mixed|array
     */
    public function tree($list, $parent_id = 0, $field = 'parent_id', $field_key = 'id')
    {
        $arr = [];
        $tree = [];
        foreach ($list as $value) {
            $arr[$value[$field]][] = $value;
        }

        foreach ($arr[$parent_id] as $key => $val) {
            $tree[$key][] = $val;
            if (isset($arr[$val[$field_key]]) && count($arr[$val[$field_key]]) > 0) {
                foreach ($arr[$val[$field_key]] as $v) {
                    $tree[$key]['son'][] = $v;
                }
            }
        }
        return $tree;
    }

    /**
     * 多级获取树形结构
     * @param $list
     * @param int $parent_id
     * @param string $field
     * @param string $field_key
     * @return mixed|array
     */
    public function getDataTree($list, $parent_id = 0, $field = 'parent_id', $field_key = 'id')
    {
        $tree = [];
        if (!empty($list)) {
            // 先修改为以id为下标的列表

            $newList = [];

            foreach ($list as $k => $v) {
                $newList[$v[$field_key]] = $v;
            }
            // 然后开始组装成特殊格式
            foreach ($newList as $value) {

                if ($parent_id == $value[$field]) { //先取出顶级
                    $tree[] = &$newList[$value[$field_key]];
                } elseif (isset($newList[$value[$field]])) {
                    // 再判定非顶级的pid是否存在，如果存在，则再pid所在的数组下面加入一个字段items，来将本身存进去
                    $newList[$value[$field]]['items'][] = &$newList[$value[$field_key]];
                }
            }
        }
        return $tree;
    }

    /**
     * 数组去重
     * @param $arr
     * @return mixed|array
     */
    public function arrayUnique($arr)
    {
        $dime = $this->arrayDepth($arr);
        if ($dime <= 1) {
            $data = array_unique($arr);
        } else {
            $temp = [];
            $new_data = [];
            foreach ($arr as $key => $v) {
                if (is_array($v)) {
                    $new_data = $this->arrayUnique($v);
                } else {
                    $temp[$key] = $v;
                }
            }
            $data = array_unique($temp);
            array_push($data, $new_data);
        }
        return $data;
    }

    /**
     * 检测数组的维度
     * @param $array
     * @return mixed|int
     */
    public function arrayDepth($array)
    {
        if (!is_array($array)) return 0;
        $max_depth = 1;
        foreach ($array as $value) {
            if (is_array($value)) {
                $depth = $this->arrayDepth($value) + 1;

                if ($depth > $max_depth) {
                    $max_depth = $depth;
                }
            }
        }
        return $max_depth;
    }

    /**
     * 数组中指定的一列
     * @param $array
     * @param $columnKey
     * @param null $indexKey
     * @return mixed|array
     */
    public function arrayCol($array, $columnKey, $indexKey = null)
    {
        $result = array();
        if (!empty($array)) {
            if (!function_exists('array_column')) {
                foreach ($array as $val) {
                    if (!is_array($val)) {
                        continue;
                    } elseif (is_null($indexKey) && array_key_exists($columnKey, $val)) {
                        $result[] = $val[$columnKey];
                    } elseif (array_key_exists($indexKey, $val)) {
                        if (is_null($columnKey)) {
                            $result[$val[$indexKey]] = $val;
                        } elseif (array_key_exists($columnKey, $val)) {
                            $result[$val[$indexKey]] = $val[$columnKey];
                        }
                    }
                }
            } else {
                $result = array_column($array, $columnKey, $indexKey);
            }
        }
        return $result;
    }

    /**
     * 对象转换成数组
     * @param $obj
     * @return mixed|array
     */
    public function objArr($obj)
    {
        $arr = is_object($obj) ? get_object_vars($obj) : $obj;
        if (is_array($arr)) {
            return array_map(array(
                __CLASS__,
                __FUNCTION__
            ), $arr);
        } else {
            return $arr;
        }
    }

    /**
     * 将多维折叠数组变为一维
     *
     * @param array $values 多维数组
     * @param bool $drop_empty 去掉为空的值
     * @return mixed|array
     */
    public function arrayFlatten(array $values, $drop_empty = false)
    {
        $result = [];
        array_walk_recursive($values, function ($value)
        use (&$result, $drop_empty) {
            if (!$drop_empty || !empty($value)) {
                $result[] = $value;
            }
        });
        return $result;
    }


    /**
     * 根据权重获取随机区间返回ID
     * @param array $array 格式为  array(array('id'=>'','value'=>''),array('id'=>'','value'=>''))   //id为标识,value为权重
     * @return mixed|int|string
     */
    public function arrayRandByWeight($array)
    {
        if (!empty($array)) {
            //区间最大值
            $sum = 0;
            //区间数组
            $interval = array();
            //制造区间
            foreach ($array as $value) {
                $interval[$value['id']]['min'] = $sum + 1;
                $interval[$value['id']]['max'] = $sum + $value['value'];
                $sum += $value['value'];
            }
            //在区间内随机一个数
            $result = rand(1, $sum);
            //获取结果属于哪个区间, 返回其ID
            foreach ($interval as $id => $v) {
                while ($result >= $v['min'] && $result <= $v['max']) {
                    return $id;
                }
            }
        }
        return 0;
    }

    /**
     * 二维数组验证一个值是否存在
     * @param $value
     * @param $array
     * @return mixed|bool
     */
    public function deepInArray($array, $value)
    {
        foreach ($array as $item) {
            if (!is_array($item)) {
                if ($item == $value) {
                    return true;
                } else {
                    continue;
                }
            }

            if (in_array($value, $item)) {
                return true;
            } else if ($this->deepInArray($item, $value)) {
                return true;
            }
        }
        return false;
    }


    /**
     * 随机返回 数组 的值
     * @param $array
     * @param int $len
     * @return mixed|array|bool
     */
    public function randVal($array, $len = 1)
    {
        if (!is_array($array)) {
            return false;
        }
        $keys = array_rand($array, $len);
        if ($len === 1) {
            return $array[$keys];
        }
        return array_intersect_key($array, array_flip($keys));
    }


    /**
     * 按权重 随机返回数组的值
     * Example:$arr = [['dd',1],['ff',2],['cc',3],['ee',4]]; 出现 ee的次数相对于其他的次数要多一点
     * @param array $array
     * @return mixed|array|bool
     */
    public function randWeighted(array $array)
    {
        if (!is_array($array)) {
            return false;
        }
        $options = [];
        foreach ($array as $weight) {
            if (!is_array($weight)) {
                return false;
            }
            for ($i = 0; $i < $weight[1]; ++$i) {
                $options[] = $weight[0];
            }
        }
        return $this->randVal($options);
    }

    /**
     * 随机打乱数组
     * @param $array
     * @param bool $statue true or  false
     * @return mixed|bool
     */
    public function arrayShuffle($array, $statue = false)
    {
        $keys = array_keys($array);
        shuffle($keys);
        $new = [];
        foreach ($keys as $key) {
            if (is_array($array[$key] && $statue)) {
                $new[$key] = $this->arrayShuffle($array[$key], 1);
            }
            $new[$key] = $array[$key];
        }
        return $new;
    }

    /**
     * 在数组中的给定位置插入元素
     * @param $array
     * @param $insert
     * @param int $position
     * @return mixed|array
     */
    public function arrayInsert($array, $insert, $position = 0)
    {
        if (!is_array($insert)) {
            $insert = [$insert];
        }

        if ($position == 0) {
            $array = array_merge($insert, $array);
        } else {
            if ($position >= (count($array) - 1)) {
                $array = array_merge($array, $insert);
            } else {
                $head = array_slice($array, 0, $position);
                $tail = array_slice($array, $position);
                $array = array_merge($head, $insert, $tail);
            }
        }
        return $array;
    }

    /**
     * 返回两个数组中不同的元素
     * @param $array
     * @param $array1
     * @return mixed|array
     */
    public function arrayFiffBoth($array, $array1)
    {
        return array_merge(array_diff($array, $array1), array_diff($array1, $array));
    }

    /**
     * 按指定的键对数组依次分组
     * @param array $arr
     * @param $key
     * @return mixed|array|bool
     */
    public function arrayGroupBy(array $arr, $key)
    {
        if (!is_string($key) && !is_int($key)) {
            return false;
        }
        $is_function = !is_string($key) && is_callable($key);
        $grouped = [];
        foreach ($arr as $value) {
            $groupKey = null;
            if ($is_function) {
                $groupKey = $key($value);
            } else if (is_object($value)) {
                $groupKey = $value->{$key};
            } else {
                if (!isset($value[$key])) {
                    return false;
                }
                $groupKey = $value[$key];
            }
            $grouped[$groupKey][] = $value;
        }
        if (func_num_args() > 2) {
            $args = func_get_args();
            foreach ($grouped as $groupKey => $value) {
                $params = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$groupKey] = call_user_func_array([$this, 'arrayGroupBy'], $params);
            }
        }
        return $grouped;
    }

    /**
     * 把数组中的null 转换成 空字符串
     * @param $arr
     * @return mixed|array|string
     */
    public function arrayNull($arr)
    {

        if ($arr !== null) {
            if (is_array($arr)) {
                if (!empty($arr)) {
                    foreach ($arr as $key => $value) {
                        if ($value === null) {
                            $arr[$key] = '';
                        } else {
                            // 递归再去执行
                            $arr[$key] = $this->arrayNull($value);
                        }
                    }
                } else {
                    $arr = '';
                }
            } else {
                if ($arr === null) {
                    $arr = '';
                }
            }
        } else {
            $arr = '';
        }
        return $arr;
    }

    /**
     * 统计数组元素出现的次数
     * @return mixed|array|bool
     */
    public function countElement()
    {
        $data = func_get_args();
        $num = func_num_args();
        $result = array();
        if ($num > 0) {
            for ($i = 0; $i < $num; $i++) {
                foreach ($data[$i] as $v) {
                    if (isset($result[$v])) {
                        $result[$v]++;
                    } else {
                        $result[$v] = 1;
                    }
                }
            }
            return $result;
        }
        return false;
    }

    /**
     * 重组数组
     * @param $array
     * @param $from
     * @param $to
     * @param null $group
     * @return mixed|array
     */
    public function map($array, $from, $to, $group = null)
    {
        if (!is_array($array)) {
            return array();
        }
        $result = [];
        foreach ($array as $element) {
            if (!array_key_exists($from, $element) or !array_key_exists($to, $element)) {
                continue;
            }
            $key = $element[$from];
            $value = $element[$to];
            if ($group !== null) {
                if (!array_key_exists($group, $element)) {
                    continue;
                }
                $result[$element[$group]][$key] = $value;
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * 按指定值去给数组排序
     * @param array $arr
     * @param $key
     * @return mixed|array
     */
    public function arrGroupBy(array $arr, $key)
    {
        if (!is_string($key) && !is_int($key) && !is_float($key) && !is_callable($key)) {
            trigger_error('array_group_by():键应该是一个字符串、一个整数、一个浮点数或一个函数', E_USER_ERROR);
        }
        $isFunction = !is_string($key) && is_callable($key);
        // Load the new array, splitting by the target key
        $grouped = [];
        foreach ($arr as $value) {
            $groupKey = null;
            if ($isFunction) {
                $groupKey = $key($value);
            } else if (is_object($value)) {
                $groupKey = $value->{$key};
            } else {
                $groupKey = $value[$key];
            }
            $grouped[$groupKey][] = $value;
        }

        if (func_num_args() > 2) {
            $args = func_get_args();
            foreach ($grouped as $groupKey => $value) {
                $params = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$groupKey] = call_user_func_array(array($this, 'self::arrayGroupBy'), $params);
            }
        }
        return $grouped;
    }

    /**
     * 按指定键给数组排序
     * @param $arr
     * @param $key
     * @param string $orderby
     * @return mixed|array|bool
     */
    public function arraySortByKey($arr, $key, $orderby = 'asc')
    {
        if (count($arr) < 0) return false;
        $keys_value = [];
        $new_array = [];
        foreach ($arr as $k => $v) {
            $keys_value[$k] = $v[$key];
        }
        if ($orderby == 'asc') {
            asort($keys_value);
        } else {
            arsort($keys_value);
        }
        reset($keys_value);
        foreach ($keys_value as $k => $v) {
            $new_array[] = $arr[$k];
        }
        return $new_array;
    }

    /**
     * 递归过滤多维数组中 空白字符，负值，false，null
     * @param $arr
     * @param bool $trim
     * @param bool $unsetEmptyArr
     * @return mixed|array
     */
    public function arrayRemoveEmpty(&$arr, $trim = true, $unsetEmptyArr = false)
    {
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $this->arrayRemoveEmpty($arr[$key]);
            } else {
                $value = trim($value);
                if ($value == '') {
                    unset($arr[$key]);
                } elseif ($trim) {
                    $arr[$key] = $value;
                }
            }
        }
        if (is_bool($unsetEmptyArr) && $unsetEmptyArr) {
            $arr = array_filter($arr);
        }
        return $arr;
    }

    /**
     * 使用给定闭包对数组进行过滤
     * @param $array
     * @param callable $callback
     * @return mixed|array
     */
    public function arrayWhere($array, callable $callback)
    {
        return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * 获取数组第一个元素
     * @param $array
     * @param callable|null $callback
     * @param null $default
     * @return mixed
     */
    public function arrayFirst($array, callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            if (empty($array)) {
                return value($default);
            }

            foreach ($array as $item) {
                return $item;
            }
        }
        foreach ($array as $key => $value) {
            if (call_user_func($callback, $value, $key)) {
                return $value;
            }
        }
        return value($default);
    }

    /**
     * 获取数组最后一个元素
     * @param $array
     * @param callable|null $callback
     * @param null $default
     * @return mixed
     */
    public function arrayLast($array, callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            return empty($array) ? value($default) : end($array);
        }

        return self::arrayFirst(array_reverse($array, true), $callback, $default);
    }

    /**
     * 结构化打印数组
     * @param $arr
     * @param int $type
     */
    public function dd($arr, $type = 1)
    {
        echo '<pre>';
        if ($type == 1) {
            print_r($arr);
        } else {
            var_dump($arr);
            exit;
        }
        echo '</pre>';
    }
}
