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
use think\facade\Request;
use mall\response\Response;
use app\admin\service\common\Uploadfiy as UploadfiyService;
use app\common\models\Attachments as AttachmentsModel;

/**
 * 上传控制器类
 * Class Uploadfiy
 * @package app\admin\controller\common
 */
class Uploadfiy extends Auth {

    /**
     * 上传附件
     * @return \think\response\Json
     */
    public function upload(){
        try{
            $dataInfo = UploadfiyService::upload(
                "file",
                Request::param("isthumb","1","intval"),
                "public",
                ["jpg","png","gif","jpeg","bmp"]
            );

            $id = AttachmentsModel::create(array_merge($dataInfo,[
                "module"=>Request::param("module","","trim,strip_tags"),
                "method"=>Request::param("method","","trim,strip_tags"),
                "cat_id"=>Request::param("cat_id",0,"intval")
            ]))->id;

            return Response::returnArray("ok",0,["src"=>'/'.trim($dataInfo["path"],"/"),"id"=>$id]);
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),1);
        }
    }

    /**
     * 上传证书
     * @return \think\response\Json
     */
    public function file(){
        try{
            $dataInfo = UploadfiyService::upload(
                "file",
                false,
                "certificate",
                ["pem","crt"]
            );

            $id = AttachmentsModel::create(array_merge($dataInfo,[
                "module"=>Request::param("module","","trim,strip_tags"),
                "method"=>Request::param("method","","trim,strip_tags"),
                "cat_id"=>Request::param("cat_id",0,"intval")
            ]))->id;

            return Response::returnArray("ok",0,["src"=>'/'.trim($dataInfo["path"],"/"),"id"=>$id]);
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),1);
        }
    }

    /**
     * 删除附件
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delete(){
        try{
            UploadfiyService::delete(Request::post("path","","strip_tags,trim"));
            return Response::returnArray("删除成功");
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

}
