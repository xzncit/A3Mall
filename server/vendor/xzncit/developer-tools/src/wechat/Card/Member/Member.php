<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat\Card\Member;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class Member extends App {

    /**
     * 创建会员卡接口
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function create(array $data){
        return HttpClient::create()->postJson("card/create?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 接口激活
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function activateUserCard(array $data){
        return HttpClient::create()->postJson("card/membercard/activate?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 一键激活
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function activateUserForm(array $data){
        return HttpClient::create()->postJson("card/membercard/activateuserform/set?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 获取会员数据
     * 开发者可以在接收到事件通知后调用激活接口，传入会员卡号、初始积分等信息或者调用拉取会员信息接口获取会员信息，详情请见：激活会员卡接口
     * @param $card_id
     * @param $code
     * @return array
     * @throws \Exception
     */
    public function getUserDataInfo($card_id,$code){
        return HttpClient::create()->postJson("card/membercard/userinfo/get?access_token=ACCESS_TOKEN",[
            "code"=>$code,"card_id"=>$card_id
        ])->toArray();
    }

    /**
     * 获取用户提交资料
     * 用户填写并提交开卡资料后，会跳转到商户的网页，商户可以在网页内获取用户已填写的信息并进行开卡资质判断，信息确认等动作。
     * @param $activate_ticket
     * @return array
     * @throws \Exception
     */
    public function activateTempInfo($activate_ticket){
        return HttpClient::create()->postJson("card/membercard/activatetempinfo/get?access_token=ACCESS_TOKEN",[
            "activate_ticket"=>$activate_ticket
        ])->toArray();
    }

    /**
     * 更新会员信息
     * 当会员持卡消费后，支持开发者调用该接口更新会员信息。会员卡交易后的每次信息变更需通过该接口通知微信，便于后续消息通知及其他扩展功能。
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function updateUserInfo(array $data){
        return HttpClient::create()->postJson("card/membercard/updateuser?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 设置支付后投放卡券
     * 开通微信支付的商户可以设置在用户微信支付后自动为用户发送一条领卡消息，用户点击消息即可领取会员卡/优惠券。
     * 目前该功能仅支持微信支付商户号主体和制作会员卡公众号主体一致的情况下配置，否则报错。
     * 开发者可以登录“公众平台”-“公众号设置”、**“微信支付商户平台首页”**插卡企业主体信息是否一致。
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function setPayGiftCard(array $data){
        return HttpClient::create()->postJson("card/paygiftcard/add?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 删除支付后投放卡券规则接口
     * 支持商户删除之前设置的规则id
     * @param $rule_id      要查询规则id
     * @return array
     * @throws \Exception
     */
    public function deletePayGiftCard($rule_id){
        return HttpClient::create()->postJson("card/paygiftcard/delete?access_token=ACCESS_TOKEN",[
            "rule_id"=>$rule_id
        ])->toArray();
    }

    /**
     * 查询支付后投放卡券规则详情接口
     * @param $rule_id      要查询规则id
     * @return array
     * @throws \Exception
     */
    public function getById($rule_id){
        return HttpClient::create()->postJson("card/paygiftcard/getbyid?access_token=ACCESS_TOKEN",[
            "rule_id"=>$rule_id
        ])->toArray();
    }

    /**
     * 批量查询支付后投放卡券规则接口
     * @param $effective        是否仅查询生效的规则
     * @param $offset           起始偏移量
     * @param $count            查询的数量
     * @return array
     * @throws \Exception
     */
    public function getPayGiftCardList($effective,$offset,$count){
        return HttpClient::create()->postJson("card/paygiftcard/batchget?access_token=ACCESS_TOKEN",[
            "type"=>"RULE_TYPE_PAY_MEMBER_CARD","effective"=>$effective,
            "offset"=>$offset,"count"=>$count
        ])->toArray();
    }



}