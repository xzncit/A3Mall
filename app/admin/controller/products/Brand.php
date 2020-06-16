<?php
namespace app\admin\controller\products;

use app\admin\controller\Auth;
use mall\basic\Attachments;
use mall\utils\Data;
use mall\utils\Date;
use mall\utils\Tool;
use mall\response\Response;
use think\facade\Request;
use think\facade\View;
use think\facade\Db;

class Brand extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $count = Db::name("products_brand")->count();
            $data = Db::name("products_brand")->order('id desc')->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            $list = $data->items();

            foreach($list as $key=>$item){
                $list[$key]['create_time'] = Date::format($item["create_time"]);
                $list[$key]['url'] = createUrl("editor",["id"=>$item["id"]]);
                $list[$key]['photo'] = Tool::thumb($item["photo"]);
            }

            return Response::returnArray("ok",0,$list,$count);
        }

        return View::fetch();
    }

    public function editor(){
        if(!Request::isAjax()){
            $id = (int)Request::param("id");
            $rs = empty($id) ? [] : Db::name("products_brand")->where("id",$id)->find();

            return View::fetch("",[
                "images"=>Db::name("attachments")->where([
                    'pid'=>$id,"module"=>"brand"
                ])->select()->toArray(),
                "data"=>$rs
            ]);
        }

        $data = Request::post();

        $data["content"] = Tool::editor($data["content"]);
        $data['attachment_id'] = empty($data['attachment_id']) ? [] : $data['attachment_id'];
        if(!empty($data["id"])){
            try {
                Db::name("products_brand")->strict(false)->where("id",$data['id'])->update($data);
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }
        }else{
            $data['create_time'] = time();
            if(!Db::name("products_brand")->strict(false)->insert($data)){
                return Response::returnArray("操作失败，请重试。",0);
            }

            $data['id'] = Db::name('products_brand')->getLastInsID();
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
            $row = Db::name("products_brand")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("products_brand")->delete($id);
            Attachments::clear(["pid"=>$id,"module"=>"brand"]);
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
            Db::name("products_brand")->where("id",$id)->update([$name=>$value]);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试！",0);
        }

        return Response::returnArray("ok");
    }

}