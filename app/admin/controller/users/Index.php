<?php
namespace app\admin\controller\users;

use app\admin\controller\Auth;
use mall\utils\Date;
use mall\utils\Tool;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;
use think\facade\View;

class Index extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $key = Request::get("key/a","","trim,strip_tags");

            $condition = [];
            if(isset($key["cat_id"]) && $key["cat_id"] != '-1'){
                $condition["u.group_id"] = $key["cat_id"];
            }

            if(!empty($key["title"])){
                $condition[] = ["u.username","like",'%'.$key["title"].'%'];
            }

            $count = Db::name("users")
                ->alias("u")
                ->join("users_group g","u.group_id=g.id","LEFT")
                ->where($condition)->count();

            $data = Db::name("users")
                ->field("u.*,g.name as group_name")
                ->alias("u")
                ->join("users_group g","u.group_id=g.id","LEFT")
                ->where($condition)->order('u.id desc')->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            $list = $data->items();

            foreach($list as $key=>$item){
                $list[$key]['time'] = Date::format($item["create_time"]);
                $list[$key]['url'] = createUrl("editor",["id"=>$item["id"]]);
                $list[$key]['log_url'] = createUrl("log",["id"=>$item["id"]]);
                $list[$key]['finance_url'] = createUrl("finance",["id"=>$item["id"]]);
            }

            return Response::returnArray("ok",0,$list,$count);
        }

        return View::fetch("",[
            "cat"=>Db::name("users_group")->select()->toArray()
        ]);
    }

    public function editor(){
        if(!Request::isAjax()){
            $id = (int)Request::param("id");
            $rs = empty($id) ? [] : Db::name("users")->where("id",$id)->find();

            return View::fetch("",[
                "cat"=>Db::name("users_group")->select()->toArray(),
                "data"=>$rs
            ]);
        }

        $data = Request::post();

        if(!empty($data["id"])){
            if(!empty($data["password"]) || !empty($data["confirm_password"])){
                if($data["password"] != $data["confirm_password"]){
                    return Response::returnArray("您输入的两次密码不致。",0);
                }

                $data["password"] = md5($data["password"]);
            }

            try {
                Db::name("users")->strict(false)->where("id",$data['id'])->update($data);
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }
        }else{
            if(empty($data["password"])){
                return Response::returnArray("请填写密码",0);
            }else if(empty($data["confirm_password"])){
                return Response::returnArray("请填写确认密码",0);
            }else if($data["password"] != $data["confirm_password"]){
                return Response::returnArray("您输入的两次密码不致。",0);
            }

            $data["password"] = md5($data["password"]);
            $data["create_time"] = time();
            if(!Db::name("users")->strict(false)->insert($data)){
                return Response::returnArray("操作失败，请重试。",0);
            }

        }

        return Response::returnArray("操作成功！");
    }

    public function delete(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        try {
            $row = Db::name("users")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("users")->delete($id);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。",0);
        }

        return Response::returnArray("ok");
    }

    public function finance(){
        $id = Request::param("id");
        if(($user = Db::name("users")->where(["id"=>$id])->find()) == false){
            if(Request::isAjax()) {
                return Response::returnArray("您操作的会员不存在！", 0);
            }else{
                $this->error("您操作的会员不存在！");
            }
        }

        if(Request::isAjax()){
            $action = Request::param("action");
            $operation = Request::param("operation");
            $num = Request::param("num");

            if($operation == 1 && ($action == 0 && $user["amount"] < $num)){
                return Response::returnArray("提现失败，用户余额不足",0);
            }

            if($operation == 1 && ($action == 1 && $user["point"] < $num)){
                return Response::returnArray("提现失败，用户积分不足",0);
            }

            if($operation == 1 && ($action == 2 && $user["exp"] < $num)){
                return Response::returnArray("提现失败，用户经验不足",0);
            }

            $field = "";
            $description = "管理员对您的";
            switch($action){
                case 0:
                    $field = "amount";
                    $description .= "【金额】";
                    break;
                case 1:
                    $field = "point";
                    $description .= "【积分】";
                    break;
                case 2:
                    $field = "exp";
                    $description .= "【经验】";
                    break;
            }

            if($operation == 1){
                $total = $user[$field] - $num;
                $description .= "执行了提现操作,";
            }else{
                $total = $user[$field] + $num;
                $description .= "执行了充值操作,";
            }

            $description .= "操作数值【".$num."】";
            $arr = [];
            $arr[$field] = $total;
            Db::name("users")->where(["id"=>$id])->update($arr);
            Db::name("users_log")->insert([
                "user_id"=>$id,
                "admin_id"=>Session::get("system_user_id"),
                "action"=>$action,
                "operation"=>$operation,
                "description"=>$description,
                $field=>$num,
                "create_time"=>time()
            ]);

            return Response::returnArray("操作成功！");
        }

        return View::fetch("",[
            "id"=>$id,"user"=>$user
        ]);
    }

    public function log(){
        $id = Request::param("id");
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $condition = ["l.user_id"=>$id];
            $count = Db::name("users_log")
                ->alias('l')
                ->join("users u","u.id=l.user_id","LEFT")
                ->where($condition)->count();
            $data = Db::name("users_log")
                ->field("l.*,u.username")
                ->alias('l')
                ->join("users u","u.id=l.user_id","LEFT")
                ->where($condition)->order("l.id DESC")->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            $list = $data->items();

            foreach($list as $key=>$item){
                $list[$key]['create_time'] = Date::format($item["create_time"]);
            }

            return Response::returnArray("ok",0,$list,$count);
        }

        return View::fetch("",["id"=>$id]);
    }

}