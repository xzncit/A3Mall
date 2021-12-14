<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\controller\order;

use app\admin\controller\Auth;
use think\facade\Request;
use think\facade\View;
use mall\response\Response;
use app\admin\service\order\Recharge as RechargeService;
use app\common\models\Setting as SettingModel;

class Recharge extends Auth{

    /**
     * 列表
     * @return string|\think\response\Json
     */
    public function index(){
        if(Request::isAjax()){
            $list = RechargeService::getList(Request::param());
            return Response::returnArray("ok",0,$list["data"],$list['count']);
        }

        return View::fetch();
    }

    /**
     * 充值设置
     * @return string|\think\response\Json
     */
    public function setting(){
        try{
            if(Request::isAjax()){
                $data = Request::post();
                $list = $data['list'];

                $i=0;
                $array = [];
                foreach($list['num'] as $key=>$value){
                    $array[] = [
                        "num"=>!empty($list["num"][$key]) ? $list["num"][$key] : "",
                    ];

                    $i++;
                }

                SettingModel::saveData("order_rechange",[ "list"=>$array ]);
                return Response::returnArray("操作成功");
            }

            return View::fetch("",["data"=>SettingModel::getArrayData("order_rechange")]);
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

    /**
     * 删除
     * @return \think\response\Json
     */
    public function delete(){
        try {
            RechargeService::delete(Request::get("id"));
            return Response::returnArray("删除成功");
        } catch (\Exception $ex) {
            return Response::returnArray($ex->getMessage(),0);
        }

    }

}