<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\promotion;

use app\admin\controller\Auth;
use mall\utils\Date;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;
use app\common\model\promotion\Bonus as BonusModel;

class Bonus extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $key = Request::get("key/a","","trim,strip_tags");

            $condition = [];
            if(!empty($key["title"])){
                $condition[] = ['name',"like",'%'.$key["title"].'%'];
            }

            $bonus = new BonusModel();
            $list = $bonus->getList($condition,$limit);

            if(empty($list["data"])){
                return Response::returnArray("当前还没有数据哦",1);
            }

            foreach($list['data'] as $key=>$item){
                $list['data'][$key]['total'] = $item["used"] . ' / ' . $item["giveout"];
                $list['data'][$key]['time'] = $item["start_time"] . ' ~ ' . $item["end_time"];
            }

            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    public function editor(){
        if(!Request::isAjax()){
            $id = (int)Request::param("id");
            $bonus = new BonusModel();
            $rs = empty($id) ? [] : $bonus::where("id",$id)->find();

            return View::fetch("",[
                "data"=>$rs
            ]);
        }

        $data = Request::post();

        $data["start_time"] = strtotime($data["start_time"]);
        $data["end_time"] = strtotime($data["end_time"]);

        if($data["start_time"] > $data["end_time"]){
            return Response::returnArray("开始时间不能小于结束时间",0);
        }

        $bonus = new BonusModel();
        try{
            if($bonus->where("id",$data["id"])->count()){
                $bonus->where("id",$data["id"])->save($data);
            }else{
                $bonus->create($data);
            }
        } catch (\Exception $ex) {
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
            $row = Db::name("promotion_bonus")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("promotion_bonus")->delete($id);
            Db::name("users_bonus")->where(["bonus_id"=>$id])->delete();
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
            Db::name("promotion_bonus")->where("id",$id)->update([$name=>$value]);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试！",0);
        }

        return Response::returnArray("ok");
    }

}