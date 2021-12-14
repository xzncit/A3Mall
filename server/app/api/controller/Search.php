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
use app\api\service\Search as SearchService;

class Search extends Base {

    /**
     * 搜索中心
     * @return \think\response\Json
     */
    public function index(){
        try{
            return $this->returnAjax("ok",1,SearchService::getSearchKeywordsList());
        }catch (\Exception $ex){
            return $this->returnAjax("服务器繁忙，请稍后在试",0);
        }
    }

    /**
     * 搜索结果
     * @return \think\response\Json
     */
    public function get_list(){
        try{
            return $this->returnAjax("ok",1,SearchService::getList(Request::param()));
        }catch (BaseException $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode(),$ex->getRaw());
        }
    }

    /**
     * 保存搜索关键词
     * @return \think\response\Json
     */
    public function keywords(){
        try{
            SearchService::keywords(Request::param());
            return $this->returnAjax("ok",1);
        }catch (\Exception $ex){
            return $this->returnAjax("ok",1);
        }
    }

}