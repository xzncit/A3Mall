<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\statistics;

use app\admin\controller\Auth;
use mall\response\Response;
use think\facade\Request;
use think\facade\View;
use app\admin\service\statistics\Sale as SaleService;

class Sale extends Auth {

    /**
     * 销售统计
     * @return string
     */
    public function index(){
        return View::fetch("",SaleService::getSalesRanking());
    }

    /**
     * 购买排行
     * @return string|\think\response\Json
     */
    public function ranking(){
        try{
            if(Request::isAjax()){
                $list = SaleService::getRankingData(Request::param());
                return Response::returnArray("ok",0,$list["data"],$list["count"]);
            }

            return View::fetch();
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

    /**
     * 销售明细
     * @return string|\think\response\Json
     */
    public function sale_list(){
        try{
            if(Request::isAjax()){
                $list = SaleService::getSaleList(Request::param());
                return Response::returnArray("ok",0,$list["data"],$list["count"]);
            }

            return View::fetch();
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

    /**
     * 商品排行
     * @return string|\think\response\Json
     */
    public function sale_order(){
        try{
            if(Request::isAjax()){
                $list = SaleService::getSaleOrder(Request::param());
                return Response::returnArray("ok",0,$list["data"],$list["count"]);
            }

            return View::fetch();
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

}