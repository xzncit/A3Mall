<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat\Online;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

/**
 * 客服消息
 * Class Online
 * @package xzncit\wechat\Online
 */
class Online extends App {

    /**
     * 添加客服帐号
     * 开发者可以通过本接口为公众号添加客服账号，每个公众号最多添加100个客服账号。
     * @param string $kfAccount     帐号
     * @param string $nickname      名称
     * @param string $password      密码明文的32位加密MD5值
     * @return array
     * @throws \Exception
     */
    public function add($kfAccount,$nickname,$password){
        return HttpClient::create()->postJson("customservice/kfaccount/add?access_token=ACCESS_TOKEN",[
            "kf_account"=>$kfAccount,"nickname"=>$nickname,"password"=>$password
        ])->toArray();
    }

    /**
     * 修改客服帐号
     * 开发者可以通过本接口为公众号修改客服账号。
     * @param string $kfAccount     帐号
     * @param string $nickname      名称
     * @param string $password      密码明文的32位加密MD5值
     * @return array
     * @throws \Exception
     */
    public function update($kfAccount,$nickname,$password){
        return HttpClient::create()->postJson("customservice/kfaccount/update?access_token=ACCESS_TOKEN",[
            "kf_account"=>$kfAccount,"nickname"=>$nickname,"password"=>$password
        ])->toArray();
    }

    /**
     * 删除客服帐号
     * 开发者可以通过该接口为公众号删除客服帐号。
     * @param string $kfAccount     帐号
     * @return array
     * @throws \Exception
     */
    public function del($kfAccount){
        return HttpClient::create()->postJson("customservice/kfaccount/del?access_token=ACCESS_TOKEN",[
            "kf_account"=>$kfAccount
        ])->toArray();
    }

    /**
     * 设置客服帐号的头像
     * @param string            $kfAccount  帐号
     * @param string|resource   $file       头像的绝对路径或者使用 fopen 返回的资源
     * @return array
     * @throws \Exception
     */
    public function avatar($kfAccount,$file){
        if(gettype($file) != "resource"){
            if(!file_exists($file)){
                throw new \Exception("file does not exist",0);
            }

            $file = fopen($file,"r+");
        }

        return HttpClient::create()
            ->upload("customservice/kfaccount/uploadheadimg?access_token=ACCESS_TOKEN&kf_account={$kfAccount}",[
                [
                    'name' => 'media',
                    //'filename' => '1.jpg',
                    'contents' => $file
                ]
            ])->toArray();
    }

    /**
     * 获取所有客服账号
     * 开发者通过本接口，获取公众号中所设置的客服基本信息，包括客服工号、客服昵称、客服登录账号。
     * @return array
     * @throws \Exception
     */
    public function getList(){
        return HttpClient::create()->get("cgi-bin/customservice/getkflist?access_token=ACCESS_TOKEN")->toArray();
    }

    /**
     * 客服接口-发消息
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function send($data){
        return HttpClient::create()->postJson("cgi-bin/message/custom/send?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 客服输入状态
     * 开发者可通过调用“客服输入状态”接口，返回客服当前输入状态给用户。
     * @param $openid           普通用户（openid）
     * @param string $command   "Typing"：对用户下发“正在输入"状态 "CancelTyping"：取消对用户的”正在输入"状态
     * @return array
     * @throws \Exception
     */
    public function status($openid,$command="Typing"){
        return HttpClient::create()->postJson("cgi-bin/message/custom/typing?access_token=ACCESS_TOKEN",[
            "touser"=>$openid,"command"=>$command
        ])->toArray();
    }

    /**
     * 邀请绑定客服帐号
     * 新添加的客服帐号是不能直接使用的，只有客服人员用微信号绑定了客服账号后，方可登录Web客服进行操作。
     * 此接口发起一个绑定邀请到客服人员微信号，客服人员需要在微信客户端上用该微信号确认后帐号才可用。
     * 尚未绑定微信号的帐号可以进行绑定邀请操作，邀请未失效时不能对该帐号进行再次绑定微信号邀请。
     * @param $kfAccount    完整客服帐号，格式为：帐号前缀@公众号微信号
     * @param $inviteWx     接收绑定邀请的客服微信号
     * @return array
     * @throws \Exception
     */
    public function inviteworker($kfAccount,$inviteWx){
        return HttpClient::create()->postJson("customservice/kfaccount/inviteworker?access_token=ACCESS_TOKEN",[
            "kf_account"=>$kfAccount,"invite_wx"=>$inviteWx
        ])->toArray();
    }

    /**
     * 获取聊天记录
     * 此接口返回的聊天记录中，对于图片、语音、视频，分别展示成文本格式的[image]、[voice]、[video]。
     * 对于较可能包含重要信息的图片消息，后续将提供图片拉取URL，近期将上线。
     * @param int $starttime    起始时间，unix时间戳
     * @param int $endtime      结束时间，unix时间戳，每次查询时段不能超过24小时
     * @param int $msgid        消息id顺序从小到大，从1开始
     * @param int $number       每次获取条数，最多10000条
     * @return array
     * @throws \Exception
     */
    public function getMessageList($starttime,$endtime,$msgid,$number){
        return HttpClient::create()->postJson("customservice/msgrecord/getmsglist?access_token=ACCESS_TOKEN",[
            "starttime"=>$starttime,"endtime"=>$endtime,"msgid"=>$msgid,"number"=>$number
        ])->toArray();
    }

}