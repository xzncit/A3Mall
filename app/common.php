<?php
use \think\facade\Request;
function createUrl(string $url = '', array $vars = [], $suffix = true, $domain = false){
    $arr = explode("/",$url);
    if(count($arr) == 1){
        $url = Request::controller(true) . '/' . $url;
    }else if(count($arr) == 2){
        // app('http')->getName()
    }

    return (string)url($url,$vars,$suffix, $domain);
}