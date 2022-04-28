<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\utils;

use mall\basic\Setting;
use think\facade\Config;
use think\facade\Request;

class Tool {

    public static function thumb($image="",$prefix=null,$root=false){
        if(Check::url($image)) return $image;
        $domain = $root ? trim(Request::domain(),"/") : "";
        if(empty($image) || !file_exists(self::getRootPath() . "public/" . trim($image,"/"))){
            return $domain . "/static/images/default.jpg";
        }

        $image = "/" . substr($image,stripos($image,"uploads"));
        return $domain . $image;
    }

    public static function removeContentAttr($content){
        return preg_replace([
            '/(<img [^<>]*?)width=.+?[\'|\"]/',
            '/(<img.*?)((height)=[\'"]+[0-9|%]+[\'"]+)/',
            '/(<img.*?)((style)=[\'"]+(.*?)+[\'"]+)/',
            '/(<img.*?)((alt)=[\'"]+(.*?)+[\'"]+)/'
        ],['$1', '$1', '$1'],$content);
    }

    public static function replaceContentImage($content){
        $matches = [];
        preg_match_all('/<img([ ]+)src="([^\"]+)"/i', $content, $matches);
        $array = [];
        foreach($matches[2] as $val){
            if(!in_array($val,$array)){
                $array[] = $val;
                // $content = str_replace($val, trim(Request::domain(),'/') . '/' . trim($val,"/") . '" style="max-width: 100%; display: block; margin:0 auto;', $content);
                $content = str_replace($val, Tool::thumb($val,"",true) . '" style="max-width: 100%; display: block; margin:0 auto;', $content);
            }
        }

        return $content;
    }

    public static function descarte($arr, $tmp = []) {
        static $n_arr = [];
        foreach (array_shift($arr) as $v) {
            $tmp[] = $v;
            if ($arr) {
                self::descarte($arr, $tmp);
            } else {
                $n_arr[] = $tmp;
            }
            array_pop($tmp);
        }

        return $n_arr;
    }

    public static function getDirSize($directory) {
        if(!file_exists($directory)){
            return 0;
        }

        $dir_size = 0;
        if (($dir_handle = opendir($directory)) != false) {
            while (($filename = readdir($dir_handle)) != false) {
                if ($filename != '.' && $filename != '..') {
                    $subFile = $directory . '/' . $filename;
                    if (is_dir($subFile))
                        $dir_size += self::getDirSize($subFile);
                    if (is_file($subFile))
                        $dir_size += filesize($subFile);
                }
            }

            closedir($dir_handle);
            return $dir_size;
        }
    }

    public static function convert($size) {
        if($size == 0){
            return "0 Byte";
        }

        $unit = array('Byte', 'KB', 'MB', 'GB', 'TB', 'PB');
        return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
    }

    public static function deleteFile($file) {
        if (empty($file) || !file_exists($file)) {
            return false;
        }

        if (is_file($file)) {
            return unlink($file);
        }

        $ret = true;
        if (($handle = opendir($file)) != false) {
            while (($filename = readdir($handle)) != false) {
                if ($filename == '.' || $filename == '..') continue;
                if (!self::deleteFile($file . '/' . $filename)) $ret = false;
            }
        } else {
            $ret = false;
        }

        closedir($handle);
        if (file_exists($file) && !rmdir($file)) {
            $ret = false;
        }

        return $ret;
    }

    public static function getRandPrize($data) {
        $index = 0;
        $sum = array_sum($data);
        foreach ($data as $key => $item) {
            $num = mt_rand(1, $sum);
            if ($num <= $item) {
                $index = $key;
                break;
            } else {
                $sum -= $item;
            }
        }

        return $index;
    }

    public static function editor($content=""){
        if(empty($content)){
            return $content;
        }

        $runtime_path    = self::getRootPath();
        $path = $runtime_path . 'runtime/htmlpurifier/';
        if(!file_exists($path)){
            mkdir($path,0777,true);
        }

        $config = \HTMLPurifier_Config::createDefault();
        $config->set('Cache.SerializerPath',$path);
        $config->set('HTML.SafeEmbed',true);
        $config->set('HTML.SafeObject',true);
        $config->set('HTML.SafeIframe',true);
        $config->set('Output.FlashCompat',true);
        $config->set('Attr.EnableID',true);
        //$config->set('HTML.AllowedElements',array('div'=>true,'table'=>true,'tr'=>true,'td'=>true,'br'=>true));
        $config->set('Core.Encoding','UTF-8');
        $def = $config->getHTMLDefinition(true);
        $def->addAttribute('a', 'target', 'Enum#_blank,_self,_target,_top');
        $def->addAttribute('iframe', 'src',"URI");

        $purifier = new \HTMLPurifier($config);
        return $purifier->purify($content);
    }

    public static function prefix(){
        $config = Config::get("database");
        return $config["connections"][$config["default"]]["prefix"];
    }

    public static function moneyPrefix($price=0){
        return Config::get("base.money_prefix") . $price;
    }

    public static function getRootPath(): string {
        $path   = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR;
        return $path;
    }

    public static function odd($num){
        return (is_numeric($num)&($num&1));
    }

    public static function even($num){
        return (is_numeric($num)&(!($num&1)));
    }
}