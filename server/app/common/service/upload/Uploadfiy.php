<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\service\upload;

use app\common\service\Service;
use think\exception\ValidateException;
use think\facade\Config;
use think\facade\Filesystem;
use think\facade\Request;
use think\Image;

class Uploadfiy extends Service {

    /**
     * 获取上传的文件信息对象
     * @param string $name
     * @return array|\think\file\UploadedFile|null
     */
    public static function get($name=""){
        return Request::file($name);
    }

    public static function upload($name="",$isThumb=false,$disk="public",$suffix=[]){
        try{
            $file = self::get($name);
            if(empty($suffix)){
                $suffix = ["jpg","png","gif","jpeg","bmp","mp4","zip","rar","txt","apk","wgt"];
            }

            if(!in_array($file->extension(),$suffix)){
                throw new \Exception("您所选择的文件不允许上传。",0);
            }

            $dir = self::getUploadPath($disk);
            $uploadFile = Filesystem::disk($disk)->putFile( self::getDirectory($file->extension()), $file);
            $name = basename($uploadFile);
            $filePath = str_replace('\\','/',$dir . '/' . $uploadFile);

            // 如果是证书文件，将不上传到oss存储服务器上
            $ossInfo = ["type"=>0,"path"=>""];

            //生成缩略图
            if(Config::get("base.is_thumb_image") && $isThumb && in_array($file->extension(),["jpg","png","gif","jpeg","bmp"])){
                $thumb_image_list = Config::get("base.thumb_image_list");
                foreach($thumb_image_list as $key=>$val){
                    $image = Image::open($filePath);
                    $tempPath = str_replace($name, $key . '_' . $name, $filePath);
                    $image->thumb($val[0], $val[1])->save($tempPath);
                }
            }

            // 返回文件信息
            return [
                "type"    => $ossInfo["type"],
                "oss_url" => ($ossInfo["type"] == 0 ? "" : $ossInfo["path"]),
                "title"   => $file->getOriginalName(),
                "name"    => $name,
                "path"    => "/".$filePath,
                "suffix"  => strtolower($file->extension()),
                "size"    => $file->getSize(),
                "time"=>time()
            ];
        }catch (ValidateException $ex){
            throw new \Exception($ex->getError(),$ex->getCode());
        }catch (\Exception $ex){
            throw new \Exception($ex->getMessage() .' a '. $ex->getFile() . ' b ' . $ex->getLine(),$ex->getCode());
        }
    }

    /**
     * 获取上传目录
     * @param $disk
     * @return string
     */
    public static function getUploadPath($disk){
        switch($disk){
            case "certificate":
                return "runtime/certificate";
            case "public":
            default:
                return "uploads";
        }
    }

    /**
     * 获取文件分类目录
     * @param $suffix
     * @return string
     */
    public static function getDirectory($suffix){
        if(in_array($suffix,["jpg","png","gif","jpeg","bmp"])){
            return "images";
        }else if(in_array($suffix,["mp4"])){
            return "video";
        }else if(in_array($suffix,["zip","rar","txt","apk","wgt"])){
            return "file";
        }else if(in_array($suffix,["pem"])){
            return "";
        }
    }

}