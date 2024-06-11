<?php

declare(strict_types=1);

namespace hulang\tool;

/**
 * 数组转树形处理类
 * @see \hulang\tool\Tree
 * @package hulang\tool\Tree
 * @mixin \hulang\tool\Tree
 * @method static mixed|array getSubTree($data, $parent = 'pid', $son = 'id', $pid = 0, $lv_name = 'lv', $lv = 0) 子孙树按等级显示
 * @method static mixed|array getSubTreeList($data, $parent = 'pid', $son = 'id', $pid = 0, $child = 'child') 子孙树列表
 * @method static mixed|array getOneMergeTree($data, $html_name = 'html', $html = '├─', $pid = 0, $lv_name = 'level', $lv = 0, $parent = 'pid', $son = 'id') 组合一维数组
 * @method static mixed|array getMultidMergeTree($data, $parent = 'pid', $son = 'id', $pid = 0, $name = 'child') 组合多维数组
 * @method static mixed|array getTree($data, $parent = 'pid', $son = 'id', $sort_type = 0, $name = 'child') 合并成父子树
 * @method static mixed|array getParents($data, $id = 0, $parent = 'pid', $son = 'id') 传递子分类的id返回所有的父级分类数据
 * @method static mixed|array getParentsIds($data, $id, $parent = 'pid', $son = 'id') 传递子分类的id返回所有的父级分类ID
 * @method static mixed|array getChildsId($data, $pid, $parent = 'pid', $son = 'id') 传递父级id返回所有子级id
 * @method static mixed|array getChilds($data, $pid, $parent = 'pid', $son = 'id') 传递父级id返回所有子级分类数据
 */
