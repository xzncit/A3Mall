<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\core\message;


class Text extends Message {

    protected $attribute = [
        "MsgType"      => "text",
        "CreateTime"   => "",
        "Content"      => "",
        "ToUserName"   => "",
        "FromUserName" => "",
    ];

    /**
     * Text constructor.
     * @param string $content
     */
    public function __construct(string $content=""){
        $this->setAttribute([
            "CreateTime"=>time(),
            "Content"=>$content
        ]);
    }

}