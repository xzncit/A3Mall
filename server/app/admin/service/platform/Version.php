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
use app\common\models\Attachments;
use app\common\models\Version as VersionModel;

class Version extends Service {

    /**
     * 获取列表数据
     * @param $data
     * @param array $condition
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data,$condition=[]){
        return [
            "count"=>VersionModel::where($condition)->count(),
            "data"=>VersionModel::where($condition)->page($data["page"]??1,$data["limit"]??10)->order("id",'desc')->select()->toArray()
        ];
    }

    /**
     * 详情
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail($data){
        return [
            "data"=>VersionModel::where("id",$data["id"]??0)->find(),
            "type"=>$data["type"]??1
        ];
    }

    /**
     * 保存
     * @param array $data
     * @return VersionModel|bool|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function save($data=[]){
        if(!empty($data["id"])){
            $obj = VersionModel::where("id",$data["id"])->find();
            if($data["url"] != $obj["url"]){
                if(Attachments::where("path",$obj["url"])->delete()){
                    $file = trim($obj["url"],"/");
                    file_exists($file) && unlink($file);
                }
            }

            return VersionModel::where("id",$data["id"])->save($data);
        }else{
            return VersionModel::create($data);
        }
    }

    /**
     * 删除
     * @param $id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function delete($id){
        $row = VersionModel::where('id',$id)->find();
        if(empty($row)){
            throw new \Exception("您要查找的数据不存在！",0);
        }

        if(Attachments::where("path",$row["url"])->delete()){
            $file = trim($row["url"],"/");
            file_exists($file) && unlink($file);
        }

        return VersionModel::where("id",$id)->delete();
    }

}