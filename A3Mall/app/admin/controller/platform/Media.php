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
use mall\basic\Attachments;
use mall\response\Response;
use mall\utils\Date;
use mall\utils\Tool;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Media extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $condition = "FIND_IN_SET(suffix,'rar,zip,doc')";
            $count = Db::name("attachments")->where($condition)->count();
            $data = Db::name("attachments")->where($condition)->order('id desc')->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            $list = $data->items();

            foreach($list as $key=>$item){
                $list[$key] = $item;
                $list[$key]['size'] = Tool::convert($item["size"]);
                $list[$key]['create_time'] = Date::format($item["time"]);
            }

            return Response::returnArray("ok",0,$list,$count);
        }

        return View::fetch();
    }

    public function delete(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        try {
            $row = Db::name("attachments")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            if(!Db::name("attachments")->delete($id)){
                throw new \Exception("删除失败，请重试！",0);
            }
            Attachments::deleteImage($row["path"]);
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
            Db::name("attachments")->where("id",$id)->update([$name=>$value]);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试！",0);
        }

        return Response::returnArray("ok");
    }
}