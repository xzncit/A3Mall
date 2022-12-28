<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\mini\Live;

use xzncit\core\App;
use xzncit\core\Config;

class Live extends App {

    /**
     * 创建直播间链接
     * @param int|string $roomId 直播间ID
     * @param array $customParams 开发者在直播间页面路径上携带自定义参数
     * @return string
     */
    public function createUrl($roomId,$params=[],$customParams=[]){
        $url = "plugin-private://" . Config::get("appid") . "/pages/live-player-plugin?room_id={$roomId}";
        if(!empty($params)) $url .= "&".http_build_query($params);
        if(!empty($customParams)) $url .= "&custom_params=" . json_encode($customParams,JSON_UNESCAPED_UNICODE);
        return $url;
    }

}