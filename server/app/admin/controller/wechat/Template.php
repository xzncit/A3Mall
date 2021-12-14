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
use app\admin\service\wechat\Subscribe as SubscribeService;
use app\common\model\wechat\SubscribeMessage as SubscribeMessageModel;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Template extends Auth {

    /**
     * 列表
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(){
        if(Request::isAjax()){
            $list = SubscribeService::getList(Request::param(),["type"=>1]);
            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    /**
     * 列表
     * @return string|\think\response\Json
     */
    public function editor(){
        try{
            if(Request::isAjax()){
                $data = Request::param();
                $data["type"] = 1;
                SubscribeService::save($data);
                return Response::returnArray("操作成功！");
            }

            return View::fetch("",SubscribeService::detail(Request::param("id")));
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
            SubscribeService::delete(Request::get("id",0,"intval"));
            return Response::returnArray("删除成功");
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。",0);
        }
    }

}