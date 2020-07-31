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
use think\facade\Db;
use mall\response\Response;
use think\facade\View;
use mall\utils\Data;
use mall\basic\Attachments;
use app\common\model\base\Archives as Article;

class Archives extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $key = Request::get("key/a","","trim,strip_tags");

            $condition = [];
            if(isset($key["cat_id"]) && $key["cat_id"] != '-1'){
                $condition["Archives.pid"] = $key["cat_id"];
            }

            if(!empty($key["title"])){
                $condition[] = ["Archives.title","like",'%'.$key["title"].'%'];
            }

            $article = new Article();
            $result = $article->getList($condition,$limit);
            if(empty($result["data"])){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            return Response::returnArray("ok",0,$result["data"],$result["count"]);
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

        $article = new Article();
        $data = Request::post();
        $data['attachment_id'] = empty($data['attachment_id']) ? [] : $data['attachment_id'];
        if(($obj = $article::find($data["id"])) != false){
            try{
                $obj->save($data);
            }catch (\Exception $e){
                return Response::returnArray($e->getMessage(),0);
            }
        }else{
            try{
                $article->save($data);
            }catch (\Exception $e){
                return Response::returnArray("操作失败，请重试。",0);
            }

            $data['id'] = $article->id;
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