<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\custom;

use app\common\model\base\A3Mall;
use app\common\model\base\Archives;
use app\common\model\base\Category;
use app\common\model\goods\Goods;
use app\common\model\promotion\Bonus;
use app\common\model\promotion\Group;
use app\common\model\promotion\Regiment;
use app\common\model\promotion\Second;
use mall\utils\Tool;
use think\facade\Db;

class Pages extends A3Mall{

    protected $type = [
        "id"=>"integer",
        "is_lock"=>"integer"
    ];

    public function getLayoutData($id,$thumbImageDomain=false){
        if(!$row = $this->where("id",$id)->find()){
            throw new \Exception("您查找的内容不存在",10);
        }

        $pagesItemsModel = new PagesItems();
        $data = $pagesItemsModel->where("pid",$id)->order("position_id","ASC")->select()->toArray();
        foreach($data as $key=>$value){
            if(in_array($value["widget_name"],[
                "article","article-group","notice","goods","group",
                "regiment","second","bonus","navbar","slider",
                "images","image-group"
            ])){
                if(empty($data[$key]['params']['list'])) $data[$key]['params']['list'] = [];
            }

            if($value["widget_name"] == "article"){
                if (!empty($data[$key]['params']['list'])) {
                    $article_id = [];
                    foreach($data[$key]['params']['list'] as $v){
                        $article_id[] = $v["id"];
                    }
                    $data[$key]['params']['list'] = Archives::where("id",'in',$article_id)->withoutField('content,update_time,status,sort,pid')->order("id","DESC")->select()->toArray();
                }
            }else if($value["widget_name"] == "article-group" && !empty($data[$key]['params']['article_id'])){
                $category = new Category();
                $arr = $category->get_children($data[$key]['params']['article_id']);
                $arr = array_merge([$data[$key]['params']['article_id']],$arr);
                $data[$key]['params']['list'] = Archives::where('pid','in',$arr)->withoutField('content,update_time,status,sort')->limit($data[$key]['params']['limit'])->select()->toArray();
            }else if($value["widget_name"] == "notice"){
                if (isset($data[$key]['params']) && $data[$key]['params']['type'] != 'auto') {
                    $article_id = [];
                    foreach($data[$key]['params']['list'] as $v){
                        $article_id[] = $v["id"];
                    }
                    $data[$key]['params']['list'] = Archives::where("id",'in',$article_id)->order("id","DESC")->select()->toArray();
                }
            }else if($value["widget_name"] == "goods"){
                $where = [];
                $goodsModel = new Goods();
                if ($data[$key]['params']['type'] == 'auto') {
                    if (isset($data[$key]['params']['classifyId']) && trim($data[$key]['params']['classifyId'])) {
                        $categoryModel = new Category();
                        $catIds = $categoryModel->get_children($data[$key]['params']['classifyId']);
                        $catIds = array_merge([$data[$key]['params']['classifyId']],$catIds);
                        $where[] = ['cat_id','in',$catIds];
                    }

                    if (isset($data[$key]['params']['brandId']) && $data[$key]['params']['brandId']) {
                        $where[] = ['brand_id', 'in', $data[$key]['params']['brandId']];
                    }

                    $where[] = ['status', '=', 0];
                    $limit = isset($data[$key]['params']['limit']) ? $data[$key]['params']['limit'] : 10;
                    $goodsData = $goodsModel->field('id,cat_id,title,goods_number,goods_weight,sell_price,market_price,store_nums,photo,briefly,status,visit,favorite,sale')->where($where)->order('sale','desc')->limit($limit)->select()->toArray();

                    $goodsArray = [];
                    foreach($goodsData as $k=>$v){
                        $goodsArray[$k] = $v;
                        $goodsArray[$k]['photo'] = Tool::thumb($v["photo"],'',$thumbImageDomain);
                    }
                    $data[$key]['params']['list'] = $goodsArray;
                } else {
                    $arr = [];
                    foreach ((array)$data[$key]['params']['list'] as $item) {
                        $arr[] = $item["id"];
                    }

                    $res = [];
                    if(!empty($arr)){
                        $res = array_map(function ($r) use($thumbImageDomain) {
                            $r['photo'] = Tool::thumb($r['photo'],"",$thumbImageDomain);
                            return $r;
                        },$goodsModel->field('id,cat_id,title,goods_number,goods_weight,sell_price,market_price,store_nums,photo,briefly,status,visit,favorite,sale')->where([
                            "status"=>0
                        ])->where('id','in',$arr)->select()->toArray());
                    }

                    $data[$key]['params']['list'] = $res;
                }
            }else if($value["widget_name"] == "group"){
                $goods_group_id = [];
                if(!empty($data[$key]['params']['list'])){
                    foreach ((array)$data[$key]['params']['list'] as $k => $v) {
                        if (isset($v['goods_group_id'])) {
                            $goods_group_id[] = $v['goods_group_id'];
                        }
                    }
                }

                $data[$key]['params']['list'] = Db::name("promotion_group")
                    ->alias("pg")->field("pg.id,pg.title,pg.goods_id,pg.sell_price,pg.start_time,pg.end_time,g.photo")
                    ->join("goods g","pg.goods_id=g.id","LEFT")
                    ->where("pg.status",0)
                    ->where("g.status",0)
                    ->where("pg.id","in",$goods_group_id)->select()->toArray();

                foreach($data[$key]['params']['list'] as $k=>$v){
                    $data[$key]['params']['list'][$k] = $v;
                    $data[$key]['params']['list'][$k]["now_time"]   = time();
                    $data[$key]['params']['list'][$k]["start_time"] = $v['start_time'];
                    $data[$key]['params']['list'][$k]["end_time"]   = $v['end_time'];
                    $data[$key]['params']['list'][$k]['photo']      = Tool::thumb($v["photo"],'',$thumbImageDomain);
                }
            }else if($value["widget_name"] == "regiment"){
                $goods_regiment_id = [];
                if (!empty($data[$key]['params']['list'])) {
                    foreach ((array)$data[$key]['params']['list'] as $k => $v) {
                        if (isset($v['id'])) {
                            $goods_regiment_id[] = $v['id'];
                        }
                    }
                }

                $data[$key]['params']['list'] = Db::name("promotion_regiment")
                    ->alias("pg")->field("pg.id,pg.title,pg.goods_id,pg.sell_price,pg.start_time,pg.end_time,g.photo")
                    ->join("goods g","pg.goods_id=g.id","LEFT")
                    ->where("pg.status",0)
                    ->where("g.status",0)
                    ->where("pg.id","in",$goods_regiment_id)->select()->toArray();

                foreach($data[$key]['params']['list'] as $k=>$v){
                    $data[$key]['params']['list'][$k] = $v;
                    $data[$key]['params']['list'][$k]["now_time"]   = time();
                    $data[$key]['params']['list'][$k]["start_time"] = $v['start_time'];
                    $data[$key]['params']['list'][$k]["end_time"]   = $v['end_time'];
                    $data[$key]['params']['list'][$k]['photo']      = Tool::thumb($v["photo"],'',$thumbImageDomain);
                }
            }else if($value["widget_name"] == "second"){
                $goods_second_id = [];
                if (!empty($data[$key]['params']['list'])) {
                    foreach ((array)$data[$key]['params']['list'] as $k => $v) {
                        if (isset($v['id'])) {
                            $goods_second_id[] = $v['id'];
                        }
                    }
                }

                $data[$key]['params']['list'] = Db::name("promotion_second")
                    ->alias("pg")->field("pg.id,pg.title,pg.goods_id,pg.sell_price,pg.start_time,pg.end_time,g.photo")
                    ->join("goods g","pg.goods_id=g.id","LEFT")
                    ->where("pg.status",0)
                    ->where("g.status",0)
                    ->where("pg.id","in",$goods_second_id)->select()->toArray();

                foreach($data[$key]['params']['list'] as $k=>$v){
                    $data[$key]['params']['list'][$k] = $v;
                    $data[$key]['params']['list'][$k]["now_time"]   = time();
                    $data[$key]['params']['list'][$k]["start_time"] = $v['start_time'];
                    $data[$key]['params']['list'][$k]["end_time"]   = $v['end_time'];
                    $data[$key]['params']['list'][$k]['photo']      = Tool::thumb($v["photo"],'',$thumbImageDomain);
                }
            }else if($value["widget_name"] == "bonus"){
                $bonusModel = new Bonus();
                $data[$key]['params']['list'] = $bonusModel
                    ->where('status',0)->where("end_time",">",time())
                    ->where('type',0)
                    ->withoutField('create_time,status,used,giveout,point,type')
                    ->select()->toArray();
                if(!empty($data[$key]['params']['list'])){
                    foreach($data[$key]['params']['list'] as $k=>$v){
                        $data[$key]['params']['list'][$k]['start_time'] = date("Y-m-d",strtotime($v["start_time"]));
                        $data[$key]['params']['list'][$k]['end_time'] = date("Y-m-d",strtotime($v["end_time"]));
                    }
                }

            }else if($value["widget_name"] == "textarea"){
                $data[$key]['params'] = Tool::replaceContentImage(Tool::removeContentAttr($data[$key]['params']));
            }else if($value["widget_name"] == "slider"){
                foreach ($data[$key]['params']['list'] as $k => $v) {
                    $data[$key]['params']['list'][$k]['thumb_image'] = Tool::thumb($v['thumb_image'], '', $thumbImageDomain);
                }
            }else if($value["widget_name"] == "navbar"){
                foreach ($data[$key]['params']['list'] as $k => $v) {
                    $data[$key]['params']['list'][$k]['thumb_image'] = Tool::thumb($v['thumb_image'], '', $thumbImageDomain);
                }
            }else if($value["widget_name"] == "body"){
                if($value['params']["type"] == 2){
                    $data[$key]['params']["thumb_image"] = Tool::thumb($value['params']["thumb_image"], '', $thumbImageDomain);
                }
            }else if($value["widget_name"] == "images"){
                foreach ($data[$key]['params']['list'] as $k => $v) {
                    $data[$key]['params']['list'][$k]['thumb_image'] = Tool::thumb($v['thumb_image'], '', $thumbImageDomain);
                }
            }else if($value["widget_name"] == "image-group"){
                foreach ($data[$key]['params']['list'] as $k => $v) {
                    $data[$key]['params']['list'][$k]['thumb_image'] = Tool::thumb($v['thumb_image'], '', $thumbImageDomain);
                }
            }else if($value["widget_name"] == "video"){
                foreach ($data[$key]['params']['list'] as $k => $v) {
                    $data[$key]['params']['list'][$k]['url'] = Tool::thumb($v['url'], '', $thumbImageDomain);
                }
            }
        }

        return $data;
    }

    public function setTypeAttr($value){
        return trim(strip_tags($value));
    }

    public function setCodeAttr($value){
        return trim(strip_tags($value));
    }

    public function setNameAttr($value){
        return trim(strip_tags($value));
    }

    public function setIntroAttr($value){
        return trim(strip_tags($value));
    }
}