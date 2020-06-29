<?php

namespace hulang\tool;

/**
 * 无限分级类
 */

class Tree
{
    /** 子孙树
     * @param $data array  数据
     * @param $parent  string 父级元素的名称 如 pid
     * @param $son     string 子级元素的名称 如 id
     * @param $pid     int    父级元素的id 实际上传递元素的主键
     * @param $lv      int    级别
     * @return array
     */
    public static function getSubTree($data, $parent = 'pid', $son = 'id', $pid = 0, $lv = 0)
    {
        $tmp = [];
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                if ($v[$parent] == $pid) {
                    $v['lv'] = $lv;
                    $tmp[] = $v;
                    $tmp = array_merge($tmp, self::getSubTree($data, $parent, $son, $v[$son], $lv + 1));
                }
            }
        }
        return $tmp;
    }
    /**
     * @param $data array  数据
     * @param $parent  string 父级元素的名称 如 pid
     * @param $son     string 子级元素的名称 如 id
     * @param $pid     int    父级元素的id 实际上传递元素的主键
     * @param $child   string 子标签包含名称默认：child
     * @return array
     */
    public static function getSubTreeList($data, $parent = 'pid', $son = 'id', $pid = 0, $child = 'child')
    {
        $tmp = [];
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                if ($v[$parent] == $pid) {
                    $v[$child] = self::getSubTreeList($data, $parent, $son, $v[$son], $child);
                    $tmp[] = $v;
                }
            }
        }
        return $tmp;
    }
    /** 组合一维数组
     * @param $data    array  数据
     * @param $html    string 层级标签
     * @param $pid     int    顶级分类的值
     * @param $level   int    等级
     * @param $parent  string 父级元素的名称 如 pid
     * @param $son     string 子级元素的名称 如 id
     * @return array
     */
    public static function getOneMergeTree($data, $html = '├─', $pid = 0, $level = 0, $parent = 'pid', $son = 'id')
    {
        $arr = [];
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                if ($v[$parent] == $pid) {
                    $v['level'] = $level + 1;
                    $v['html'] = str_repeat($html, $level);
                    $arr[] = $v;
                    $arr = array_merge($arr, self::getOneMergeTree($data, $html, $v[$son], $level + 1, $parent, $son));
                }
            }
        }
        return $arr;
    }
    /** 组合多维数组
     * @param $data    array  数据
     * @param $pid     int    顶级分类的值
     * @param $name    string 二级数组的名称
     * @return array
     */
    public static function getMultidMergeTree($data, $pid = 0, $name = 'child')
    {
        $arr = [];
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                if ($v['pid'] == $pid) {
                    $v[$name] = self::getMultidMergeTree($data, $v['id'], $name);
                    $arr[] = $v;
                }
            }
        }
        return $arr;
    }
    /** 合并成父子树
     * @param $data          array  数据
     * @param $parent        string 父级元素的名称 如 pid
     * @param $son           string 子级元素的名称 如 id
     * @param $sort_type     int    二级数组排序方式
     * @param $name          string 二级数组的名称
     * @return array
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
    /** 传递子分类的id返回所有的父级分类
     * @param $data    array  数据
     * @param $id      int    子级元素id值
     * @param $parent  string 父级元素的名称 如 pid
     * @param $son     string 子级元素的名称 如 id
     * @return array
     */
    public static function getParents($data, $id, $parent = 'pid', $son = 'id')
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
    /** 传递子分类的id返回所有的父级分类
     * @param $data    array  数据
     * @param $id      int    子级元素id值
     * @param $parent  string 父级元素的名称 如 pid
     * @param $son     string 子级元素的名称 如 id
     * @return array
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
     * @param $data    array  数据
     * @param $pid     int    父级元素id值
     * @param $parent  string 父级元素的名称 如 pid
     * @param $son     string 子级元素的名称 如 id
     * @return array
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
    /** 传递父级id返回所有子级分类
     * @param $data    array  数据
     * @param $pid     int    父级元素id值
     * @param $parent  string 父级元素的名称 如 pid
     * @param $son     string 子级元素的名称 如 id
     * @return array
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
