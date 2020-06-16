<?php
namespace app\admin\controller\platform;

use app\admin\controller\Auth;
use mall\utils\Date;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use mall\utils\Data;
use think\facade\View;
use think\facade\Route;

class Category extends Auth {

    public function index(){
        if(Request::isAjax()) {
            $limit = Request::get("limit");

            $condition = ["pid"=>0,"module"=>"article"];
            $count = Db::name("category")->where($condition)->count();
            $data = Db::name("category")->where($condition)->order("id DESC")->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            $list = $data->items();

            $_list = [];
            foreach($list as $key=>$value){
                $_list[] = $value;
                $children = \mall\basic\Category::getCategoryChildren($value["id"]);
                $arr = Data::analysisTree(Data::familyProcess($children,[],$value["id"]));
                array_splice($_list, count($_list), 0, $arr);
            }

            foreach($_list as $key=>$item){
                $_list[$key]['title'] = (empty($item['level']) ? '' : $item['level']) . $item["title"];
                $_list[$key]['create_time'] = Date::format($item["create_time"]);
                $_list[$key]['url'] = createUrl("editor",["id"=>$item["id"]]);
            }

            return Response::returnArray("ok",0,$_list,$count);
        }

        return View::fetch();
    }

    public function editor(){
        if(!Request::isAjax()){
            $id = (int)Request::param("id");
            $rs = empty($id) ? [] : Db::name("category")->where("id",$id)->find();
            $list = Db::name("category")->where(["module"=>"article"])->select()->toArray();

            return View::fetch("",[
                "cat"=>Data::analysisTree(Data::familyProcess($list)),
                "data"=>$rs
            ]);
        }

        $data = Request::post();

        $data["module"] = "article";
        if(!empty($data["id"])){
            if (!Data::checkTree(Db::name("category")->where(["module"=>"article"])->select()->toArray(), $data)) {
                return Response::returnArray("{$data['title']} 是 ID {$data['pid']} 的父栏目,不能修改！", 0);
            }

            try {
                $data['update_time'] = time();
                Db::name("category")
                    ->strict(false)
                    ->where("id",$data['id'])->update($data);
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }

            return Response::returnArray("操作成功！",1);
        }

        $data['create_time'] = time();
        if(!Db::name("category")->strict(false)->insert($data)){
            return Response::returnArray("操作失败，请重试。",0);
        }

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

            // 检测是否有文章
            if(Db::name("archives")->where("pid",$id)->count() > 0){
                return Response::returnArray("该分类下有文章，请先删除文章。",0);
            }

            Db::name("category")->delete($id);
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