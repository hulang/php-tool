<?php

declare(strict_types=1);

namespace hulang\tool;

/**
 * 数组转树形处理类
 * @see \hulang\tool\TreeHelper
 * @package hulang\tool\TreeHelper
 * @mixin \hulang\tool\TreeHelper
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
class TreeHelper extends Tree
{
}
