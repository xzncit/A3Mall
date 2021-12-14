<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\service\wechat;

use app\api\service\Service;
use app\common\library\wechat\Factory;
use app\common\service\wechat\WechatUser as WechatUserService;
use mall\basic\Users;
use mall\library\tools\jwt\Token;
use think\facade\Config;
use think\facade\Db;
use app\common\models\Setting as SettingModel;
use app\common\models\wechat\WechatUsers as WechatUsersModel;
use app\common\service\Spread\Spread;
use app\common\models\users\Users as UsersModel;

class OAuth extends Service {

    /**
     * 登录
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public static function login($params=[]){
        Db::startTrans();
        $result = [];
        switch ($params["source"]){
            case 1: // H5
                $result = self::mp($params);
                break;
            case 2: // mini
                break;
            default:
                Db::rollback();
                throw new \Exception("非法操作",0);
        }

        Db::commit();
        return $result;
    }

    /**
     * 公众号登录
     * @param $params
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    protected static function mp($params){
        $appid = SettingModel::getArrayData("wechat.appid");
        $state = $params["state"] ?? "";
        if($state != $appid){
            $domain = getDomain() . (in_array(Config::get("base.app_type"),["pc","page"]) ? "/wap" : "");
            return [ "url"=>$domain, "appid"=>$appid, "state"=>$appid ];
        }

        $wxToken = Factory::wechat()->oauth->getOauthAccessToken($params["code"]);
        if(!isset($wxToken['openid'])) {
            throw new \Exception("获取授权信息失败，openid为空",0);
        }

        $user = Factory::wechat()->oauth->getUserInfo($wxToken['access_token'],$wxToken['openid']);
        $condition = [];
        if(isset($user["unionid"])){
            $condition["unionid"] = $user["unionid"];
        }else{
            $condition["openid"] = $user['openid'];
        }

        // 如果用户不存在，注册新用户
        if(!$row=WechatUsersModel::where($condition)->find()){
            $user_id = WechatUserService::register($user);
        }

        if(!empty($row)) $user_id = $row["user_id"];

        $info = Users::info($user_id);
        $token = Token::get("id",$info["id"]);

        return [
            "id"            => $info["id"],
            "token"         => $token,
            "username"      => $info["username"],
            "nickname"      => $info["nickname"],
            "group_name"    => $info["group_name"],
            "shop_count"    => $info["shop_count"],
            "coupon_count"  => $info["coupon_count"],
            "mobile"        => $info["mobile"],
            "sex"           => $info["sex"],
            "point"         => $info["point"],
            "amount"        => $info["amount"],
            "last_ip"       => $info["last_ip"],
            "last_login"    => $info["last_login"]
        ];
    }

}