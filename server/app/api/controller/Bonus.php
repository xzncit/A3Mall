<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller;

use app\common\exception\BaseException;
use think\facade\Request;
use app\api\service\Bonus as BonusService;

class Bonus extends Base {

    /**
     * 领劵中心
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(){
        try{
            return $this->returnAjax("ok",1,BonusService::getList(Request::param()));
        }catch (BaseException $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode(),$ex->getRaw());
        }
    }

    /**
     * 领劵
     * @return \think\response\Json
     */
    public function receive(){
        try {
            return $this->returnAjax("领取优惠劵成功",1,BonusService::receive(Request::param("id","0","intval")));
        }catch (BaseException $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode(),$ex->getRaw());
        }
    }
}