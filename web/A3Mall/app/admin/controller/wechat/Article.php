<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\wechat;

use app\admin\controller\Auth;
use mall\basic\Attachments;
use mall\response\Response;
use mall\utils\CString;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;
use think\facade\View;

class Article extends Auth {

    public function index(){
        if(Request::isAjax()){
            $page = Request::param("page","1","intval");
            $size = 4*4;
            $count = Db::name("wechat_news")->count();
            $total_page = ceil($count/$size);

            if($total_page == $page -1){
                return [
                    "info"=>"ok","status"=>0,
                    "data"=>[],"total"=>$total_page,
                    "page"=>$page
                ];
            }

            $data = Db::name("wechat_news")->limit((($page - 1) * $size),$size)->select()->toArray();

            if(empty($data)){
                return [
                    "info"=>"ok","status"=>0,
                    "data"=>[],"total"=>$total_page,
                    "page"=>$page
                ];
            }

            foreach($data as $key=>$item){
                $data[$key] = $item;
                $row = Db::name("wechat_news_article")->where("id","in",$item["article_id"])->select()->toArray();
                $data[$key]["list"] = $row;
                $data[$key]["editor"] = createUrl("editor",["id"=>$item["id"]]);
                $data[$key]["remove"] = createUrl("delete",["id"=>$item["id"]]);
            }

            return [
                "info"=>"ok","status"=>1,
                "data"=>$data,"total"=>$total_page,
                "page"=>$page
            ];
        }

        return View::fetch();
    }

    public function editor(){
        if(Request::isAjax()){
            $post = Request::param("post");
            $pid = Request::param("pid","0","intval");
            $arrIds = [];

            Db::startTrans();
            try{
                foreach($post as $value){
                    $attachment_id = [];
                    if(empty($value["digest"])){
                        $value["digest"] = CString::msubstr($value["content"],100,false);
                    }
                    if($value["id"] == 0){
                        $value["create_time"] = time();
                        Db::name("wechat_news_article")->strict(false)->insert($value);
                        $value["id"] = Db::name("wechat_news_article")->getLastInsID();
                        $arrIds[] = $value["id"];
                    }else{
                        Db::name("wechat_news_article")
                            ->strict(false)
                            ->where("id",$value["id"])
                            ->update($value);
                        $arrIds[] = $value["id"];
                    }

                    if(!empty($value["images"])){
                        foreach($value["images"] as $img){
                            $attachment_id[] = $img["id"];
                        }
                        Attachments::handle($attachment_id,$value["id"],false);
                    }
                }

                if($pid <= 0){
                    Db::name("wechat_news")->insert([
                        "admin_id"=>Session::get("system_user_id"),
                        "article_id"=>implode(",",$arrIds),
                        "create_time"=>time()
                    ]);

                    $pid = Db::name("wechat_news")->getLastInsID();
                }else{
                    $row = Db::name("wechat_news")->where(["id"=>$pid])->find();
                    Db::name("wechat_news")->where(["id"=>$pid])->update([
                        "admin_id"=>Session::get("system_user_id"),
                        "article_id"=>implode(",",$arrIds),
                        "update_time"=>time()
                    ]);

                    $a = array_diff(explode(",",$row["article_id"]),$arrIds);
                    Db::name("wechat_news_article")->where("id","in",$a)->delete();
                }

                Db::commit();
            }catch (\Exception $e){
                Db::rollback();
                return Response::returnArray($e->getMessage(),0);
            }

            return Response::returnArray("操作成功");
        }

        $data = [];
        $id = Request::get("id","0","intval");
        if($id > 0){
            $data = Db::name("wechat_news")->where("id",$id)->find();
            if(!empty($data)) {
                $article = Db::name("wechat_news_article")->where("id", "in", $data["article_id"])->select()->toArray();
                foreach($article as $k=>$v){
                    $article[$k]["images"] = Db::name("attachments")->where([
                        "pid"=>$v["id"],"module"=>"wechat","method"=>"article"
                    ])->select()->toArray();
                }
                $data["article"] = json_encode($article,JSON_UNESCAPED_UNICODE);
            }
        }

        return View::fetch("",[
            "data"=>$data
        ]);
    }

    public function delete(){
        $id = Request::get("id","0","intval");
        if(!Request::isAjax()){
            $this->error("本页面不允许直接访问",0);
        }

        $row = Db::name("wechat_news")->where("id",$id)->find();
        if(empty($row)){
            return Response::returnArray("您要查找的内容不存在！",0);
        }

        Db::startTrans();
        try{
            Db::name("wechat_news")->where("id",$id)->delete();
            $article = Db::name("wechat_news_article")->where("id","in",$row["article_id"])->select()->toArray();
            foreach($article as $val){
                Attachments::deleteById($val["id"],"wechat","article");
            }
            Db::name("wechat_news_article")->where("id","in",$row["article_id"])->delete();
            Db::commit();
        }catch (\Exception $e){
            Db::rollback();
            return Response::returnArray($e->getMessage(),0);
        }

        return Response::returnArray("操作成功");
    }

}