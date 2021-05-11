<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\mini\Online;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class Online extends App {

    /**
     * customerServiceMessage.getTempMedia
     * 获取客服消息内的临时素材。即下载临时的多媒体文件。目前小程序仅支持下载图片文件
     * @param $media_id     媒体文件 ID
     * @return array
     * @throws \Exception
     */
    public function getTempMedia($media_id){
        return HttpClient::create()->get("cgi-bin/media/get?access_token=ACCESS_TOKEN",[
            "media_id"=>$media_id
        ])->toArray();
    }

    /**
     * customerServiceMessage.send
     * 发送客服消息给用户。详细规则见 发送客服消息
     * @param $touser               用户的 OpenID
     * @param $msgtype              消息类型
     * @param $text                 文本消息，msgtype="text" 时必填
     * @param $image                图片消息，msgtype="image" 时必填
     * @param $link                 图文链接，msgtype="link" 时必填
     * @param $miniprogrampage      小程序卡片，msgtype="miniprogrampage" 时必填
     * @return array
     * @throws \Exception
     */
    public function send($touser,$msgtype,$text,$image,$link,$miniprogrampage){
        return HttpClient::create()->postJson("cgi-bin/message/custom/send?access_token=ACCESS_TOKEN",[
            "touser"=>$touser,"msgtype"=>$msgtype,"text"=>$text,
            "image"=>$image,"link"=>$link,"miniprogrampage"=>$miniprogrampage
        ])->toArray();
    }

    /**
     * customerServiceMessage.setTyping
     * 下发客服当前输入状态给用户。详见 客服消息输入状态
     * @param $touser           用户的 OpenID
     * @param $command          命令
     * @return array
     * @throws \Exception
     */
    public function setTyping($touser,$command){
        return HttpClient::create()->postJson("cgi-bin/message/custom/typing?access_token=ACCESS_TOKEN",[
            "touser"=>$touser,"command"=>$command
        ])->toArray();
    }

    /**
     * customerServiceMessage.uploadTempMedia
     * @param string|resource $file      文件的绝对路径或者使用 fopen 返回的资源
     * @param string          $type      文件类型
     * @return array
     * @throws \Exception
     */
    public function uploadTempMedia($file,$type="image"){
        if(gettype($file) != "resource"){
            if(!file_exists($file)){
                throw new \Exception("file does not exist",0);
            }

            $file = fopen($file,"r+");
        }

        return HttpClient::create()->upload("cgi-bin/media/upload?access_token=ACCESS_TOKEN&type={$type}",[
            [
                "name"=>"media",
                "contents"=>$file
            ]
        ])->toArray();
    }

}