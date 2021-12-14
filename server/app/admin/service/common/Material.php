<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\common;

use app\admin\service\Service;
use app\common\models\Attachments as AttachmentsModel;
use app\common\models\AttachmentCategory as AttachmentCategoryModel;
use mall\utils\Tool;

class Material extends Service {

    /**
     * 获取列表数据
     * @param $data
     * @return array
     * @throws \think\db\exception\DbException
     */
    public static function getList($data){
        $type = $data["type"]??"image";
        $cat_id = $data["cat_id"]??0;

        $condition = [];
        switch ($type){
            case "video":
                $condition[] = ["suffix","=","mp4"];
                break;
            case "file":
                $condition[] = ["suffix","in",["zip","rar","txt","apk"]];
                break;
            case "image":
            default:
                $condition[] = ["suffix","in",["jpg","png","gif","jpeg","bmp"]];
        }

        if($cat_id > 0){
            $condition[] = ["cat_id","=",$cat_id];
        }

        $data = AttachmentsModel::where($condition)->order('id','desc')->paginate([
            "list_rows" => 18,
            "query"=>[
                "type"=>$type,
                "cat_id"=>$cat_id
            ]
        ]);

        $list = array_map(function ($res){
            if(in_array(strtolower($res["suffix"]),["zip","rar","txt","mp4"])){
                $res["path"] = '/static/system/images/' . strtolower($res["suffix"]).'.png';
            }else{
                $res["path"] = Tool::thumb($res["path"]);
            }

            $res["size"] = Tool::convert($res["size"]);
            return $res;
        },$data->items());

        $count = AttachmentsModel::where($condition)->count();
        return [ "result"=>$list, "count"=>$count, "page"=>$data->render() ];
    }

    /**
     * 获取附件分类
     * @param $type
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getCategory($type){
        return AttachmentCategoryModel::where("type",$type)->select()->toArray();
    }

    /**
     * 创建分类
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public static function createCategory($data){
        if(empty($data["name"])){
            throw new \Exception("请填写分类名称",0);
        }

        if(AttachmentCategoryModelwhere([ "name"=>$data["name"]])->count()){
            throw new \Exception("分类名称己存在",0);
        }

        AttachmentCategoryModel::create([ "name"=>$data["name"],"type"=>$data["type"]??"image" ]);
        return true;
    }

    /**
     * 删除附件
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function delete($id){
        $array = array_map("intval",explode(",",$id));
        if(count($array) <= 0){
            throw new \Exception("请选择需要删除的图片",0);
        }

        AttachmentsModel::where("id","in",$array)->chunk(100,function ($array) {
            foreach($array as $value){
                Uploadfiy::delete($value["path"]);
            }
        });

        return true;
    }

    /**
     * 删除附件分类
     * @param $id
     * @return bool
     */
    public static function deleteCategory($id){
        if(!AttachmentCategoryModel::where(["id"=>$id])->delete()){
            return false;
        }

        AttachmentsModel::where(["cat_id"=>$id])->chunk(100,function ($array) {
            foreach($array as $value){
                Uploadfiy::delete($value["path"]);
            }
        });

        return true;
    }

}