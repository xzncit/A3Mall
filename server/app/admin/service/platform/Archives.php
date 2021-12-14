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
use app\admin\service\platform\Category as CategoryService;
use app\admin\model\Archives as ArchivesModel;
use mall\utils\Tool;

class Archives extends Service {

    /**
     * 获取文章列表数据
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $count = ArchivesModel::withSearch(["pid","title"],[
            'pid'=>$data['key']["cat_id"]??'',
            'title'=>$data['key']["title"]??''
        ])->withJoin("category")->count();

        $result = array_map(function ($res){
            $res["photo"] = Tool::thumb($res["photo"]);
            return $res;
        },ArchivesModel::withSearch(["pid","title"],[
            'pid'=>$data['key']["cat_id"]??'',
            'title'=>$data['key']["title"]??''
        ])->withJoin("category")->order("archives.id","desc")->page($data["page"]??1,$data["limit"]??10)->select()->toArray());

        return ["count"=>$count, "data"=>$result];
    }

    /**
     * 获取单篇文章数据
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail($id){
        return [
            "data"=>ArchivesModel::where(["id"=>$id])->find(),
            "cat"=>CategoryService::getTree(["status"=>0,"module"=>"article"])
        ];
    }

    /**
     * 保存
     * @param $data
     * @return bool
     */
    public static function save($data){
        if(ArchivesModel::where("id",$data["id"])->count()){
            ArchivesModel::where("id",$data["id"])->save($data);
        }else{
            ArchivesModel::create($data);
        }

        return true;
    }

    /**
     * 删除文章
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function delete($id){
        if(!ArchivesModel::where("id",$id)->count()){
            throw new \Exception("您删除的内容不存在",0);
        }

        ArchivesModel::where("id",$id)->delete();
        return true;
    }

    /**
     * 更新字段值
     * @return ArchivesModel
     */
    public static function setFields(){
        $data = self::getFields();
        return ArchivesModel::where("id",$data["id"])->update([$data["name"]=>$data["value"]]);
    }

}