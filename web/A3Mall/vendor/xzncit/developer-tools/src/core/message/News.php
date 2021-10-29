<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\core\message;


class News extends Message {

    protected $attribute = [
        "CreateTime"   => "",
        "MsgType"      => 'news',
        "Articles"     => [],
        "ToUserName"   => "",
        "FromUserName" => "",
        "ArticleCount" => "",
    ];

    /**
     * News constructor.
     * @param array $data [
     *      "item"=>[
     *          "Title"=>"",
     *          "Description"=>"",
     *          "PicUrl"=>"",
     *          "Url"=>""
     *      ]
     * ]
     */
    public function __construct(array $data=[]){
        $this->setAttribute([
            "CreateTime"    =>  time(),
            "Articles"      =>  $data,
            "ArticleCount"  =>  count($data)
        ]);
    }

}