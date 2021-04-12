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

class Deliver extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");

            $deliver = new \app\common\model\base\Deliver();
            $list = $deliver->getList([],$limit);
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
            $rs = empty($id) ? [] : Db::name("deliver")->where("id",$id)->find();

            $province = Db::name('area')->where(['pid' => 0])->select()->toArray();

            $city = [];
            if(!empty($rs["province"])){
                $city = Db::name('area')->where(['pid' => $rs["province"]])->select()->toArray();
            }

            $area = [];
            if(!empty($rs["city"])){
                $area = Db::name('area')->where(['pid' => $rs["city"]])->select()->toArray();
            }

            return View::fetch("",[
                "province"=>$province,
                "city"=>$city,
                "area"=>$area,
                "data"=>$rs
            ]);
        }

        $data = Request::post();
        $deliver = new \app\common\model\base\Deliver();
        $data["is_default"] = isset($data["is_default"]) && is_numeric($data["is_default"]) ? $data["is_default"] : 0;
        if($data["is_default"] == 1){
            $deliver->where("1=1")->update(["is_default" => 0]);
        }

        try{
            if($deliver->where("id",$data["id"])->count()){
                $deliver->where("id",$data["id"])->save($data);
            }else{
                $deliver->create($data);
            }
        }catch (\Exception $ex){
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
            $row = Db::name("deliver")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("deliver")->delete($id);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。",0);
        }

        return Response::returnArray("ok");
    }

}