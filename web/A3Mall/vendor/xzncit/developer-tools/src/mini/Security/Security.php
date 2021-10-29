<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\mini\Security;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class Security extends App {

    /**
     * security.imgSecCheck
     * 校验一张图片是否含有违法违规内容
     * @param string|resource $file     文件的绝对路径或者使用 fopen 返回的资源
     * @return array
     * @throws \Exception
     */
    public function imgSecCheck($file){
        if(gettype($file) != "resource"){
            if(!file_exists($file)){
                throw new \Exception("file does not exist",0);
            }

            $file = fopen($file,"r+");
        }

        return HttpClient::create()->upload("wxa/img_sec_check?access_token=ACCESS_TOKEN",[
            [
                "name"=>"media",
                "contents"=>$file
            ]
        ])->toArray();
    }

    /**
     * security.mediaCheckAsync
     * 异步校验图片/音频是否含有违法违规内容。
     * @param $media_url        要检测的多媒体url
     * @param $media_type       1:音频;2:图片
     * @return array
     * @throws \Exception
     */
    public function mediaCheckAsync($media_url,$media_type=2){
        return HttpClient::create()->postJson("wxa/media_check_async?access_token=ACCESS_TOKEN",[
            "media_url"=>$media_url,"media_type"=>$media_type
        ])->toArray();
    }

    /**
     * security.msgSecCheck
     * 检查一段文本是否含有违法违规内容
     * @param $content          要检测的文本内容，长度不超过 500KB
     * @return array
     * @throws \Exception
     */
    public function msgSecCheck($content){
        return HttpClient::create()->postJson("wxa/msg_sec_check?access_token=ACCESS_TOKEN",[
            "content"=>$content
        ])->toArray();
    }

}