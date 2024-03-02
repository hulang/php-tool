<?php

declare(strict_types=1);

namespace hulang\tool;

use hulang\tool\build\Base;

class Arr
{
    protected $array_link;
    protected function driver()
    {
        $this->array_link = new Base();
        return $this;
    }

    public function __call($method, $params)
    {
        if (is_null($this->array_link)) {
            $this->driver();
        }
        if (method_exists($this->array_link, $method)) {
            return call_user_func_array([$this->array_link, $method], $params);
        }
    }
    public static function single()
    {
        static $array_link;
        if (is_null($array_link)) {
            $array_link = new static();
        }
        return $array_link;
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([static::single(), $name], $arguments);
    }
}
