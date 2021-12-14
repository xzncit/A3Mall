<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\core\message;


class Raw extends Message {

    public function __construct(string $content=""){
        $this->message = $content;
    }

    public function send(){
        return $this->message;
    }

}