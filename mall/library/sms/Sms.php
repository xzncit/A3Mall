<?php
namespace mall\library\sms;

use mall\library\sms\alibaba\Alibaba;
use think\facade\Db;

class Sms {

    public static function send($data = []){
        $content = Db::name("setting")->where(["name"=>"sms"])->value("value");
        $setting = json_decode($content,true);
        $Alibaba = new Alibaba($setting);
        return $Alibaba->send($data);
    }



}