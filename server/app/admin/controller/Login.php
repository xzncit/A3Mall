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
use think\facade\Request;
use think\facade\View;
use think\facade\Session;
use app\admin\service\SystemUsers as SystemUsersService;
use app\admin\validate\SystemUsers as SystemUsersValidate;
use think\exception\ValidateException;
use app\common\models\Setting as SettingModel;

class Login extends Base {

    /**
     * 登录页
     * @return string|\think\response\Redirect
     */
    public function index(){
        if (Session::has("system_user_id")) {
            return redirect(createUrl('platform.index/index'));
        } else{
            $copy = SettingModel::getArrayData("copyright");
            return View::fetch("",["data"=>$copy["copyright"]??[]]);
        }
    }

    /**
     * 登录
     * @return \think\response\Json
     */
    public function post(){
        try{
            $post = Request::param();
            validate(SystemUsersValidate::class)->scene('login')->check($post);
            SystemUsersService::login($post);
            return Response::returnArray("登录成功，正在为您跳转中…", 1, createUrl("platform.index/index"));
        }catch (ValidateException $e){
            return Response::returnArray($e->getError(), 0);
        } catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(), 0);
        }
    }

    /**
     * 验证码
     * @return \think\Response
     */
    public function verify(){
        return Captcha::create();
    }

    /**
     * 退出登录
     * @return \think\response\Redirect
     */
    public function logout() {
        return redirect(SystemUsersService::logout());
    }
}
