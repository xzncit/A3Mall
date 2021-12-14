<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\service\wechat;

use app\api\service\Service;
use app\common\models\Setting as SettingModel;
use app\common\models\wechat\SubscribeMessage as SubscribeMessageModel;

class Mini extends Service {

    /**
     * 获取订阅消息数据
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getTemplate(){
        $config = SettingModel::getArrayData("wemini_base");
        if(isset($config["is_subscribe"]) && $config["is_subscribe"] == 1){
            throw new \Exception("订阅消息未开启",0);
        }

        $data = SubscribeMessageModel::where("status",0)->select()->toArray();
        $array = [];
        foreach($data as $value){
            $array[$value["sign"]] = $value["temp_id"];
        }

        return $array;
    }

}