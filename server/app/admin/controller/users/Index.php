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
use app\admin\service\users\Finance as FinanceService;
use mall\response\Response;
use think\facade\Request;
use think\facade\View;
use app\admin\service\users\Users as UsersService;

class Index extends Auth {

    /**
     * 列表
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(){
        if(Request::isAjax()){
            $list = UsersService::getList(Request::param());
            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch("",UsersService::getSearchData());
    }

    /**
     * 编辑
     * @return string|\think\response\Json
     */
    public function editor(){
        try{
            if(Request::isAjax()){
                UsersService::save(Request::param());
                return Response::returnArray("操作成功！");
            }

            return View::fetch("",UsersService::detail(Request::param("id",0,"intval")));
        }catch (\Exception $ex){
            return Response::returnArray("操作失败，请重试。",0);
        }
    }

    /**
     * 删除会员
     * @return \think\response\Json
     */
    public function delete(){
        try{
            UsersService::delete(Request::get("id",""));
            return Response::returnArray("删除成功");
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

    /**
     * 财务明细
     * @return string|\think\response\Json
     */
    public function finance(){
        try{
            if(Request::isAjax()){
                UsersService::financeSave(Request::param());
                return Response::returnArray("操作成功！");
            }

            return View::fetch("",UsersService::financeDetail(Request::param("id",0,"intval")));
        }catch (\Exception $ex){
            if(Request::isAjax()) {
                return Response::returnArray($ex->getMessage(), 0);
            }

            $this->error($ex->getMessage());
        }
    }

    /**
     * 会员日志
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function log(){
        if(Request::isAjax()){
            $list = FinanceService::getList(Request::param(),["users_log.user_id"=>Request::param("id",0,"intval")]);
            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch("",[ "id"=>Request::param("id",0,"intval") ]);
    }

    /**
     * 编辑标签
     * @return \think\response\Json
     */
    public function tags(){
        UsersService::updateUsersTags(Request::param());
        return Response::returnArray("ok",0);
    }

    /**
     * 会员收藏
     * @return string|\think\response\Json
     */
    public function collect(){
        try{
            if(Request::isAjax()){
                $list = UsersService::getFavoriteList(Request::param("id"));
                return Response::returnArray("ok",0,$list["data"],$list["count"]);
            }

            return View::fetch("",["id"=>Request::param("id","0","intval")]);
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

    /**
     * 会员地址
     * @return string|\think\response\Json
     */
    public function address(){
        try{
            if(Request::isAjax()){
                $result = UsersService::getAddressList(Request::param("id","0","intval"));
                return Response::returnArray("ok",0, $result, count($result));
            }

            return View::fetch("",[ "id" => Request::param("id","0","intval") ]);
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),1);
        }
    }

}