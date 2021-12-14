<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\core\http;


class Response {

    /**
     * 解析数据
     * @param $data
     * @param string $type
     * @return mixed|string|null
     * @throws \Exception
     */
    public static function getResult($content,$type="array"){
        if(empty($content)){
            return null;
        }

        switch($type){
            case "array":
                return self::json2arr($content);
            case "object":
                return self::json2obj($content);
            case "xml":
                return self::xml2arr($content);
            case "text":
                return $content;
        }
    }

    /**
     * 解析JSON内容到数组
     */
    public static function json2arr($json){
        $result = json_decode($json, true);
        if (empty($result)) {
            throw new \Exception('invalid response.', '0');
        }

        if (!empty($result['errcode'])) {
            throw new \Exception($result['errmsg'], $result['errcode']);
        }

        return $result;
    }

    public static function json2obj($json){
        $result = json_decode($json);
        if (empty($result)) {
            throw new \Exception('invalid response.', 0);
        }

        if(!empty($result->errcode)){
            throw new \Exception($result->errmsg, $result->errcode);
        }

        return $result;
    }

    /**
     * 数组转xml内容
     * @param array $data
     * @return null|string|string
     */
    public static function arr2json($data){
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        return $json === '[]' ? '{}' : $json;
    }

    /**
     * 解析XML内容到数组
     */
    public static function xml2arr($xml){
        if (PHP_VERSION_ID < 80000) $entity = libxml_disable_entity_loader(true);
        $data = (array)simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        if (PHP_VERSION_ID < 80000) libxml_disable_entity_loader($entity);
        return json_decode(json_encode($data), true);
    }

    /**
     * 数组转XML内容
     */
    public static function arr2xml($data){
        return "<xml>" . self::_arr2xml($data) . "</xml>";
    }

    /**
     * 生成XML内容
     */
    private static function _arr2xml($data, $content = ''){
        foreach ($data as $key => $val) {
            is_numeric($key) && $key = 'item';
            $content .= "<{$key}>";
            if (is_array($val) || is_object($val)) {
                $content .= self::_arr2xml($val);
            } elseif (is_string($val)) {
                $content .= '<![CDATA[' . preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/", '', $val) . ']]>';
            } else {
                $content .= $val;
            }
            $content .= "</{$key}>";
        }
        return $content;
    }
}