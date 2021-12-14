<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller;

use app\api\service\Category as CategoryService;

class Category extends Base {

    /**
     * 分类中心
     * @return \think\response\Json
     */
    public function index(){
        try{
            return $this->returnAjax("ok",1,CategoryService::getList());
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),0);
        }
    }

}