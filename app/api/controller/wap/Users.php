<?php
namespace app\api\controller\wap;

use mall\basic\Sms;
use mall\utils\Tool;
use think\facade\Db;
use think\facade\Request;
use mall\utils\Check;

class Users extends Base {

    public function login(){
        $username = Request::param("username","","trim,strip_tags");
        $password = Request::param("password","","trim,strip_tags");

        if(empty($username)){
            return $this->returnAjax("请填写手机号码！",0);
        }else if(empty($password)){
            return $this->returnAjax("请填写密码！",0);
        }else if(!Check::mobile($username)){
            return $this->returnAjax("您填写的手机号码不正确！",0);
        }

        $users = Db::name("users")->where([
            "mobile"=>$username
        ])->find();

        if(empty($users)){
            return $this->returnAjax("用户不存在！",0);
        }

        if($users["status"] == 3){
            return $this->returnAjax("您的用户己被管理员禁止登录！",0);
        }

        if($users["password"] != md5($password)){
            return $this->returnAjax("您填写的密码不正确！",0);
        }

        if(in_array($users["status"],[1,2])){
            $status = \mall\basic\Users::statusInfo($users["status"]);
            return $this->returnAjax("您当前用户状态：" . $status . "，如有疑问请联系管理员。",0);
        }

        Db::name("users")->where("id",$users["id"])->update([
            "last_ip"=>Request::ip(),
            "last_login"=>time()
        ]);

        $salt = mt_rand(100,10000);
        $token = sha1($users["id"].$users["username"].$users["password"].$salt.time());
        Db::name("users_token")->insert([
            "user_id"=>$users["id"],
            "token"=>$token,
            "salt"=>$salt,
            "ip"=>Request::ip(),
            "create_time"=>time()
        ]);

        $info = \mall\basic\Users::info($users["id"]);

        return $this->returnAjax("ok",1,[
            "token"=>$token,
            "username"=>$info["username"],
            "nickname"=>$info["nickname"],
            "group_name"=>$info["group_name"],
            "shop_count"=>$info["shop_count"],
            "coupon_count"=>$info["coupon_count"],
            "mobile"=>$info["mobile"],
            "sex"=>$info["sex"],
            "point"=>$info["point"],
            "amount"=>$info["amount"],
            "last_ip"=>$info["last_ip"],
            "last_login"=>$info["last_login"]
        ]);
    }

    public function register(){
        $username = Request::param("username","","trim,strip_tags");
        $password = Request::param("password","","trim,strip_tags");
        $code = Request::param("code","","intval");

        if(empty($username)){
            return $this->returnAjax("请填写手机号码！",0);
        }else if(empty($password)){
            return $this->returnAjax("请填写密码！",0);
        }else if(!Check::mobile($username)){
            return $this->returnAjax("您填写的手机号码不正确！",0);
        }else if(empty($code)){
            return $this->returnAjax("请填写验证码！",0);
        }

        $sms = Db::name("users_sms")->where("mobile",$username)->order("id","DESC")->find();
        if(empty($sms) || time() > ($sms["create_time"] + (60 * 5))){
            return $this->returnAjax("您填写的验证码错误",0);
        }

        if(Db::name("users")->where("mobile",$username)->count()){
            return $this->returnAjax("您填写的手机号码己存在！",0);
        }

        $group_id = Db::name("users_group")->order('minexp','ASC')->value("id");
        Db::name("users")->insert([
            "group_id"=>$group_id,
            "username"=>$username,
            "mobile"=>$username,
            "password"=>md5($password),
            "status"=>0,
            "create_ip"=>Request::ip(),
            "last_ip"=>Request::ip(),
            "create_time"=>time(),
            "last_login"=>time()
        ]);

        $user_id = Db::name("users")->getLastInsID();
        $salt = mt_rand(100,10000);
        $token = sha1($user_id.$username.$password.$salt.time());
        Db::name("users_token")->insert([
            "user_id"=>$user_id,
            "token"=>$token,
            "salt"=>$salt,
            "ip"=>Request::ip(),
            "create_time"=>time()
        ]);

        Db::name("users_sms")->where("mobile",$username)->delete();

        $info = \mall\basic\Users::info($user_id);

        return $this->returnAjax("注册成功！",1,[
            "token"=>$token,
            "username"=>$info["username"],
            "nickname"=>$info["nickname"],
            "group_name"=>$info["group_name"],
            "shop_count"=>$info["shop_count"],
            "coupon_count"=>$info["coupon_count"],
            "mobile"=>$info["mobile"],
            "sex"=>$info["sex"],
            "point"=>$info["point"],
            "amount"=>$info["amount"],
            "last_ip"=>$info["last_ip"],
            "last_login"=>$info["last_login"]
        ]);
    }

