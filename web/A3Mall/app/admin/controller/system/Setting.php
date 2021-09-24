<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\system;

use app\admin\controller\Auth;
use mall\basic\Attachments;
use mall\library\delivery\aliyun\Aliyun;
use mall\library\oss\aliyun\AliyunOssClient;
use think\facade\Config;
use think\facade\Db;
use think\facade\Request;
use mall\response\Response;
use think\facade\View;
use app\common\model\system\Setting as SettingConfig;

class Setting extends Auth {

    public function index(){
        if(Request::isAjax()){
            $post = Request::post();
            $setting = new SettingConfig();
            foreach([
                "web_name"=>$post["web_name"],
                "web_title"=>$post["web_title"],
                "web_keywords"=>$post["web_keywords"],
                "web_description"=>$post["web_description"],
                "web_copyright"=>$post["web_copyright"],
                "web_logo"=>$post["web_logo"]
            ] as $key=>$value){
                if(Db::name("setting")->where('name',$key)->count()){
                    $setting->where("name",$key)->save(["value"=>$value]);
                }else{
                    $setting->create(["name"=>$key,"value"=>$value]);
                }
            }

            Attachments::handle($post["attachment_id"],999);
            return Response::returnArray("操作成功！");
        }

        $setting = Db::name("setting")->select()->toArray();
        $data = [];
        foreach($setting as $item){
            $data[$item["name"]] = $item["value"];
        }

        return View::fetch("",[
            "data"=>$data
        ]);
    }

    public function email(){
        if(Request::isAjax()){
            $post = Request::post();
            $data = json_encode($post,JSON_UNESCAPED_UNICODE);
            $setting = new SettingConfig();
            $setting->where("name","email")->save(["value"=>$data]);
            return Response::returnArray("操作成功！");
        }

        $content = Db::name("setting")->where("name","email")->value("value");
        if(!empty($content)){
            $content = json_decode($content,true);
        }

        return View::fetch("",[
            "data"=>$content
        ]);
    }

    public function store(){
        if(Request::isAjax()){
            $post = Request::post();
            $data = json_encode($post,JSON_UNESCAPED_UNICODE);
            $setting = new SettingConfig();
            $setting->where("name","store")->save(["value"=>$data]);
            return Response::returnArray("操作成功！");
        }

        $content = Db::name("setting")->where("name","store")->value("value");
        if(!empty($content)){
            $content = json_decode($content,true);
        }

        return View::fetch("",[
            "data"=>$content
        ]);
    }

    public function upload(){
        if(Request::isAjax()){
            $post = Request::post();
            $data = json_encode($post,JSON_UNESCAPED_UNICODE);
            $setting = new SettingConfig();
            $result = $setting->where("name","upload")->find();
            $setting->where("name","upload")->save(["value"=>$data]);
            try {
                $client = new AliyunOssClient();
                if(!empty($post["domain"])){
                    $client->addDomain(str_replace(["http://","https://"],["",""],$post["domain"]));
                }else{
                    !empty($result["domain"]) && $client->deleteDomain(str_replace(["http://","https://"],["",""],$result["domain"]));
                }
            }catch (\Exception $ex){}
            return Response::returnArray("操作成功！");
        }

        $content = Db::name("setting")->where("name","upload")->value("value");
        if(!empty($content)){
            $content = json_decode($content,true);
        }

        return View::fetch("",[
            "city"=>Config::get("oss.aliyun.city"),
            "data"=>$content
        ]);
    }

    public function delivery(){
        if(Request::isAjax()){
            $post = Request::post();
            Aliyun::setConfig($post);
            return Response::returnArray("操作成功！");
        }

        return View::fetch("",[
            "data"=>Aliyun::getConfig()
        ]);
    }

    public function address(){
        if(Request::isAjax()){
            $post = Request::post();
            $data = json_encode($post,JSON_UNESCAPED_UNICODE);
            $setting = new SettingConfig();
            $setting->where("name","address")->save(["value"=>$data]);
            return Response::returnArray("操作成功！");
        }

        $content = Db::name("setting")->where("name","address")->value("value");
        if(!empty($content)){
            $content = json_decode($content,true);
        }

        return View::fetch("",[
            "data"=>$content
        ]);
    }
}