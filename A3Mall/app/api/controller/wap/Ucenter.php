<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\wap;

use mall\utils\Check;
use mall\utils\CString;
use mall\utils\Tool;
use think\facade\Db;
use think\facade\Request;

class Ucenter extends Auth {

    public function favorite(){
        $page = Request::param("page","1","intval");
        $size = 10;

        $count = Db::name("users_favorite")->where([
            "user_id"=>$this->users["id"]
        ])->count();

        $total = ceil($count/$size);
        if($total == $page -1){
            return $this->returnAjax("empty",-1,[]);
        }

        $result = Db::name("users_favorite")
            ->alias("f")
            ->field("g.*,f.id as f_id")
            ->join("goods g","f.goods_id=g.id","LEFT")
            ->where("f.user_id",$this->users["id"])
            ->order("f.id","DESC")
            ->limit((($page - 1) * $size),$size)->select()->toArray();

        $data = [];
        foreach($result as $key=>$value){
            $data[$key] = [
                "id"=>$value["f_id"],
                "title"=>$value["title"],
                "price"=>$value["sell_price"],
                "origin_price"=>$value["market_price"],
                "thumb"=>Tool::thumb($value["photo"],"medium",true),
                "desc"=>CString::msubstr($value["briefly"],100,false),
            ];
        }

        return $this->returnAjax("ok",1,[
            "list"=>$data,
            "page"=>$page,
            "total"=>$total,
            "size"=>$size
        ]);
    }

    public function favorite_delete(){
        $id = Request::param("id","","intval");
        $condition = [
            "user_id"=>$this->users["id"],
            "id"=>$id
        ];

        if(!Db::name("users_favorite")->where($condition)->count()){
            return $this->returnAjax("删除失败，请检查是否连接",0);
        }

        Db::name("users_favorite")->where($condition)->delete();
        return $this->returnAjax("ok",1);
    }

    public function coupon(){
        $type = Request::param("type","1","intval");
        $page = Request::param("page","1","intval");
        $size = 10;

        $condition = '';
        $nowTime = time();
        switch($type){
            case 2:
                $condition = 'u.status=1 || ' . $nowTime . ' > b .end_time';
                break;
            case 1:
            default:
                $condition = 'u.status=0 and b.end_time > ' . $nowTime;
        }

        $count = Db::name("users_bonus")
            ->alias("u")
            ->field("b.*")
            ->join("promotion_bonus b","u.bonus_id=b.id","LEFT")
            ->where($condition)
            ->count();

        $total = ceil($count / $size);
        if($total == $page -1){
            return $this->returnAjax("empty",-1,[]);
        }

        $bonus = Db::name("users_bonus")
            ->alias("u")
            ->field("b.*")
            ->join("promotion_bonus b","u.bonus_id=b.id","LEFT")
            ->where($condition)
            ->order("u.id","DESC")
            ->limit((($page - 1) * $size),$size)
            ->select()->toArray();

        $data = [];
        foreach($bonus as $key=>$value){
            $data[$key] = [
                "name"=>$value["name"],
                "amount"=>number_format($value["amount"]),
                "price"=>$value["order_amount"],
                "end_time"=>date('Y-m-d',$value["end_time"]),
            ];
        }

        return $this->returnAjax("ok",1,[
            "list"=>$data,
            "page"=>$page,
            "total"=>$total,
            "size"=>$size
        ]);
    }

