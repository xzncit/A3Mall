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
        $type = Request::param("type","0","intval");
        $sort = Request::param("sort","1","intval");

        switch($type){
            case '2':
                $order = 'sell_price';
                $text = $sort == 1 ? "ASC" : "DESC";
                break;
            case '1':
                $order = 'sale';
                $text = 'DESC';
                break;
            case '0':
            default :
                $order = 'id';
                $text = 'DESC';
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
            return $this->returnAjax("empty",-1,[
                "list"=>[],
                "page"=>$page,
                "total"=>$total,
                "size"=>$size
            ]);
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

    public function keywords(){
        $keywords = Request::param("keywords","","trim,strip_tags");
        $goods_id = Request::param("goods_id","0","intval");
        $client_type = Request::param("client_type","0","intval");
        $type = Request::param("type","0","intval");
        if(empty($keywords)){
            return $this->returnAjax("ok",1);
        }

        if(Db::name("statistics_search")->where("name",$keywords)->count()){
            Db::name("statistics_search")->where("name",$keywords)->inc("num")->update();
        }else{
            Db::name("statistics_search")->insert(["name"=>$keywords,"num"=>1]);
        }

        if(Db::name("goods")->where('id',$goods_id)->count()){
            Db::name("statistics_search_goods")->insert([
                "goods_id"=>$goods_id,
                "name"=>$keywords,
                "referer"=>$client_type,
                "type"=>(in_array($client_type,[0,1,3]) ? $client_type : $type),
                "create_time"=>time()
            ]);
        }

        return $this->returnAjax("ok",1);
    }

}