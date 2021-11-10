<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use mall\response\Response;
use think\captcha\facade\Captcha;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;
use think\facade\Session;

class Login extends Base {

    public function index(){
        if (Session::has("system_user_id")) {
            return redirect(createUrl('platform.index/index'));
        } else{
            return View::fetch();
        }
    }

    public function post(){
        if(Request::isAjax()){
            $username = Request::param("username","","trim,htmlspecialchars");
            $password = Request::param("password","","trim,htmlspecialchars");
            $code = Request::param("code","","trim,htmlspecialchars");

            if (empty($code)) {
                return Response::returnArray("请填写验证码", 0);
            }

            if(!captcha_check($code)){
                return Response::returnArray("您填写的验证码不正确", 0);
            }

            if (empty($username)) {
                return Response::returnArray("请填写用户名", 0);
            }

            if (empty($password)) {
                return Response::returnArray("请填写密码", 0);
            }

            $get_ip = Request::ip();

            if (($user = Db::name("system_users")->where(['username' => $username])->find()) == false) {
                Db::name("system_users_log")->insert([
                    "intro" => "状态：用户 [ " . $username . " ] 用户不存在 时间：" . date("Y-m-d H:i:s") . "",
                    "ip" => $get_ip, "time" => time()
                ]);

                return Response::returnArray("用户名不存在", 0);
            }

            if(md5($password) != $user["password"]){
                Db::name("system_users_log")->insert([
                    "user_id" => $user["id"],
                    "intro" => "状态：用户 [ " . $user['username'] . " ] 密码不正确 时间：" . date("Y-m-d H:i:s") . "",
                    "ip" => $get_ip, "time" => time()
                ]);

                return Response::returnArray("您填写的密码不正确！", 0);
            }

            if ($user['status'] == 1) {
                Db::name("system_users_log")->insert(array(
                    "user_id" => $user["id"],
                    "intro" => "状态：用户 [ " . $user['username'] . " ] 帐号禁止使用 时间：" . date("Y-m-d H:i:s") . "",
                    "ip" => $get_ip, "time" => time()
                ));

                return Response::returnArray("您的帐号己被禁止使用！", 0);
            }

            Db::name("system_users")->where(['id' => $user['id']])->update([
                'count' => ($user['count'] + 1), 'time' => time(), 'ip' => $get_ip
            ]);

            if (Db::name("system_manage")->where(["id" => $user["role_id"], "status" => 0])->count() <= 0) {
                return Response::returnArray("您所在的权限组己被管理员禁用！", 0);
            }

            Db::name("system_users_log")->insert([
                "user_id" => $user["id"],
                "intro" => "状态：用户 [ " . $user['username'] . " ] 登录成功 时间：" . date("Y-m-d H:i:s") . " 登录地点：" . $get_ip,
                "ip" => $get_ip, "time" => time()
            ]);

            Session::set("system_user_id", $user["id"]);
            return Response::returnArray("登录成功，正在为您跳转中…", 1, createUrl("platform.index/index"));
        }else{
            $this->error("该页面不允许直接访问!");
        }
    }

    public function verify(){
        return Captcha::create();
    }

    public function logout() {
        if (Session::has("system_user_id")) {
            Session::delete("system_user_id");
            Session::delete("users");
        }
            
        return redirect(createUrl('login/index'));
    }
}
