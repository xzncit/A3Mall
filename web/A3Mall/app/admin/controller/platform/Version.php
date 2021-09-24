<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\platform;

use app\admin\controller\Auth;
use mall\basic\Attachments;
use mall\response\Response;
use think\exception\ValidateException;
use think\facade\Db;
use think\facade\Filesystem;
use think\facade\Request;
use think\facade\View;

class Version extends Auth {

    public function index(){
        $type = Request::get("type","1","0");
        if(Request::isAjax()){
            $limit = Request::get("limit");

            $versionModel = new \app\common\model\base\Version();
            $list = $versionModel->getList(["type"=>$type],$limit);
            if(empty($list["data"])){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch("",[
            "type"=>$type
        ]);
    }

    public function editor(){
        if(!Request::isAjax()){
            $id = (int)Request::param("id");
            $type = Request::get("type","1","0");
            $rs = empty($id) ? [] : Db::name("version")->where("id",$id)->find();

            return View::fetch("editor",[
                "data"=>$rs,
                "type"=>$type
            ]);
        }

        $data = Request::post();
        $versionModel = new \app\common\model\base\Version();
        try{
            if(!empty($data["id"])){
                $obj = $versionModel->where("id",$data["id"])->find();
                if($data["url"] != $obj["url"]){
                    if(Db::name("attachments")->where("path",$obj["url"])->delete()){
                        $file = trim($obj["url"],"/");
                        file_exists($file) && unlink($file);
                    }
                }

                $versionModel->where("id",$data["id"])->save($data);
            }else{
                unset($data["id"]);
                $versionModel->create($data);
            }
        } catch(\Exception $ex) {
            return Response::returnArray("操作失败，请重试。",0);
        }

        return Response::returnArray("操作成功！");
    }

    public function delete(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        try {
            $row = Db::name("version")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            if(Db::name("attachments")->where("path",$row["url"])->delete()){
                $file = trim($row["url"],"/");
                file_exists($file) && unlink($file);
            }

            Db::name("version")->delete($id);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。",0);
        }

        return Response::returnArray("ok");
    }

    public function file(){
        $file = Request::file('file');
        try {
            if(!in_array($file->extension(),["apk","jpg","png","gif","jpeg"])){
                throw new \Exception("您所选择的文件不允许上传。",0);
            }

            $dir = "/uploads/";
            $uploadFile = Filesystem::disk("public")->putFile( '', $file);
            $name = basename($uploadFile);
            $lastId = Attachments::save($name,$dir . trim($uploadFile,"/"),$file->extension(),$file->getSize());
            Attachments::handle($lastId,1);
            return Response::returnArray("ok",0,["src"=>$dir . trim($uploadFile,"/"),"id"=>$lastId]);
        } catch (ValidateException $e) {
            return Response::returnArray($e->getMessage(),1);
        }

        return Response::returnArray("上传参数错误",1);
    }

}