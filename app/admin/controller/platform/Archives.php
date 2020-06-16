<?php
namespace app\admin\controller\platform;

use app\admin\controller\Auth;
use think\facade\Request;
use think\facade\Db;
use mall\utils\Tool;
use mall\response\Response;
use think\facade\View;
use mall\utils\Data;
use mall\basic\Attachments;
use mall\utils\Date;

class Archives extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $key = Request::get("key/a","","trim,strip_tags");

            $condition = [];
            if(isset($key["cat_id"]) && $key["cat_id"] != '-1'){
                $condition["a.pid"] = $key["cat_id"];
            }

            if(!empty($key["title"])){
                $condition[] = ["a.title","like",'%'.$key["title"].'%'];
            }

            $count = Db::name("archives")
                ->alias("a")
                ->join("category c","a.pid=c.id","LEFT")
                ->where($condition)->count();

            $data = Db::name("archives")
                ->field("a.*,c.title as cat_name")
                ->alias("a")
                ->join("category c","a.pid=c.id","LEFT")
                ->where($condition)->order('a.id desc')->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            $list = $data->items();

            foreach($list as $key=>$item){
                $list[$key]['create_time'] = Date::format($item["create_time"]);
                $list[$key]['url'] = createUrl("editor",["id"=>$item["id"]]);
                $list[$key]['photo'] = Tool::thumb($item["photo"],"small");
            }

            return Response::returnArray("ok",0,$list,$count);
        }

        $cat = Db::name("category")->where(["status"=>0,"module"=>"article"])->select()->toArray();
        return View::fetch("",[
            "cat"=>Data::analysisTree(Data::familyProcess($cat))
        ]);
    }

    public function editor(){
        if(!Request::isAjax()){
            $id = (int)Request::param("id");
            $rs = empty($id) ? [] : Db::name("archives")->where("id",$id)->find();
            $cat = Db::name("category")->where(["status"=>0,"module"=>"article"])->select()->toArray();

            return View::fetch("",[
                "cat"=>Data::analysisTree(Data::familyProcess($cat)),
                "images"=>Db::name("attachments")->where([
                    'pid'=>$id,"module"=>"archives","method"=>"article"
                ])->select()->toArray(),
                "data"=>$rs
            ]);
        }

        $data = Request::post();

        $data["content"] = Tool::editor($data["content"]);
        $data['attachment_id'] = empty($data['attachment_id']) ? [] : $data['attachment_id'];
        if(!empty($data["id"])){
            try {
                $data['update_time'] = time();
                Db::name("archives")->strict(false)->where("id",$data['id'])->update($data);
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }
        }else{
            $data['create_time'] = time();
            if(!Db::name("archives")->strict(false)->insert($data)){
                return Response::returnArray("操作失败，请重试。",0);
            }

            $data['id'] = Db::name('archives')->getLastInsID();
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
            $row = Db::name("archives")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("archives")->delete($id);
            Attachments::clear(["pid"=>$id,"module"=>"archives","method"=>"article"]);
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
            Db::name("archives")->where("id",$id)->update([$name=>$value]);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试！",0);
        }

        return Response::returnArray("ok");
    }

}