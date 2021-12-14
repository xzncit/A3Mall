<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat\Card\GiftCard;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class GiftCard extends App {

    /**
     * 创建礼品卡接口
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function create(array $data){
        return HttpClient::create()->postJson("card/create?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 创建-礼品卡货架接口
     * 开发者可以通过该接口创建一个礼品卡货架并且用于公众号、门店的礼品卡售卖
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function addGiftcard(array $data){
        return HttpClient::create()->postJson("card/giftcard/page/add?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 查询-礼品卡货架信息接口
     * @param string $page_id
     * @return array
     * @throws \Exception
     */
    public function getGiftCardInfo($page_id){
        return HttpClient::create()->postJson("card/giftcard/page/get?access_token=ACCESS_TOKEN",[
            "page_id"=>$page_id
        ])->toArray();
    }

    /**
     * 修改-礼品卡货架信息接口
     * 开发者可以通过该接口更新礼品卡货架信息
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function updateGiftCard(array $data){
        return HttpClient::create()->postJson("card/giftcard/page/update?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 查询-礼品卡货架列表接口
     * 开发者可以通过该接口查询当前商户下所有的礼品卡货架id
     * @return array
     * @throws \Exception
     */
    public function getGiftCardList(){
        return HttpClient::create()->postJson("card/giftcard/page/batchget?access_token=ACCESS_TOKEN")->toArray();
    }

    /**
     * 下架-礼品卡货架接口
     * @param string $page_id       需要下架的page_id
     * @param bool   $maintain      默认为true
     * @param bool   $all           如果为true代表所有货品下架
     * @return array
     * @throws \Exception
     */
    public function downGiftCard($page_id,$maintain=true,$all=false){
        $data = ["page_id"=>$page_id,"maintain"=>$maintain];
        if($all){
            $data["all"] = $all;
        }else{
            $data["page_id"] = $page_id;
        }

        return HttpClient::create()->postJson("card/giftcard/maintain/set?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 申请微信支付礼品卡权限接口
     * @param $sub_mch_id
     * @return array
     * @throws \Exception
     */
    public function whiteList($sub_mch_id){
        return HttpClient::create()->postJson("card/giftcard/pay/whitelist/add?access_token=ACCESS_TOKEN",[
            "sub_mch_id"=>$sub_mch_id
        ])->toArray();
    }

    /**
     * 绑定商户号到礼品卡小程序接口
     * @param string $sub_mch_id
     * @param string $wxa_appid
     * @return array
     * @throws \Exception
     */
    public function bindGiftCardMini($sub_mch_id,$wxa_appid){
        return HttpClient::create()->postJson("card/giftcard/pay/submch/bind?access_token=ACCESS_TOKEN",[
            "sub_mch_id"=>$sub_mch_id,"wxa_appid"=>$wxa_appid
        ])->toArray();
    }

    /**
     * 上传小程序代码
     * @param string $wxa_appid
     * @param string $page_id
     * @return array
     * @throws \Exception
     */
    public function uploadGiftCard($wxa_appid,$page_id){
        return HttpClient::create()->postJson("card/giftcard/wxa/set?access_token=ACCESS_TOKEN",[
            "wxa_appid"=>$wxa_appid,"page_id"=>$page_id
        ])->toArray();
    }

    /**
     * 查询-单个礼品卡订单信息接口
     * @param $order_id     礼品卡订单号，商户可以通过购买成功的事件推送或者批量查询订单接口获得
     * @return array
     * @throws \Exception
     */
    public function getGiftCardOrder($order_id){
        return HttpClient::create()->postJson("card/giftcard/order/get?access_token=ACCESS_TOKEN",[
            "order_id"=>$order_id
        ])->toArray();
    }

    /**
     * 查询-批量查询礼品卡订单信息接口
     * @param $begin_time   查询的时间起点，十位时间戳（utc+8）
     * @param $end_time     查询的时间终点，十位时间戳（utc+8）
     * @param $sort_type    填"ASC" / "DESC"，表示对订单创建时间进行“升 / 降”排序
     * @param $offset       查询的订单偏移量，如填写100则表示从第100个订单开始拉取
     * @param $count        查询订单的数量，如offset填写100，count填写10，则表示查询第100个到第110个订单
     * @return array
     * @throws \Exception
     */
    public function getOrderListInfo($begin_time,$end_time,$sort_type,$offset,$count){
        return HttpClient::create()->postJson("card/giftcard/order/batchget?access_token=ACCESS_TOKEN",[
            "begin_time"=>$begin_time,"end_time"=>$end_time,"sort_type"=>$sort_type,
            "offset"=>$offset,"count"=>$count
        ])->toArray();
    }

    /**
     * 更新用户礼品卡信息接口
     * 当礼品卡被使用后，开发者可以通过该接口变更某个礼品卡的余额信息
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function updateGiftCardUserInfo(array $data){
        return HttpClient::create()->postJson("card/generalcard/updateuser?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 核销用户礼品卡接口
     * @param string $code      卡券Code码
     * @param string $card_id   卡券ID,自定义code卡券必填，否则非必填
     * @return array
     * @throws \Exception
     */
    public function consume($code,$card_id){
        return HttpClient::create()->postJson("card/code/consume?access_token=ACCESS_TOKEN",[
            "code"=>$code,"card_id"=>$card_id
        ])->toArray();
    }

    /**
     * 查询礼品卡信息接口
     * @param string $code      卡券Code码
     * @param string $card_id   卡券ID,自定义code卡券必填，否则非必填
     * @return array
     * @throws \Exception
     */
    public function getGiftCardCode($code,$card_id){
        return HttpClient::create()->postJson("card/code/get?access_token=ACCESS_TOKEN",[
            "code"=>$code,"card_id"=>$card_id
        ])->toArray();
    }

    /**
     * 退款接口
     * @param $order_id     须退款的订单id
     * @return array
     * @throws \Exception
     */
    public function refund($order_id){
        return HttpClient::create()->postJson("card/giftcard/order/refund?access_token=ACCESS_TOKEN",[
            "order_id"=>$order_id
        ])->toArray();
    }

    /**
     * 设置支付后开票功能
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function setbizattr(array $data){
        return HttpClient::create()->postJson("card/invoice/setbizattr?action=set_pay_mch&access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 查询支付后开票信息接口
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function getPayMch(array $data){
        return HttpClient::create()->postJson("card/invoice/setbizattr?action=get_pay_mch&access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 设置开票页面信息接口
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function setInvoice($data){
        return HttpClient::create()->postJson("card/invoice/setbizattr?action=set_auth_field&access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 查询授权页字段信息接口
     * 开发者可以通过该接口查看授权页抬头的填写项
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function getAuthField(array $data){
        return HttpClient::create()->postJson("card/invoice/setbizattr?action=get_auth_field&access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 查询开票信息
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function getAuthData(array $data){
        return HttpClient::create()->postJson("card/invoice/getauthdata?access_token=ACCESS_TOKEN",$data)->toArray();
    }

}