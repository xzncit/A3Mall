<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\users;

use app\admin\controller\Auth;
use mall\basic\Attachments;
use mall\response\Response;
use mall\utils\Tool;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;
use app\common\model\system\Setting as SettingConfig;

class Setting extends Auth {

    public function base(){
        $setting = new SettingConfig();
        if(Request::isAjax()){
            $data = Request::post();
            $data["username"] = strip_tags(trim($data["username"]));
            $data["amount"] = (float)$data["amount"];
            $data["bank"] = strip_tags(trim($data["bank"]));
            $setting->saveConfigData("users",$data);
            return Response::returnArray("操作成功",1);
        }

        return View::fetch("",[
            "data"=>$setting->getConfigData("users")
        ]);
    }

    public function register(){
        $setting = new SettingConfig();
        if(Request::isAjax()){
            $data = Request::post();
            $setting->saveConfigData("register",Tool::editor($data["content"]));
            return Response::returnArray("操作成功",1);
        }

        return View::fetch("",[
            "data"=>$setting->getConfigData("register",false)
        ]);
    }

}