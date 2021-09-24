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
use mall\utils\Tool;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;
use app\common\model\wechat\Qrcode as QrcodeModel;
use mall\response\Response;

class Qrcode extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");

            $productsBrand = new QrcodeModel();
            $list = $productsBrand->getList([],$limit);

            if(empty($list['data'])){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            foreach ($list['data'] as $k=>$v){
                $user = Db::name("users")->where("id",$v['user_id'])->find();
                $list['data'][$k]['image'] = Tool::thumb($v['path']);
                $list['data'][$k]['username'] = $user["username"];
                $list['data'][$k]['nickname'] = $user["nickname"];
            }

            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    public function delete(){
        $id = (int)Request::get("id");
        try {
            $row = Db::name("wechat_qrcode")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("wechat_qrcode")->delete($id);
            Tool::deleteFile(Tool::getRootPath() . 'public' . $row['path']);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。",0);
        }

        return Response::returnArray("ok");
    }

    public function remove(){
        try {
            Db::name("wechat_qrcode")->where("1=1")->delete();
            Tool::deleteFile(Tool::getRootPath() . 'public/uploads/qrcode');
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。",0);
        }

        return Response::returnArray("ok");
    }

}