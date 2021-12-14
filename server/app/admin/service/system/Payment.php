<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\system;

use app\admin\service\Service;
use app\common\models\Payment as PaymentModel;

class Payment extends Service {

    /**
     * 列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList(){
        return PaymentModel::where('is_show',0)->select()->toArray();
    }

    /**
     * 详情
     * @param $id
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail($id){
        $row = PaymentModel::where("id",$id)->where('is_show',0)->find();
        $row["config"] = !empty($row["config"]) ? json_decode($row["config"],true) : [];
        return $row;
    }

    /**
     * 保存数据
     * @param $data
     * @return bool
     */
    public static function save($data){
        $data['config'] = json_encode($data["config"]);
        PaymentModel::where("id",$data["id"])->update($data);
        return true;
    }

    /**
     * 更新字段值
     * @return PaymentModel
     */
    public static function setFields(){
        $data = self::getFields();
        return PaymentModel::where("id",$data["id"])->update([$data["name"]=>$data["value"]]);
    }

}