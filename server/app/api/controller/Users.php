<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller;

use app\api\validate\Login;
use app\api\validate\Register;
use app\api\validate\Sms;
use think\exception\ValidateException;
use think\facade\Request;
use app\api\service\Users as UsersService;

class Users extends Base {

    /**
     * 会员登录
     * @return \think\response\Json
     */
    public function login(){
        try{
            $post = Request::param();
            validate(Login::class)->scene('login')->check($post);
            return $this->returnAjax("登录成功，正在为您跳转中…", 1,UsersService::login($post));
        }catch (ValidateException $e){
            return $this->returnAjax($e->getError(), 0);
        } catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(), 0);
        }
    }

    /**
     * 注册
     * @return \think\response\Json
     */
    public function register(){
        try{
            $post = Request::param();
            validate(Register::class)->scene('register')->check($post);
            return $this->returnAjax("注册成功，正在为您跳转中…", 1,UsersService::register($post));
        }catch (ValidateException $e){
            return $this->returnAjax($e->getError(), 0);
        } catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(), 0);
        }
    }

    /**
     * 修改密码
     * @return \think\response\Json
     */
    public function forget(){
        try{
            $post = Request::param();
            validate(Register::class)->scene('forget')->check($post);
            return $this->returnAjax("修改密码成功!", 1,UsersService::forget($post));
        }catch (ValidateException $e){
            return $this->returnAjax($e->getError(), 0);
        } catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(), 0);
        }
    }

    /**
     * 自动登录
     * @return \think\response\Json
     */
    public function autologin(){
        try{
            return $this->returnAjax("登录成功！",1000,UsersService::autologin());
        }catch(\Exception $ex){
            return json(["info"=>$ex->getMessage(),"status"=>"-1002"]);
        }
    }

    /**
     * 发送短信
     * @return \think\response\Json
     */
    public function send_sms(){
        try{
            $post = Request::param();
            validate(Sms::class)->scene('sms')->check($post);
            return $this->returnAjax(UsersService::sendSMS($post), 1);
        }catch (ValidateException $e){
            return $this->returnAjax($e->getError(), 0);
        } catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode());
        }
    }

}