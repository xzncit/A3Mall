<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\wechat;

use app\admin\controller\Auth;
use app\common\model\system\Setting;
use mall\basic\Attachments;
use mall\response\Response;
use think\facade\Request;
use think\facade\View;

class Mini extends Auth {

    public function base(){
        if(Request::isAjax()){
            $post = Request::post();
            $data = json_encode($post,JSON_UNESCAPED_UNICODE);

            Setting::where("name","wemini_base")->update(["value"=>$data]);
            return Response::returnArray("操作成功！");
        }

        $content = Setting::where("name","wemini_base")->value("value");
        if(!empty($content)){
            $content = json_decode($content,true);
        }

        return View::fetch("",[
            "data"=>$content
        ]);
    }

    public function pay(){
        if(Request::isAjax()){
            $post = Request::post();
            foreach($post as $k=>$v){
                $post[$k] = trim($v);
            }

            $data = json_encode($post,JSON_UNESCAPED_UNICODE);
            Setting::where("name","wemini")->update(["value"=>$data]);
            return Response::returnArray("操作成功！");
        }

        $content = Setting::where("name","wemini")->value("value");
        if(!empty($content)){
            $content = json_decode($content,true);
        }

        return View::fetch("",[
            "data"=>$content
        ]);
    }

}