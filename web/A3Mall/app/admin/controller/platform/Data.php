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
use app\common\model\base\DataItem;
use think\facade\Request;
use think\facade\Db;
use mall\basic\Attachments;
use mall\response\Response;
use think\facade\View;

class Data extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");

            $dataModel = new \app\common\model\base\Data();
            $list = $dataModel->getList([],$limit);

            if(empty($list["data"])){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            return Response::returnArray("ok",0,$list['data'],$list["count"]);
        }

        return View::fetch();
    }

    public function editor(){
        if(!Request::isAjax()){
            $id = (int)Request::param("id");
            $rs = empty($id) ? [] : Db::name("data")->where("id",$id)->find();

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
        $dataModel = new \app\common\model\base\Data();
        try{
            if($dataModel->where("id",$data["id"])->count()){
                $dataModel->where("id",$data["id"])->save($data);
            }else{
                unset($data["id"]);
                $data["id"] = $dataModel->create($data)->id;
            }
        }catch (\Exception $ex) {
            return Response::returnArray("操作失败，请重试。",0);
        }

        $marketing = $data['marketing'];
        $i=0;
        $in = [];
        $dataItemModel = new DataItem();
        foreach($marketing['id'] as $key=>$value){
            $arr = [
                "pid"=>$data['id'],
                "name"=>!empty($marketing["name"][$key]) ? $marketing["name"][$key] : "",
                "url"=>!empty($marketing["url"][$key]) ? $marketing["url"][$key] : "",
                "photo"=>!empty($marketing["photo"][$key]) ? $marketing["photo"][$key] : "",
                "sort"=>$i,
                "target"=>!empty($marketing["target"][$key]) ? $marketing["target"][$key] : 0
            ];

            if($dataItemModel->where("id",$value)->count()){
                $in[] = $value;
                $dataItemModel->where("id",$value)->save($arr);
            }else{
                $in[] = $dataItemModel->create($arr)->id;
            }

            $i++;
        }

        Attachments::handle($marketing["attachment_id"],$data['id']);
        if(!empty($in)){
            $r = $dataItemModel->where("pid",$data['id'])->where("id","not in",$in)->select()->toArray();
            foreach($r as $val){
                $dataItemModel->where("id",$val['id'])->delete();
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
            $row = Db::name("data")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            if(!Db::name("data")->delete($id)){
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