<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\payment\Tools\Allocation;

use xzncit\core\base\BasePayment;
use xzncit\core\http\Response;

class Allocation extends BasePayment {

    /**
     * 请求单次分账
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function profitSharing(array $data){
        return $this->request("https://api.mch.weixin.qq.com/secapi/pay/profitsharing",$data,true);
    }

    /**
     * 请求多次分账
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function multiProfitSharing(array $data){
        return $this->request("https://api.mch.weixin.qq.com/secapi/pay/profitsharing",$data,true);
    }

    /**
     * 查询分账结果
     * @param $transaction_id   微信支付订单号
     * @param $out_order_no     查询分账结果，输入申请分账时的商户分账单号； 查询分账完结执行的结果，输入发起分账完结时的商户分账单号
     * @return array
     * @throws \Exception
     */
    public function profitSharingQuery($transaction_id,$out_order_no){
        return $this->request("https://api.mch.weixin.qq.com/pay/profitsharingquery",[
            "out_order_no"=>$out_order_no,"transaction_id"=>$transaction_id
        ],false);
    }

    /**
     * 添加分账接收方
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function profitSharingAddReceiver(array $data){
        return $this->request("https://api.mch.weixin.qq.com/pay/profitsharingaddreceiver",$data,false);
    }

    /**
     * 删除分账接收方
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function profitSharingRemoveReceiver(array $data){
        return $this->request("https://api.mch.weixin.qq.com/pay/profitsharingremovereceiver",$data,false);
    }

    /**
     * 完结分账
     * @param $transaction_id       微信支付订单号
     * @param $out_order_no         商户系统内部的分账单号，在商户系统内部唯一（单次分账、多次分账、完结分账应使用不同的商户分账单号），同一分账单号多次请求等同一次。只能是数字、大小写字母_-|*@
     * @param $description          分账完结的原因描述
     * @return array
     * @throws \Exception
     */
    public function profitSharingFinish($transaction_id,$out_order_no,$description){
        return $this->request("https://api.mch.weixin.qq.com/secapi/pay/profitsharingfinish",[
            "transaction_id"=>$transaction_id,"out_order_no"=>$out_order_no,"description"=>$description
        ],true);
    }

    /**
     * 查询订单待分账金额
     * @param $transaction_id   微信支付订单号
     * @return array
     * @throws \Exception
     */
    public function profitSharingOrderAmountQuery($transaction_id){
        return $this->request("https://api.mch.weixin.qq.com/pay/profitsharingorderamountquery",[
            "transaction_id"=>$transaction_id
        ],false);
    }

    /**
     * 分账回退
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function profitSharingReturn(array $data){
        return $this->request("https://api.mch.weixin.qq.com/secapi/pay/profitsharingreturn",$data,true);
    }

    /**
     * 回退结果查询
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function profitSharingReturnQuery(array $data){
        return $this->request("https://api.mch.weixin.qq.com/pay/profitsharingreturnquery",$data,false);
    }

    /**
     * 分账动账通知
     */
    public function getNotify(){
        return Response::xml2arr(file_get_contents('php://input'));
    }

}