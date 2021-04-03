<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\core\message;


class Video extends Message {

    protected $attribute = [
        "CreateTime"   => "",
        "MsgType"      => "video",
        "Video"        => [],
        "ToUserName"   => "",
        "FromUserName" => ""
    ];

    /**
     * Video constructor.
     * @param array $data [
     *      "MediaId"=>media_id
     *      "Title"=>"",
     *      "Description"=>""
     * ]
     */
    public function __construct(array $data=[]){
        $this->setAttribute([
            "CreateTime"    =>  time(),
            "Video"         =>  $data
        ]);
    }

}