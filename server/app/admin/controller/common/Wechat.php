<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\common;

use app\admin\controller\Auth;
use mall\response\Response;
use think\facade\Request;
use think\facade\View;
use app\admin\service\common\Wechat as WechatService;

class Wechat extends Auth {

    /**
     * 详情
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(){
        return View::fetch("common/wechat/" . Request::get("type","text"),WechatService::getContent(Request::param()));
    }

    /**
     * 列表
     * @return string|\think\response\Json
     */
    public function article(){
        if(Request::isAjax()){
            $list = WechatService::getArticle(Request::param());
            return Response::returnArray("ok",0,$list["data"],$list["count"]);
        }

        return View::fetch();
    }

}