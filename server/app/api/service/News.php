<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\service;

use mall\utils\Tool;
use app\common\models\Archives as ArchivesModel;
use app\common\models\Category as CategoryModel;
use think\facade\Config;

class News extends Service {

    public static function getList($data){
        $condition = [
            ["status","=",0]
        ];

        $condition[] = ["pid","=",$data["cat_id"]??"72"];
        $size = Config::get("website.pageSize");
        $page = $data["page"]??1;

        $count = ArchivesModel::where($condition)->count();
        $result = ArchivesModel::field("id,title,create_time,photo")->where($condition)->order("id","desc")->page($page,$size)->select()->toArray();

        foreach($result as $key=>$value){
            $result[$key]["photo"] = Tool::thumb($value["photo"],"medium",true);
        }

        $array = [ "list"=>$result??[], "page"=>$page, "total"=>0, "size"=>$size ];
        $total = ceil($count / $size);
        $array["total"] = $total;

        if($total == $page -1){
            throw new BaseException("没有数据了哦！",-1,$array);
        }

        return $array;
    }

    /**
     * 新闻详情
     * @param $id
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail($id){
        if(!$row = ArchivesModel::field("pid,title,hits,content,create_time")->where(["id"=>$id,"status"=>0])->find()){
            throw new \Exception("内容不存在",0);
        }

        ArchivesModel::where("id",$id)->where("status",0)->inc("hits",1)->update();
        $row["content"] = Tool::replaceContentImage(Tool::removeContentAttr($row["content"]));
        $row["cat_name"] = CategoryModel::where("id",$row["pid"])->value("title");
        return $row;
    }

}