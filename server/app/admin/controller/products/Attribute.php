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
use app\admin\service\products\Attribute as AttributeService;

class Attribute extends Auth {

    public function index(){
        if(Request::isAjax()){
            $list = AttributeService::getList(Request::param(),["pid"=>0]);
            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    public function editor(){
        try {
            if(Request::isAjax()){
                AttributeService::save(Request::param());
                return Response::returnArray("操作成功！");
            }

            return View::fetch("",AttributeService::detail(Request::param("id",0,"intval")));
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

    /**
     * 删除
     * @return \think\response\Json
     */
    public function delete(){
        try{
            AttributeService::delete(Request::get("id","0","intval"));
            return Response::returnArray("删除成功");
        }catch (\Exception $ex){
            return Response::returnArray("操作失败，请稍候在试。",0);
        }
    }

}