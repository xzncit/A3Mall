<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\wap;

use mall\basic\Area;
use mall\basic\Payment;
use mall\utils\Check;
use mall\utils\CString;
use mall\utils\Tool;
use think\exception\ValidateException;
use think\facade\Db;
use think\facade\Filesystem;
use think\facade\Request;
use mall\basic\Users;
use think\Image;

class Ucenter extends Base {

    public function favorite(){
        $page = Request::param("page","1","intval");
        $size = 10;

        $count = Db::name("users_favorite")->where([
            "user_id"=>Users::get("id")
        ])->count();

        $total = ceil($count/$size);
        if($total == $page -1){
            return $this->returnAjax("empty",-1,[]);
        }

        $result = Db::name("users_favorite")
            ->alias("f")
            ->field("g.*,f.id as f_id")
            ->join("goods g","f.goods_id=g.id","LEFT")
            ->where("f.user_id",Users::get("id"))
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
        $id = Request::param("id","");
        $id = array_map("intval",explode(",",$id));

        if(!Db::name("users_favorite")->where("user_id",Users::get("id"))->where("id","in",$id)->count()){
            return $this->returnAjax("删除失败，请检查是否连接",0);
        }

        Db::name("users_favorite")->where("user_id",Users::get("id"))->where("id","in",$id)->delete();
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
            return $this->returnAjax("empty",-1,[
                "list"=>[],
                "page"=>$page,
                "total"=>$total,
                "size"=>$size
            ]);
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
            ->where('status',0)->count();

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
            "user_id"=>Users::get("id")
        ])->count();

        $total = ceil($count/$size);
        if($total == $page -1){
            return $this->returnAjax("empty",-1,[
                "list"=>[],
                "page"=>$page,
                "total"=>$total,
                "size"=>$size
            ]);
        }

        $result = Db::name("users_log")
            ->field("operation,point,description,create_time")
            ->where("user_id",Users::get("id"))
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
            "size"=>$size,
            "point"=>Users::get("point")
        ]);
    }

    public function info(){
        $info = Users::info(Users::get("id"));
        return $this->returnAjax("ok",1,[
            "username"=>$info["username"],
            "nickname"=>$info["nickname"],
            "mobile"=>$info["mobile"],
            "coupon_count"=>$info["coupon_count"],
            "amount"=>$info["amount"],
            "avatar"=>Users::avatar($info["avatar"]),
            "order_count"=>[
                "a"=>Db::name("order")->where(["status"=>1,"pay_status"=>0,"user_id"=>Users::get("id")])->count(),
                "b"=>Db::name("order")->where(["status"=>2,"pay_status"=>1,"user_id"=>Users::get("id")])->where('distribution_status','=','0')->count(),
                "c"=>Db::name("order")->where(["status"=>2,"pay_status"=>1,"user_id"=>Users::get("id")])->where('distribution_status','in','1,2')->count(),
                "d"=>Db::name("order")->where(["status"=>5,"pay_status"=>1,"delivery_status"=>1,"user_id"=>Users::get("id")])->where('evaluate_status','in','0,2')->count()
            ]
        ]);
    }

    public function wallet(){
        $info = Users::info(Users::get("id"));
        return $this->returnAjax("ok",1,[
            "amount"=>$info["amount"],
            "recharge_amount"=>Db::name("users_rechange")->where("user_id",Users::get("id"))->where("status",1)->sum("order_amount"),
            "consume_amount"=>Db::name("order")->where("user_id",Users::get("id"))->where("status",5)->sum("order_amount")
        ]);
    }

    public function get_setting(){
        $info = Users::info(Users::get("id"));
        return $this->returnAjax("ok",1,[
            "nickname"=>$info["nickname"],
            "birthday"=>date("Y-m-d",$info["birthday"]),
            "sex"=>$info["sex"],
            "avatar"=>Users::avatar($info["avatar"])
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

        Db::name("users")->where("id",Users::get("id"))->update([
            "nickname"=>$post["username"],
            "sex"=>$post["sex"],
            "birthday"=>strtotime($post["birthday"])
        ]);

        return $this->returnAjax("会员资料更新成功");
    }

    public function address(){
        $id = Request::param("id","","intval");

        if(($row = Db::name("users_address")->where([
                "user_id"=>Users::get("id"),
                "id"=>$id
            ])->find()) == false){
            return $this->returnAjax("address empty",0);
        }

        $extends_info = json_decode($row["extends_info"],true);
        return $this->returnAjax("ok",1,[
            "areaCode"=>$extends_info["areaCode"],
            "isDefault"=>$row["is_default"] ? true : false,
            "name"=> $row["accept_name"],
            "tel"=>$row["mobile"],
            "addressDetail"=>$row["address"],
            "province"=>$row["province"],
            "county"=>$row["city"],
            "city"=>$row["area"],
            "area_name"=>Area::get_area([$row["province"],$row["city"],$row["area"]],',')
        ]);
    }

    public function address_list(){
        $data = Db::name("users_address")->where([
            "user_id"=>Users::get("id")
        ])->select()->toArray();

        $list = [];
        foreach($data as $key=>$item){

            $area = [];
            foreach([$item["province"],$item["city"],$item["area"]] as $value){
                $area[] = Db::name("area")->where("id",$value)->value("name");
            }

            $list[$key] = [
                "id"=>$item["id"],
                "is_default"=>$item['is_default'],
                "name"=>$item["accept_name"],
                "tel"=>$item["mobile"],
                "address"=>implode(" ", $area) . $item["address"]
            ];
        }

        return $this->returnAjax("ok",1,$list);
    }

    public function address_delete(){
        $id = Request::param("id","","intval");

        Db::name("users_address")->where([
            "user_id"=>Users::get("id"),
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
        }else if(empty($post["city"])){
            return $this->returnAjax("请选择市",0);
        }else if(empty($post["county"])){
            return $this->returnAjax("请选择区",0);
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

        if(empty($province) || empty($city)){
            return $this->returnAjax("您选择的地址不存在",0);
        }

        DB::startTrans();
        try{
            $is_default = isset($post["is_default"]) ? intval($post["is_default"]) : 0;
            if($is_default){
                Db::name("users_address")->where(["user_id"=>Users::get("id")])->update(["is_default"=>0]);
            }
            if(empty($post["id"])){
                Db::name("users_address")->insert([
                    "user_id"=>Users::get("id"),
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
                    ->where("user_id",Users::get("id"))
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

    public function fund(){
        $page = Request::param("page","1","intval");
        $size = 10;

        $count = Db::name("users_log")
            ->where("user_id",Users::get("id"))
            ->where("action","0")
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

        $data = Db::name("users_log")
            ->where("user_id",Users::get("id"))
            ->where("action","0")
            ->order('id','DESC')->select()->toArray();

        $list = [];
        foreach($data as $key=>$value){
            $list[$key]["description"] = $value["description"];
            $list[$key]["amount"] = $value["amount"];
            $list[$key]['time'] = date("Y-m-d H:i:s",$value["create_time"]);
        }

        return $this->returnAjax("ok",1,[
            "list"=>$list,
            "page"=>$page,
            "total"=>$total,
            "size"=>$size
        ]);
    }

    public function cashlist(){
        $page = Request::param("page","1","intval");
        $size = 10;

        $count = Db::name("users_withdraw_log")
            ->where("user_id",Users::get("id"))
            ->where("withdraw_type","1")
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

        $data = Db::name("users_withdraw_log")
            ->where("user_id",Users::get("id"))
            ->where("withdraw_type","1")
            ->order('id DESC')->select()->toArray();

        $list = [];
        $status = ["0"=>"审核中","1"=>"己提现","2"=>"未通过"];
        foreach($data as $key=>$value){
            $list[$key]["description"] = $value["msg"];
            $list[$key]["amount"] = $value["price"];
            $list[$key]['status'] = $value["status"];
            $list[$key]['text'] = $status[$value["status"]];
            $list[$key]['time'] = date("Y-m-d H:i:s",$value["create_time"]);
        }

        return $this->returnAjax("ok",1,[
            "list"=>$list,
            "page"=>$page,
            "total"=>$total,
            "size"=>$size
        ]);
    }

    public function rechange(){
        $payment = Request::post("payment","","trim,strip_tags");
        $source = Request::post("source","","intval");
        $price = Request::post("price/f","0");

        $payment = $source == 2 ? "wechat" : "wechat-h5";

        try{
            $rs = Payment::rechang($payment,$price);
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode());
        }

        return $this->returnAjax('ok',1,$rs);
    }

    public function settlement(){
        $setting = Db::name("setting")->where(["name"=>"users"])->value("value");
        $setting = json_decode($setting,true);
        $setting["bank"] = explode("|",$setting["bank"]);
        return $this->returnAjax("ok",1,[
            "bank"=>$setting["bank"],
            "money"=>Users::get("amount")
        ]);
    }

    public function settlement_save(){
        $data = Request::post();
        $setting = Db::name("setting")->where(["name"=>"users"])->value("value");
        $setting = json_decode($setting,true);
        if(Db::name("users_withdraw_log")->where(["user_id"=>Users::get("id"),"status"=>0,"withdraw_type"=>1])->count()){
            return $this->returnAjax("您还有提现申请未处理。",0);
        }

        if(empty($data["name"])){
            return $this->returnAjax("请填写持卡人。",0);
        }

        if(empty($data["code"])){
            return $this->returnAjax("请填写卡号。",0);
        }

        if(empty($data["price"])){
            return $this->returnAjax("请填写金额。",0);
        }

        if($data["price"] < $setting["amount"]){
            return $this->returnAjax("提现金额不能小于" . $setting["amount"],0);
        }

        Db::name("users_withdraw_log")->insert([
            "user_id"=>Users::get("id"),
            "withdraw_type"=>1,
            "bank_name"=>$data["bank_type"],
            "bank_real_name"=>$data["name"],
            "type"=>1,
            "code"=>$data["code"],
            "price"=>$data["price"],
            "status"=>0,
            "create_time"=>time()
        ]);

        return $this->returnAjax("申请提现成功，请等待管理员审核");
    }

    public function avatar() {
        $file = Request::file('file');
        $isthumb = Request::param("isthumb","1","int");
        try {
            if(!in_array($file->extension(),["jpg","png","gif","jpeg","bmp"])){
                return $this->returnAjax("您所选择的文件不允许上传。",0);
            }

            $dir = "uploads";
            $uploadFile = Filesystem::putFile( 'images', $file);

            //生成缩略图
            $thumb = $dir . '/' . $uploadFile;
            $image = Image::open($thumb);
            $image->thumb(80, 80)->save($thumb);

            $users = Db::name("users")->where(["id"=>Users::get("id")])->find();
            if($users['avatar']){
                $path = trim($users['avatar'],'/');
                file_exists($path) && unlink($path);
            }

            Db::name("users")->where(["id"=>Users::get("id")])->update([
                'avatar'=>'/' . $thumb
            ]);

            return $this->returnAjax("ok",1,Tool::thumb('/'.trim($thumb,"/"),'',true));
        } catch (ValidateException $e) {
            //return $this->returnAjax($e->getMessage(),0);
        }

        return $this->returnAjax("上传参数错误",0);
    }

}