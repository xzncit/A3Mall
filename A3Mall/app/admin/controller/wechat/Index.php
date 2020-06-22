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
use mall\basic\Attachments;
use mall\basic\Setting;
use mall\basic\Users;
use mall\library\wechat\chat\classes\Fans;
use mall\library\wechat\chat\classes\Menu;
use mall\library\wechat\chat\WeChat;
use mall\utils\Data;
use mall\utils\Date;
use mall\utils\Tool;
use think\facade\Db;
use think\facade\Request;
use mall\response\Response;
use think\facade\Session;
use think\facade\View;
use mall\utils\CString;

class Index extends Auth {

    public function api(){
        if(Request::isAjax()){
            $post = Request::post();
            $data = json_encode($post,JSON_UNESCAPED_UNICODE);

            Db::name("setting")->where("name","wechat")->update([
                "value"=>$data
            ]);
            return Response::returnArray("操作成功！");
        }

        $content = Db::name("setting")->where("name","wechat")->value("value");
        if(!empty($content)){
            $content = json_decode($content,true);
        }

        $content["ip"] = $_SERVER['SERVER_ADDR'];
        $content["url"] = createUrl("api/wechat.index/",[],false,true);
        return View::fetch("",[
            "data"=>$content
        ]);
    }

    public function pay(){
        if(Request::isAjax()){
            $post = Request::post();
            $data = json_encode($post,JSON_UNESCAPED_UNICODE);

            Db::name("setting")->where("name","wepay")->update([
                "value"=>$data
            ]);
            return Response::returnArray("操作成功！");
        }

        $content = Db::name("setting")->where("name","wepay")->value("value");
        if(!empty($content)){
            $content = json_decode($content,true);
        }

        return View::fetch("",[
            "data"=>$content
        ]);
    }

    public function menu(){
        if(Request::isAjax()){
            $data = Request::post("post","");
            Setting::save("wechat_menu",$data);
            try {
                Menu::save(Setting::get("wechat_menu",false));
            }catch (\Exception $e){
                return Response::returnArray($e->getMessage(),0);
            }

            return Response::returnArray("操作成功");
        }

        return View::fetch("",[
            "data"=>Setting::get("wechat_menu",true),
            "keys"=>Db::name("wechat_keys")->where("keys","not in","default,subscribe")->select()->toArray()
        ]);
    }

}