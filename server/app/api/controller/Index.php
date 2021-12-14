<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller;

use think\facade\Request;
use app\api\service\Home as HomeService;
use app\common\exception\BaseException;

/**
 * 首页控制器
 * Class Index
 * @package app\api\controller
 */
class Index extends Base {

    /**
     * 首页
     * @return \think\response\Json
     */
    public function index(){
        try{
            return $this->returnAjax("ok",1,HomeService::getData());
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),0);
        }
    }

    /**
     * 获取列表数据
     * @return \think\response\Json
     */
    public function get_list(){
        try{
            return $this->returnAjax("ok",1,HomeService::getList(Request::param()));
        }catch (BaseException $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode(),$ex->getRaw());
        }
    }

}
