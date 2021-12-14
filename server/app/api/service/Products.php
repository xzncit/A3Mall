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
use app\common\models\goods\GoodsExtends as GoodsExtendsModel;
use mall\utils\Tool;
use think\facade\Config;

class Products extends Service {

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
                $order = 'g.sell_price';
                $text = $sort == 1 ? "ASC" : "DESC";
                break;
            case '1':
                $order = 'g.sale';
                $text = 'DESC';
                break;
            case '0':
            default :
                $order = 'g.id';
                $text = 'DESC';
                break;
        }

        return [ "field"=>$order, "order"=>$text ];
    }

    /**
     * 获取商品数据
     * @param $data
     * @param array $condition
     * @return array
     * @throws BaseException
     */
    public static function getList($data,$condition=[]){
        $sort = self::getOrder($data);
        $size = Config::get("website.pageSize");
        $page = $data["page"]??1;

        $count = GoodsExtendsModel::alias("e")->join("goods g","e.goods_id=g.id","LEFT")->where($condition)->count();
        $result = GoodsExtendsModel::field("g.id,g.title,g.photo,g.sell_price as price,g.sale")->alias("e")->join("goods g","e.goods_id=g.id","LEFT")->where($condition)->order($sort["field"],$sort["order"])->page($page,$size)->select()->toArray();

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

}