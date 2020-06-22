<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\users;

use app\admin\controller\Auth;
use mall\utils\Date;
use mall\utils\Tool;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;
use think\facade\View;

class Comment extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $key = Request::get("key/a","","trim,strip_tags");

            $condition = [];
            if(isset($key["cat_id"]) && $key["cat_id"] != '-1'){
                $filed = $key["cat_id"] == 0 ? "u.username" : "g.title";
                $condition[] = [$filed,"like",'%'.$key["title"].'%'];
            }

            $count = Db::name("users_comment")
                ->alias('c')
                ->join("users u","c.user_id=u.id","LEFT")
                ->join("goods g","c.goods_id=g.id")
                ->where($condition)->count();

            $data = Db::name("users_comment")
                ->alias('c')
                ->field("c.*,g.title as goods_name,u.username")
                ->join("users u","c.user_id=u.id","LEFT")
                ->join("goods g","c.goods_id=g.id")->order("c.id DESC")
                ->where($condition)->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            $list = $data->items();

            foreach($list as $key=>$item){
                $list[$key]['create_time'] = Date::format($item["create_time"]);
                $list[$key]['url'] = createUrl("detail",["id"=>$item["id"]]);
            }

            return Response::returnArray("ok",0,$list,$count);
        }

        return View::fetch();
    }

    public function detail(){
        if(Request::isAjax()){
            $data = Request::post();

            if(empty($data["reply_content"])){
                return Response::returnArray("请填写回复内容",0);
            }

            $data["reply_content"] = Tool::editor($data["reply_content"]);
            $data["admin_id"] = Session::get("system_user_id");
            $data["reply_time"] = time();
            $data["status"] = 1;
            Db::name("users_comment")->where("id",$data["id"])->update($data);

            return Response::returnArray("操作成功");
        }

        $id = Request::param("id","0","intval");

        if(($row=Db::name("users_comment")->where("id",$id)->find())==false){
            $this->error("您要查找的内容不存在！");
        }

        $row["goods_name"] = Db::name("goods")->where("id",$row["goods_id"])->value("title");
        $row["username"] = Db::name("users")->where("id",$row["user_id"])->value("username");

        return View::fetch("",[
            "data"=>$row
        ]);
    }

    public function delete(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        try {
            $row = Db::name("users_comment")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("users_comment")->delete($id);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。",0);
        }

        return Response::returnArray("ok");
    }

}