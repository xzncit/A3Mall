<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace xzncit\microapp\Live;

use xzncit\core\App;
use xzncit\core\base\AccessToken;
use xzncit\core\http\HttpClient;

class Live extends App {

    /**
     * 直播间自定义封面图
     * 目前抖音直播已经集成了直播功能卡片跳转小程序的能力。为了给开发者提供更多的自由度，
     * 我们支持开发者定制功能卡片封面图，可通过此接口上传封面图。图片会在直播功能卡区域展示。
     * @param resource $image      封面图
     * @param string $app_id       小程序的
     * @param string $start_page   跳转页
     * @param string $title        标题
     * @param string|int $room_id  直播间
     * @return array
     * @throws \Exception
     */
    public function thumb($image,$app_id,$start_page,$title,$room_id){
        if(gettype($image) != "resource"){
            if(!file_exists($image)){
                throw new \Exception("file does not exist",0);
            }

            $file = fopen($image,"r+");
        }

        $data = [
            ['name' => 'image', 'contents' => $file],
            ['name' => 'app_id', 'contents' => $app_id],
            ['name' => 'start_page', 'contents' => $start_page],
            ['name' => 'title', 'contents' => $title],
            ['name' => 'room_id', 'contents' => (int)$room_id]
        ];

        return HttpClient::create()->upload("api/apps/upload_live_image", $data)->toArray();
    }

    /**
     * 提供将在抖音及今日头条宿主小程序拍摄抖音能力获取的 videoId 转换为抖音开放平台可用的 item_id，从而提供获取更多数据的能力。
     * 需要注意的是：开放平台端获取数据也需要经过抖音开放平台的获取 access_token 流程，这部分请参阅抖音开放平台的文档。
     * @param list[string] $video_ids        要转换的 videoId 列表，最长为 100 个
     * @param string $app_id                 小程序 ID
     * @param string $access_key             访问密钥 根据打通文档的说明，此处在使用的应用类型为小程序时应当为小程序的 appid，更详细的内容请参见小程序获取抖音权限结尾部分。
     * @return array
     * @throws \Exception
     */
    public function videoId($video_ids,$app_id,$access_key){
        return HttpClient::create()->postJson("api/apps/convert_video_id/video_id_to_open_item_id",[
            "access_token"=>AccessToken::get(),
            "video_ids"=>$video_ids,
            "app_id"=>$app_id,
            "access_key"=>$access_key
        ])->toArray();
    }

    /**
     * 提供将在抖音开放平台获取的 item_id 转换为小程序中可用的 encryptedId，从而在小程序宿主中播放。
     * 通过这一能力，可以将原先无法获取的用户存量视频在字节小程序中使用。参见跳转视频播放页。
     * @param $video_ids
     * @param $access_key
     * @return array
     * @throws \Exception
     */
    public function convertVideoId($video_ids,$access_key){
        return HttpClient::create()->postJson("api/apps/convert_video_id/video_id_to_open_item_id",[
            "access_token"=>AccessToken::get(),
            "video_ids"=>$video_ids,
            "access_key"=>$access_key
        ])->toArray();
    }

}