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
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class News extends BaseController {

    public function view(){
        $id = Request::get("id","0","intval");

        Db::name("wechat_news_article")->where(["id"=>$id])->inc("visit")->update();
        if(($data = Db::name("wechat_news_article")->where(["id"=>$id])->find()) != false){
            $data["create_time"] = date("Y-m-d H:i:s",$data["create_time"]);
        }
        return View::fetch("",[
            "data"=>$data
        ]);
    }

}