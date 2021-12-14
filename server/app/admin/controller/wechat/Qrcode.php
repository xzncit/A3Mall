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
use think\facade\Request;
use think\facade\View;
use mall\response\Response;
use app\admin\service\wechat\Qrcode as QrcodeService;

class Qrcode extends Auth {

    /**
     * 列表
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(){
        if(Request::isAjax()){
            $list = QrcodeService::getList(Request::param());
            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    /**
     * 删除
     * @return \think\response\Json
     */
    public function delete(){
        try {
            QrcodeService::delete(Request::get("id",0,"intval"));
            return Response::returnArray("删除成功");
        } catch (\Exception $ex) {
            return Response::returnArray($ex->getMessage(),0);
        }
    }

    /**
     * 清空
     * @return \think\response\Json
     */
    public function remove(){
        try {
            QrcodeService::clear();
            return Response::returnArray("操作成功");
        } catch (\Exception $ex) {
            return Response::returnArray($ex->getMessage(),0);
        }
    }

}