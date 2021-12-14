<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\core\message;


class Music extends Message {

    protected $attribute = [
        'CreateTime'   => "",
        'MsgType'      => "music",
        'Music'     => [],
        'ToUserName'   => "",
        'FromUserName' => ""
    ];

    /**
     * Music constructor.
     * @param array $data [
     *      "Title"=>"",
     *      "Description"=>"",
     *      "MusicUrl"=>"",
     *      "HQMusicUrl"=>"",
     *      "ThumbMediaId"=>""
     * ]
     */
    public function __construct(array $data=[]){
        $this->setAttribute([
            "CreateTime"    =>  time(),
            "Music"         =>  $data
        ]);
    }

}