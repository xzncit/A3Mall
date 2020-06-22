<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\library\wechat\chat\module;

use mall\library\wechat\chat\BasicWeChat;

class Custom extends BasicWeChat{

    /**
     * 添加客服帐号
     * @param string $kf_account 客服账号
     * @param string $nickname 客服昵称
     * @return array
     */
    public function addAccount($kf_account, $nickname){
        $data = ['kf_account' => $kf_account, 'nickname' => $nickname];
        $url = "https://api.weixin.qq.com/customservice/kfaccount/add?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, $data);
    }

    /**
     * 修改客服帐号
     * @param string $kf_account 客服账号
     * @param string $nickname 客服昵称
     * @return array
     */
    public function updateAccount($kf_account, $nickname){
        $data = ['kf_account' => $kf_account, 'nickname' => $nickname];
        $url = "https://api.weixin.qq.com/customservice/kfaccount/update?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, $data);
    }

    /**
     * 删除客服帐号
     * @param string $kf_account 客服账号
     * @return array
     */
    public function deleteAccount($kf_account){
        $data = ['kf_account' => $kf_account];
        $url = "https://api.weixin.qq.com/customservice/kfaccount/del?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, $data);
    }

    /**
     * 邀请绑定客服帐号
     * @param string $kf_account 完整客服帐号，格式为：帐号前缀@公众号微信号
     * @param string $invite_wx 接收绑定邀请的客服微信号
     * @return array
     */
    public function inviteWorker($kf_account, $invite_wx){
        $url = 'https://api.weixin.qq.com/customservice/kfaccount/inviteworker?access_token=ACCESS_TOKEN';
        return $this->httpPost($url, ['kf_account' => $kf_account, 'invite_wx' => $invite_wx]);
    }

    /**
     * 获取所有客服账号
     * @return array
     */
    public function getAccountList(){
        $url = "https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token=ACCESS_TOKEN";
        return $this->httpGet($url);
    }

    /**
     * 设置客服帐号的头像
     * @param string $kf_account 客户账号
     * @param string $image 头像文件位置
     * @return array
     */
    public function uploadHeadimg($kf_account, $image){
        $url = "http://api.weixin.qq.com/customservice/kfaccount/uploadheadimg?access_token=ACCESS_TOKEN&kf_account={$kf_account}";
        return $this->httpPost($url, ['media' => $this->createCurlFile($image)]);
    }

    /**
     * 客服接口-发消息
     * @param array $data
     * @return array
     */
    public function send(array $data){
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, $data);
    }

    /**
     * 客服输入状态
     * @param string $openid 普通用户（openid）
     * @param string $command Typing:正在输入,CancelTyping:取消正在输入
     * @return array
     */
    public function typing($openid, $command = 'Typing'){
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/typing?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, ['touser' => $openid, 'command' => $command]);
    }

    /**
     * 根据标签进行群发【订阅号与服务号认证后均可用】
     * @param array $data
     * @return array
     */
    public function massSendAll(array $data){
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, $data);
    }

    /**
     * 根据OpenID列表群发【订阅号不可用，服务号认证后可用】
     * @param array $data
     * @return array
     */
    public function massSend(array $data){
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, $data);
    }

    /**
     * 删除群发【订阅号与服务号认证后均可用】
     * @param integer $msg_id 发送出去的消息ID
     * @param null|integer $article_idx 要删除的文章在图文消息中的位置，第一篇编号为1，该字段不填或填0会删除全部文章
     * @return array
     */
    public function massDelete($msg_id, $article_idx = null){
        $data = ['msg_id' => $msg_id];
        is_null($article_idx) || $data['article_idx'] = $article_idx;
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/delete?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, $data);
    }

    /**
     * 预览接口【订阅号与服务号认证后均可用】
     * @param array $data
     * @return array
     */
    public function massPreview(array $data){
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, $data);
    }

    /**
     * 查询群发消息发送状态【订阅号与服务号认证后均可用】
     * @param integer $msg_id 群发消息后返回的消息id
     * @return array
     */
    public function massGet($msg_id){
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/get?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, ['msg_id' => $msg_id]);
    }

    /**
     * 获取群发速度
     * @return array
     */
    public function massGetSeed(){
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/speed/get?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, []);
    }

    /**
     * 设置群发速度
     * @param integer $speed 群发速度的级别
     * @return array
     */
    public function massSetSeed($speed){
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/speed/set?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, ['speed' => $speed]);
    }


}