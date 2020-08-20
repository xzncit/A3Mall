<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\users;

use app\admin\controller\Auth;
use app\common\model\users\Log as UsersLog;
use app\common\model\users\WithdrawLog as UsersWithdrawLog;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Finance extends Auth {

    private $type = ["1"=>"银行卡","2"=>"支付宝","3"=>"微信"];
    private $status = ["0"=>"审核中","1"=>"已提现","2"=>"未通过"];

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $condition = ["log.action"=>4];

            $usersLog = new UsersLog();
            $list = $usersLog->getList($condition,$limit);

            if(empty($list['data'])){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    public function fund(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $condition = ["log.action"=>0];

            $usersLog = new UsersLog();
            $list = $usersLog->getList($condition,$limit);

            if(empty($list['data'])){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    public function point(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $condition = ["log.action"=>1];

            $usersLog = new UsersLog();
            $list = $usersLog->getList($condition,$limit);

            if(empty($list['data'])){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    public function exp(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $condition = ["log.action"=>2];

            $usersLog = new UsersLog();
            $list = $usersLog->getList($condition,$limit);

            if(empty($list['data'])){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    public function apply(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $condition = [];

            $usersWithdrawLog = new UsersWithdrawLog();
            $list = $usersWithdrawLog->get_list($condition,$limit);

            if(empty($list['data'])){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            foreach($list['data'] as $key=>$item){
                $list['data'][$key]["type_name"] = $this->type[$item["type"]];
                $list['data'][$key]["status_name"] = $this->status[$item["status"]];
                // 提现方式
                $str = '';
                if($item["type"] == 1){
                    $str .= "<p>卡号：" . $item["code"] . '</p>';
                    $str .= "<p>开户地址：" . $item["address"] . '</p>';
                    $str .= "<p>银行：" . $item["bank_name"] . '</p>';
                }else if($item["type"] == 2){
                    $str .= "<p>用户名：" . $item["username"] . '</p>';
                    $str .= "<p>支付宝：" . $item["account"] . '</p>';
                }else if($item["type"] == 3){
                    $str .= "<p>用户名：" . $item["username"] . '</p>';
                    $str .= "<p>微信：" . $item["account"] . '</p>';
                }

                $list['data'][$key]['string'] = $str;
            }

            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    public function handle(){
        $id = Request::param("id");
        if(($row = Db::name("users_withdraw_log")->where(["id"=>$id])->find()) == false){
            if(Request::isAjax()) {
                return Response::returnArray("您要查找的内容不存在！", 0);
            }else{
                $this->error("您要查找的内容不存在！");
            }

        }
        $str = '&nbsp;&nbsp;';
        if($row["type"] == 1){
            $str .= "<span>卡号：" . $row["code"] . '</span>&nbsp;&nbsp;';
            $str .= "<span>开户地址：" . $row["address"] . '</span>&nbsp;&nbsp;';
            $str .= "<span>银行：" . $row["bank_name"] . '</span>&nbsp;&nbsp;';
        }else if($row["type"] == 2){
            $str .= "<span>用户名：" . $row["username"] . '</span>&nbsp;&nbsp;';
            $str .= "<span>支付宝：" . $row["account"] . '</span>&nbsp;&nbsp;';
        }else if($row["type"] == 3){
            $str .= "<span>用户名：" . $row["username"] . '</span>&nbsp;&nbsp;';
            $str .= "<span>微信：" . $row["account"] . '</span>&nbsp;&nbsp;';
        }
        $row["string"] = $str;
        if(($user = Db::name("users")->where(["id"=>$row["user_id"]])->find()) == false){
            if(Request::isAjax()) {
                return Response::returnArray("您操作的会员不存在！", 0);
            }else{
                $this->error("您操作的会员不存在！");
            }
        }
        if(Request::isAjax()){
            $data = Request::post();

            $u = Db::name("users")->where(["id"=>$row["user_id"]])->find();
            if($u["amount"] <= $row["price"]){
                return Response::returnArray("操作失败，余额不足！",0);
            }

            Db::startTrans();
            try {
                Db::name("users_withdraw_log")->where(["id"=>$id])->update([
                    "msg"=>$data["msg"],
                    "status"=>$data["status"],
                    "update_time"=>time()
                ]);

                if($data["status"] == 1){
                    Db::name("users")
                        ->where(["id"=>$row["user_id"]])
                        ->dec("amount",$row["price"])->update();
                }

                Db::commit();
            }catch (\Exception $e){
                Db::rollback();
                return Response::returnArray("操作失败，请稍后在试！",0);
            }

            return Response::returnArray("操作成功！");
        }

        return View::fetch("",[
            "id"=>$id,"user"=>$user,"row"=>$row
        ]);
    }

}