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
use think\facade\Filesystem;
use think\facade\Request;
use think\facade\View;
use app\admin\service\platform\Version as VersionService;

class Version extends Auth {

    /**
     * 列表
     * @return string|\think\response\Json
     */
    public function index(){
        if(Request::isAjax()){
            $list = VersionService::getList(Request::param(),["type"=>Request::param("type",1,"intval")]);
            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    /**
     * 编辑
     * @return string|\think\response\Json
     */
    public function editor(){
        try{
            if(Request::isAjax()){
                VersionService::save(Request::param());
                return Response::returnArray("操作成功！");
            }

            return View::fetch("",VersionService::detail(Request::param()));
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

    /**
     * 删除
     * @return \think\response\Json
     */
    public function delete(){
        try{
            VersionService::delete(Request::param("id","0","intval"));
            return Response::returnArray("删除成功");
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

    /**
     * 上传
     * @return \think\response\Json
     * @throws \Exception
     */
    public function file(){
        $file = Request::file('file');
        try {
            if(!in_array($file->extension(),["apk","wgt","jpg","png","gif","jpeg"])){
                throw new \Exception("您所选择的文件不允许上传。",0);
            }

            $dir = "/uploads/";
            $uploadFile = Filesystem::disk("public")->putFile( '', $file);
            $name = basename($uploadFile);
            $lastId = Attachments::save($name,$dir . trim($uploadFile,"/"),$file->extension(),$file->getSize());
            return Response::returnArray("ok",0,["src"=>$dir . trim($uploadFile,"/"),"id"=>$lastId]);
        } catch (ValidateException $e) {
            return Response::returnArray($e->getMessage(),1);
        }

        return Response::returnArray("上传参数错误",1);
    }

}