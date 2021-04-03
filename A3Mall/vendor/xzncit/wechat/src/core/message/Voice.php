<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\core\message;


class Voice extends Message {

    protected $attribute = [
        "MsgType"      => "image",
        "CreateTime"   => "",
        "ToUserName"   => "",
        "FromUserName" => "",
        "Voice"        => [],
    ];

    /**
     * Voice constructor.
     * @param string $mediaId
     */
    public function __construct(string $mediaId=""){
        $this->setAttribute([
            "CreateTime"    =>  time(),
            "Voice"         =>  ['MediaId' => $mediaId]
        ]);
    }

}