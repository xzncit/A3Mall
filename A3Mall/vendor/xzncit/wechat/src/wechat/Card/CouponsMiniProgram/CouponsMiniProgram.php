<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat\Card\CouponsMiniProgram;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class CouponsMiniProgram extends App {

    /**
     * 取开卡插件参数
     * @param $card_id      会员卡的card_id
     * @param $outer_str    渠道值，用于统计本次领取的渠道参数
     * @return array
     * @throws \Exception
     */
    public function getActivate($card_id,$outer_str){
        return HttpClient::create()->postJson("card/membercard/activate/geturl?access_token=ACCESS_TOKEN",[
            "card_id"=>$card_id,"outer_str"=>$outer_str
        ])->toArray();
    }

    /**
     * 获取用户开卡时提交的信息（跳转型开卡组件）
     * @param $activate_ticket      跳转型开卡组件开卡后回调中的激活票据，可以用来获取用户开卡资料
     * @return array
     * @throws \Exception
     */
    public function getActivateTempInfo($activate_ticket){
        return HttpClient::create()->postJson("card/membercard/activatetempinfo/get?access_token=ACCESS_TOKEN",[
            "activate_ticket"=>$activate_ticket
        ])->toArray();
    }

    /**
     * 获取用户开卡时提交的信息（非跳转型开卡组件）
     * @param string $code      会员卡的code
     * @param string $card_id   卡券id，非自定义code类型会员卡不必填写
     * @return array
     * @throws \Exception
     */
    public function getUserCartInfo($code,$card_id=null){
        $data = ["code"=>$code];
        is_null($card_id) || $data["card_id"] = $card_id;
        return HttpClient::create()->postJson("card/code/get?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 激活用户领取的会员卡（跳转型开卡组件）
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function getActivateMemberCard(array $data){
        return HttpClient::create()->postJson("card/membercard/activate?access_token=ACCESS_TOKEN",$data)->toArray();
    }



}