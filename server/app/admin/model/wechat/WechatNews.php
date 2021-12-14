<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model\wechat;

use app\common\models\wechat\WechatNews as WechatNewsModel;

class WechatNews extends WechatNewsModel {

    /**
     * 增加查询条件
     * @param $query
     * @param $value
     * @param $data
     */
    public function searchKeysAttr($query, $value, $data){
        if(!empty($value)){
            $query->where(["title","like",'%'.$value.'%']);
        }
    }

}