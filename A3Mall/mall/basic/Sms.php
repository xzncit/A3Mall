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

    public static function send($data=[],$type=""){
        if(empty($data["mobile"])){
            throw new \Exception("手机号码不能为空");
        }

        Db::startTrans();
        try{
            $config = Db::name("sms_template")->where("sign",$type)->find();
            if(empty($config)){
                throw new \Exception("短信模板不存在");
            }else if($config["status"] == 1){
                throw new \Exception("当前短信模板己被禁用");
            }

            switch($type){
                case "register":
                case "repassword":
                    $num = mt_rand(1000,9999);
                    $str = str_replace('${code}',$num,$config["template_param"]);
                    Db::name("users_sms")->insert([
                        "mobile"=>$data["mobile"],
                        "code"=>$num,
                        "create_time"=>time()
                    ]);
                    break;
                case "payment_success":
                case "deliver_goods":
                    if(empty($data['order_no'])){
                        throw new \Exception("订单号不能为空");
                    }

                    $str = str_replace('${order_no}',$data['order_no'],$config["template_param"]);
                    break;
            }

            \mall\library\sms\Sms::send([
                "PhoneNumbers"=>$data["mobile"],
                "SignName"=>$config["sign_name"],
                "TemplateCode"=>$config["template_code"],
                "TemplateParam"=>$str
            ]);

            Db::commit();
        }catch (\Exception $e){
            Db::rollback();
            throw new \Exception($e->getMessage(),$e->getCode());
        }

        return true;
    }

}