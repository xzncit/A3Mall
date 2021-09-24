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
use app\common\model\system\Setting;
use mall\library\wechat\chat\classes\Menu;
use think\facade\Db;
use think\facade\Request;
use mall\response\Response;
use think\facade\View;

class Index extends Auth {

    public function api(){
        if(Request::isAjax()) {
            $post = Request::post();
            foreach($post as $k=>$v){
                $post[$k] = trim($v);
            }

            $data = json_encode($post,JSON_UNESCAPED_UNICODE);
            Setting::where("name","wechat")->save(["value"=>$data]);
            return Response::returnArray("操作成功！");
        }

        $content = Setting::where("name","wechat")->value("value");
        if(!empty($content)){
            $content = json_decode($content,true);
        }

        // $content["ip"] = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : "0.0.0.0";
        $content["ip"] = gethostbyname(Request::host());
        $content["url"] = createUrl("api/wechat.index/",[],false,true);
        return View::fetch("",[
            "data"=>$content
        ]);
    }

    public function pay(){
        if(Request::isAjax()){
            $post = Request::post();
            foreach($post as $k=>$v){
                $post[$k] = trim($v);
            }

            $data = json_encode($post,JSON_UNESCAPED_UNICODE);
            Setting::where("name","wepay")->save(["value"=>$data]);
            return Response::returnArray("操作成功！");
        }

        $content = Setting::where("name","wepay")->value("value");
        if(!empty($content)){
            $content = json_decode($content,true);
        }

        return View::fetch("",[
            "data"=>$content
        ]);
    }

    public function menu(){
        if(Request::isAjax()){
            $post = Request::post("post");
            $data = json_encode($post,JSON_UNESCAPED_UNICODE);
            Setting::where("name","wechat_menu")->save(["value"=>$data]);
            try {
                Menu::save($post);
            }catch (\Exception $e){
                return Response::returnArray($e->getMessage(),0);
            }

            return Response::returnArray("操作成功");
        }

        $content = Setting::where("name","wechat_menu")->value("value");
        return View::fetch("",[
            "data"=>$content,
            "keys"=>Db::name("wechat_keys")->where("keys","not in","default,subscribe")->select()->toArray()
        ]);
    }

}