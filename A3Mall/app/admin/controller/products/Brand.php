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
use app\common\model\products\Brand as ProductsBrand;
use mall\basic\Attachments;
use mall\response\Response;
use think\facade\Request;
use think\facade\View;
use think\facade\Db;

class Brand extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");

            $productsBrand = new ProductsBrand();
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
            $rs = empty($id) ? [] : Db::name("products_brand")->where("id",$id)->find();

            return View::fetch("",[
                "images"=>Db::name("attachments")->where([
                    'pid'=>$id,"module"=>"brand"
                ])->select()->toArray(),
                "data"=>$rs
            ]);
        }

        $data = Request::post();
        $productsBrand = new ProductsBrand();
        $data['attachment_id'] = empty($data['attachment_id']) ? [] : $data['attachment_id'];
        if(($obj=$productsBrand::find($data["id"])) != false){
            try {
                $obj->save($data);
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }
        }else{
            try {
                $productsBrand->save($data);
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }

            $data['id'] = $productsBrand->id;
        }

        Attachments::handle($data["attachment_id"],$data['id']);
        return Response::returnArray("操作成功！");
    }

    public function delete(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        try {
            $row = Db::name("products_brand")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("products_brand")->delete($id);
            Attachments::clear(["pid"=>$id,"module"=>"brand"]);
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
            Db::name("products_brand")->where("id",$id)->update([$name=>$value]);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试！",0);
        }

        return Response::returnArray("ok");
    }

}