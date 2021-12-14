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
use app\admin\service\users\Suggest as SuggestService;

class Suggest extends Auth {

    /**
     * 列表
     * @return string|\think\response\Json
     */
    public function index(){
        if(Request::isAjax()){
            $list = SuggestService::getList(Request::param());
            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    /**
     * 详情
     * @return string|\think\response\Json
     */
    public function detail(){
        try{
            if(Request::isAjax()){
                SuggestService::save(Request::param());
                return Response::returnArray("操作成功");
            }

            return View::fetch("",SuggestService::detail(Request::param("id",0,"intval")));
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

    /**
     * 删除
     * @return \think\response\Json
     */
    public function delete(){
        try {
            SuggestService::delete(Request::param("id",0,"intval"));
            return Response::returnArray("删除成功");
        } catch (\Exception $ex) {
            return Response::returnArray($ex->getMessage(),0);
        }
    }

}