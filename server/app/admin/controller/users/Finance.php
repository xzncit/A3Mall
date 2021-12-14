<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\users;

use app\admin\controller\Auth;
use mall\response\Response;
use think\facade\Request;
use think\facade\View;
use app\admin\service\users\Finance as FinanceService;

class Finance extends Auth {

    private $type = ["1"=>"银行卡","2"=>"支付宝","3"=>"微信"];
    private $status = ["0"=>"审核中","1"=>"已提现","2"=>"未通过"];

    /**
     * 列表
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(){
        if(Request::isAjax()){
            $list = FinanceService::getList(Request::param(),["users_log.action"=>4]);
            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    /**
     * 资金日志
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function fund(){
        if(Request::isAjax()){
            $list = FinanceService::getList(Request::param(),["users_log.action"=>0]);
            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    /**
     * 退款日志
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function refund(){
        if(Request::isAjax()){
            $list = FinanceService::getList(Request::param(),["users_log.action"=>3]);
            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    /**
     * 积分日志
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function point(){
        if(Request::isAjax()){
            $list = FinanceService::getList(Request::param(),["users_log.action"=>1]);
            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    /**
     * 经验日志
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function exp(){
        if(Request::isAjax()){
            $list = FinanceService::getList(Request::param(),["users_log.action"=>2]);
            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    /**
     * 提现申请
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function apply(){
        if(Request::isAjax()){
            $list = FinanceService::apply(Request::param());
            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    /**
     * 提现
     * @return string|\think\response\Json
     */
    public function handle(){
        try{
            if(Request::isAjax()){
                FinanceService::save(Request::param());
                return Response::returnArray("操作成功！");
            }

            return View::fetch("",FinanceService::detail(Request::param("id",0,"intval")));
        }catch (\Exception $ex){
            if(Request::isAjax()) {
                return Response::returnArray($ex->getMessage(), 0);
            }

            $this->error($ex->getMessage());
        }
    }

}