    public function forget(){
        $username = Request::param("username","","trim,strip_tags");
        $password = Request::param("password","","trim,strip_tags");
        $code = Request::param("code","","intval");

        if(empty($username)){
            return $this->returnAjax("请填写手机号码！",0);
        }else if(empty($password)){
            return $this->returnAjax("请填写密码！",0);
        }else if(!Check::mobile($username)){
            return $this->returnAjax("您填写的手机号码不正确！",0);
        }else if(empty($code)){
            return $this->returnAjax("请填写验证码！",0);
        }

        $sms = Db::name("users_sms")->where("mobile",$username)->order("id","DESC")->find();
        if(empty($sms) || time() > ($sms["create_time"] + (60 * 5))){
            return $this->returnAjax("您填写的验证码错误",0);
        }

        if(($users = Db::name("users")->where("mobile",$username)->find()) == false){
            return $this->returnAjax("您填写的手机号码不存在！",0);
        }

        Db::name("users")->where("mobile",$username)->update([
            "password"=>md5($password),
            "last_ip"=>Request::ip(),
            "last_login"=>time()
        ]);

        $user_id = $users["id"];
        $salt = mt_rand(100,10000);
        $token = sha1($user_id.$username.$password.$salt.time());
        Db::name("users_token")->insert([
            "user_id"=>$user_id,
            "token"=>$token,
            "salt"=>$salt,
            "ip"=>Request::ip(),
            "create_time"=>time()
        ]);

        Db::name("users_sms")->where("mobile",$username)->delete();

        $info = \mall\basic\Users::info($user_id);

        return $this->returnAjax("修改密码成功！",1,[
            "token"=>$token,
            "username"=>$info["username"],
            "nickname"=>$info["nickname"],
            "group_name"=>$info["group_name"],
            "shop_count"=>$info["shop_count"],
            "coupon_count"=>$info["coupon_count"],
            "mobile"=>$info["mobile"],
            "sex"=>$info["sex"],
            "point"=>$info["point"],
            "amount"=>$info["amount"],
            "last_ip"=>$info["last_ip"],
            "last_login"=>$info["last_login"]
        ]);
    }

    public function send_sms(){
        $username = Request::param("username","","trim,strip_tags");
        $type = Request::param("type","","trim,strip_tags");

        if(!Check::mobile($username)){
            return $this->returnAjax("您填写的手机号码不正确",0);
        }

        if(empty($type)){
            return $this->returnAjax("非法操作",0);
        }else if(!in_array($type,["register","repassword"])){
            return $this->returnAjax("非法操作",0);
        }

        if($type == "repassword" && Db::name("users")->where("mobile",$username)->count() <= 0){
            return $this->returnAjax("您填写的手机号码不存在！",0);
        }

        $sms = Db::name("users_sms")->where("mobile",$username)->order("id","DESC")->find();
        if(!empty($sms) && ($sms["create_time"] + (60 * 5)) > time()){
            return $this->returnAjax("您的验证码己发送，请注意查收");
        }

        try{
            Sms::send($username,$type);
        }catch (\Exception $e){
            //return $this->returnAjax($e->getMessage());
        }

        return $this->returnAjax("发送成功，验证码5分钟内有效");
    }

}