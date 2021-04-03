<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\mini\ImmediateDelivery;


use xzncit\core\App;
use xzncit\core\http\HttpClient;

class ImmediateDelivery extends App {

    /**
     * immediateDelivery.abnormalConfirm
     * 异常件退回商家商家确认收货接口
     * @param $shopid           商家id，由配送公司分配的appkey
     * @param $shop_order_id    唯一标识订单的 ID，由商户生成
     * @param $shop_no          商家门店编号，在配送公司登记，闪送必填，值为店铺id
     * @param $delivery_sign    用配送公司提供的appSecret加密的校验串说明
     * @param $waybill_id       配送单id
     * @param null $remark      备注
     * @return array
     * @throws \Exception
     */
    public function abnormalConfirm($shopid,$shop_order_id,$shop_no,$delivery_sign,$waybill_id,$remark=null){
        $data = [
            "shopid"=>$shopid,
            "shop_order_id"=>$shop_order_id,
            "shop_no"=>$shop_no,
            "delivery_sign"=>$delivery_sign,
            "waybill_id"=>$waybill_id
        ];

        is_null($remark) || $data["remark"] = $remark;
        return HttpClient::create()->postJson("cgi-bin/express/local/business/order/confirm_return?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * immediateDelivery.addOrder
     * 下配送单接口
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function addOrder(array $data){
        return HttpClient::create()->postJson("cgi-bin/express/local/business/order/add?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * immediateDelivery.addTip
     * 可以对待接单状态的订单增加小费。需要注意：订单的小费，以最新一次加小费动作的金额为准，故下一次增加小费额必须大于上一次小费额
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function addTip(array $data){
        return HttpClient::create()->postJson("cgi-bin/express/local/business/order/addtips?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * immediateDelivery.bindAccount
     * 第三方代商户发起绑定配送公司帐号的请求
     * @param $delivery_id      配送公司ID
     * @return array
     * @throws \Exception
     */
    public function bindAccount($delivery_id){
        return HttpClient::create()->postJson("cgi-bin/express/local/business/shop/add?access_token=ACCESS_TOKEN",[
            "delivery_id"=>$delivery_id
        ])->toArray();
    }

    /**
     * immediateDelivery.cancelOrder
     * 取消配送单接口
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function cancelOrder(array $data){
        return HttpClient::create()->postJson("cgi-bin/express/local/business/order/cancel?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * immediateDelivery.getAllImmeDelivery
     * 获取已支持的配送公司列表接口
     * @return array
     * @throws \Exception
     */
    public function getAllImmeDelivery(){
        return HttpClient::create()->postJson("cgi-bin/express/local/business/delivery/getall?access_token=ACCESS_TOKEN")->toArray();
    }

    /**
     * immediateDelivery.getBindAccount
     * 拉取已绑定账号
     * @return array
     * @throws \Exception
     */
    public function getBindAccount(){
        return HttpClient::create()->postJson("cgi-bin/express/local/business/shop/get?access_token=ACCESS_TOKEN")->toArray();
    }

    /**
     * immediateDelivery.getOrder
     * 拉取配送单信息
     * @param $shopid           商家id， 由配送公司分配的appkey
     * @param $shop_order_id    唯一标识订单的 ID，由商户生成
     * @param $shop_no          商家门店编号， 在配送公司登记，如果只有一个门店，可以不填
     * @param $delivery_sign    用配送公司提供的appSecret加密的校验串说明
     * @return array
     * @throws \Exception
     */
    public function getOrder($shopid,$shop_order_id,$shop_no,$delivery_sign){
        return HttpClient::create()->postJson("cgi-bin/express/local/business/order/get?access_token=ACCESS_TOKEN",[
            "shopid"=>$shopid,"shop_order_id"=>$shop_order_id,"shop_no"=>$shop_no,
            "delivery_sign"=>$delivery_sign
        ])->toArray();
    }

    /**
     * immediateDelivery.mockUpdateOrder
     * 模拟配送公司更新配送单状态, 该接口只用于沙盒环境，即订单并没有真实流转到运力方
     * @param $shopid           商家id, 必须是 "test_shop_id"
     * @param $shop_order_id    唯一标识订单的 ID，由商户生成
     * @param $action_time      状态变更时间点，Unix秒级时间戳
     * @param $order_status     配送状态，枚举值
     * @param $action_msg       附加信息
     * @return array
     * @throws \Exception
     */
    public function mockUpdateOrder($shopid,$shop_order_id,$action_time,$order_status,$action_msg){
        $data = [
            "shopid"=>$shopid,
            "shop_order_id"=>$shop_order_id,
            "action_time"=>$action_time,
            "order_status"=>$order_status
        ];

        is_null($action_msg) || $data["action_msg"] = $action_msg;
        return HttpClient::create()->postJson("cgi-bin/express/local/business/test_update_order?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * immediateDelivery.openDelivery
     * 第三方代商户发起开通即时配送权限
     * @return array
     * @throws \Exception
     */
    public function openDelivery(){
        return HttpClient::create()->postJson("cgi-bin/express/local/business/open?access_token=ACCESS_TOKEN")->toArray();
    }

    /**
     * immediateDelivery.preAddOrder
     * 预下配送单接口
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function preAddOrder(array $data){
        return HttpClient::create()->postJson("cgi-bin/express/local/business/order/pre_add?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * immediateDelivery.preCancelOrder
     * 预取消配送单接口
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function preCancelOrder(array $data){
        return HttpClient::create()->postJson("cgi-bin/express/local/business/order/precancel?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * immediateDelivery.realMockUpdateOrder
     * 模拟配送公司更新配送单状态, 该接口用于测试账户下的单，将请求转发到运力测试环境
     * @return array
     * @throws \Exception
     */
    public function realMockUpdateOrder(){
        return HttpClient::create()->postJson("cgi-bin/express/local/business/realmock_update_order?access_token=ACCESS_TOKEN")->toArray();
    }

    /**
     * immediateDelivery.reOrder
     * 重新下单
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function reOrder(array $data){
        return HttpClient::create()->postJson("cgi-bin/express/local/business/order/readd?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * immediateDelivery.updateOrder
     * 配送公司更新配送单状态
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function updateOrder(array $data){
        return HttpClient::create()->postJson("cgi-bin/express/local/delivery/update_order?access_token=ACCESS_TOKEN",$data)->toArray();
    }

}