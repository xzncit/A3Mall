<?php
namespace app\admin\controller\wechat;

use app\admin\controller\Auth;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Mini extends Auth {

    public function pay(){
        if(Request::isAjax()){
            $post = Request::post();
            $data = json_encode($post,JSON_UNESCAPED_UNICODE);

            Db::name("setting")->where("name","wemini")->update([
                "value"=>$data
            ]);
            return Response::returnArray("操作成功！");
        }

        $content = Db::name("setting")->where("name","wemini")->value("value");
        if(!empty($content)){
            $content = json_decode($content,true);
        }

        return View::fetch("",[
            "data"=>$content
        ]);
    }

}