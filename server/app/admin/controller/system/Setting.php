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
use mall\library\delivery\aliyun\Aliyun;
use think\facade\Config;
use think\facade\Request;
use mall\response\Response;
use think\facade\View;
use app\common\models\Setting as SettingModel;

class Setting extends Auth {

    /**
     * 站点设置
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(){
        if(Request::isAjax()){
            SettingModel::saveData(Request::param());
            return Response::returnArray("操作成功！");
        }

        $array = [];
        foreach(["web_name", "web_title", "web_keywords", "web_description", "web_copyright", "web_logo"] as $value){
            $array[$value] = SettingModel::getStringData($value);
        }

        return View::fetch("",[ "data"=>$array ]);
    }

    /**
     * 邮箱设置
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function email(){
        if(Request::isAjax()){
            SettingModel::saveData("email",Request::param());
            return Response::returnArray("操作成功！");
        }

        return View::fetch("",[ "data"=>SettingModel::getArrayData("email") ]);
    }

    /**
     * 上传设置
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function upload(){
        if(Request::isAjax()){
            SettingModel::saveData("upload",Request::post());
            return Response::returnArray("操作成功！");
        }

        return View::fetch("",[
            "city"=>Config::get("oss.aliyun.city"),
            "data"=>SettingModel::getArrayData("upload")
        ]);
    }

    /**
     * 物流查询
     * @return string|\think\response\Json
     */
    public function delivery(){
        if(Request::isAjax()){
            $post = Request::post();
            Aliyun::setConfig($post);
            return Response::returnArray("操作成功！");
        }

        return View::fetch("",[
            "data"=>Aliyun::getConfig()
        ]);
    }

    /**
     * 联系方式
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function address(){
        if(Request::isAjax()){
            SettingModel::saveData("address",Request::post());
            return Response::returnArray("操作成功！");
        }

        return View::fetch("",[ "data"=>SettingModel::getArrayData("address") ]);
    }

    /**
     * 备案信息
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function copyright(){
        if(Request::isAjax()){
            SettingModel::saveData("copyright",Request::post());
            return Response::returnArray("操作成功！");
        }

        return View::fetch("",[ "data"=>SettingModel::getArrayData("copyright")["copyright"] ]);
    }
}