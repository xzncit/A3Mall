<?php
use \think\facade\Request;
use think\facade\Db;

function createUrl(string $url = '', array $vars = [], $suffix = true, $domain = false){
    $arr = explode("/",$url);
    if(count($arr) == 1){
        $url = Request::controller(true) . '/' . $url;
    }else if(count($arr) == 2){
        // app('http')->getName()
    }

    return (string)url($url,$vars,$suffix, $domain);
}

function getUserName($data=[]){
    if(isset($data["user_id"])){
        $data = Db::name("users")->where("id",$data["user_id"])->find();
    }

    if(empty($data)){
        return "未知用户";
    }

    $wechat_users = Db::name("wechat_users")->where("user_id",$data["id"])->find();
    if(!empty($wechat_users["nickname"])){
        return $wechat_users["nickname"] == "微信用户" ? $data["username"] : $wechat_users["nickname"];
    }

    if(!empty($data["nickname"])){
        return $data["nickname"] == "微信用户" ? $data["username"] : $data["nickname"];
    }else if(!empty($data["realname"])){
        return $data["realname"];
    }

    return isset($data["username"]) ? $data["username"] : "游客";
}

function G($name = null){
    static $_arr = array();
    if(is_null($name)){
        return $_arr;
    }

    if(is_string($name)){
        $arr = explode('.', $name);
        $count = count($arr);
        $string = $_string = null;
        for($i=0; $i<$count;$i++){
            if(empty($string)){
                $string = empty($_arr[$arr[$i]]) ? null : $_arr[$arr[$i]];
            }else{
                $_string = $string[$arr[$i]];
                $string = $_string;
            }
        }
        return $string;
    }

    if (is_array($name)) {
        $_arr = array_merge($_arr, $name);
    }

    return $_arr;
}

function checkMobile() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $mobile_browser = [
        "mqqbrowser",
        "MicroMessenger",
        "opera mobi",
        "juc","iuc",
        "fennec","ios",
        "applewebKit/420",
        "applewebkit/525",
        "applewebkit/532","ipad","iphone","ipaq","ipod",
        "iemobile", "windows ce",
        "240×320","480×640","acer","android",
        "anywhereyougo.com","asus","audio",
        "blackberry","blazer","coolpad" ,
        "dopod", "etouch", "hitachi",
        "htc","huawei", "jbrowser",
        "lenovo","lg","lg-","lge-","lge",
        "mobi","moto","nokia","phone",
        "samsung","sony","symbian",
        "tablet","tianyu","wap","xda","xde","zte"
    ];

    $is_mobile = false;
    foreach ($mobile_browser as $device) {
        if (stristr($user_agent, $device)) {
            $is_mobile = true;
            break;
        }
    }
    return $is_mobile;
}

