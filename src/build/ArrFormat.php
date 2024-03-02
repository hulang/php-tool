<?php

declare(strict_types=1);

namespace hulang\tool\build;

trait ArrFormat
{
    public $rootNodeName = 'root';
    public $type_func = [
        'json' => 'formatJson',
        'xml' => 'formatXml',
        'serialize' => 'formatSerialize',
        'obj'      => 'formatObj',
        'csv'      => 'formatCsv'
    ];

    public function encode($array, $type = 'json')
    {
        if (method_exists($this, $this->type_func[$type])) {
            return call_user_func([$this, $this->type_func[$type]], $array);
        } else {
            throw new \Exception(sprintf('The required method "' . $this->type_func[$type] . '" does not exist for!', $this->type_func[$type], get_class($this)));
        }
    }

    private function formatJson($array)
    {
        return json_encode($array);
    }

    private function formatXml($array)
    {
        if (ini_get('zend.ze1_compatibility_mode') == 1) {
            ini_set('zend.ze1_compatibility_mode', 0);
        }
        return $this->toXml($array, $this->rootNodeName);
    }

    private function formatSerialize($array)
    {
        $array = serialize($array);
        return $array;
    }

    private function toXml($data, $rootNodeName = 'root', $xml = null)
    {
        if ($xml == null) {
            $xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
        }
        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                $key = "unknownNode_" . (string) $key;
            }

            if (is_array($value)) {
                $node = $xml->addChild($key);
                $this->toXml($value, $rootNodeName, $node);
            } else {
                $value = htmlentities($value, ENT_QUOTES, 'UTF-8');
                $xml->addChild($key, $value);
            }
        }
        return $xml->asXML();
    }

    public function formatCsv($data)
    {
        if (is_array($data) and isset($data[0])) {
            $headings = array_keys($data[0]);
        } else {
            $headings = array_keys((array) $data);
            $data = array($data);
        }
        $output = implode(',', $headings) . PHP_EOL;
        foreach ($data as &$row) {
            $output .= '"' . implode('","', (array) $row) . PHP_EOL;
        }
        return $output;
    }

    private function formatObj($array)
    {
        $array = json_encode($array);
        $arr = json_decode($array, false);
        return $arr;
    }
}
