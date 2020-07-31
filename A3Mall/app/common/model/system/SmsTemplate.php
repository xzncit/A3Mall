<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\system;

use app\common\model\base\A3Mall;
use mall\utils\Tool;

class SmsTemplate extends A3Mall{

    protected $type = [
        "id"=>"integer",
        "status"=>"integer",
        "create_time"=>"integer"
    ];

    public function getList($condition=[],$size=10){
        $count = $this->where($condition)->count();
        $data = $this->where($condition)->order("id DESC")->paginate($size);

        $list = array_map(function ($res){
            $res['url'] = $res->template_editor;
            return $res;
        },$data->items());

        return [
            "count"=>$count,
            "data"=>$list
        ];
    }

    public function setSignAttr($value){
        return strip_tags(trim($value));
    }

    public function setSignNameAttr($value){
        return strip_tags(trim($value));
    }

    public function setTemplateCodeAttr($value){
        return strip_tags(trim($value));
    }

    public function setTemplateNameAttr($value){
        return strip_tags(trim($value));
    }

    public function setTemplateParamAttr($value){
        return Tool::editor($value);
    }

    public function getTemplateEditorAttr($value,$data){
        return createUrl('template_editor',["id"=>$data["id"]]);
    }

    public function getCreateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }
}