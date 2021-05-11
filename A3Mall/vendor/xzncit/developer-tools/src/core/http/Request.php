<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\core\http;

use Symfony\Component\HttpFoundation\Request as HttpRequest;

class Request {

    /**
     * 获取 $_GET 数组
     * @param $name
     * @param string $default
     * @return mixed
     */
    public static function get($name,$default=null){
        return HttpRequest::createFromGlobals()->query->get($name,$default);
    }

    /**
     * 获取 $_POST 数组
     * @param $name
     * @param null $default
     * @return mixed
     */
    public static function post($name,$default=null){
        return HttpRequest::createFromGlobals()->request->get($name,$default);
    }

    /**
     * @param string|null $name
     * @param string $default
     * @return array|mixed|string
     */
    public static function request($name=null,$default=""){
        if(empty($name)){
            return $_REQUEST;
        }

        return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
    }

    /**
     * 获取原始数据源
     * @return mixed
     */
    public static function getBody(){
        return Request::createFromGlobals()->getContent();
    }

    /**
     * 获取 $_FILES 数组
     * @param $name
     * @param null $default
     * @return mixed
     */
    public static function file($name,$default=null){
        return HttpRequest::createFromGlobals()->files->get($name,$default);
    }

    /**
     * 获取 $_SERVER 数组
     * @param $name
     * @param null $default
     * @return mixed
     */
    public static function server($name,$default=null){
        return HttpRequest::createFromGlobals()->server->get($name,$default);
    }

    /**
     * 获取 $_COOKIE 数组
     * @param $name
     * @param null $default
     * @return mixed
     */
    public static function cookies($name,$default=null){
        return HttpRequest::createFromGlobals()->cookies->get($name,$default);
    }

    /**
     * Headers (taken from the $_SERVER).
     * @param $name
     * @param null $default
     * @return mixed
     */
    public static function headers($name,$default=null){
        return HttpRequest::createFromGlobals()->headers->get($name,$default);
    }

    /**
     * 获取请求类型
     * GET, POST, PUT, DELETE, HEAD
     * @return mixed
     */
    public static function getMethod(){
        return HttpRequest::createFromGlobals()->getMethod();
    }

    /**
     * 是否为get提交
     * @return bool
     */
    public static function isGet(){
        return self::getMethod() === "GET";
    }

    /**
     * 是否为post提交
     * @return bool
     */
    public static function isPost(){
        return self::getMethod() === "POST";
    }
}