<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\platform;

use app\admin\service\Service;
use app\common\models\SearchKeywords;

class Keywords extends Service {

    /**
     * 获取列表
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        return [
            "count"=>SearchKeywords::count(),
            "data"=>SearchKeywords::page($data["page"]??1,$data["limit"]??10)->order("id","desc")->select()->toArray()
        ];
    }

    /**
     * 获取单条数据
     * @param $id
     * @return array[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail($id){
        return [
            "data"=>SearchKeywords::where("id",$id)->find() ?? []
        ];
    }

    /**
     * 保存数据
     * @param $data
     * @return SearchKeywords|bool|\think\Model
     */
    public static function save($data){
        if(SearchKeywords::where("id",$data['id'])->count()){
            return SearchKeywords::where("id",$data['id'])->save($data);
        }else{
            return SearchKeywords::create($data);
        }
    }

    /**
     * 删除
     * @param $id
     * @return bool
     */
    public static function delete($id){
        return SearchKeywords::where("id",$id)->delete();
    }

    /**
     * 更新字段值
     * @return SearchKeywords
     */
    public static function setFields(){
        $data = self::getFields();
        return SearchKeywords::where("id",$data["id"])->update([$data["name"]=>$data["value"]]);
    }
}