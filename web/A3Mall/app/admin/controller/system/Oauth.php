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
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Oauth extends Auth {

    public function index(){
        if(Request::isAjax()){
            $data = Db::name("oauth")->select()->toArray();
            return Response::returnArray("ok",0,$data,count($data));
        }

        return View::fetch();
    }

    public function editor(){
        if(!Request::isAjax()){
            $id = Request::param("id","0","intval");
            $row = Db::name("oauth")->where("id",$id)->find();
            if(empty($row)){
                $this->error("您查找的内容不存在！");
            }

            $row["config"] = !empty($row["config"]) ? json_decode($row["config"],true) : [];

            return View::fetch("editor_".$row["code"],[
                "data"=>$row
            ]);
        }

        $data = Request::param();
        $data['config'] = json_encode($data["config"]);
        try{
            Db::name("oauth")->where("id",$data["id"])->update($data);
        }catch(\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
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

        $row = Db::name("oauth")->where('id',$id)->find();
        if(empty($row)){
            throw new \Exception("您要查找的数据不存在！",0);
        }

        try {
            Db::name("oauth")->where("id",$id)->update([$name=>$value]);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试！",0);
        }

        return Response::returnArray("ok");
    }

}