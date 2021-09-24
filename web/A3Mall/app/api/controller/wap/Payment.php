<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\api\controller\wap;


use think\facade\Db;
use think\facade\Request;

class Payment extends Base {

    public function index(){
        $type = Request::param("type","h5","strip_tags");
        $payType = Request::param("pay_type","order","strip_tags");
        $in = [];
        $in[] = $type;
        if($payType == "order"){
            $in[] = "common";
        }

        $res = Db::name("payment")->where("status","0")->where("type","in",$in)->order("sort","DESC")->select()->toArray();

        $array = [];
        $style = [
            "wechat"=>["css"=>"iconfont iconweixin","text"=>'\ue673'],
            "alipay"=>["css"=>"iconfont iconumidd17","text"=>'\ue603'],
            "balance"=>["css"=>"iconfont iconxinbaniconshangchuan-","text"=>'\ue608']
        ];
        foreach($res as $value){
            $id = explode("-",$value["code"]);
            $array[] = [
                "name"=>$value["alias_name"],
                "id"=>$id[0],
                "class"=>$style[$id[0]]["css"],
                "text"=>$style[$id[0]]["text"]
            ];
        }

        return $this->returnAjax("ok",1,$array);
    }

}