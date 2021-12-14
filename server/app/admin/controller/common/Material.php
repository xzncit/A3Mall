<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\common;

use app\admin\controller\Auth;
use mall\response\Response;
use think\facade\Request;
use think\facade\View;
use app\admin\service\common\Material as MaterialService;

class Material extends Auth {

    /**
     * 图库中心
     * @return string
     */
    public function index(){
        return View::fetch("",[
            "type"=>Request::param("type","image","trim"),
            "callback"=>Request::param("callback","handle","trim")
        ]);
    }

    /**
     * 获取附件分类
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function get_cat(){
        return Response::returnArray("ok",1,MaterialService::getCategory(Request::param("type","image","trim")));
    }

    /**
     * 获取列表数据
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function get_list(){
        return Response::returnArray("ok",1,MaterialService::getList(Request::param()));
    }

    /**
     * 添加分类
     * @return \think\response\Json
     */
    public function create_category(){
        try {
            MaterialService::createCategory(Request::param());
            return Response::returnArray("添加成功");
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

    /**
     * 删除分类和清空分类下的附件
     * @return \think\response\Json
     */
    public function delete_category(){
        try {
            MaterialService::deleteCategory(Request::param("id","0","intval"));
            return Response::returnArray("删除成功");
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

    /**
     * 删除附件
     * @return \think\response\Json
     */
    public function delete(){
        try{
            MaterialService::delete(Request::param("id","","strip_tags"));
            return Response::returnArray("删除成功");
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

}