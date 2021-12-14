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
use mall\response\Response;
use think\facade\Request;
use think\facade\View;
use app\common\models\Setting as SettingModel;

class Mini extends Auth {

    /**
     * 基本设置
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function base(){
        if(Request::isAjax()){
            SettingModel::saveData("wemini_base",Request::post());
            return Response::returnArray("操作成功！");
        }

        return View::fetch("",[ "data"=>SettingModel::getArrayData("wemini_base") ]);
    }

    /**
     * 支付设置
     * @return string|\think\response\Json
     */
    public function pay(){
        if(Request::isAjax()){
            SettingModel::saveData("wemini",Request::post());
            return Response::returnArray("操作成功！");
        }

        return View::fetch("",[ "data"=>SettingModel::getArrayData("wemini") ]);
    }

}