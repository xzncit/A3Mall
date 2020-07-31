<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\controller\common;


use think\facade\Request;
use think\facade\View;

class Index extends \app\admin\controller\Auth{

    public function get_goods(){
        return View::fetch();
    }

    public function get_goods_data(){
        return View::fetch();
    }

    public function get_regiment(){
        return View::fetch();
    }

    public function get_group(){
        return View::fetch();
    }

    public function get_second(){
        return View::fetch();
    }

    public function get_article(){
        return View::fetch();
    }

    public function get_notice(){
        return View::fetch();
    }

}