<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller;

use app\common\exception\BaseException;
use think\facade\Request;
use app\api\service\Order as OrderService;
use app\api\service\PaymentOrder as PaymentOrderService;

class Order extends Base {

    /**
     * 确认订单
     * @return \think\response\Json
     */
    public function confirm(){
        try{
            return $this->returnAjax("ok",1,PaymentOrderService::confirm(Request::param()));
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode());
        }
    }

    /**
     * 创建订单并支付
     * @return \think\response\Json
     */
    public function create(){
        try{
            return $this->returnAjax("ok",1,PaymentOrderService::create(Request::param()));
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage().' file: '.$ex->getFile() . ' line: ' . $ex->getLine(),$ex->getCode());
        }
    }

    /**
     * 订单列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function get_list(){
        try{
            return $this->returnAjax("ok",1,OrderService::getList(Request::param()));
        }catch (BaseException $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode(),$ex->getRaw());
        }
    }

    /**
     * 订单详情
     * @return \think\response\Json
     */
    public function detail(){
        try{
            return $this->returnAjax("ok",1,OrderService::detail(Request::post("id","0","intval")));
        }catch (\Exception $ex){
            return $this->returnAjax("服务器繁忙，请稍后在试",$ex->getCode());
        }
    }

    /**
     * 申请退款
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function refund(){
        try{
            return $this->returnAjax("ok",1,OrderService::refund(Request::post("id","0","intval")));
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode());
        }
    }

    /**
     * 提交退款申请
     * @return \think\response\Json
     */
    public function apply_refund(){
        try{
            OrderService::applyRefund(Request::param());
            return $this->returnAjax("您的退款申请己提交，请等待管理员审核");
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode());
        }
    }

    /**
     * 确认收货详情
     * @return \think\response\Json
     */
    public function delivery(){
        try{
            return $this->returnAjax("ok",1,OrderService::delivery(Request::post("id","0","intval")));
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode());
        }
    }

    /**
     * 确认收货
     * @return \think\response\Json
     */
    public function confirm_delivery(){
        try{
            OrderService::confirmDelivery(Request::post("id","0","intval"));
            return $this->returnAjax("确认收货成功");
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode());
        }
    }

    /**
     * 评价详情
     * @return \think\response\Json
     */
    public function evaluate(){
        try{
            return $this->returnAjax("ok",1,OrderService::evaluate(Request::post("id","0","intval")));
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode());
        }
    }

    /**
     * 提交评价
     * @return \think\response\Json
     */
    public function do_evaluate(){
        try{
            OrderService::applyEvaluate(Request::param());
            return $this->returnAjax("评价成功");
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode());
        }
    }

    /**
     * 重新发起订单支付
     * @return \think\response\Json
     */
    public function payment(){
        try{
            return $this->returnAjax("ok",1,PaymentOrderService::payment(Request::param()));
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode());
        }
    }

    /**
     * 取消订单
     * @return \think\response\Json
     */
    public function cancel(){
        try{
            OrderService::cancel(Request::get("order_id","","intval"));
            return $this->returnAjax("取消订单成功");
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode());
        }
    }

    /**
     * 售后列表
     * @return \think\response\Json
     */
    public function service(){
        try{
            return $this->returnAjax("ok",1,OrderService::service(Request::param()));
        }catch (BaseException $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode(),$ex->getRaw());
        }
    }

    /**
     * 获取物流信息
     * @return \think\response\Json
     */
    public function express(){
        try{
            return $this->returnAjax("ok",1,OrderService::getExpressData(Request::post("id","0","intval")));
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode());
        }
    }

    /**
     * 获取订单信息
     * @return \think\response\Json
     */
    public function info(){
        try{
            return $this->returnAjax("ok",1,OrderService::getOrderInfo(Request::param("order_id","0","intval")));
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode());
        }
    }

}