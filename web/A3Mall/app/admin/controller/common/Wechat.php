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
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Wechat extends Auth {

    public function index(){
        $type = Request::get("type","text");
        $content = Request::get("content","");

        $news = [];
        if($type == "image"){
            $content = file_exists(ltrim($content,"/")) ? $content : "/static/images/default.jpg";
        }else if($type == "news"){
            $news = Db::name("wechat_news")->where(["id"=>$content])->find();
            if(!empty($news["article_id"])){
                $news["article"] = Db::name("wechat_news_article")
                    ->where("id","in",$news["article_id"])
                    ->orderRaw('find_in_set(id,"'.$news["article_id"].'")')
                    ->select()->toArray();
            }
        }

        return View::fetch("common/wechat/" . $type,[
            "content"=>$content,
            "news"=>$news
        ]);
    }

    public function article(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $key = Request::get("key/a","","trim,strip_tags");

            $condition = [];

            if(!empty($key["title"])){
                $condition[] = ["title","like",'%'.$key["title"].'%'];
            }

            $count = Db::name("wechat_news")
                ->where($condition)->count();

            $data = Db::name("wechat_news")
                ->where($condition)->order('id desc')->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            $list = $data->items();

            foreach($list as $key=>$item){
                $article = Db::name("wechat_news_article")
                    ->where("id","in",$item["article_id"])
                    ->orderRaw('find_in_set(id,"'.$item["article_id"].'")')
                    ->find();
                $list[$key]["title"] = $article["title"];
                $list[$key]["local_url"] = $article["local_url"];
                $list[$key]["create_time"] = date("Y-m-d H:i:s",$item["create_time"]);
            }

            return Response::returnArray("ok",0,$list,$count);
        }

        return View::fetch();
    }

}