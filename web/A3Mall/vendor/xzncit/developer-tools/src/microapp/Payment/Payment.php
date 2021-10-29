<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace xzncit\microapp\Payment;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

/**
 * Class Payment 支付类
 * @package xzncit\microapp\Payment
 */
class Payment extends App {

    /**
     * 支付下单
     * 服务端预下单
     * @param $param [
        字段名             类型      是否必传                字段描述
        app_id          string       是                  小程序APPID
        out_order_no    string       是                  开发者侧的订单号, 同一小程序下不可重复
        total_amount    number       是                  支付价格; 接口中参数支付金额单位为[分]
        subject         string       是                  商品描述; 长度限制 128 字节，不超过 42 个汉字
        body            string       是                  商品详情
        valid_time      number       是                  订单过期时间(秒); 最小 15 分钟，最大两天
        sign            string       是                  开发者对核心字段签名, 签名方式见文档附录, 防止传输过程中出现意外
        cp_extra        string       否                  开发者自定义字段，回调原样回传
        notify_url      string       否                  商户自定义回调地址
        thirdparty_id   string       否 服务商模式接入必传  第三方平台服务商 id，非服务商模式留空
        disable_msg     number       否                  是否屏蔽担保支付的推送消息，1-屏蔽 0-非屏蔽，接入 POI 必传
        msg_page        string       否                  担保支付消息跳转页
     * ]
     * @return array
     * @throws \Exception
     */
    public function createOrder($param){
        return HttpClient::create()->postJson("api/apps/ecpay/v1/create_order",$param)->toArray();
    }

    /**
     * 订单查询
     * @param $params [
     *      app_id           小程序APPID
     *      out_order_no     开发者侧的订单号, 同一小程序下不可重复
     *      sign             开发者对核心字段签名, 签名方式见文档附录, 防止传输过程中出现意外
     *      thirdparty_id    第三方平台服务商 id，非服务商模式留空
     * ]
     * @return array
     * @throws \Exception
     */
    public function query($params){
        return HttpClient::create()->postJson("api/apps/ecpay/v1/query_order",$params)->toArray();
    }

    /**
     * 退款
     * 当交易发生之后一段时间内，由于买家或者卖家的原因需要退款时，卖家可以通过退款接口将支付款退还给买家，将在收到退款请求并且验证成功之后，按照退款规则将支付款按原路退到买家帐号上。
     * 注意：在途资金中的所有货款均是与订单关联的，只有当该订单在途资金中剩余金额超过退款金额时，才可以进行在途资金的退款。否则，需要将 all_settle 字段置为 1，进行分账后退款。
     * 分账后退款会从账户的可提现金额中进行退款。 当可提现金额也不足退款金额时，会发生提交退款成功，但回调失败的情况。
     * 目前可提现金额没有充值渠道，为了避免出现订单无法退款的情况出现，请根据业务情况自行保留一部分可提现金额在系统中。
     * @param $params [
            参数             类型  是否必传                    说明
            app_id          string 是                      小程序APPID
            out_order_no    string 是                      商户分配订单号，标识进行退款的订单
            out_refund_no   string 是                      商户分配退款号
            reason          string 是                      退款原因
            refund_amount   number 是                      退款金额，单位[分]
            sign            string 是                      开发者对核心字段签名, 签名方式见文档附录, 防止传输过程中出现意外
            cp_extra        string 否                      开发者自定义字段，回调原样回传
            notify_url      string 否                      商户自定义回调地址
            thirdparty_id   string 否，服务商模式接入必传      第三方平台服务商 id，非服务商模式留空
            disable_msg     number 否                      是否屏蔽担保支付的推送消息，1-屏蔽 0-非屏蔽，接入 POI 必传
            msg_page        string 否                      担保支付消息跳转页
            all_settle      number 否                      是否为分账后退款，1-分账后退款；0-分账前退款。分账后退款会扣减可提现金额，请保证余额充足
     * ]
     * @return array
     * @throws \Exception
     */
    public function createRefund($params){
        return HttpClient::create()->postJson("api/apps/ecpay/v1/create_refund",$params)->toArray();
    }

    /**
     * 查询退款
     * @param $params [
           app_id                   小程序APPID
           out_refund_no            开发者侧的退款号
           sign                     开发者对核心字段签名, 签名方式见文档附录, 防止传输过程中出现意外
           thirdparty_id     第三方平台服务商 id，非服务商模式留空
     * ]
     * @return array
     * @throws \Exception
     */
    public function queryRefund($params){
        return HttpClient::create()->postJson("api/apps/ecpay/v1/query_refund",$params)->toArray();
    }