class Tree
{
    /** 子孙树按等级显示
     * @param array $data 数据
     * @param string $parent 父级元素的名称 如:pid
     * @param string $son 子级元素的名称 如:id
     * @param int $pid 父级元素的id 实际上传递元素的主键
     * @param string $lv_name 等级名称
     * @param int $lv 级别
     * @return mixed|array
     */
    public static function getSubTree($data, $parent = 'pid', $son = 'id', $pid = 0, $lv_name = 'lv', $lv = 0)
    {
        $tmp = [];
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                if ($v[$parent] == $pid) {
                    $v[$lv_name] = $lv;
                    $tmp[] = $v;
                    $tmp = array_merge($tmp, self::getSubTree($data, $parent, $son, $v[$son], $lv_name, $lv + 1));
                }
            }
        }
        return $tmp;
    }
    /** 子孙树列表
     * @param array $data 数据
     * @param string $parent 父级元素的名称 如:pid
     * @param string $son 子级元素的名称 如:id
     * @param string $child 子级名称
     * @return mixed|array
     */
    public static function getSubTreeList($data, $parent = 'pid', $son = 'id', $pid = 0, $child = 'child')
    {
        $tmp = [];
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                if ($v[$parent] == $pid) {
                    $arr = [];
                    $arr = self::getSubTreeList($data, $parent, $son, $v[$son], $child);
                    if (!empty($arr)) {
                        $v['count'] = count($arr);
                        $v[$child] = $arr;
                    }
                    $tmp[] = $v;
                    unset($arr);
                }
            }
        }
        return $tmp;
    }
    /** 组合一维数组
     * @param array $data 数据
     * @param string $html_name 层级标签名称
     * @param string $html html 层级标签 如:├─
     * @param int $pid 顶级分类的值
     * @param string $lv_name 等级名称
     * @param int $lv 级别
     * @param string $parent 父级元素的名称 如:pid
     * @param string $son 子级元素的名称 如:id
     * @return mixed|array
     */
    public static function getOneMergeTree($data, $html_name = 'html', $html = '├─', $pid = 0, $lv_name = 'level', $lv = 0, $parent = 'pid', $son = 'id')
    {
        $arr = [];
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                if ($v[$parent] == $pid) {
                    $v[$lv_name] = $lv + 1;
                    $v['html'] = str_repeat($html, $lv);
                    $arr[] = $v;
                    $arr = array_merge($arr, self::getOneMergeTree($data, $html_name, $html, $v[$son], $lv_name, $lv + 1, $parent, $son));
                }
            }
        }
        return $arr;
    }
    /** 组合多维数组
     * @param array $data 数据
     * @param string $parent 父级元素的名称 如:pid
     * @param string $son 子级元素的名称 如:id
     * @param int $pid 顶级分类的值
     * @param string $name 二级数组的名称
     * @return mixed|array
     */
    public static function getMultidMergeTree($data, $parent = 'pid', $son = 'id', $pid = 0, $name = 'child')
    {
        $arr = [];
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                if ($v[$parent] == $pid) {
                    $tmp = [];
                    $tmp = self::getMultidMergeTree($data, $parent, $son, $v[$son], $name);
                    if (!empty($tmp)) {
                        $v[$name] = $tmp;
                    }
                    $arr[] = $v;
                    unset($tmp);
                }
            }
        }
        return $arr;
    }
    /** 合并成父子树
     * @param array $data 数据
     * @param string $parent 父级元素的名称 如:pid
     * @param string $son 子级元素的名称 如:id
     * @param int $sort_type 二级数组排序方式
     * @param string $name 二级数组的名称
     * @return mixed|array
     */
    public static function getTree($data, $parent = 'pid', $son = 'id', $sort_type = 0, $name = 'child')
    {
        $tmp = [];
        if (!empty($data)) {
            // 父级
            $fu = [];
            // 子级
            $zi = [];
            foreach ($data as $k => $v) {
                if ($v[$parent] == 0) {
                    $fu[] = $v;
                } else {
                    $zi[] = $v;
                }
            }
            // 返回数组中子级某一列的值
            $arr = array_column($zi, $son);
            // 子级排序方式
            if ($sort_type == 1) {
                array_multisort($arr, SORT_ASC, $zi);
            } else {
                array_multisort($arr, SORT_DESC, $zi);
            }
            // 循环子级
            foreach ($zi as $k => $v) {
                // 搜索
                $key = array_search($v[$parent], $arr);
                if ($key !== false) {
                    // 填充子级
                    $zi[$k][$parent] = $zi[$key][$parent];
                }
            }
            // 返回数组中父级某一列的值
            $array = array_column($fu, $son);
            foreach ($zi as $k => $v) {
                // 搜索
                $key = array_search($v[$parent], $array);
                if ($key !== false) {
                    // 填充父级
                    $fu[$key][$name][] = $v;
                }
            }
            $tmp = $fu;
        }
        return $tmp;
    }
    /** 传递子分类的id返回所有的父级分类数据
     * @param array $data 数据
     * @param int $id 子级元素id值
     * @param string $parent 父级元素的名称 如:pid
     * @param string $son 子级元素的名称 如:id
     * @return mixed|array
     */
    public static function getParents($data, $id = 0, $parent = 'pid', $son = 'id')
    {
        $arr = [];
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                if ($v[$son] == $id) {
                    $arr[] = $v;
                    $arr = array_merge(self::getParents($data, $v[$parent], $parent, $son), $arr);
                }
            }
        }
        return $arr;
    }
    /** 传递子分类的id返回所有的父级分类ID
     * @param array $data 数据
     * @param int $id 子级元素id值
     * @param string $parent 父级元素的名称 如:pid
     * @param string $son 子级元素的名称 如:id
     * @return mixed|array
     */
    public static function getParentsIds($data, $id, $parent = 'pid', $son = 'id')
    {
        $arr = [];
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                if ($v[$son] == $id && $v[$parent] != 0) {
                    $arr[] = $v[$parent];
                    $arr = array_merge(self::getParentsIds($data, $v[$parent], $parent, $son), $arr);
                }
            }
        }
        return $arr;
    }
    /** 传递父级id返回所有子级id
     * @param array $data 数据
     * @param int $pid 父级元素id值
     * @param string $parent 父级元素的名称 如:pid
     * @param string $son 子级元素的名称 如:id
     * @return mixed|array
     */
    public static function getChildsId($data, $pid, $parent = 'pid', $son = 'id')
    {
        $arr = [];
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                if ($v[$parent] == $pid) {
                    $arr[] = $v[$son];
                    $arr = array_merge($arr, self::getChildsId($data, $v[$son], $parent, $son));
                }
            }
        }
        return $arr;
    }
    /** 传递父级id返回所有子级分类数据
     * @param array $data 数据
     * @param int $pid 父级元素id值
     * @param string $parent 父级元素的名称 如:pid
     * @param string $son 子级元素的名称 如:id
     * @return mixed|array
     */
    public static function getChilds($data, $pid, $parent = 'pid', $son = 'id')
    {
        $arr = [];
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                if ($v[$parent] == $pid) {
                    $arr[] = $v;
                    $arr = array_merge($arr, self::getChilds($data, $v[$son], $parent, $son));
                }
            }
        }
        return $arr;
    }
}
