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
use mall\library\wechat\chat\WeChat;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;
use app\common\model\wechat\Users;

class Fans extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $key = Request::get("key/a","","trim,strip_tags");

            $condition = [];

            if(!empty($key["title"])){
                $condition[] = ["nickname","like",'%'.$key["title"].'%'];
            }

            $wechatUusers = new Users();
            $list = $wechatUusers->getList($condition,$limit);

            if(empty($list["data"])){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    public function sync_fans(){
        try {
            \mall\library\wechat\chat\classes\Fans::syncFans();
        }catch (\Exception $e){
            return Response::returnArray($e->getMessage(),0);
        }

        return Response::returnArray("同步成功！");
    }

    public function sync_black(){
        try {
            Fans::syncBlack();
        }catch (\Exception $e){
            return Response::returnArray($e->getMessage(),0);
        }

        return Response::returnArray("同步成功！");
    }

    public function sync_tags(){
        try {
            Fans::syncTags();
        }catch (\Exception $e){
            return Response::returnArray($e->getMessage(),0);
        }

        return Response::returnArray("同步成功！");
    }

    public function add_black(){
        try {
            $openid = Request::param("openid");
            foreach (array_chunk(explode(',', $openid), 20) as $openids) {
                WeChat::User()->batchBlackList($openids);
                Db::name('wechat_users')->whereIn('openid', $openids)->update(['is_black' => '1']);
            }

            return Response::returnArray('拉入黑名单成功！');
        } catch (\Exception $e) {
            return Response::returnArray($e->getMessage(),0);
        }
    }

    public function remove_black(){
        try {
            $openid = Request::param("openid");
            foreach (array_chunk(explode(',', $openid), 20) as $openids) {
                WeChat::User()->batchUnblackList($openids);
                Users::whereIn('openid', $openids)->save(['is_black' => '0']);
            }

            return Response::returnArray('移出黑名单成功！');
        } catch (\Exception $e) {
            return Response::returnArray($e->getMessage(),0);
        }
    }

    public function delete(){
        $id = (int)Request::get("id");

        try{
            $users = new Users();
            $users->del($id);
        }catch (\Exception $ex){
            return Response::returnArray("操作失败，请稍候在试。" . $ex->getMessage(),0);
        }

        return Response::returnArray("ok");
    }

}