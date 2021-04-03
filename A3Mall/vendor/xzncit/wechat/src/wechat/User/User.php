<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat\User;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class User extends App {

    /**
     * 公众号可通过本接口来获取帐号的关注者列表，关注者列表由一串OpenID（加密后的微信号，每个用户对每个公众号的OpenID是唯一的）组成。
     * 一次拉取调用最多拉取10000个关注者的OpenID，可以通过多次拉取的方式来满足需求。
     * @param string $next_openid   第一个拉取的OPENID，不填默认从头开始拉取
     * @return array
     * @throws \Exception
     */
    public function getUserList(string $next_openid){
        return HttpClient::create()->get("cgi-bin/user/get?access_token=ACCESS_TOKEN",[
            "next_openid"=>$next_openid
        ])->toArray();
    }

    /**
     * 获取公众号的黑名单列表
     * 公众号可通过该接口来获取帐号的黑名单列表，黑名单列表由一串 OpenID（加密后的微信号，每个用户对每个公众号的OpenID是唯一的）组成。
     * 该接口每次调用最多可拉取 10000 个OpenID，当列表数较多时，可以通过多次拉取的方式来满足需求。
     * @param string $openid 当 openid 为空时，默认从开头拉取。
     * @return array
     * @throws \Exception
     */
    public function getBlackList($openid=""){
        $params = [];
        if(!empty($openid)){
            $params["begin_openid"] = $openid;
        }

        return HttpClient::create()->postJson("cgi-bin/tags/members/getblacklist?access_token=ACCESS_TOKEN",$params)->toArray();
    }

    /**
     * 拉黑用户
     * 公众号可通过该接口来拉黑一批用户，黑名单列表由一串 OpenID （加密后的微信号，每个用户对每个公众号的OpenID是唯一的）组成。
     * @param array $openid_list    需要拉入黑名单的用户的openid，一次拉黑最多允许20个
     * @return array
     * @throws \Exception
     */
    public function batchBlackList(array $openid_list){
        return HttpClient::create()->postJson("cgi-bin/tags/members/batchblacklist?access_token=ACCESS_TOKEN",[
            "openid_list"=>$openid_list
        ])->toArray();
    }

    /**
     * 取消拉黑用户
     * 公众号可通过该接口来取消拉黑一批用户，黑名单列表由一串OpenID（加密后的微信号，每个用户对每个公众号的OpenID是唯一的）组成。
     * @param array $openid_list    需要拉入黑名单的用户的openid，一次拉黑最多允许20个
     * @return array
     * @throws \Exception
     */
    public function batchUnBlackList(array $openid_list){
        return HttpClient::create()->postJson("cgi-bin/tags/members/batchunblacklist?access_token=ACCESS_TOKEN",[
            "openid_list"=>$openid_list
        ])->toArray();
    }

    /**
     * 开发者可以通过该接口对指定用户设置备注名，该接口暂时开放给微信认证的服务号。
     * @param string  $openid
     * @param string  $remark
     * @return array
     * @throws \Exception
     */
    public function updateRemark($openid,$remark){
        return HttpClient::create()->postJson("cgi-bin/user/info/updateremark?access_token=ACCESS_TOKEN",[
            "openid"=>$openid,"remark"=>$remark
        ])->toArray();
    }

    /**
     * 获取用户基本信息（包括UnionID机制）
     * 开发者可通过OpenID来获取用户基本信息。
     * @param string $openid
     * @param string $lang
     * @return array
     * @throws \Exception
     */
    public function getUserInfo($openid,$lang="zh_CN"){
        return HttpClient::create()->get("cgi-bin/user/info?access_token=ACCESS_TOKEN",[
            "openid"=>$openid,"lang"=>$lang
        ])->toArray();
    }

    /**
     * 批量获取用户基本信息
     * 开发者可通过该接口来批量获取用户基本信息。最多支持一次拉取100条。
     * @param array $user_list
     * @return array
     * @throws \Exception
     */
    public function batchUserInfo(array $user_list){
        return HttpClient::create()->postJson("cgi-bin/user/info/batchget?access_token=ACCESS_TOKEN",[
            "user_list"=>$user_list
        ])->toArray();
    }

}