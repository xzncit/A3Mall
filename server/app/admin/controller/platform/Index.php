<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\platform;

use app\admin\controller\Auth;
use think\facade\View;
use think\facade\Request;
use mall\response\Response;
use app\admin\service\platform\Dashboard as DashboardService;

class Index extends Auth {

    /**
     * 首页
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(){
        return View::fetch("",DashboardService::getData());
    }

    /**
     * 系统信息
     * @return string
     */
    public function info(){
        return View::fetch("info",DashboardService::getSystemInfo());
    }

    /**
     * 清理缓存
     * @return array|string|\think\response\Json
     */
    public function cache(){
        if(Request::isAjax()){
            try{
                $type = Request::get("type","","trim,strip_tags");
                if(empty($type)){
                    return DashboardService::getCacheList();
                }

                DashboardService::clearCache($type);
                return Response::returnArray("清理缓存成功！",1);
            }catch (\Exception $ex){
                return Response::returnArray($ex->getMessage(),0);
            }
        }

        return View::fetch();
    }

}