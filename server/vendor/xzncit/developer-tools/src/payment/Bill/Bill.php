<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\payment\Bill;

use xzncit\core\base\BasePayment;

class Bill extends BasePayment {

    /**
     * 下载交易账单
     * 商户可以通过该接口下载历史交易清单。比如掉单、系统错误等导致商户侧和微信侧数据不一致，通过对账单核对后可校正支付状态。
     * 注意：
     * 1、微信侧未成功下单的交易不会出现在对账单中。支付成功后撤销的交易会出现在对账单中，跟原支付单订单号一致；
     * 2、微信在次日9点启动生成前一天的对账单，建议商户10点后再获取；
     * 3、对账单中涉及金额的字段单位为“元”。
     * 4、对账单接口只能下载三个月以内的账单。
     * 5、对账单是以商户号维度来生成的，如一个商户号与多个appid有绑定关系，则使用其中任何一个appid都可以请求下载对账单。对账单中的appid取自交易时候提交的appid，与请求下载对账单时使用的appid无关。
     * 6、自2018年起入驻的商户默认是开通免充值券后的结算对账单，且汇总数据为总交易单数，应结订单总金额，退款总金额，充值券退款总金额，手续费总金额，订单总金额，申请退款总金额。
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function download(array $data){
        return $this->request("https://api.mch.weixin.qq.com/pay/downloadbill",$data,false);
    }

    /**
     * 下载资金账单
     * 商户可以通过该接口下载自2017年6月1日起 的历史资金流水账单。
     * 说明：
     * 1、资金账单中的数据反映的是商户微信账户资金变动情况；
     * 2、当日账单在次日上午9点开始生成，建议商户在上午10点以后获取；
     * 3、资金账单中涉及金额的字段单位为“元”。
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function downloadfundflow($data){
        return $this->request("https://api.mch.weixin.qq.com/pay/downloadfundflow",$data,true);
    }

}