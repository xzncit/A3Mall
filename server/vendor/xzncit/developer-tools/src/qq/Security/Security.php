<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace xzncit\qq\Security;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class Security extends App {

    /**
     * 校验一张图片是否含有违法违规内容
     * security.imgSecCheck
     * @param $file         要检测的图片文件，格式支持PNG、JPEG、JPG、GIF，图片尺寸不超过 750px x 1334px
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

        return HttpClient::create()->upload("wxa/img_sec_check?access_token=ACCESS_TOKEN&appid=".$this->app->config["appid"],[
            [
                "name"=>"media",
                "contents"=>$file
            ]
        ])->toArray();
    }

    /**
     * 检查一段文本是否含有违法违规内容
     * 用户个人资料违规文字检测；
     * 媒体新闻类用户发表文章，评论内容检测；
     * 游戏类用户编辑上传的素材(如答题类小游戏用户上传的问题及答案)检测等。 频率限制：单个 appId 调用上限为 4000 次/分钟，2,000,000 次/天
     * @param $content
     * @return array
     * @throws \Exception
     */
    public function msgSecCheck($content){
        return HttpClient::create()->postJson("wxa/msg_sec_check?access_token=ACCESS_TOKEN",[
            "content"=>$content,"appid"=>$this->app->config["appid"]
        ])->toArray();
    }

    /**
     * 异步校验图片/音频是否含有违法违规内容
     * @param $media_url        要检测的多媒体url
     * @param int $media_type   1:音频;2:图片
     * @return array
     * @throws \Exception
     */
    public function mediaCheckAsync($media_url,$media_type=2){
        return HttpClient::create()->postJson("wxa/media_check_async?access_token=ACCESS_TOKEN",[
            "media_url"=>$media_url,"media_type"=>$media_type,"appid"=>$this->app->config["appid"]
        ])->toArray();
    }

}