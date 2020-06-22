<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\system;

use app\admin\controller\Auth;
use think\facade\Db;
use think\facade\Request;
use mall\response\Response;
use think\facade\View;

class Setting extends Auth {

    public function index(){
        if(Request::isAjax()){
            $post = Request::post();

            foreach($post as $key=>$value){
                if(Db::name("setting")->where('name',$key)->count()){
                    Db::name("setting")->where("name",$key)->update([
                        "value"=>$value
                    ]);
                }else{
                    Db::name("setting")->insert([
                        "name"=>$key,"value"=>$value
                    ]);
                }
            }

            return Response::returnArray("操作成功！");
        }

        $setting = Db::name("setting")->select()->toArray();
        $data = [];
        foreach($setting as $item){
            $data[$item["name"]] = $item["value"];
        }

        return View::fetch("",[
            "data"=>$data
        ]);
    }

    public function email(){
        if(Request::isAjax()){

            $post = Request::post();
            $data = json_encode($post,JSON_UNESCAPED_UNICODE);

            Db::name("setting")->where("name","email")->update([
                "value"=>$data
            ]);
            return Response::returnArray("操作成功！");
        }

        $content = Db::name("setting")->where("name","email")->value("value");
        if(!empty($content)){
            $content = json_decode($content,true);
        }

        return View::fetch("",[
            "data"=>$content
        ]);
    }

    public function store(){
        if(Request::isAjax()){

            $post = Request::post();
            $data = json_encode($post,JSON_UNESCAPED_UNICODE);

            Db::name("setting")->where("name","store")->update([
                "value"=>$data
            ]);
            return Response::returnArray("操作成功！");
        }

        $content = Db::name("setting")->where("name","store")->value("value");
        if(!empty($content)){
            $content = json_decode($content,true);
        }

        return View::fetch("",[
            "data"=>$content
        ]);
    }

    public function upload(){
        if(Request::isAjax()){

            $post = Request::post();
            $data = json_encode($post,JSON_UNESCAPED_UNICODE);

            Db::name("setting")->where("name","upload")->update([
                "value"=>$data
            ]);
            return Response::returnArray("操作成功！");
        }

        $content = Db::name("setting")->where("name","upload")->value("value");
        if(!empty($content)){
            $content = json_decode($content,true);
        }

        return View::fetch("",[
            "data"=>$content
        ]);
    }
}