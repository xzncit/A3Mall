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
use mall\response\Response;
use mall\utils\Tool;
use think\facade\Request;
use think\facade\View;
use app\common\models\Setting as SettingModel;

class Setting extends Auth {

    /**
     * 基本设置
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function base(){
        if(Request::isAjax()){
            $data = Request::post();
            $data["username"] = strip_tags(trim($data["username"]));
            $data["amount"] = (float)$data["amount"];
            $data["bank"] = strip_tags(trim($data["bank"]));
            SettingModel::saveData("users",$data);
            return Response::returnArray("操作成功",1);
        }

        return View::fetch("",[
            "data"=>SettingModel::getArrayData("users")
        ]);
    }

    /**
     * 注册协议
     * @return string|\think\response\Json
     */
    public function register(){
        if(Request::isAjax()){
            $data = Request::post();
            SettingModel::saveData("register",Tool::editor($data["content"]));
            return Response::returnArray("操作成功",1);
        }

        return View::fetch("",[
            "data"=>SettingModel::getStringData("register")
        ]);
    }

}