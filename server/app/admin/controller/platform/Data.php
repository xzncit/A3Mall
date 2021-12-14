<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\platform;

use app\admin\controller\Auth;
use think\facade\Request;
use mall\response\Response;
use think\facade\View;
use app\admin\service\platform\Data as DataService;

class Data extends Auth {

    /**
     * 列表
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(){
        if(Request::isAjax()){
            $list = DataService::getList(Request::param());
            return Response::returnArray("ok",0,$list['data'],$list["count"]);
        }

        return View::fetch();
    }

    /**
     * 编辑
     * @return array|\think\response\Json
     */
    public function editor(){
        try{
            if(Request::isAjax()){
                DataService::save(Request::param());
                return Response::returnArray("操作成功！");
            }

            return View::fetch("",DataService::detail(Request::param("id","0","intval")));
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
            DataService::delete(Request::get("id","0","intval"));
            return Response::returnArray("删除成功");
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

}