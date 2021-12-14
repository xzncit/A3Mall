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
use think\facade\Request;
use mall\response\Response;
use think\facade\View;
use app\admin\service\users\Tags as TagsService;

class Tags extends Auth {

    /**
     * 列表
     * @return string|\think\response\Json
     */
    public function index(){
        if(Request::isAjax()){
            $result = TagsService::getList(Request::param());
            return Response::returnArray("ok",0,$result["data"],$result["count"]);
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
                TagsService::save(Request::param());
                return Response::returnArray("操作成功！");
            }

            return View::fetch("",TagsService::detail(Request::param("id",0,"intval")));
        } catch (\Exception $e){
            return Response::returnArray($e->getMessage(),0);
        }
    }

    /**
     * 删除
     * @return \think\response\Json
     */
    public function delete(){
        try {
            TagsService::delete(Request::param("id",0,"intval"));
            return Response::returnArray("删除成功");
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。",0);
        }
    }

}