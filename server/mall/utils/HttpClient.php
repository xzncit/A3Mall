<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace mall\utils;


class HttpClient {

    public static function get($url, $query = [], $options = []){
        $options['query'] = $query;
        return self::http('get', $url, $options);
    }

    public static function post($url, $data = [], $options = []){
        $options['data'] = $data;
        return self::http('post', $url, $options);
    }

    public static function download(){}

    public static function http($method, $url, $options = []){
        $curl = curl_init();
        // GET参数设置
        if (!empty($options['query'])) {
            $url .= (stripos($url, '?') !== false ? '&' : '?') . http_build_query($options['query']);
        }

        // CURL头信息设置
        if (!empty($options['headers'])) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $options['headers']);
        }

        // POST数据设置
        if (strtolower($method) === 'post') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS,is_array($options['data']) ? http_build_query($options['data']) : $options['data']);
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        list($content) = [curl_exec($curl), curl_close($curl)];

        return $content;
    }

}