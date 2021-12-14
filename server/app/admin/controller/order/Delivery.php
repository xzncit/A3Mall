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
use think\facade\Request;
use mall\response\Response;
use think\facade\View;
use app\admin\service\order\Delivery as DeliveryService;

class Delivery extends Auth {

    /**
     * 列表
     * @return string|\think\response\Json
     */
    public function index(){
        try{
            if(Request::isAjax()){
                $list = DeliveryService::getList(Request::param());
                return Response::returnArray("ok",0,$list["data"],$list['count']);
            }

            return View::fetch();
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),1);
        }
    }

    /**
     * 详情
     * @return string
     */
    public function detail(){
        try{
            return View::fetch("",DeliveryService::detail(Request::param("id")));
        }catch (\Exception $ex){
            $this->error($ex->getMessage());
        }
    }
    
    
}