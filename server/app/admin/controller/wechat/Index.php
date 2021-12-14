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
use app\common\library\wechat\mp\Menu as WechatMenu;
use think\facade\Request;
use mall\response\Response;
use think\facade\View;
use app\common\models\Setting as SettingModel;
use app\common\models\wechat\WechatKeys as WechatKeysModel;

class Index extends Auth {

    /**
     * 接口设置
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function api(){
        if(Request::isAjax()) {
            SettingModel::saveData("wechat",Request::param());
            return Response::returnArray("操作成功！");
        }

        $data = SettingModel::getArrayData("wechat");
        $data["ip"] = gethostbyname(Request::host());
        $data["url"] = createUrl("api/wechat.index/",[],false,true);
        return View::fetch("",[ "data"=>$data ]);
    }

    /**
     * 支付设置
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function pay(){
        if(Request::isAjax()){
            SettingModel::saveData("wepay",Request::post());
            return Response::returnArray("操作成功！");
        }

        return View::fetch("",[ "data"=>SettingModel::getArrayData("wepay") ]);
    }

    /**
     * 设置公众号菜单
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function menu(){
        if(Request::isAjax()){
            try {
                $post = Request::post("post");
                SettingModel::saveData("wechat_menu",$post);
                WechatMenu::save($post);
            }catch (\Exception $e){
                return Response::returnArray($e->getMessage(),0);
            }

            return Response::returnArray("操作成功");
        }

        return View::fetch("",[
            "data"=>SettingModel::getStringData("wechat_menu"),
            "keys"=>WechatKeysModel::where("keys","not in","default,subscribe")->select()->toArray()
        ]);
    }

}