<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\system;

use app\admin\controller\Auth;
use app\common\model\system\SmsTemplate;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;
use app\common\model\system\Setting;

class Sms extends Auth {

    public function setting(){
        if(Request::isAjax()){
            $post = Request::post();
            $data = json_encode($post,JSON_UNESCAPED_UNICODE);
            $setting = new Setting();
            $setting->where("name","sms")->save(["value"=>$data]);
            return Response::returnArray("操作成功！");
        }

        $content = Db::name("setting")->where("name","sms")->value("value");
        if(!empty($content)){
            $content = json_decode($content,true);
        }

        return View::fetch("",[
            "data"=>$content
        ]);
    }

    public function template(){
        if(Request::isAjax()){
            $limit = Request::get("limit");

            $smsTemplate = new SmsTemplate();
            $list = $smsTemplate->getList([],$limit);

            if(empty($list['data'])){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    public function template_editor(){
        if(!Request::isAjax()){
            $id = (int)Request::param("id");
            $rs = empty($id) ? [] : Db::name("sms_template")->where("id",$id)->find();

            return View::fetch("",[
                "data"=>$rs
            ]);
        }

        $data = Request::post();
        $smsTemplate = new SmsTemplate();
        if(($obj=$smsTemplate::find($data["id"])) != false){
            try {
                $obj->save($data);
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }
        }else{
            try {
                $smsTemplate->save($data);
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }
        }

        return Response::returnArray("操作成功！");
    }

    public function field(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        $name = strip_tags(trim(Request::get("name")));
        $value = (int)Request::get("value");

        $row = Db::name("sms_template")->where('id',$id)->find();
        if(empty($row)){
            throw new \Exception("您要查找的数据不存在！",0);
        }

        try {
            Db::name("sms_template")->where("id",$id)->update([$name=>$value]);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试！",0);
        }

        return Response::returnArray("ok");
    }
}