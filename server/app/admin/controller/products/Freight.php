<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\products;

use app\admin\controller\Auth;
use mall\response\Response;
use think\facade\Request;
use think\facade\View;
use app\admin\service\products\Freight as FreightService;

class Freight extends Auth {

    /**
     * 列表
     * @return string|\think\response\Json
     */
    public function index(){
        if(Request::isAjax()){
            $list = FreightService::getList(Request::param());
            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    /**
     * 编辑
     * @return string|\think\response\Json
     */
    public function editor(){
        try{
            if(Request::isAjax()){
                FreightService::save(Request::param());
                return Response::returnArray("操作成功！");
            }

            return View::fetch("",FreightService::detail(Request::param("id",0,"intval")));
        }catch (\Exception $ex){
            return Response::returnArray("操作失败，请重试。",0);
        }
    }

    /**
     * 删除
     * @return \think\response\Json
     */
    public function delete(){
        try{
            FreightService::delete(Request::param("id",0,"intval"));
            return Response::returnArray("删除成功");
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

    /**
     * 更新字段
     * @return \think\response\Json
     */
    public function field(){
        try {
            FreightService::setFields();
            return Response::returnArray("操作成功");
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

}