<?php
namespace app\admin\controller\wechat;

use app\admin\controller\Auth;
use mall\basic\Users;
use mall\library\wechat\chat\WeChat;
use mall\response\Response;
use mall\utils\Date;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Fans extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $key = Request::get("key/a","","trim,strip_tags");

            $condition = [];

            if(!empty($key["title"])){
                $condition[] = ["nickname","like",'%'.$key["title"].'%'];
            }

            $count = Db::name("wechat_users")
                ->where($condition)->count();

            $data = Db::name("wechat_users")
                ->where($condition)->order('id desc')->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            $list = $data->items();
            foreach($list as $key=>$item){
                $list[$key] = $item;
                $list[$key]['subscribe_time'] = Date::format($item["subscribe_time"]);
                $list[$key]['remove'] = createUrl("remove",["id"=>$item["id"]]);
                $list[$key]['url'] = createUrl("editor",["id"=>$item["id"]]);
                $list[$key]['photo'] = $item["headimgurl"];
                $tags = Db::name('wechat_users_tags')->column('name', 'id');

                $item['tags'] = [];
                foreach (explode(',', $item['tagid_list']) as $tagid) {
                    if (isset($tags[$tagid])) $item['tags'][] = $tags[$tagid];
                }

                $list[$key]['tags'] = implode(",",$item['tags']);
                $list[$key]['area'] = implode(",",[
                    $item["country"],$item["province"],$item["city"]
                ]);
            }

            return Response::returnArray("ok",0,$list,$count);
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
                Db::name('wechat_users')->whereIn('openid', $openids)->update(['is_black' => '0']);
            }

            return Response::returnArray('移出黑名单成功！');
        } catch (\Exception $e) {
            return Response::returnArray($e->getMessage(),0);
        }
    }

    public function delete(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        try {
            $row = Db::name("wechat_users")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("wechat_users")->delete($id);
            Users::delete($row["user_id"]);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。" . $ex->getMessage(),0);
        }

        return Response::returnArray("ok");
    }

}