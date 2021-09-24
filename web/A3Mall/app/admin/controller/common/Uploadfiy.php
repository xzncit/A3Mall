<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\common;

use app\admin\controller\Auth;
use mall\basic\Setting;
use mall\library\oss\aliyun\AliyunOssClient;
use think\facade\Request;
use think\facade\Db;
use mall\response\Response;
use think\Image;
use think\facade\Config;
use mall\basic\Attachments;
use think\facade\Filesystem;
use think\exception\ValidateException;

class Uploadfiy extends Auth {

    public function image() {
        $file = Request::file('file');
        $isthumb = Request::param("isthumb","1","intval");
        try {
            if(!in_array($file->extension(),["jpg","png","gif","jpeg","bmp"])){
                throw new \Exception("您所选择的文件不允许上传。",0);
            }

            $dir = "uploads";
            $uploadFile = Filesystem::putFile( 'images', $file);
            $name = basename($uploadFile);
            $filePath = str_replace('\\','/',$dir . '/' . $uploadFile);
            $lastId = Attachments::save($name,$filePath,$file->extension(),$file->getSize());

            //生成缩略图
            if(Config::get("base.is_thumb_image") && $isthumb){
                $thumb_image_list = Config::get("base.thumb_image_list");
                foreach($thumb_image_list as $key=>$val){
                    $image = Image::open($filePath);
                    $image->thumb($val[0], $val[1])->save(str_replace($name, $key . '_' . $name, $filePath));
                }
            }

            return Response::returnArray("ok",0,["src"=>'/'.trim($filePath,"/"),"id"=>$lastId]);
        } catch (ValidateException $e) {
            return Response::returnArray($e->getMessage(),1);
        }

        return Response::returnArray("上传参数错误",1);
    }

    public function video() {
        $file = Request::file('file');
        try {
            if(!in_array($file->extension(),["mp4"])){
                throw new \Exception("您所选择的文件不允许上传。",0);
            }

            $dir = "uploads";
            $uploadFile = Filesystem::putFile( 'video', $file);
            $name = basename($uploadFile);
            $filePath = $dir . '/' . $uploadFile;
            $lastId = Attachments::save($name,$filePath,$file->extension(),$file->getSize());

            return Response::returnArray("ok",0,["src"=>'/'.trim($filePath,"/"),"id"=>$lastId]);
        } catch (ValidateException $e) {
            return Response::returnArray($e->getMessage(),1);
        }

        return Response::returnArray("上传参数错误",1);
    }

    public function file(){
        $file = Request::file('file');
        try {
            if(!in_array($file->extension(),["pem"])){
                throw new \Exception("您所选择的文件不允许上传。",0);
            }

            $dir = "/runtime/certificate/";
            $uploadFile = Filesystem::disk("certificate")->putFile( '', $file);
            $name = basename($uploadFile);
            $lastId = Attachments::save($name,$dir . trim($uploadFile,"/"),$file->extension(),$file->getSize());
            Attachments::handle($lastId,$lastId);
            return Response::returnArray("ok",0,["src"=>$dir . trim($uploadFile,"/"),"id"=>$lastId]);
        } catch (ValidateException $e) {
            return Response::returnArray($e->getMessage(),1);
        }

        return Response::returnArray("上传参数错误",1);
    }

    public function delete(){
        $path = trim(Request::post("path","","strip_tags"));
        $row = Db::name("attachments")->where(["path"=>$path])->find();
        if(Db::name("attachments")->where(["path"=>$path])->delete()){
            Attachments::deleteImage($row);
            return Response::returnArray("ok");
        }
        
        return Response::returnArray("删除失败，请重试。",0);
    }

}
