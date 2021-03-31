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
use mall\basic\Attachments;
use mall\utils\Data;
use mall\utils\Tool;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Page extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");

            $condition = ["pid"=>0,"module"=>"page"];
            $categoryModel = new \app\common\model\base\Category();
            $list = $categoryModel->getList($condition,$limit);

            if(empty($list['data'])){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    public function editor(){
        if(!Request::isAjax()){
            $id = (int)Request::param("id");
            $rs = empty($id) ? [] : Db::name("category")->where("id",$id)->find();
            $list = Db::name("category")->where(["module"=>"page"])->select()->toArray();

            return View::fetch("editor",[
                "images"=>Db::name("attachments")->where([
                    'pid'=>$id,"module"=>"page"
                ])->select()->toArray(),
                "cat"=>Data::analysisTree(Data::familyProcess($list)),
                "data"=>$rs
            ]);
        }

        $data = Request::post();

        $categoryModel = new \app\common\model\base\Category();
        $data["module"] = "page";
        $data["content"] = Tool::editor($data["content"]);
        $data['attachment_id'] = empty($data['attachment_id']) ? [] : $data['attachment_id'];
        try{
            if($categoryModel->where("id",$data["id"])->count()){
                if (!$categoryModel->check_tree($categoryModel->where(["module"=>"page"])->select()->toArray(), $data)) {
                    return Response::returnArray("{$data['title']} 是 ID {$data['pid']} 的父栏目,不能修改！", 0);
                }

                $categoryModel->where("id",$data["id"])->save($data);
            }else{
                unset($data["id"]);
                $data["id"] = $categoryModel->create($data)->id;
            }
        }catch (\Exception $e){
            return Response::returnArray("操作失败，请重试。",0);
        }

        Attachments::handle($data["attachment_id"],$data['id']);
        return Response::returnArray("操作成功！");
    }

    public function delete(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        try {
            // 检测是否有子分类
            if(Db::name("category")->where("pid",$id)->count() > 0){
                return Response::returnArray("该分类下有子分类，请先删除子分类。",0);
            }

            $row = Db::name("category")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("category")->delete($id);
            Attachments::clear(["pid"=>$id,"module"=>"page"]);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。",0);
        }

        return Response::returnArray("ok");
    }

    public function field(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        $name = strip_tags(trim(Request::get("name")));
        $value = (int)Request::get("value");

        try {
            Db::name("category")->where("id",$id)->update([$name=>$value]);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试！",0);
        }

        return Response::returnArray("ok");
    }

}