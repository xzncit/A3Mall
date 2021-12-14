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
use mall\response\Response;
use think\facade\Request;
use think\facade\View;
use app\common\models\Setting as SettingModel;
use app\admin\service\system\Sms as SmsService;

class Sms extends Auth {

    /**
     * 短信设置
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function setting(){
        if(Request::isAjax()){
            SettingModel::saveData("sms",Request::post());
            return Response::returnArray("操作成功！");
        }

        return View::fetch("",[ "data"=>SettingModel::getArrayData("sms") ]);
    }

    /**
     * 短信模板
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function template(){
        if(Request::isAjax()){
            $list = SmsService::getList(Request::param());
            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    /**
     * 编辑模板
     * @return string|\think\response\Json
     */
    public function template_editor(){
        try{
            if(Request::isAjax()){
                SmsService::save(Request::param());
                return Response::returnArray("操作成功！");
            }

            return View::fetch("",SmsService::detail(Request::param("id",0,"intval")));
        }catch (\Exception $ex){

        }
    }

    /**
     * 更新字段
     * @return \think\response\Json
     */
    public function field(){
        try {
            SmsService::setFields();
            return Response::returnArray("操作成功");
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }
}