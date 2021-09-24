<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\api\controller\wechat;


use mall\basic\Setting;
use think\facade\Db;

class Mini {

    public function template(){
        $config = Setting::get("wemini_base");
        if(isset($config["is_subscribe"]) && $config["is_subscribe"] == 1){
            return json(["data"=>[],"status"=>1,"msg"=>"ok"]);
        }

        $data = Db::name("wechat_mini_subscribe_message")->where("status",0)->select()->toArray();
        $array = [];
        foreach($data as $value){
            $array[$value["sign"]] = $value["temp_id"];
        }

        return json(["data"=>$array,"status"=>1,"msg"=>"ok"]);
    }

}