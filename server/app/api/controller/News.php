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
use app\api\service\News as NewsService;

class News extends Base {

    /**
     * 新闻列表
     * @return \think\response\Json
     */
    public function index(){
        try{
            return $this->returnAjax("ok",1,NewsService::getList(Request::param()));
        }catch (BaseException $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode(),$ex->getRaw());
        }
    }

    /**
     * 新闻详情
     * @return \think\response\Json
     */
    public function view(){
        try {
            return $this->returnAjax("ok",1,NewsService::detail(Request::param("id","0","intval")));
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),0);
        }
    }

}