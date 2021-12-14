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
use app\admin\service\wechat\Fans as FansService;

class Fans extends Auth {

    /**
     * 列表
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(){
        if(Request::isAjax()){
            $list = FansService::getList(Request::param());
            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    /**
     * 同步粉丝
     * @return \think\response\Json
     */
    public function sync_fans(){
        try {
            FansService::syncFans();
        }catch (\Exception $e){
            return Response::returnArray($e->getMessage(),0);
        }

        return Response::returnArray("同步成功！");
    }

    /**
     * 同步黑名单
     * @return \think\response\Json
     */
    public function sync_black(){
        try {
            FansService::syncBlack();
        }catch (\Exception $e){
            return Response::returnArray($e->getMessage(),0);
        }

        return Response::returnArray("同步成功！");
    }

    /**
     * 同步标签
     * @return \think\response\Json
     */
    public function sync_tags(){
        try {
            FansService::syncTags();
        }catch (\Exception $e){
            return Response::returnArray($e->getMessage(),0);
        }

        return Response::returnArray("同步成功！");
    }

    /**
     * 添加黑名单
     * @return \think\response\Json
     */
    public function add_black(){
        try {
            FansService::addBlack(Request::param("openid"));
            return Response::returnArray('拉入黑名单成功！');
        } catch (\Exception $e) {
            return Response::returnArray($e->getMessage(),0);
        }
    }

    /**
     * 移出黑名单
     * @return \think\response\Json
     */
    public function remove_black(){
        try {
            FansService::removeBlack(Request::param("openid"));
            return Response::returnArray('移出黑名单成功！');
        } catch (\Exception $e) {
            return Response::returnArray($e->getMessage(),0);
        }
    }

    /**
     * 删除
     * @return \think\response\Json
     */
    public function delete(){
        try{
            FansService::delete(Request::param("id",0,"intval"));
            return Response::returnArray("删除成功");
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

}