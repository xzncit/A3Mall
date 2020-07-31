<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\products;

use app\admin\controller\Auth;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Area extends Auth {

    public function index(){
        $pid = (int)Request::param("pid");
        if(Request::isAjax()){
            $limit = Request::get("limit");

            $area = new \app\common\model\base\Area();
            $list = $area->getList(['pid'=>$pid],$limit);

            if(empty($list['data'])){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }


        return View::fetch("",[
            'pid'=>$pid
        ]);
    }

    public function editor(){
        if(!Request::isAjax()){
            $id = (int)Request::param("id");
            $pid = (int)Request::param("pid");
            $rs = empty($id) ? [] : Db::name("area")->where("id",$id)->find();

            return View::fetch("",[
                "pid"=>$pid,
                "data"=>$rs
            ]);
        }

        $data = Request::post();
        $area = new \app\common\model\base\Area();
        if(($obj=$area::find($data["id"])) != false){
            try {
                unset($data['pid']);
                $obj->update($data);
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }
        }else{
            try {
                $area->save($data);
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
            if(Db::name("area")->where('pid',$id)->count()){
                throw new \Exception("该地区下有子区域，请先删除！",0);
            }

            Db::name("area")->delete($id);
        } catch (\Exception $ex) {
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

        try {
            Db::name("area")->where("id",$id)->update([$name=>$value]);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试！",0);
        }

        return Response::returnArray("ok");
    }

}