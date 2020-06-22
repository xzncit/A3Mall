<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\wap;

use think\facade\Db;
use think\facade\Request;
use mall\utils\Tool;

class Search extends Auth {

    public function index(){
        $data = Db::name("search_keywords")->where(["is_top"=>0])->select()->toArray();
        $keywords = array_map(function ($result){
            return $result["name"];
        },$data);

        return $this->returnAjax("ok",1,$keywords);
    }

    public function get_list(){
        $page = Request::param("page","1","intval");
        $keywords = Request::param("keywords","","strip_tags,trim");
        $type = Request::param("type","default","strip_tags,trim");
        $sort = Request::param("sort","0","intval");

        $text = $sort == 0 ? "ASC" : "DESC";
        $order = '';
        switch($type){
            case 'price':
                $order = 'sell_price';
                break;
            case 'sales':
                $order = 'sale';
                break;
            case 'default':
            default :
                $order = 'id';
                break;
        }

        $size = 10;
        $count = Db::name("goods")
            ->where('status',0)
            ->where("title",'like','%'.$keywords.'%')
            ->whereOr("content","like",'%'.$keywords.'%')
            ->count();

        $total = ceil($count/$size);
        if($total == $page -1){
            return $this->returnAjax("empty",-1,[]);
        }

        $result = Db::name("goods")
            ->field("id,title,photo,sell_price as price,sale")
            ->where('status',0)->where("title",'like','%'.$keywords.'%')
            ->whereOr("content","like",'%'.$keywords.'%')
            ->order($order,$text)->limit((($page - 1) * $size),$size)->select()->toArray();

        $data = array_map(function ($rs){
            $rs["photo"] = Tool::thumb($rs["photo"],"medium",true);
            return $rs;
        },$result);

        return $this->returnAjax("ok",1, [
            "list"=>$data,
            "page"=>$page,
            "total"=>$total,
            "size"=>$size
        ]);
    }

}