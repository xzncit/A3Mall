<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model\wechat;

use app\common\models\wechat\WechatKeys as WechatKeysModel;

class WechatKeys extends WechatKeysModel {

    /**
     * 增加查询条件
     * @param $query
     * @param $value
     * @param $data
     */
    public function searchKeysAttr($query, $value, $data){
        $condition = [];
        if(!empty($value)){
            $condition[] = ["keys","like",'%'.$value.'%'];
        }

        $condition[] = ["keys","not in","defaults,subscribe"];
        $query->where($condition);
    }

}