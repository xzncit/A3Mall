<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\wechat;

use app\common\model\base\A3Mall;
use mall\utils\Tool;

class Keys extends A3Mall {

    protected $name = "wechat_keys";

    protected $type = [
        "id"=>"integer",
        "news_id"=>"integer",
        "sort"=>"integer",
        "status"=>"integer",
        "admin_id"=>"integer",
        "create_time"=>"integer",
    ];

    public function getList($condition,$size,$page=1){
        $count = $this->where($condition)->count();
        $data = $this->where($condition)->order('id','desc')->paginate($size);

        $list = array_map(function ($res){
            $res['create_time'] = $res->create_time;
            return $res;
        },$data->items());

        return [
            "count"=>$count,
            "data"=>$list
        ];
    }

    public function setCreateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

    public function setAppidAttr($value){
        return strip_tags(trim($value));
    }

    public function setTypeAttr($value){
        return strip_tags(trim($value));
    }

    public function setKeysAttr($value){
        return strip_tags(trim($value));
    }

    public function setContentAttr($value){
        return Tool::editor($value);
    }

    public function setImageUrlAttr($value){
        return strip_tags(trim($value));
    }

    public function setVoiceUrlAttr($value){
        return strip_tags(trim($value));
    }

    public function setMusicTitleAttr($value){
        return strip_tags(trim($value));
    }

    public function setMusicUrlAttr($value){
        return strip_tags(trim($value));
    }

    public function setMusicImageAttr($value){
        return strip_tags(trim($value));
    }

    public function setMusicDescAttr($value){
        return strip_tags(trim($value));
    }

    public function setVideoTitleAttr($value){
        return strip_tags(trim($value));
    }

    public function setVideoUrlAttr($value){
        return strip_tags(trim($value));
    }

    public function setVideoDescAttr($value){
        return strip_tags(trim($value));
    }

}