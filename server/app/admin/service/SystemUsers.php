<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service;

use think\facade\Db;
use think\facade\Request;
use think\facade\Session;
use app\common\models\system\Users;
use app\common\models\system\UsersLog;
use app\common\models\system\Manage;

class SystemUsers extends Service {

    /**
     * 管理员登录
     * @param array $data
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function login($data=[]){
        $get_ip = Request::ip();
        if (($user = Users::where(['username' => $data["username"]])->find()) == false) {
            Db::name("system_users_log")->insert([
                "intro" => "状态：用户 [ " . $data["username"] . " ] 用户不存在 时间：" . date("Y-m-d H:i:s") . "",
                "ip" => $get_ip, "time" => time()
            ]);

            throw new \Exception("用户名不存在", 0);
        }

        if(md5($data["password"]) != $user["password"]){
            UsersLog::create([
                "user_id" => $user["id"],
                "intro" => "状态：用户 [ " . $user['username'] . " ] 密码不正确 时间：" . date("Y-m-d H:i:s") . "",
                "ip" => $get_ip, "time" => time()
            ]);
            throw new \Exception("您填写的密码不正确！", 0);
        }

        if ($user['status'] == 1) {
            UsersLog::create(array(
                "user_id" => $user["id"],
                "intro" => "状态：用户 [ " . $user['username'] . " ] 帐号禁止使用 时间：" . date("Y-m-d H:i:s") . "",
                "ip" => $get_ip, "time" => time()
            ));

            throw new \Exception("您的帐号己被禁止使用！", 0);
        }

        Users::where(['id' => $user['id']])->update(['count' => ($user['count'] + 1), 'time' => time(), 'ip' => $get_ip]);

        if (Manage::where(["id" => $user["role_id"], "status" => 0])->count() <= 0) {
            throw new \Exception("您所在的权限组己被管理员禁用！", 0);
        }

        UsersLog::create([
            "user_id" => $user["id"],
            "intro" => "状态：用户 [ " . $user['username'] . " ] 登录成功 时间：" . date("Y-m-d H:i:s") . " 登录地点：" . $get_ip,
            "ip" => $get_ip, "time" => time()
        ]);

        Session::set("system_user_id", $user["id"]);
        return $user;
    }

    /**
     * 管理员退出登录
     * @return string
     */
    public static function logout(){
        if(Session::has("system_user_id")) {
            Session::delete("system_user_id");
            Session::delete("users");
        }

        return createUrl('login/index');
    }

}