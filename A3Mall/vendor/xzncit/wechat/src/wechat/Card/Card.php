<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat\Card;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class Card extends App {

    /**
     * 创建卡券
     * @link https://developers.weixin.qq.com/doc/offiaccount/Cards_and_Offer/Create_a_Coupon_Voucher_or_Card.html
     */
    /**
     * 创建卡券
     * 创建卡券接口是微信卡券的基础接口，用于创建一类新的卡券，获取card_id，
     * 创建成功并通过审核后，商家可以通过文档提供的其他接口将卡券下发给用户，每次成功领取，库存数量相应扣除。
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function create(array $data){
        return HttpClient::create()->postJson("card/create?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 设置买单接口
     * @param string $card_id   卡券ID
     * @param bool   $is_open   是否开启买单功能，填true/false
     * @return array
     * @throws \Exception
     */
    public function setPaycell($card_id,$is_open=false){
        return HttpClient::create()->postJson("card/paycell/set?access_token=ACCESS_TOKEN",[
            "card_id"=>$card_id,"is_open"=>$is_open
        ])->toArray();
    }

    /**
     * 设置自助核销接口
     * @param string $card_id               卡券ID
     * @param bool   $is_open               是否开启自助核销功能，填true/false，默认为false
     * @param bool   $need_verify_cod       用户核销时是否需要输入验证码， 填true/false， 默认为false
     * @param bool   $need_remark_amount    用户核销时是否需要备注核销金额， 填true/false， 默认为false
     * @return array
     * @throws \Exception
     */
    public function selfconsumecell($card_id,$is_open=false,$need_verify_cod=false,$need_remark_amount=false){
        return HttpClient::create()->postJson("card/selfconsumecell/set?access_token=ACCESS_TOKEN",[
            "card_id"=>$card_id,"is_open"=>$is_open,
            "need_verify_cod"=>$need_verify_cod,"need_remark_amount"=>$need_remark_amount
        ])->toArray();
    }

    /**
     * 投放卡劵
     * @link https://developers.weixin.qq.com/doc/offiaccount/Cards_and_Offer/Distributing_Coupons_Vouchers_and_Cards.html
     */
    /**
     * 创建二维码接口
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function createQRCode(array $data){
        return HttpClient::create()->postJson("card/qrcode/create?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 创建货架接口
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function landingpage(array $data){
        return HttpClient::create()->postJson("card/landingpage/create?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 导入自定义code
     * @param string $card_id   需要进行导入code的卡券ID
     * @param array  $code      需导入微信卡券后台的自定义code，上限为100个
     * @return array
     * @throws \Exception
     */
    public function deposit($card_id,array $code){
        return HttpClient::create()->postJson("card/code/deposit?access_token=ACCESS_TOKEN",[
            "card_id"=>$card_id,"code"=>$code
        ])->toArray();
    }

    /**
     * 查询导入code数目接口
     * @param $card_id      进行导入code的卡券ID
     * @return array
     * @throws \Exception
     */
    public function getDepositCount($card_id){
        return HttpClient::create()->postJson("card/code/getdepositcount?access_token=ACCESS_TOKEN",[
            "card_id"=>$card_id
        ])->toArray();
    }

    /**
     * 核查code接口
     * @param $card_id      进行导入code的卡券ID
     * @param $code         已经微信卡券后台的自定义code，上限为100个
     * @return array
     * @throws \Exception
     */
    public function checkCode($card_id,$code){
        return HttpClient::create()->postJson("card/code/checkcode?access_token=ACCESS_TOKEN",[
            "card_id"=>$card_id,"code"=>$code
        ])->toArray();
    }

    /**
     * 图文消息群发卡券
     * @param string $card_id      卡券ID
     * @return array
     * @throws \Exception
     */
    public function getNewsMsgCard($card_id){
        return HttpClient::create()->postJson("card/mpnews/gethtml?access_token=ACCESS_TOKEN",[
            "card_id"=>$card_id
        ])->toArray();
    }

    /**
     * 设置测试白名单
     * @param array $openid     测试的openid列表
     * @param array $username   测试的微信号列表
     * @return array
     * @throws \Exception
     */
    public function testWhiteList(array $openid,array $username){
        return HttpClient::create()->postJson("card/testwhitelist/set?access_token=ACCESS_TOKEN",[
            "openid"=>$openid,"username"=>$username
        ])->toArray();
    }

    /**
     * 核销卡劵
     * @link https://developers.weixin.qq.com/doc/offiaccount/Cards_and_Offer/Redeeming_a_coupon_voucher_or_card.html
     */
    /**
     * 查询Code接口
     * @param string $card_id           单张卡券的唯一标准
     * @param string $code              卡券ID代表一类卡券。自定义code卡券必填
     * @param bool   $check_consume     是否校验code核销状态，填入true和false时的code异常状态返回数据不同
     * @return array
     * @throws \Exception
     */
    public function getCardCode($card_id,$code,$check_consume=true){
        return HttpClient::create()->postJson("card/code/get?access_token=ACCESS_TOKEN",[
            "card_id"=>$card_id,"code"=>$code,"check_consume"=>$check_consume
        ])->toArray();
    }

    /**
     * 核销Code接口
     * 消耗code接口是核销卡券的唯一接口,开发者可以调用当前接口将用户的优惠券进行核销，该过程不可逆。
     * @param $card_id      卡券ID。创建卡券时use_custom_code填写true时必填。非自定义Code不必填写
     * @param $code         需核销的Code码
     * @return array
     * @throws \Exception
     */
    public function consume($card_id,$code){
        return HttpClient::create()->postJson("card/code/consume?access_token=ACCESS_TOKEN",[
            "card_id"=>$card_id,"code"=>$code
        ])->toArray();
    }

    /**
     * Code解码接口
     * @param $encrypt_code     经过加密的Code码
     * @return array
     * @throws \Exception
     */
    public function decrypt($encrypt_code){
        return HttpClient::create()->postJson("card/code/decrypt?access_token=ACCESS_TOKEN",[
            "encrypt_code"=>$encrypt_code
        ])->toArray();
    }

    /**
     * 管理卡劵
     * @link https://developers.weixin.qq.com/doc/offiaccount/Cards_and_Offer/Managing_Coupons_Vouchers_and_Cards.html
     */
    /**
     * 获取用户已领取卡券接口
     * @param string $openid        需要查询的用户openid
     * @param string $card_id       卡券ID。不填写时默认查询当前appid下的卡券
     * @return array
     * @throws \Exception
     */
    public function getCardList($openid,$card_id){
        return HttpClient::create()->postJson("card/user/getcardlist?access_token=ACCESS_TOKEN",[
            "openid"=>$openid,"card_id"=>$card_id
        ])->toArray();
    }

    /**
     * 查看卡券详情
     * @param $card_id      卡券ID
     * @return array
     * @throws \Exception
     */
    public function getCardDetail($card_id){
        return HttpClient::create()->postJson("card/get?access_token=ACCESS_TOKEN",[
            "card_id"=>$card_id
        ])->toArray();
    }

    /**
     * 批量查询卡券列表
     * @param int $offset               查询卡列表的起始偏移量，从0开始，即offset: 5是指从从列表里的第六个开始读取
     * @param int $count                需要查询的卡片的数量（数量最大50）
     * @param array $status_list        支持开发者拉出指定状态的卡券列表 “CARD_STATUS_NOT_VERIFY”, 待审核 ； “CARD_STATUS_VERIFY_FAIL”, 审核失败； “CARD_STATUS_VERIFY_OK”， 通过审核； “CARD_STATUS_DELETE”， 卡券被商户删除； “CARD_STATUS_DISPATCH”， 在公众平台投放过的卡券；
     * @return array
     * @throws \Exception
     */
    public function batchget($offset,$count,array $status_list){
        return HttpClient::create()->postJson("card/batchget?access_token=ACCESS_TOKEN",[
            "offset"=>$offset,"count"=>$count,"status_list"=>$status_list
        ])->toArray();
    }

    /**
     * 更改卡券信息接口
     * 支持更新所有卡券类型的部分通用字段及特殊卡券（会员卡、飞机票、电影票、会议门票）中特定字段的信息。
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function updateCard(array $data){
        return HttpClient::create()->postJson("card/update?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 修改库存接口
     * @param $card_id                  卡券ID
     * @param $increase_stock_value     增加多少库存，支持不填或填0
     * @param $reduce_stock_value       减少多少库存，可以不填或填0
     * @return array
     * @throws \Exception
     */
    public function modifyStock($card_id,$increase_stock_value,$reduce_stock_value){
        return HttpClient::create()->postJson("card/modifystock?access_token=ACCESS_TOKEN",[
            "card_id"=>$card_id,"increase_stock_value"=>$increase_stock_value,
            "reduce_stock_value"=>$reduce_stock_value
        ])->toArray();
    }

    /**
     * 更改Code接口
     * @param string $code          卡券ID。自定义Code码卡券为必填
     * @param string $card_id       需变更的Code码
     * @param string $new_code      变更后的有效Code码
     * @return array
     * @throws \Exception
     */
    public function updateCode($code,$card_id,$new_code){
        return HttpClient::create()->postJson("card/code/update?access_token=ACCESS_TOKEN",[
            "code"=>$code,"card_id"=>$card_id,"new_code"=>$new_code
        ])->toArray();
    }

    /**
     * 删除卡券接口
     * @param string $card_id      卡券ID
     * @return array
     * @throws \Exception
     */
    public function deleteCard($card_id){
        return HttpClient::create()->postJson("card/delete?access_token=ACCESS_TOKEN",[
            "card_id"=>$card_id
        ])->toArray();
    }

    /**
     * 设置卡券失效接口
     * @param $card_id
     * @param $code
     * @param $reason
     * @return array
     * @throws \Exception
     */
    public function unavailable($card_id,$code,$reason){
        return HttpClient::create()->postJson("card/code/unavailable?access_token=ACCESS_TOKEN",[
            "card_id"=>$card_id,"code"=>$code,"reason"=>$reason
        ])->toArray();
    }

    /**
     * 拉取卡券概况数据接口
     * 支持调用该接口拉取本商户的总体数据情况，包括时间区间内的各指标总量
     * @param string $begin_date       查询数据的起始时间
     * @param string $end_date         查询数据的截至时间
     * @param int $cond_source         卡券来源，0为公众平台创建的卡券数据 、1是API创建的卡券数据
     * @return array
     * @throws \Exception
     */
    public function getCardBizUininfo($begin_date,$end_date,$cond_source=0){
        return HttpClient::create()->postJson("datacube/getcardbizuininfo?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date,"cond_source"=>$cond_source
        ])->toArray();
    }

    /**
     * 获取免费券数据接口
     * @param string $begin_date        查询数据的起始时间
     * @param string $end_date          查询数据的截至时间
     * @param int    $cond_source       卡券来源，0为公众平台创建的卡券数据、1是API创建的卡券数据
     * @param null|string $card_id      卡券ID。填写后，指定拉出该卡券的相关数据
     * @return array
     * @throws \Exception
     */
    public function getCardInfo($begin_date,$end_date,$cond_source=0,$card_id=null){
        $data = ["begin_date"=>$begin_date,"end_date"=>$end_date,"cond_source"=>$cond_source];
        is_null($card_id) || $data["card_id"] = $card_id;
        return HttpClient::create()->postJson("datacube/getcardcardinfo?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 拉取会员卡概况数据接口
     * 支持开发者调用该接口拉取公众平台创建的会员卡相关数据。
     * @param string $begin_date        查询数据的起始时间
     * @param string $end_date          查询数据的截至时间
     * @param int $cond_source          卡券来源，0为公众平台创建的卡券数据、1是API创建的卡券数据
     * @return array
     * @throws \Exception
     */
    public function getCardMemberCardInfo($begin_date,$end_date,$cond_source=0){
        return HttpClient::create()->postJson("datacube/getcardmembercardinfo?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date,"cond_source"=>$cond_source
        ])->toArray();
    }

    /**
     * 拉取单张会员卡数据接口
     * 支持开发者调用该接口拉取API创建的会员卡数据情况
     * @param string $begin_date        查询数据的起始时间
     * @param string $end_date          查询数据的截至时间
     * @param string $card_id           卡券id
     * @return array
     * @throws \Exception
     */
    public function getCardMemberCardDetail($begin_date,$end_date,$card_id){
        return HttpClient::create()->postJson("datacube/getcardmembercarddetail?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date,"card_id"=>$card_id
        ])->toArray();
    }



}