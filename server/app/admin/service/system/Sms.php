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
use app\common\models\SmsTemplate as SmsTemplateModel;

class Sms extends Service {

    /**
     * 获取列表数据
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $count = SmsTemplateModel::count();
        $list = SmsTemplateModel::page($data["page"]??1,$data["limit"]??10)->order("id","desc")->select()->toArray();

        return [ "count"=>$count,"data"=>$list ];
    }

    /**
     * 详情
     * @param $id
     * @return array[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail($id){
        $row = SmsTemplateModel::where("id",$id)->find();
        return [ "data"=>$row??[] ];
    }

    /**
     * 保存数据
     * @param $data
     */
    public static function save($data){
        if(SmsTemplateModel::where("id",$data["id"])->count()){
            SmsTemplateModel::where("id",$data["id"])->save($data);
        }else{
            SmsTemplateModel::create($data);
        }

        return true;
    }

    /**
     * 更新字段值
     * @return SmsTemplateModel
     */
    public static function setFields(){
        $data = self::getFields();
        return SmsTemplateModel::where("id",$data["id"])->update([$data["name"]=>$data["value"]]);
    }

}