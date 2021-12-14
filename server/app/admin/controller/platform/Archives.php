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
use app\admin\service\platform\Archives as ArchivesService;
use app\admin\service\platform\Category as CategoryService;

class Archives extends Auth {

    /**
     * 列表
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(){
        if(Request::isAjax()){
            $result = ArchivesService::getList(Request::param());
            return Response::returnArray("ok",0,$result["data"],$result["count"]);
        }

        return View::fetch("",[ "cat"=>CategoryService::getTree(["status"=>0,"module"=>"article"]) ]);
    }

    /**
     * 编辑
     * @return string|\think\response\Json
     */
    public function editor(){
        try{
            if(Request::isAjax()){
                ArchivesService::save(Request::post());
                return Response::returnArray("操作成功！");
            }else{
                return View::fetch("",ArchivesService::detail(Request::param("id","0","intval")));
            }
        }catch (\Exception $ex){
            return Response::returnArray("操作失败，请重试。",0);
        }
    }

    /**
     * 删除
     * @return \think\response\Json
     */
    public function delete(){
        try {
            ArchivesService::delete(Request::get("id","0","intval"));
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
            ArchivesService::setFields();
            return Response::returnArray("操作成功");
        } catch (\Exception $ex) {
            return Response::returnArray($ex->getMessage(),0);
        }
    }

}