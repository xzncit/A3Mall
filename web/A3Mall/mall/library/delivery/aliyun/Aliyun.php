<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace mall\library\delivery\aliyun;

class Aliyun {

    private static $host = "https://wuliu.market.alicloudapi.com"; //api访问链接
    public static $errorInfo = [
        201=>"快递单号错误",
        203=>"快递公司不存在",
        204=>"快递公司识别失败",
        205=>"没有信息",
        207=>"IP限制",
        0=>"正常"
    ];

    public static function query($no="",$type=""){
        $config = self::getConfig();
        if(empty($config["AppKey"])){
            throw new \Exception("参数AppKey不能为空",0);
        }else if(empty($config["AppSecret"])){
            throw new \Exception("参数AppSecret不能为空",0);
        }else if(empty($config["AppCode"])){
            throw new \Exception("参数AppCode不能为空",0);
        }else if(empty($no)){
            throw new \Exception("物流单号不能为空",0);
        }

        $headers = [];
        array_push($headers, "Authorization:APPCODE " . $config["AppCode"]);
        $querys = "no=".$no;
        if(!empty($type)){
            $querys .= "&type=" . $type;
        }
        $url = self::$host . "/kdi" . "?" . $querys;

        $result = self::get($url,$headers);

        list($header, $body) = explode("\r\n\r\n", $result["data"], 2);
        if ($result["code"] == 200) {
            $array = json_decode($body,true);
            return [
                "expName"=>$array["result"]["expName"],
                "number"=>$array["result"]["number"],
                "takeTime"=>$array["result"]["takeTime"],
                "updateTime"=>$array["result"]["updateTime"],
                "list"=>$array["result"]["list"],
            ];
        } else {
            if ($result["code"] == 400 && strpos($header, "Invalid Param Location") !== false) {
                throw new \Exception("参数错误",$result["code"]);
            } elseif ($result["code"] == 400 && strpos($header, "Invalid AppCode") !== false) {
                throw new \Exception("AppCode错误",$result["code"]);
            } elseif ($result["code"] == 400 && strpos($header, "Invalid Url") !== false) {
                throw new \Exception("请求的 Method、Path 或者环境错误",$result["code"]);
            } elseif ($result["code"] == 403 && strpos($header, "Unauthorized") !== false) {
                throw new \Exception("服务未被授权（或URL和Path不正确）",$result["code"]);
            } elseif ($result["code"] == 403 && strpos($header, "Quota Exhausted") !== false) {
                throw new \Exception("套餐包次数用完",$result["code"]);
            } elseif ($result["code"] == 500) {
                throw new \Exception("API网关错误",$result["code"]);
            } elseif ($result["code"] == 0) {
                throw new \Exception("URL错误",$result["code"]);
            } else {
                throw new \Exception("参数名错误 或 其他错误",$result["code"]);
            }
        }
    }

    public static function get($url,$headers){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        if (1 == strpos("$" . self::$host, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }

        $data = curl_exec($curl);

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        return [
            "data"=>$data,
            "code"=>$httpCode
        ];
    }

    public static function getConfig(){
        $path = dirname(__FILE__) . '/config.php';
        if(file_exists($path)){
            $config = include $path;
        }else{
            $config = [
                "AppKey"=>"",
                "AppSecret"=>"",
                "AppCode"=>""
            ];
        }

        return $config;
    }

    public static function setConfig($data=[]){
        if(empty($data)){
            return false;
        }

        $path = dirname(__FILE__) . '/config.php';
        return file_put_contents($path,"<?php " . PHP_EOL . "return " . var_export($data,true) . ';' . PHP_EOL . '?>');
    }
}