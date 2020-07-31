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
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;
use mall\basic\Promotion;
use app\common\model\promotion\Order;

class Index extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $key = Request::get("key/a","","trim,strip_tags");

            $condition = [];
            if(!empty($key["title"])){
                $condition[] = ["name","like",'%'.$key["title"].'%'];
            }

            $order = new Order();
            $list = $order->getList($condition,$limit);


            if(empty($list['data'])){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            foreach($list['data'] as $key=>$item){
                $list['data'][$key]['type'] = Promotion::getType($item["type"]);
            }

            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    public function editor(){
        if(!Request::isAjax()){
            $id = (int)Request::param("id");
            $order = new Order();
            $rs = empty($id) ? [] : $order->where("id",$id)->find();

            return View::fetch("",[
                "type"=>Promotion::getType(),
                "data"=>$rs
            ]);
        }

        $data = Request::post();

        $data["start_time"] = strtotime($data["start_time"]);
        $data["end_time"] = strtotime($data["end_time"]);

        if($data["start_time"] > $data["end_time"]){
            return Response::returnArray("开始时间不能小于结束时间",0);
        }

        $order = new Order();
        if(($obj=$order->where('id',$data["id"])->find()) != false){
            try {
                $obj->save($data);
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }
        }else{
            try {
                $order->save($data);
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }
        }

        return Response::returnArray("操作成功！");
    }

    public function delete(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        try {
            $row = Db::name("promotion_order")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("promotion_order")->delete($id);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。",0);
        }

        return Response::returnArray("ok");
    }

}