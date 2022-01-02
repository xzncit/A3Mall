<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\order;

use app\admin\controller\Auth;
use mall\response\Response;
use think\facade\Request;
use think\facade\View;
use app\admin\service\order\Order as OrderService;

class Index extends Auth {

    /**
     * 列表
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(){
        if(Request::isAjax()){
            $list = OrderService::getList(Request::param());
            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    /**
     * 订单详情
     * @return string
     */
    public function detail(){
        try{
            return View::fetch("",OrderService::detail(Request::param("id",0,"intval")));
        }catch (\Exception $ex){
            $this->error($ex->getMessage());
        }
    }

    /**
     * 支付订单
     * @return string|\think\response\Json
     */
    public function payment(){
        try{
            if(Request::isAjax()){
                return Response::returnArray("操作成功！",1,createUrl("detail",["id"=>OrderService::payOrder(Request::param())]));
            }

            return View::fetch("",OrderService::getPaymentDetail(Request::param("id",0,"intval")));
        }catch (\Exception $ex){
            if(Request::isAjax()){
                return Response::returnArray($ex->getMessage(),0);
            }

            $this->error($ex->getMessage());
        }
    }

    /**
     * 订单发货
     * @return string|\think\response\Json
     */
    public function distribution(){
        try{
            if(Request::isAjax()){
                return Response::returnArray("操作成功！",1,createUrl("detail",["id"=>OrderService::deliverGoods(Request::param())]));
            }

            return View::fetch("",OrderService::getDistributionDetail(Request::param("id",0,"intval")));
        }catch (\Exception $ex){
            if(Request::isAjax()){
                return Response::returnArray($ex->getMessage(),0);
            }

            $this->error($ex->getMessage());
        }
    }

    /**
     * 退款
     * @return string|\think\response\Json
     */
    public function refundment(){
        try{
            if(Request::isAjax()){
                return Response::returnArray("操作成功！",1,createUrl("detail",["id"=>OrderService::orderRefund(Request::param())]));
            }

            return View::fetch("",OrderService::orderRefundDetail(Request::param("id",0,"intval")));
        }catch (\Exception $ex){
            if(Request::isAjax()){
                return Response::returnArray($ex->getMessage(),0);
            }

            $this->error($ex->getMessage());
        }
    }

    /**
     * 完成订单
     */
    public function complete(){
        OrderService::completeOrder(Request::param());
        $this->success("操作成功");
    }

    /**
     * 修改订单金额
     * @return string|\think\response\Json
     */
    public function update_amount(){
        try{
            if(Request::isAjax()){
                OrderService::updateAmount(Request::param());
                return Response::returnArray("操作成功！",1);
            }

            return View::fetch("",OrderService::updateAmountDetail(Request::param("id","0","intval")));
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

    /**
     * 物流信息
     * @return string
     */
    public function express(){
        try{
            return View::fetch("",OrderService::getExpressData(Request::get("id","0","intval")));
        }catch (\Exception $ex){
            $this->error($ex->getMessage());
        }
    }

    /**
     * 删除
     * @return \think\response\Json
     */
    public function delete(){
        try {
            OrderService::delete(Request::get("id",""));
            return Response::returnArray("删除成功");
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。",0);
        }
    }

}