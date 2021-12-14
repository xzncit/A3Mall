<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\wechat;

use app\BaseController;
use think\facade\Request;
use think\facade\View;
use app\api\service\wechat\News as NewsService;

class News extends BaseController {

    /**
     * 详情
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function view(){
        return View::fetch("",NewsService::detail(Request::get("id","0","intval")));
    }

}