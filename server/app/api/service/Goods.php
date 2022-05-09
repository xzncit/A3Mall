<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\service;

use mall\library\tools\jwt\Token;
use mall\utils\Tool;
use think\facade\Config;
use app\common\exception\BaseException;
use mall\basic\Users;
use app\common\models\users\Users as UsersModel;
use app\common\models\goods\Goods as GoodsModel;
use app\common\models\goods\GoodsItem as GoodsItemModel;
use app\common\models\goods\GoodsImage as GoodsImageModel;
use app\common\models\goods\GoodsAttribute as GoodsAttributeModel;
use app\common\models\users\UsersFavorite as UsersFavoriteModel;
use app\api\service\Comments as CommentsService;

class Goods extends Service {

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
     * 获取商品数据
     * @param $data
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $condition = [
            ["cat_id","=",$data["id"]??""],
            ["status","=",0]
        ];

        $sort = self::getOrder($data);

        $size = Config::get("website.pageSize");
        $page = $data["page"]??1;

        $count = GoodsModel::where($condition)->count();
        $result = GoodsModel::field("id,title,photo,sell_price as price,sale")->where($condition)->order($sort["field"],$sort["order"])->page($page,$size)->select()->toArray();

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
     * 详情
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail($id){
        if(!$goods = GoodsModel::where("id",$id)->where("status",0)->find()){
            throw new \Exception("商品不存在",0);
        }

        $data = [];
        $data["collect"] = false;

        try{
            $token = Token::check();
            $result  = Token::parse($token,"id");
            if(is_array($result)){
                if($row=UsersModel::where("id",$result["value"])->find()){
                    $data["collect"] = UsersFavoriteModel::where([ "user_id"=>$row["id"], "goods_id"=>$id ])->count() ? true : false;
                }
            }
        }catch(\Exception $ex){}

        $data["photo"] = array_map(function ($result){
            return Tool::thumb($result["photo"],"",true);
        }, GoodsImageModel::field("path as photo")->where([
            "goods_id"=>$id,
        ])->select()->toArray());

        // 商品规格
        $attr = GoodsAttributeModel::where("goods_id",$id)->select()->toArray();

        $goods_attr = [];
        foreach ($attr as $key=>$val){
            if(!in_array($val["attr_id"],array_keys($goods_attr))){
                $goods_attr[$val["attr_id"]] = [
                    "id"=>$val["attr_id"],
                    "name"=>$val["name"],
                    "list"=>[]
                ];
            }

            $goods_attr[$val["attr_id"]]["list"][$val["attr_data_id"]] = [
                "id"=>$val["attr_data_id"],
                "pid"=>$val["attr_id"],
                "value"=>$val["value"],
                "selected"=>false,
                "disable"=>false
            ];
        }

        $goods_attr = array_values($goods_attr);
        foreach($goods_attr as $key=>$val){
            $goods_attr[$key]['list'] = array_values($val["list"]);

        }

        $data["attr"] = $goods_attr;

        $item = GoodsItemModel::where("goods_id",$id)->select()->toArray();
        $goods_item = [];
        foreach($item as $key=>$val){
            $sku_id = str_replace([",",":"],["_","_"], $val["spec_key"]);
            $goods_item[$sku_id]["key"] = $val["spec_key"];
            $goods_item[$sku_id]["sell_price"] = $val["sell_price"];
            $goods_item[$sku_id]["goods_weight"] = $val["goods_weight"];
            $goods_item[$sku_id]["store_nums"] = $val["store_nums"];
            $goods_item[$sku_id]["goods_no"] = $val["goods_number"];
            $goods_item[$sku_id]["product_id"] = $val["id"];
        }

        $data['item'] = $goods_item;
        $goods["content"] = Tool::replaceContentImage(Tool::removeContentAttr($goods["content"]));

        $data["goods"] = [
            "id"=>$id,
            "title"=>$goods["title"],
            "briefly"=>$goods["briefly"],
            "briefly_color"=>$goods["briefly_color"],
            "photo"=>Tool::thumb($goods["photo"],'medium ',true),
            "sell_price"=>$goods["sell_price"],
            "market_price"=>$goods["market_price"],
            "store_nums"=>$goods["store_nums"],
            "sale"=>$goods["sale"],
            "content"=>$goods["content"]
        ];

        $data["comments"] = [];
        try{
            $comments = CommentsService::getList(["id"=>$id, "type"=>"goods"]);
            $data["comments"] = $comments["data"];
        }catch (\Exception $e){}

        return $data;
    }

    /**
     * 用户收藏操作
     * @param $id
     * @return int
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function favorite($id){
        if(Users::isEmpty("id")){
            throw new \Exception("您还没有登录，请先登录",0);
        }

        if(($row = GoodsModel::where("id",$id)->find()) == false){
            throw new \Exception("非法操作",0);
        }

        $condition = [ "user_id"=>Users::get("id"), "goods_id"=>$id ];
        if(UsersFavoriteModel::where($condition)->count()){
            UsersFavoriteModel::where($condition)->delete();
            return 2;
        }

        UsersFavoriteModel::create([ "user_id"=>Users::get("id"), "goods_id"=>$id, "create_time"=>time() ]);
        return 1;
    }

}