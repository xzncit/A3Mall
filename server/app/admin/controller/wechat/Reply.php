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
use think\facade\Db;
use think\facade\Request;
use think\facade\View;
use app\admin\service\wechat\Reply as ReplyService;

class Reply extends Auth {

    /**
     * 列表
     * @return string|\think\response\Json
     */
    public function index(){
        if(Request::isAjax()){
            $list = ReplyService::getList(Request::param());
            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    /**
     * 编辑
     * @return string|\think\response\Json
     */
    public function editor(){
        try{
            if(Request::isAjax()){
                ReplyService::save(Request::param());
                return Response::returnArray("操作成功");
            }

            $condition = [];
            $condition[] = ["id","=",Request::param("id",0,"intval")];
            $condition[] = ['keys','not in','defaults,subscribe'];
            return View::fetch("",ReplyService::detail($condition));
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
            ReplyService::delete(Request::get("id",0,"intval"));
            return Response::returnArray("删除成功");
        } catch (\Exception $ex) {
            return Response::returnArray($ex->getMessage(),0);
        }
    }

    /**
     * 更新字段
     * @return \think\response\Json
     */
    public function field(){
        try {
            ReplyService::setFields();
            return Response::returnArray("操作成功");
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

    /**
     * 关注回复
     * @return string|\think\response\Json
     */
    public function subscribe(){
        try {
            if(Request::isAjax()){
                $data = Request::param();
                $data["keys"] = "subscribe";
                ReplyService::saveKeys($data);
                return Response::returnArray("操作成功");
            }

            return View::fetch("",ReplyService::detail(["keys"=>"subscribe"]));
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

    /**
     * 默认回复
     * @return string|\think\response\Json
     */
    public function defaults(){
        try {
            if(Request::isAjax()){
                $data = Request::param();
                $data["keys"] = "defaults";
                ReplyService::saveKeys($data);
                return Response::returnArray("操作成功");
            }

            return View::fetch("",ReplyService::detail(["keys"=>"defaults"]));
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

}