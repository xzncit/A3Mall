<?php
namespace mall\library\wechat\chat\module;

use mall\library\wechat\chat\BasicWeChat;

class User extends BasicWeChat{

    /**
     * 设置用户备注名
     * @param string $openid
     * @param string $remark
     * @return array
     */
    public function updateMark($openid, $remark){
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info/updateremark?access_token=ACCESS_TOKEN';
        return $this->httpPost($url, ['openid' => $openid, 'remark' => $remark]);
    }

    /**
     * 获取用户基本信息（包括UnionID机制）
     * @param string $openid
     * @param string $lang
     * @return array
     */
    public function getUserInfo($openid, $lang = 'zh_CN'){
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=ACCESS_TOKEN&openid={$openid}&lang={$lang}";
        return $this->httpGet($url);
    }

    /**
     * 批量获取用户基本信息
     * @param array $openids
     * @param string $lang
     * @return array
     */
    public function getBatchUserInfo(array $openids, $lang = 'zh_CN'){
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=ACCESS_TOKEN';
        $data = ['user_list' => []];
        foreach ($openids as $openid) {
            $data['user_list'][] = ['openid' => $openid, 'lang' => $lang];
        }

        return $this->httpPost($url, $data);
    }

    /**
     * 获取用户列表
     * @param string $next_openid
     * @return array
     */
    public function getUserList($next_openid = ''){
        $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=ACCESS_TOKEN&next_openid={$next_openid}";
        return $this->httpGet($url);
    }

    /**
     * 获取标签下粉丝列表
     * @param integer $tagid 标签ID
     * @param string $next_openid 第一个拉取的OPENID
     * @return array
     */
    public function getUserListByTag($tagid, $next_openid = ''){
        $url = 'https://api.weixin.qq.com/cgi-bin/user/tag/get?access_token=ACCESS_TOKEN';
        return $this->httpPost($url, ['tagid' => $tagid, 'next_openid' => $next_openid]);
    }

    /**
     * 获取公众号的黑名单列表
     * @param string $begin_openid
     * @return array
     */
    public function getBlackList($begin_openid = ''){
        $url = "https://api.weixin.qq.com/cgi-bin/tags/members/getblacklist?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, ['begin_openid' => $begin_openid]);
    }

    /**
     * 批量拉黑用户
     * @param array $openids
     * @return array
     */
    public function batchBlackList(array $openids){
        $url = "https://api.weixin.qq.com/cgi-bin/tags/members/batchblacklist?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, ['openid_list' => $openids]);
    }

    /**
     * 批量取消拉黑用户
     * @param array $openids
     * @return array
     */
    public function batchUnblackList(array $openids){
        $url = "https://api.weixin.qq.com/cgi-bin/tags/members/batchunblacklist?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, ['openid_list' => $openids]);
    }

}