    public function goods(){
        $page = Request::param("page","1","intval");
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
            ->where('status',0)->count();

        $total = ceil($count/$size);
        if($total == $page -1){
            return $this->returnAjax("empty",-1,[]);
        }

        $result = Db::name("goods")
            ->field("id,title,photo,sell_price as price,sale")
            ->where('status',0)
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

    public function point(){
        $page = Request::param("page","1","intval");
        $size = 10;

        $count = Db::name("users_log")->where([
            "user_id"=>$this->users["id"]
        ])->count();

        $total = ceil($count/$size);
        if($total == $page -1){
            return $this->returnAjax("empty",-1,[]);
        }

        $result = Db::name("users_log")
            ->field("operation,point,description,create_time")
            ->where("user_id",$this->users["id"])
            ->where("action",1)
            ->order("id","DESC")
            ->limit((($page - 1) * $size),$size)->select()->toArray();

        $data = [];
        foreach($result as $key=>$value){
            $data[$key] = [
                "point"=>$value["operation"] == 0 ? '+'.$value["point"] : '-'.$value["point"],
                "operation"=>$value["operation"] == 0 ? '增加' : '减少',
                "description"=>$value["description"],
                "time"=>date("Y-m-d H:i:s",$value["create_time"]),
            ];
        }

        return $this->returnAjax("ok",1,[
            "list"=>$data,
            "page"=>$page,
            "total"=>$total,
            "size"=>$size
        ]);
    }

    public function info(){
        $info = \mall\basic\Users::info($this->users["id"]);
        return $this->returnAjax("ok",1,[
            "token"=>$this->token,
            "username"=>$info["username"],
            "nickname"=>$info["nickname"],
            "group_name"=>$info["group_name"],
            "shop_count"=>$info["shop_count"],
            "coupon_count"=>$info["coupon_count"],
            "mobile"=>$info["mobile"],
            "sex"=>$info["sex"],
            "point"=>$info["point"],
            "amount"=>$info["amount"],
            "last_ip"=>$info["last_ip"],
            "last_login"=>$info["last_login"]
        ]);
    }

    public function setting(){
        $post = Request::param();
        if(!Check::chsDash($post["username"])){
            return $this->returnAjax("您填写的昵称不合法",0);
        }

        if(!in_array($post["sex"],['男', '女', '未知'])){
            $post["sex"] = '男';
        }

        $post["sex"] = $post["sex"] == '男' ? 1 : ($post["sex"] == '女' ? 2 : 0);

        if(!preg_match('/\d{4}\-[0-9]{1,2}\-[0-9]{1,2}/is',$post["birthday"])){
            return $this->returnAjax("您填写的日期不合法",0);
        }

        Db::name("users")->where("id",$this->users["id"])->update([
            "nickname"=>$post["username"],
            "sex"=>$post["sex"],
            "birthday"=>strtotime($post["birthday"])
        ]);

        return $this->returnAjax("会员资料更新成功");
    }

    public function address(){
        $id = Request::param("id","","intval");

        if(($row = Db::name("users_address")->where([
                "user_id"=>$this->users["id"],
                "id"=>$id
            ])->find()) == false){
            return $this->returnAjax("address empty",0);
        }

        $extends_info = json_decode($row["extends_info"],true);
        return $this->returnAjax("ok",1,[
            "addressDetail"=>$row["address"],
            "areaCode"=>$extends_info["areaCode"],
            "isDefault"=>$row["is_default"] ? true : false,
            "name"=> $row["accept_name"],
            "tel"=>$row["mobile"],
        ]);
    }

    public function address_list(){
        $data = Db::name("users_address")->where([
            "user_id"=>$this->users["id"]
        ])->select()->toArray();

        $list = [];
        $chosenAddressId = 0;
        foreach($data as $key=>$item){
            if($item["is_default"]){
                $chosenAddressId = $item["id"];
            }

            $area = [];
            foreach([$item["province"],$item["city"],$item["area"]] as $value){
                $area[] = Db::name("area")->where("id",$value)->value("name");
            }

            $list[$key] = [
                "id"=>$item["id"],
                "name"=>$item["accept_name"],
                "tel"=>$item["mobile"],
                "address"=>implode(" ", $area) . $item["address"],
            ];
        }

        return $this->returnAjax("ok",1,[
            "list"=>$list,
            "chosenAddressId"=>$chosenAddressId
        ]);
    }

    public function address_delete(){
        $id = Request::param("id","","intval");

        Db::name("users_address")->where([
            "user_id"=>$this->users["id"],
            "id"=>$id,
        ])->delete();

        return $this->returnAjax("ok");
    }

    public function address_editor(){
        $post = Request::post();
        if(empty($post["name"])){
            return $this->returnAjax("请填写姓名！",0);
        }else if(!Check::chsAlphaNum($post["name"])){
            return $this->returnAjax("您填写的姓名不合法！",0);
        }else if(!Check::mobile($post["tel"])){
            return $this->returnAjax("您填写的手机号码不正确",0);
        }else if(empty($post["addressDetail"])){
            return $this->returnAjax("请填写地址",0);
        }else if(empty($post["province"])){
            return $this->returnAjax("请选择省份",0);
        }else if(empty($post["county"])){
            return $this->returnAjax("请选择地区",0);
        }else if(empty($post["city"])){
            return $this->returnAjax("请选择市",0);
        }else if(empty($post["areaCode"])){
            return $this->returnAjax("请选择所在地区",0);
        }

        $province = Db::name("area")
            ->where("level",1)
            ->where("name","like",'%'.$post["province"].'%')
            ->find();

        $city = Db::name("area")
            ->where("pid",$province["id"])
            ->where("level",2)
            ->where("name","like",'%'.$post["city"].'%')
            ->find();

        $county = Db::name("area")
            ->where("pid",$city["id"])
            ->where("level",3)
            ->where("name","like",'%'.$post["county"].'%')
            ->find();

        DB::startTrans();
        try{
            $is_default = isset($post["is_default"]) ? intval($post["is_default"]) : 0;
            if(empty($post["id"])){
                if($is_default){
                    Db::name("users_address")->where([
                        "user_id"=>$this->users["id"]
                    ])->update([
                        "is_default"=>0
                    ]);
                }

                Db::name("users_address")->insert([
                    "user_id"=>$this->users["id"],
                    "accept_name"=>$post["name"],
                    "mobile"=>$post["tel"],
                    "province"=>$province["id"],
                    "city"=>$city["id"],
                    "area"=>$county["id"],
                    "address"=>$post["addressDetail"],
                    "is_default" => $is_default,
                    "extends_info"=>json_encode(["areaCode"=>$post["areaCode"]],JSON_UNESCAPED_UNICODE),
                    "create_time"=>time()
                ]);
                $lastInsID = Db::name("users_address")->getLastInsID();
            }else{
                Db::name("users_address")
                    ->where("id",intval($post["id"]))
                    ->where("user_id",$this->users["id"])
                    ->update([
                        "accept_name"=>$post["name"],
                        "mobile"=>$post["tel"],
                        "province"=>$province["id"],
                        "city"=>$city["id"],
                        "area"=>$county["id"],
                        "address"=>$post["addressDetail"],
                        "is_default" => $is_default,
                        "extends_info"=>json_encode(["areaCode"=>$post["areaCode"]],JSON_UNESCAPED_UNICODE),
                        "create_time"=>time()
                    ]);
                $lastInsID = $post["id"];
            }

            DB::commit();
        }catch (\Exception $e){
            DB::rollback();
            return $this->returnAjax("操作失败，请稍后在试",0);
        }

        return $this->returnAjax("操作成功",1,$lastInsID);
    }

    public function help(){
        return $this->returnAjax("ok",1, array_map(function($res){
            $res["content"] = Tool::replaceContentImage(Tool::removeContentAttr($res["content"]));
            return $res;
        },$archives = Db::name("archives")->where([
            "pid"=>"70"
        ])->select()->toArray()));
    }

}