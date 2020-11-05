<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\controller\order;

use app\admin\controller\Auth;
use think\facade\Db;
use think\facade\Request;
use app\common\model\users\Rechange as RechangeModel;
use think\facade\View;
use mall\response\Response;

class Rechange extends Auth{

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $key = Request::get("key/a","","trim,strip_tags");

            $condition = [];
            $arr = ["rechange.order_no","users.username"];
            if((isset($key["type"]) && isset($arr[$key["type"]])) && !empty($key["title"])){
                $condition[] = [$arr[$key["type"]],"like",'%'.$key["title"].'%'];
            }

            $orderDelivery = new RechangeModel();
            $list = $orderDelivery->getList($condition,$limit);

            if(empty($list["data"])){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            return Response::returnArray("ok",0,$list["data"],$list['count']);
        }

        return View::fetch();
    }

    public function delete(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        try {
            $row = Db::name("users_rechange")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("users_rechange")->delete($id);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。",0);
        }

        return Response::returnArray("ok");
    }

}