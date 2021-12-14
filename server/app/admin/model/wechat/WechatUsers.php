<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model\wechat;

use app\common\models\wechat\WechatUsers as WechatUsersModel;

class WechatUsers extends WechatUsersModel {

    /**
     * 增加查询条件
     * @param $query
     * @param $value
     * @param $data
     */
    public function searchNicknameAttr($query, $value, $data){
        if(!empty($value)){
            $query->where('nickname','=',$value);
        }
    }

}