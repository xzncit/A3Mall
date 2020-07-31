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
use app\common\model\wechat\Keys;
use mall\basic\Attachments;
use mall\response\Response;
use mall\utils\Date;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Reply extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $key = Request::get("key/a","","trim,strip_tags");

            $condition = [];

            if(!empty($key["title"])){
                $condition[] = ["keys","like",'%'.$key["title"].'%'];
            }

            $condition[] = ["keys","not in","defaults,subscribe"];

            $wechatKeys = new Keys();
            $list = $wechatKeys->getList($condition,$limit);

            if(empty($list['count'])){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    public function editor(){
        $id = Request::get('id',"","intval");
        if(Request::isAjax()){
            $data = Request::post();

            $wechatKeys = new Keys();
            if($wechatKeys::where(["keys"=>$data["keys"]])->count()){
                return Response::returnArray("关键字己存在！",0);
            }

            try{
                if(($obj=$wechatKeys::where(["id"=>$data["id"]])->find()) !=false){
                    $obj->where(["id"=>$data["id"]])->save($data);
                }else{
                    $wechatKeys->save($data);
                }
            }catch (\Exception $ex){
                return Response::returnArray("操作失败，请稍后在试。".$ex->getMessage(),0);
            }

            return Response::returnArray("操作成功");
        }


        return View::fetch("",[
            "data"=>Db::name("wechat_keys")->where('keys','not in','defaults,subscribe')->where(["id"=>$id])->find()
        ]);
    }

    public function delete(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        try {

            $row = Db::name("wechat_keys")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("wechat_keys")->delete($id);
            Attachments::clear(["pid"=>$id,"module"=>"keys"]);
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
            Db::name("wechat_keys")->where("id",$id)->update([$name=>$value]);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试！",0);
        }

        return Response::returnArray("ok");
    }

    public function subscribe(){
        if(Request::isAjax()){
            $data = Request::post();
            if(Db::name("wechat_keys")->where(["keys"=>"subscribe"])->count()){
                Db::name("wechat_keys")->strict(false)->where(["keys"=>"defaults"])->update($data);
            }else{
                $data["keys"] = "subscribe";
                $data["create_time"] = time();
                unset($data["id"]);
                Db::name("wechat_keys")->strict(false)->insert($data);
            }

            return Response::returnArray("操作成功");
        }


        return View::fetch("",[
            "data"=>Db::name("wechat_keys")->where(["keys"=>"subscribe"])->find()
        ]);
    }

    public function defaults(){
        if(Request::isAjax()){
            $data = Request::post();
            if(Db::name("wechat_keys")->where(["keys"=>"defaults"])->count()){
                Db::name("wechat_keys")->strict(false)->where(["keys"=>"defaults"])->update($data);
            }else{
                $data["keys"] = "defaults";
                $data["create_time"] = time();
                unset($data["id"]);
                Db::name("wechat_keys")->strict(false)->insert($data);
            }

            return Response::returnArray("操作成功");
        }

        return View::fetch("",[
            "data"=>Db::name("wechat_keys")->where(["keys"=>"defaults"])->find()
        ]);
    }

}