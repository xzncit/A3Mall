<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\service;
use app\common\models\Payment as PaymentModel;

class Payment extends Service {

    /**
     * 获取支付方式
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $type = $data["type"]??"h5";
        $payType = $data["pay_type"]??"order";

        $in = [];
        $in[] = $type;
        if($payType == "order"){
            $in[] = "common";
        }

        $res = PaymentModel::where("status","0")->where("type","in",$in)->order("sort","DESC")->select()->toArray();
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

        return $array;
    }

}