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
use app\admin\service\platform\Category as CategoryService;
use mall\utils\Tool;
use mall\response\Response;
use think\facade\Request;
use think\facade\View;

class Page extends Auth {

    /**
     * 列表
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(){
        if(Request::isAjax()) {
            $list = CategoryService::getList(Request::param(),["pid"=>0,"module"=>"page"]);
            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    /**
     * 编辑
     * @return string|\think\response\Json
     */
    public function editor(){
        try {
            if(Request::isAjax()){
                $data = Request::post();
                $data["module"] = "page";
                $data["content"] = Tool::editor($data["content"]);
                CategoryService::save($data);
                return Response::returnArray("操作成功！");
            }

            return View::fetch("",CategoryService::detail(Request::param("id","0","intval"),["status"=>0,"module"=>"page"]));
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
            CategoryService::save(Request::param("id"));
            return Response::returnArray("删除成功");
        }catch (\Exception $ex){
            return Response::returnArray("操作失败，请稍候在试。",0);
        }
    }

    /**
     * 编辑字段
     * @return \think\response\Json
     */
    public function field(){
        try {
            CategoryService::setFields();
            return Response::returnArray("操作成功");
        } catch (\Exception $ex) {
            return Response::returnArray($ex->getMessage(),0);
        }
    }

}