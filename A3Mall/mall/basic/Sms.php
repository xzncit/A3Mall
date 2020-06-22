<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\basic;

use think\facade\Db;

class Sms {

    public static function send($mobile="",$type=""){
        if(empty($mobile)){
            throw new \Exception("手机号码不能为空");
        }

        $config = Db::name("sms_template")->where("sign",$type)->find();
        switch($type){
            case "register":
            case "repassword":
                $num = mt_rand(1000,9999);
                $str = str_replace('${code}',$num,$config["template_param"]);
                break;
        }

        Db::startTrans();
        try{
            \mall\library\sms\Sms::send([
                "PhoneNumbers"=>$mobile,
                "SignName"=>$config["sign_name"],
                "TemplateCode"=>$config["template_code"],
                "TemplateParam"=>$str
            ]);

            Db::name("users_sms")->insert([
                "mobile"=>$mobile,
                "code"=>$num,
                "create_time"=>time()
            ]);

            Db::commit();
        }catch (\Exception $e){
            Db::rollback();
            throw new \Exception($e->getMessage(),$e->getCode());
        }

        return true;
    }

}