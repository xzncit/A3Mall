<?php
namespace app\api\controller\wap;

use think\facade\Request;
use think\facade\Db;

class Auth extends Base{

    protected $token = null;
    protected $users = null;

    public function __construct(){
        $this->token = Request::header("Auth-Token");
        if (!empty($this->token)) {
            $userToken = Db::name("users_token")->where(["token"=>$this->token])->find();
            if(!empty($userToken)){
                $this->users = \mall\basic\Users::info($userToken["user_id"]);
            }
        }
    }


}