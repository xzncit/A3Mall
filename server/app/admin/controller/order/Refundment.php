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
use app\admin\service\order\Refundment as RefundmentService;

class Refundment extends Auth {
    
    public function index(){
        if(Request::isAjax()){
            $list = RefundmentService::getList(Request::param());
            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }
        
        return View::fetch();
    }
    
    public function detail(){
        try{
            return View::fetch("",RefundmentService::detail(Request::param("id")));
        }catch (\Exception $ex){
            $this->error($ex->getMessage());
        }
    }
    
}