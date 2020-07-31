<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\wap;

use think\facade\Request;
use think\facade\Db;
use mall\basic\Users;

class Auth extends Base{

    protected $token = null;
    protected $users = null;

    public function __construct(){
        $this->token = Request::header("Auth-Token");
        if (!empty($this->token)) {
            $userToken = Db::name("users_token")->where(["token"=>$this->token])->find();
            if(!empty($userToken)){
                $this->users = Users::info($userToken["user_id"]);
            }
        }
    }


}