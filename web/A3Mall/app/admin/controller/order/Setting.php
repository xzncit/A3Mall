<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\controller\order;

use app\admin\controller\Auth;
use app\common\model\system\Setting as SettingConfig;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Setting extends Auth {

    public function index(){
        if(Request::isAjax()){

            $post = Request::post();
            $data = json_encode($post,JSON_UNESCAPED_UNICODE);
            $setting = new SettingConfig();
            $setting->where("name","order")->save(["value"=>$data]);
            return Response::returnArray("操作成功！");
        }

        $content = Db::name("setting")->where("name","order")->value("value");
        if(!empty($content)){
            $content = json_decode($content,true);
        }

        return View::fetch("",[
            "data"=>$content
        ]);
    }

}