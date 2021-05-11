<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat\GenShorten;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class GenShorten extends App {

    /**
     * 短key托管类似于短链API，开发者可以通过GenShorten将不超过4KB的长信息转成短key，再通过FetchShorten将短key还原为长信息。
     * @param string $long_data         需要转换的长信息，不超过4KB
     * @param int    $expire_seconds    过期秒数，最大值为2592000（即30天），默认为2592000
     * @return array
     * @throws \Exception
     */
    public function gen($long_data,$expire_seconds=2592000){
        return HttpClient::create()->postJson("cgi-bin/shorten/gen?access_token=ACCESS_TOKEN",[
            "long_data"=>$long_data,"expire_seconds"=>intval($expire_seconds)
        ])->toArray();
    }

    /**
     * FetchShorten
     * @param string $short_key    短key
     * @return array
     * @throws \Exception
     */
    public function fetch($short_key){
        return HttpClient::create()->postJson("cgi-bin/shorten/fetch?access_token=ACCESS_TOKEN",[
            "short_key"=>$short_key
        ])->toArray();
    }

}