    /**
     * 分账请求
     * 分账功能用于在交易发生之后一段时间后，可以根据需求分配货款，将资金从冻结户转移至可提现账户。
     * 为了保证业务正确处理, 请按担保交易设置页面的分账周期处理分账. 订单在支付后 150 天后如果仍然未进行分账，则会自动分配全部货款给卖家。
     * 在分账环节中，小程序平台会参与对整笔交易的手续费进行扣除，详情可以参考附录手续费计算规则。
     * 手续费默认由卖家承担。目前担保支付只支持单次的分账，会自动将未指定的货款分配给卖家账户
     * @param $params [
            app_id              string      是                       小程序APPID
            out_settle_no       string      是                       开发者侧的结算号, 不可重复
            out_order_no        string      是                       商户分配订单号，标识进行结算的订单
            settle_desc         string      是                       结算描述，长度限制 80 个字符
            settle_params       string      否                       其他分账方信息，分账分配参数 SettleParameter 数组序列化后生成的 json 格式字符串
            sign                string      是                       开发者对核心字段签名, 签名方式见文档附录, 防止传输过程中出现意外
            cp_extra            string      否                       开发者自定义字段，回调原样回传
            notify_url          string      否                       商户自定义回调地址
            thirdparty_id       string      否，服务商模式接入必传       第三方平台服务商 id，非服务商模式留空
     * ]
     * @return array
     * @throws \Exception
     */
    public function settle($params){
        return HttpClient::create()->postJson("api/apps/ecpay/v1/settle",$params)->toArray();
    }

    /**
     * 查询分账
     * @param $params [
     *      app_id           小程序APPID
     *      out_settle_no    开发者侧的分账号
     *      sign             开发者对核心字段签名, 签名方式见文档附录, 防止传输过程中出现意外
     *      thirdparty_id    第三方平台服务商 id，非服务商模式留空
     * ]
     * @return array
     * @throws \Exception
     */
    public function querySettle($params){
        return HttpClient::create()->postJson("api/apps/ecpay/v1/settle",$params)->toArray();
    }

    /**
     * 支付设置校验
     * 小程序通过 GET 请求，携带以下 query 参数以如下逻辑校验请求是否来源于字节跳动小程序服务端
     * @param $token
     * @param $timestamp
     * @param $msg
     * @param $nonce
     * @return string
     */
    public function responSign($token,$timestamp,$msg,$nonce){
        return sha1($msg.$nonce.$timestamp.$token);
    }

    /**
     * 请求签名算法
     * 发往小程序服务端的请求，在没有特殊说明时，均需要使用担保支付秘钥进行签名，由于保证请求的来源：
     * 1. sign, app_id , thirdparty_id 字段用于标识身份字段，不参与签名。将其他字段内容（不包含 key）与支付 SALT 一起进行字典序排序后，使用&符号链接
     * 2. 使用 md5 算法对该字符串计算摘要，作为结果
     * 3. 参与加签的字段均以 POST 请求中的 body 内容为准, 不考虑参数默认值等规则. 对于对象类型与数组类型的参数, 使用 POST 中的字符串原串进行左右去除空格后进行加签
     * 4. 如有其他安全性需要, 可以在请求中添加 nonce 字段, 该字段无任何业务影响, 仅影响加签内容, 使同一请求的多次签名不同.
     * @param array $map
     * @param string $salt
     * @return string
     */
    public function sign($map,$salt="") {
        $rList = array();
        foreach($map as $k =>$v) {
            if ($k == "other_settle_params" || $k == "app_id" || $k == "sign" || $k == "thirdparty_id")
                continue;
            $value = trim(strval($v));
            $len = strlen($value);
            if ($len > 1 && substr($value, 0,1)=="\"" && substr($value,$len, $len-1)=="\"")
                $value = substr($value,1, $len-1);
            $value = trim($value);
            if ($value == "" || $value == "null")
                continue;
            array_push($rList, $value);
        }

        array_push($rList, $salt);
        sort($rList, 2);
        return md5(implode('&', $rList));
    }

    /**
     * 计算手续费
     * 手续费=floor((订单总金额-已退款金额)*0.006)
     * 手续费计算需要根据当前用户可操作金额（订单总金额-已退款或结算金额）计算，
     * 对乘费率千分之六后的金额四舍五入即为该笔交易的手续费。 手续费会在调用结算时扣减，
     * 若该笔交易在结算后产生退款等行为，手续费不会回退。
     * @param float $orderAmount 订单金额
     * @param float $refundPrice 退款金额
     * @param float $price       手续费
     * @return float|int
     */
    public function commission($orderAmount,$refundPrice,$price=0.006){
        return floor(($orderAmount-$refundPrice) * $price);
    }

    /**
     * 返回通知内容
     * @param int $errNo
     * @param string $tips
     * @return false|string
     */
    public function getNotifyReply($errNo=0,$tips="success"){
        return json_encode(["err_no"=>$errNo,"err_tips"=>$tips]);
    }

}