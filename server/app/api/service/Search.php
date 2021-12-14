<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\service;

use app\common\exception\BaseException;
use app\common\models\goods\Goods as GoodsModel;
use app\common\models\StatisticsSearch as StatisticsSearchModel;
use app\admin\model\StatisticsSearchGoods as StatisticsSearchGoodsModel;
use app\common\models\SearchKeywords as SearchKeywordsModel;
use mall\utils\Tool;
use think\facade\Config;

class Search extends Service {

    /**
     * 获取关键字
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getSearchKeywordsList(){
        return array_map(function ($result){
            return $result["name"];
        },SearchKeywordsModel::where(["is_top"=>0])->select()->toArray());
    }

    /**
     * 商品列表排序
     * @param $data
     * @return array
     */
    protected static function getOrder($data){
        $type = $data["type"]??0;
        $sort = $data["sort"]??1;
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

        return [ "field"=>$order, "order"=>$text ];
    }

    /**
     * 获取列表数据
     * @param $data
     * @return array
     * @throws BaseException
     */
    public static function getList($data){
        $keywords = strip_tags($data["keywords"])??"";
        $condition = [
            ["status","=",0],
            ["title","like",'%'.$keywords.'%']
        ];

        $sort = self::getOrder($data);
        $size = Config::get("website.pageSize");
        $page = $data["page"]??1;

        $count = GoodsModel::where($condition)->whereOr("content","like",'%'.$keywords.'%')->count();
        $result = GoodsModel::field("id,title,photo,sell_price as price,sale")->where($condition)->whereOr("content","like",'%'.$keywords.'%')->order($sort["field"],$sort["order"])->page($page,$size)->select()->toArray();

        $array = [ "list"=>array_map(function ($res){
            $res["photo"] = Tool::thumb($res["photo"],"medium",true);
            return $res;
        },$result), "page"=>$page, "total"=>0, "size"=>$size ];
        $total = ceil($count / $size);
        $array["total"] = $total;
        if($total == $page -1){
            throw new BaseException("没有数据了哦！",-1,$array);
        }

        return $array;
    }

    /**
     * 保存搜索记录
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function keywords($data){
        $keywords = strip_tags($data["keywords"])??"";
        $goods_id = intval($data["goods_id"])??0;
        $client_type = intval($data["client_type"])??0;
        $type = intval($data["type"])??0;
        if(empty($keywords)){
            throw new \Exception("ok",1);
        }

        if(StatisticsSearchModel::where("name",$keywords)->count()){
            StatisticsSearchModel::where("name",$keywords)->inc("num")->update();
        }else{
            StatisticsSearchModel::create(["name"=>$keywords,"num"=>1]);
        }

        if(!GoodsModel::where('id',$goods_id)->find()){
            return true;
        }

        StatisticsSearchGoodsModel::create([
            "goods_id"=>$goods_id,
            "name"=>$keywords,
            "referer"=>$client_type,
            "type"=>(in_array($client_type,[0,1,3]) ? $client_type : $type),
            "create_time"=>time()
        ]);

        return true;
    }

}