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
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;
use app\common\model\wechat\SubscribeMessage as SubscribeMessageModel;

class Subscribe extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");

            $productsBrand = new SubscribeMessageModel();
            $list = $productsBrand->getList([],$limit);

            if(empty($list['data'])){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    public function editor(){
        if(!Request::isAjax()){
            $id = (int)Request::param("id");
            $rs = empty($id) ? [] : Db::name("wechat_mini_subscribe_message")->where("id",$id)->find();
            if(!empty($rs["content"])){
                $rs["attr"] = json_decode($rs["content"],true);
            }

            return View::fetch("",[
                "data"=>$rs
            ]);
        }

        $data = Request::post();
        $data["content"] = !empty($data["attr"]) ? json_encode($data["attr"],JSON_UNESCAPED_UNICODE) : "";

        $subscribeMessageModel = new SubscribeMessageModel();
        try{
            if($subscribeMessageModel->where("id",$data["id"])->count()){
                $subscribeMessageModel->where("id",$data["id"])->save($data);
            }else{
                $subscribeMessageModel->create($data);
            }
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请重试。",0);
        }

        return Response::returnArray("操作成功！");
    }

    public function delete(){
        $id = (int)Request::get("id");
        try {
            $row = Db::name("wechat_mini_subscribe_message")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("wechat_mini_subscribe_message")->delete($id);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。",0);
        }

        return Response::returnArray("ok");
    }
}