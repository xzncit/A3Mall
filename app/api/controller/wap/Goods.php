<?php
namespace app\api\controller\wap;

use think\facade\Db;
use think\facade\Request;
use mall\utils\Tool;
use mall\utils\BC;

class Goods extends Auth {

    public function index(){
        $page = Request::param("page","1","intval");
        $id = Request::param("id","","intval");
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
            ->where('status',0)->where("cat_id",$id)
            ->count();

        $total = ceil($count/$size);
        if($total == $page -1){
            return $this->returnAjax("empty",-1,[]);
        }

        $result = Db::name("goods")
            ->field("id,title,photo,sell_price as price,sale")
            ->where('status',0)->where("cat_id",$id)
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

    public function view(){
        $id = Request::param("id","0","intval");
        if(($goods = Db::name("goods")->where("id",$id)->where("status",0)->find()) == false){
            return $this->returnAjax("商品不存在",0);
        }

        $data = [];
        $data["collect"] = false;
        if(!empty($this->users)){
            $data["collect"] = Db::name("users_favorite")->where([
                "user_id"=>$this->users["id"],
                "goods_id"=>$id
            ])->count() ? true : false;
        }

        $data["photo"] = array_map(function ($result){
            return Tool::thumb($result["photo"],"",true);
        }, Db::name("attachments")->field("path as photo")->where([
            "pid"=>$id,
            "module"=>"goods",
            "method"=>"photo"
        ])->select()->toArray());


        $goods_attribute = Db::name("goods_attribute")->where([
            "goods_id"=>$id
        ])->select()->toArray();

        if(!empty($goods_attribute)){
            $attribute = [];
            foreach($goods_attribute as $key=>$val){
                if(empty($attribute[$val["attr_id"]])){
                    $attribute[$val["attr_id"]]['k'] = $val["name"];
                }
                $attribute[$val["attr_id"]]['v'][] = [
                    "id"=>$val["attr_id"].":".$val["attr_data_id"],
                    "name"=>$val["value"],
                ];
            }

            $goodsItem = Db::name("goods_item")->where([
                "goods_id"=>$id
            ])->select()->toArray();

            $sku = [];
            $i=0;
            foreach($attribute as $key=>$value){
                $value["k_s"] = 's' . $i++;
                $sku[] = $value;
            }

            /**
            sku: {
            // 所有sku规格类目与其值的从属关系，比如商品有颜色和尺码两大类规格，颜色下面又有红色和蓝色两个规格值。
            // 可以理解为一个商品可以有多个规格类目，一个规格类目下可以有多个规格值。
            tree: [
            {
            k: '颜色', // skuKeyName：规格类目名称
            v: [
            {
            id: '30349', // skuValueId：规格值 id
            name: '红色', // skuValueName：规格值名称
            },
            {
            id: '1215',
            name: '蓝色',
            }
            ],
            k_s: 's1' // skuKeyStr：sku 组合列表（下方 list）中当前类目对应的 key 值，value 值会是从属于当前类目的一个规格值 id
            },
            {
            k: '大小', // skuKeyName：规格类目名称
            v: [
            {
            id: '303491', // skuValueId：规格值 id
            name: '红色', // skuValueName：规格值名称
            },
            {
            id: '12152',
            name: '蓝色',
            }
            ],
            k_s: 's2' // skuKeyStr：sku 组合列表（下方 list）中当前类目对应的 key 值，value 值会是从属于当前类目的一个规格值 id
            }
            ],
            // 所有 sku 的组合列表，比如红色、M 码为一个 sku 组合，红色、S 码为另一个组合
            list: [
            {
            id: 2259, // skuId，下单时后端需要
            price: 100, // 价格（单位分）
            s1: '1215', // 规格类目 k_s 为 s1 的对应规格值 id
            s2: '12152', // 规格类目 k_s 为 s2 的对应规格值 id
            s3: '0', // 最多包含3个规格值，为0表示不存在该规格
            stock_num: 110 // 当前 sku 组合对应的库存
            },
            {
            id: 2260, // skuId，下单时后端需要
            price: 1100, // 价格（单位分）
            s1: '30349', // 规格类目 k_s 为 s1 的对应规格值 id
            s2: '303491', // 规格类目 k_s 为 s2 的对应规格值 id
            s3: '0', // 最多包含3个规格值，为0表示不存在该规格
            stock_num: 10 // 当前 sku 组合对应的库存
            }
            ],
            price: '1.00', // 默认价格（单位元）
            stock_num: 227, // 商品总库存
            collection_id: 2261, // 无规格商品 skuId 取 collection_id，否则取所选 sku 组合对应的 id
            none_sku: false, // 是否无规格商品
            hide_stock: false // 是否隐藏剩余库存
            }
             */
            $item = [];
            foreach($goodsItem as $key=>$value){
                $item[$key]['id'] = $value["id"];
                $item[$key]['price'] = BC::mul(100,$value["sell_price"],2);
                $arr = explode(",",$value["spec_key"]);
                foreach($sku as $k=>$v){
                    $item[$key][$v["k_s"]] = $arr[$k];
                }
                $item[$key]['stock_num'] = $value["store_nums"];
            }

            $data["sku"]["tree"] = $sku;
            $data["sku"]["list"] = $item;
        }else{
            $data["sku"]["tree"] = [];
            $data["sku"]["list"] = [];
        }

        $data["sku"]["price"] = $goods["sell_price"];
        $data["sku"]["stock_num"] = $goods["store_nums"];
        $data["sku"]["collection_id"] = $goods["id"];
        $data["sku"]["none_sku"] = empty($goods_attribute) ? true : false;
        $data["sku"]["hide_stock"] = false;

        $goods["content"] = Tool::replaceContentImage(Tool::removeContentAttr($goods["content"]));

        $data["goods"] = [
            "title"=>$goods["title"],
            "photo"=>Tool::thumb($goods["photo"],'medium ',true),
            "sell_price"=>$goods["sell_price"],
            "market_price"=>$goods["market_price"],
            "store_nums"=>$goods["store_nums"],
            "sale"=>$goods["sale"],
            "content"=>$goods["content"]
        ];


        return $this->returnAjax("ok",1,$data);
    }

    public function favorite(){
        $id = Request::param("id","","intval");
        if(empty($this->users)){
            return $this->returnAjax("您还没有登录，请先登录",0);
        }

        if(($row = Db::name("goods")->where("id",$id)->find()) == false){
            return $this->returnAjax("非法操作",0);
        }

        $condition = [
            "user_id"=>$this->users["id"],
            "goods_id"=>$id
        ];

        if(Db::name("users_favorite")->where($condition)->count()){
            Db::name("users_favorite")->where($condition)->delete();
            return $this->returnAjax("取消成功",1,'2');
        }

        Db::name("users_favorite")->insert([
            "user_id"=>$this->users["id"],
            "goods_id"=>$id,
            "create_time"=>time()
        ]);

        return $this->returnAjax("收藏成功",1,1);
    }

}