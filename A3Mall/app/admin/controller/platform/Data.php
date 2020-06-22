<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\platform;

use app\admin\controller\Auth;
use think\facade\Request;
use think\facade\Db;
use mall\utils\Date;
use mall\basic\Attachments;
use mall\response\Response;
use think\facade\View;

class Data extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $count = Db::name("data_category")->count();
            $data = Db::name("data_category")->order('id desc')->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            $list = $data->items();

            foreach($list as $key=>$item){
                $list[$key]['time'] = Date::format($item["create_time"]);
                $list[$key]['url'] = createUrl("editor",["id"=>$item["id"]]);
            }

            return Response::returnArray("ok",0,$list,$count);
        }

        return View::fetch();
    }

    public function editor(){
        if(!Request::isAjax()){
            $id = (int)Request::param("id");
            $rs = empty($id) ? [] : Db::name("data_category")->where("id",$id)->find();

            $marketing = Db::name("data_item")->where('pid',$id)->order('sort ASC')->select()->toArray();
            foreach($marketing as $key=>$value){
                $marketing[$key]['attachment_id'] = Db::name("attachments")->where('path',$value['photo'])->value("id");
            }

            return View::fetch("",[
                "marketing"=>$marketing,
                "data"=>$rs
            ]);
        }

        $data = Request::post();

        if(!empty($data["id"])){
            try {
                Db::name("data_category")->strict(false)->where("id",$data['id'])->update($data);
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }
        }else{
            $data["create_time"] = time();
            if(!Db::name("data_category")->strict(false)->insert($data)){
                return Response::returnArray("操作失败，请重试。",0);
            }

            $data['id'] = Db::name('data_category')->getLastInsID();
        }

        $marketing = $data['marketing'];

        $i=0;
        $in = [];
        foreach($marketing['id'] as $key=>$value){
            $arr = [
                "pid"=>$data['id'],
                "name"=>!empty($marketing["name"][$key]) ? $marketing["name"][$key] : "",
                "url"=>!empty($marketing["url"][$key]) ? $marketing["url"][$key] : "",
                "photo"=>!empty($marketing["photo"][$key]) ? $marketing["photo"][$key] : "",
                "sort"=>$i,
                "target"=>!empty($marketing["target"][$key]) ? $marketing["target"][$key] : 0
            ];

            if(empty($value)){
                Db::name("data_item")->insert($arr);
                $in[] = Db::name("data_item")->getLastInsID();
            }else{
                $in[] = $value;
                Db::name("data_item")->where(['id'=>$value])->update($arr);
            }

            $i++;
        }

        Attachments::handle($marketing["attachment_id"],$data['id']);

        if(!empty($in)){
            $r = Db::name("data_item")->where("pid",$data['id'])->where("id","not in",$in)->select()->toArray();
            foreach($r as $val){
                Db::name("data_item")->delete($val['id']);
                Attachments::clear(['path'=>$val['photo']]);
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
            $row = Db::name("data_category")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            if(!Db::name("data_category")->delete($id)){
                throw new \Exception("删除失败，请重试！",0);
            }

            $result = Db::name("data_item")->where(["pid"=>$id])->select()->toArray();

            foreach($result as $val){
                Db::name("data_item")->delete($val['id']);
                Attachments::clear(['path'=>$val['photo']]);
            }
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。",0);
        }

        return Response::returnArray("ok");
    }

}