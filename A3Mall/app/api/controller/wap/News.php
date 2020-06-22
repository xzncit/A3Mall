<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\wap;

use mall\utils\Tool;
use think\facade\Db;
use think\facade\Request;

class News extends Auth {

    public function index(){
        $page = Request::param("page","1","intval");
        $size = 10;
        $count = Db::name("archives")
            ->where('status',0)->where('pid','72')->count();
        $total = ceil($count / $size);

        if($total == $page -1){
            return $this->returnAjax("empty",-1,[]);
        }

        $result = Db::name("archives")
            ->field("id,title,create_time,photo")
            ->where('status',0)->where('pid','72')
            ->limit((($page - 1) * $size),$size)->select()->toArray();

        $data = array_map(function ($rs){
            $rs["photo"] = Tool::thumb($rs["photo"],"medium",true);
            $rs["create_time"] = date("Y-m-d H:i:s",$rs["create_time"]);
            return $rs;
        },$result);

        return $this->returnAjax("ok",1, [
            "list"=>$data,
            "page"=>$page,
            "total"=>$total,
            "size"=>$size
        ]);
    }

    public function view(){
        $id = Request::param("id","0","intval");
        if(($row = Db::name("archives")->field("pid,title,hits,content,create_time")->where("id",$id)->where("status",0)->find()) == false){
            return $this->returnAjax("内容不存在",0);
        }

        Db::name("archives")->where("id",$id)->where("status",0)->inc("hits",1)->update();

        $row["content"] = Tool::removeContentAttr(Tool::replaceContentImage($row["content"]));
        $row["cat_name"] = Db::name("category")->where("id",$row["pid"])->value("title");
        $row["create_time"] = date("Y-m-d H:i:s",$row["create_time"]);

        return $this->returnAjax("ok",1, $row);
    }
}