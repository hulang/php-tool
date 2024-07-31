<?php

declare(strict_types=1);

namespace hulang\tool;

/**
 * 数组转树形助手类
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
class TreeHelper
{
    /**
     * 获取子树
     * 该静态方法用于根据给定的数据数组和父级标识,构建一个树状结构
     * 它通过递归调用自身,将所有子元素组织成一个嵌套的数组结构
     *
     * @param array $data 数据数组,包含树的节点
     * @param string $parent 父级字段名,用于标识节点的父级
     * @param string $son 子级字段名,用于标识节点的子级
     * @param int $pid 父级ID,用于开始构建子树的起始点
     * @param string $lv_name 级别字段名,用于在节点中记录其级别
     * @param int $lv 当前级别,用于递归过程中跟踪当前的级别
     * @return mixed|array 返回一个嵌套的数组结构,代表了从给定父级ID开始的子树
     */
    public static function getSubTree($data, $parent = 'pid', $son = 'id', $pid = 0, $lv_name = 'lv', $lv = 0)
    {
        // 初始化一个空数组,用于存储子树
        $tmp = [];
        // 如果数据数组不为空,则开始构建子树
        if (!empty($data)) {
            // 遍历数据数组中的每个节点
            foreach ($data as $k => $v) {
                // 检查当前节点的父级ID是否等于指定的父级ID
                if ($v[$parent] == $pid) {
                    // 为当前节点设置级别,并将其添加到子树数组中
                    $v[$lv_name] = $lv;
                    $tmp[] = $v;
                    // 递归调用自身,以构建当前节点的子树,并将其合并到子树数组中
                    $tmp = array_merge($tmp, self::getSubTree($data, $parent, $son, $v[$son], $lv_name, $lv + 1));
                }
            }
        }
        // 返回构建好的子树数组
        return $tmp;
    }

    /**
     * 获取子树列表
     * 该方法用于从给定的数据数组中构建一个子树列表,每个节点包含其子节点的列表
     * 
     * @param array $data 数据数组,包含树的节点信息
     * @param string $parent 父节点标识的字段名,默认为'pid'
     * @param string $son 子节点标识的字段名,默认为'id'
     * @param string $child 存放子节点的字段名,默认为'child'
     * @param int $pid 父节点的ID,用于查找该父节点的子节点,默认为0,表示查找根节点
     * 
     * @return mixed|array 返回构建好的子树列表
     */
    public static function getSubTreeList($data, $parent = 'pid', $son = 'id', $pid = 0, $child = 'child')
    {
        // 初始化临时数组,用于存放构建好的子树列表
        $tmp = [];
        // 如果给定的数据数组不为空,则开始构建子树列表
        if (!empty($data)) {
            // 遍历数据数组中的每个节点
            foreach ($data as $k => $v) {
                // 检查当前节点是否为指定父节点的子节点
                if ($v[$parent] == $pid) {
                    // 递归调用自身,获取当前节点的所有子节点
                    $arr = [];
                    $arr = self::getSubTreeList($data, $parent, $son, $v[$son], $child);
                    // 如果当前节点有子节点,则将子节点数和子节点列表添加到当前节点
                    $v['count'] = 0;
                    if (!empty($arr)) {
                        $v['count'] = count($arr);
                        $v[$child] = $arr;
                    }
                    // 将当前节点添加到临时数组中
                    $tmp[] = $v;
                    // 释放子节点数组,以防止内存泄漏
                    unset($arr);
                }
            }
        }
        // 返回构建好的子树列表
        return $tmp;
    }

    /**
     * 生成一维数组的树状结构
     * 该方法通过递归处理给定的一维数组,将具有父子关系的数据转换为树状结构,便于多级分类或层级展示
     * 
     * @param array $data 原始数据数组,假设每个元素包含'id'和'pid'两个字段,分别表示自身ID和父ID
     * @param string $html_name 层级标签的字段名,默认为'html',用于存储每个元素的层级标识
     * @param string $html 层级标签的前缀,默认为'├─',用于构建层级标识
     * @param int $pid 父ID的值,表示当前遍历的根节点,默认为0,表示顶级节点
     * @param string $lv_name 级别字段的名称,默认为'level',用于记录每个元素的层级
     * @param int $lv 当前遍历的层级,默认为0,表示顶级层级
     * @param string $parent 数据中表示父ID的字段名,默认为'pid'
     * @param string $son 数据中表示子ID的字段名,默认为'id'
     * 
     * @return mixed|array 返回处理后的树状结构数组
     */
    public static function getOneMergeTree($data, $html_name = 'html', $html = '├─', $pid = 0, $lv_name = 'level', $lv = 0, $parent = 'pid', $son = 'id')
    {
        // 初始化结果数组
        $arr = [];
        // 如果原始数据不为空,则开始遍历处理
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                // 判断当前元素的父ID是否等于指定的$pid,如果是,则表示当前元素是$pid的子元素
                if ($v[$parent] == $pid) {
                    // 设置当前元素的层级为父元素层级加1
                    $v[$lv_name] = $lv + 1;
                    // 构建当前元素的层级标识,通过重复$html字符串来实现
                    $v['html'] = str_repeat($html, $lv);
                    // 将处理后的元素添加到结果数组中
                    $arr[] = $v;
                    // 递归调用自身,处理当前元素的子元素,子元素的父ID为当前元素的ID
                    // 并将子元素的处理结果合并到结果数组中
                    $arr = array_merge($arr, self::getOneMergeTree($data, $html_name, $html, $v[$son], $lv_name, $lv + 1, $parent, $son));
                }
            }
        }
        // 返回处理后的结果数组
        return $arr;
    }

    /**
     * 构建多维数组的树状结构
     * 该方法通过递归处理输入的数组数据,将具有父子关系的数据转换为树状结构的多维数组
     * 这种树状结构适用于显示具有层级关系的数据,如菜单系统或文件系统
     *
     * @param array $data 原始数据数组,假设其中包含有'pid'和'id'字段
     * @param string $parent 原始数据数组中表示父ID的字段名
     * @param string $son 原始数据数组中表示子ID的字段名
     * @param int $pid 当前遍历层级的父ID值
     * @param string $name 在构建的树状结构中,子元素数组的键名
     * @return mixed|array 返回构建好的树状结构数组
     */
    public static function getMultidMergeTree($data, $parent = 'pid', $son = 'id', $pid = 0, $name = 'child')
    {
        // 初始化结果数组
        $arr = [];
        // 当输入数据不为空时,进行处理
        if (!empty($data)) {
            // 遍历原始数据数组
            foreach ($data as $k => $v) {
                // 判断当前元素的父ID是否等于当前遍历的父ID
                if ($v[$parent] == $pid) {
                    // 递归调用自身,以当前元素的ID作为新的父ID,构建子元素的树状结构
                    $tmp = [];
                    $tmp = self::getMultidMergeTree($data, $parent, $son, $v[$son], $name);
                    // 如果子元素不为空,则将其作为当前元素的一个属性
                    if (!empty($tmp)) {
                        $v[$name] = $tmp;
                    }
                    // 将当前元素加入到结果数组中
                    $arr[] = $v;
                    // 释放临时变量
                    unset($tmp);
                }
            }
        }
        // 返回构建好的树状结构数组
        return $arr;
    }

    /**
     * 将数据转换为树状结构
     * 这个方法接受一个数据数组,并根据指定的父ID和子ID字段,将数据转换为树状结构,方便进行层级遍历和操作
     * 
     * @param array $data 数据数组,包含待转换为树状结构的元素
     * @param string $parent 父ID字段的名称,默认为'pid'
     * @param string $son 子ID字段的名称,默认为'id'
     * @param int $sort_type 子元素的排序方式,0表示降序,1表示升序,默认为0
     * @param string $name 子元素在父元素中存储的键名,默认为'child'
     * 
     * @return mixed|array 返回转换后的树状结构数组
     */
    public static function getTree($data, $parent = 'pid', $son = 'id', $sort_type = 0, $name = 'child')
    {
        // 初始化临时数组,用于存储转换后的树状结构
        $tmp = [];
        // 检查输入数据是否为空
        if (!empty($data)) {
            // 初始化父元素和子元素数组
            $fu = [];
            // 子级
            $zi = [];
            // 遍历数据数组,将父元素和子元素分开
            foreach ($data as $k => $v) {
                if ($v[$parent] == 0) {
                    $fu[] = $v;
                } else {
                    $zi[] = $v;
                }
            }
            // 提取子元素的子ID字段,用于后续的排序和搜索
            // 返回数组中子级某一列的值
            $arr = array_column($zi, $son);
            // 根据排序方式对子元素进行排序
            // 子级排序方式
            if ($sort_type == 1) {
                array_multisort($arr, SORT_ASC, $zi);
            } else {
                array_multisort($arr, SORT_DESC, $zi);
            }
            // 遍历排序后的子元素,将它们按照父ID关联到父元素数组中
            // 循环子级
            foreach ($zi as $k => $v) {
                // 搜索
                $key = array_search($v[$parent], $arr);
                if ($key !== false) {
                    // 填充子级
                    $zi[$k][$parent] = $zi[$key][$parent];
                }
            }
            // 提取父元素的子ID字段,用于后续的子元素关联
            // 返回数组中父级某一列的值
            $array = array_column($fu, $son);
            // 遍历子元素数组,将每个子元素关联到对应的父元素中
            foreach ($zi as $k => $v) {
                // 搜索
                $key = array_search($v[$parent], $array);
                if ($key !== false) {
                    // 填充父级
                    $fu[$key][$name][] = $v;
                }
            }
            // 将处理后的父元素数组作为最终结果返回
            $tmp = $fu;
        }
        return $tmp;
    }

    /**
     * 获取所有父级分类
     * 该方法用于递归地查找给定子分类ID的所有父级分类
     * 它适用于具有层级关系的数据结构,如分类系统
     * 
     * @param array $data 分类数据数组,包含所有分类及其ID和父ID的信息
     * @param int $id 当前子分类的ID,用于查找其父分类
     * @param string $parent 父分类在数据数组中的键名
     * @param string $son 子分类在数据数组中的键名
     * @return mixed|array 包含所有父级分类的数组,如果给定的ID没有父级,则返回空数组
     */
    public static function getParents($data, $id = 0, $parent = 'pid', $son = 'id')
    {
        // 初始化一个空数组用于存放找到的父级分类
        $arr = [];
        // 检查传入的数据是否为空
        if (!empty($data)) {
            // 遍历数据数组,寻找匹配的父级分类
            foreach ($data as $k => $v) {
                // 如果当前分类的子分类ID与目标ID匹配
                if ($v[$son] == $id) {
                    // 将当前分类添加到结果数组中
                    $arr[] = $v;
                    // 递归调用方法,查找当前分类的父级分类,并合并到结果数组中
                    $arr = array_merge(self::getParents($data, $v[$parent], $parent, $son), $arr);
                }
            }
        }
        // 返回包含所有父级分类的结果数组
        return $arr;
    }

    /**
     * 获取给定子分类ID的所有父分类ID数组
     * 此函数用于递归查找给定分类的父分类ID,形成一个数组
     * 主要用于树状结构的数据处理,比如菜单或分类系统的层级关系处理
     *
     * @param array $data 分类数据数组,包含所有的分类及其ID和父ID信息
     * @param int $id 当前子分类的ID,用于查找其父分类
     * @param string $parent 父分类的字段名,默认为'pid',表示父分类的ID所在字段
     * @param string $son 子分类的字段名,默认为'id',表示当前分类的ID所在字段
     * @return mixed|array 返回一个数组,包含所有父分类的ID,如果找不到则返回空数组
     */
    public static function getParentsIds($data, $id, $parent = 'pid', $son = 'id')
    {
        // 初始化一个空数组,用于存放找到的父分类ID
        $arr = [];
        // 检查输入的数据数组是否为空,非空则进行遍历
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                // 比较当前遍历到的分类的子分类ID是否与目标ID匹配,并且父ID不为0(表示这不是根分类)
                if ($v[$son] == $id && $v[$parent] != 0) {
                    // 如果匹配,将当前分类的父ID加入到结果数组中
                    $arr[] = $v[$parent];
                    // 递归调用自身,查找当前分类的父分类,并将结果与当前结果数组合并
                    // 这一步是实现递归的关键,通过不断向上查找父分类,直到找到根分类或者没有父分类为止
                    $arr = array_merge(self::getParentsIds($data, $v[$parent], $parent, $son), $arr);
                }
            }
        }
        // 返回最终的结果数组,包含所有父分类的ID
        return $arr;
    }

    /**
     * 获取指定父级ID的所有子级ID数组
     * 该方法通过递归处理给定的数据数组,找出所有指定父级ID的子级ID,并以数组形式返回
     * 
     * @param array $data 数据数组,通常包含多级ID关系
     * @param int $pid 指定的父级ID
     * @param string $parent 字段名,表示父级ID的字段
     * @param string $son 字段名,表示子级ID的字段
     * @return mixed|array 包含所有子级ID的数组
     */
    public static function getChildsId($data, $pid, $parent = 'pid', $son = 'id')
    {
        // 初始化用于存储子级ID的数组
        $arr = [];
        // 检查数据数组是否为空
        if (!empty($data)) {
            // 遍历数据数组,寻找子级ID
            foreach ($data as $k => $v) {
                // 比较当前元素的父级ID是否与指定的PID相符
                if ($v[$parent] == $pid) {
                    // 将找到的子级ID添加到结果数组中
                    $arr[] = $v[$son];
                    // 递归调用,查找当前子级ID的所有子级ID,并合并到结果数组中
                    $arr = array_merge($arr, self::getChildsId($data, $v[$son], $parent, $son));
                }
            }
        }
        // 返回包含所有子级ID的数组
        return $arr;
    }

    /**
     * 获取指定父级ID的所有子级数据
     * 该方法通过递归方式从给定的数据数组中找出所有属于指定父级ID的子级数据,适用于树形结构的数据处理
     * 
     * @param array $data 数据数组,包含多个元素,每个元素应至少包含父级ID和子级ID字段
     * @param int $pid 指定的父级ID,用于查找其子级元素
     * @param string $parent 字段名用于指定父级ID在数据元素中的键名,默认为'pid'
     * @param string $son 字段名用于指定子级ID在数据元素中的键名,默认为'id'
     * @return mixed|array 返回一个包含所有指定父级ID的子级数据的数组,如果不存在则返回空数组
     */
    public static function getChilds($data, $pid, $parent = 'pid', $son = 'id')
    {
        // 初始化一个空数组用于存放找到的子级数据
        $arr = [];
        // 检查输入的数据数组是否为空
        if (!empty($data)) {
            // 遍历数据数组中的每个元素
            foreach ($data as $k => $v) {
                // 比较当前元素的父级ID是否等于指定的父级ID
                if ($v[$parent] == $pid) {
                    // 如果匹配,将当前元素添加到结果数组中
                    $arr[] = $v;
                    // 递归调用自身,查找当前元素的子级元素,并将结果合并到结果数组中
                    $arr = array_merge($arr, self::getChilds($data, $v[$son], $parent, $son));
                }
            }
        }
        // 返回包含所有子级数据的结果数组
        return $arr;
    }
}
