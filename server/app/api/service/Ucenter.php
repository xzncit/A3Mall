<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\service;

use app\common\library\utils\Image;
use app\common\models\Setting as SettingModel;
use mall\basic\Users;
use mall\utils\Check;
use mall\utils\CString;
use mall\utils\Tool;
use think\facade\Config;
use app\common\exception\BaseException;
use app\common\models\users\Users as UsersModel;
use app\common\models\users\UsersLog as UsersLogModel;
use app\common\models\users\UsersBonus as UsersBonusModel;
use app\common\models\users\UsersFavorite as UsersFavoriteModel;
use app\common\models\StoreUsers as StoreUsersModel;
use app\common\models\order\Order as OrderModel;
use app\common\models\users\UsersRechange as UsersRechangeModel;
use app\common\models\Archives as ArchivesModel;
use app\common\models\users\UsersWithdrawLog as UsersWithdrawLogModel;
use app\admin\service\common\Uploadfiy as UploadfiyService;

class Ucenter extends Service {

    /**
     * 获取收藏列表数据
     * @param $data
     * @return array
     */
    public static function getFavoriteList($data){
        $size = Config::get("website.pageSize");
        $page = $data["page"]??1;

        $count = UsersFavoriteModel::where([ "user_id"=>Users::get("id") ])->count();
        $result = UsersFavoriteModel::alias("f")->field("g.*,f.id as f_id")->join("goods g","f.goods_id=g.id","LEFT")->where("f.user_id",Users::get("id"))->order("f.id","DESC")->page($page,$size)->select()->toArray();

        $array = [ "list"=>[], "page"=>$page, "total"=>0, "size"=>$size ];
        $total = ceil($count / $size);
        $array["total"] = $total;
        if($total == $page -1){
            throw new BaseException("没有数据了哦！",-1,$array);
        }

        $list = [];
        foreach($result as $key=>$value){
            $list[$key] = [
                "id"=>$value["f_id"],
                "title"=>$value["title"],
                "price"=>$value["sell_price"],
                "origin_price"=>$value["market_price"],
                "thumb"=>Tool::thumb($value["photo"],"medium",true),
                "desc"=>CString::msubstr($value["briefly"],100,false),
            ];
        }

        $array["list"] = $list;
        return $array;
    }

    /**
     * 删除收藏商品
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function favoriteDelete($id){
        $id = array_map("intval",explode(",",$id));

        if(!UsersFavoriteModel::where("user_id",Users::get("id"))->where("id","in",$id)->count()){
            throw new \Exception("删除失败，请稍后在试",0);
        }

        UsersFavoriteModel::where("user_id",Users::get("id"))->where("id","in",$id)->delete();
        return true;
    }

    /**
     * 获取优惠劵
     * @param $data
     * @return array
     */
    public static function getCouponList($data){
        $size = Config::get("website.pageSize");
        $page = $data["page"]??1;
        $type = $data["type"]??1;

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

        $count = UsersBonusModel::alias("u")->field("b.*")->join("promotion_bonus b","u.bonus_id=b.id","LEFT")->where($condition)->where("u.user_id",Users::get("id"))->count();
        $bonus = UsersBonusModel::alias("u")->field("b.*")->join("promotion_bonus b","u.bonus_id=b.id","LEFT")->where($condition)->where("u.user_id",Users::get("id"))->order("u.id","DESC")->page($page,$size)->select()->toArray();

        $array = [ "list"=>[], "page"=>$page, "total"=>0, "size"=>$size ];
        $total = ceil($count / $size);
        $array["total"] = $total;
        if($total == $page -1){
            throw new BaseException("没有数据了哦！",-1,$array);
        }

        $list = [];
        foreach($bonus as $key=>$value){
            $list[$key] = [
                "name"=>$value["name"],
                "amount"=>number_format($value["amount"]),
                "price"=>$value["order_amount"],
                "end_time"=>date('Y-m-d',$value["end_time"]),
            ];
        }

        $array["list"] = $list;
        return $array;
    }

    /**
     * 获取会员积分数据
     * @param $data
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getPointList($data){
        $size = Config::get("website.pageSize");
        $page = $data["page"]??1;

        $count = UsersLogModel::where([ "user_id"=>Users::get("id") ])->count();
        $result = UsersLogModel::field("operation,point,description,create_time")->where("user_id",Users::get("id"))->where("action",1)->order("id","DESC")->page($page,$size)->select()->toArray();

        $array = [ "list"=>[], "page"=>$page, "total"=>0, "size"=>$size ];
        $total = ceil($count / $size);
        $array["total"] = $total;
        if($total == $page -1){
            throw new BaseException("没有数据了哦！",-1,$array);
        }

        $list = [];
        foreach($result as $key=>$value){
            $list[$key] = [
                "point"=>$value["operation"] == 0 ? '+' . $value["point"] : '-' . $value["point"],
                "operation"=>$value["operation"] == 0 ? '增加' : '减少',
                "description"=>$value["description"],
                "time"=>$value["create_time"],
            ];
        }

        $array["list"] = $list;
        return $array;
    }

    /**
     * 获取会员资料
     * @return array
     * @throws \Exception
     */
    public static function getUsersInfo(){
        $info = Users::info(Users::get("id"));
        return [
            "username"=>$info["username"],
            "nickname"=>$info["nickname"],
            "mobile"=>$info["mobile"],
            "coupon_count"=>$info["coupon_count"],
            "amount"=>$info["amount"],
            "avatar"=>Image::avatar($info["avatar"]),
            "spread"=>$info["is_spread"],
            "shop"=>StoreUsersModel::where("user_id",Users::get("id"))->count(),
            "order_count"=>[
                "a"=>OrderModel::where(["status"=>1,"pay_status"=>0,"user_id"=>Users::get("id")])->count(),
                "b"=>OrderModel::where(["status"=>2,"pay_status"=>1,"user_id"=>Users::get("id")])->where('distribution_status','=','0')->count(),
                "c"=>OrderModel::where(["status"=>2,"pay_status"=>1,"user_id"=>Users::get("id")])->where('distribution_status','in','1,2')->count(),
                "d"=>OrderModel::where(["status"=>5,"pay_status"=>1,"delivery_status"=>1,"user_id"=>Users::get("id")])->where('evaluate_status','in','0,2')->count()
            ]
        ];
    }

    /**
     * 我的钱包
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function wallet(){
        $info = Users::info(Users::get("id"));
        $config = SettingModel::getArrayData("wemini_base");
        return [
            "switch_1"=>$config["is_rechange"] == 0 ? 1 : 0,
            "switch_2"=>$config["is_withdrawal"] == 0 ? 1 : 0,
            "amount"=>$info["amount"],
            "recharge_amount"=>UsersRechangeModel::where("user_id",Users::get("id"))->where("status",1)->sum("order_amount"),
            "consume_amount"=>OrderModel::where("user_id",Users::get("id"))->where("status",5)->sum("order_amount")
        ];
    }

    /**
     * 获取会员资料
     * @return array
     * @throws \Exception
     */
    public static function getSetting(){
        $info = Users::info(Users::get("id"));
        return [
            "nickname"=>$info["nickname"],
            "birthday"=>date("Y-m-d",$info["birthday"]),
            "sex"=>$info["sex"],
            "avatar"=>Image::avatar($info["avatar"])
        ];
    }

    /**
     * 保存会员设置
     * @param $post
     * @return bool
     * @throws \Exception
     */
    public static function settingSave($post){
        if(!Check::chsDash($post["username"])){
            throw new \Exception("您填写的昵称不合法",0);
        }

        if(!in_array($post["sex"],['男', '女', '未知'])){
            $post["sex"] = '男';
        }

        $post["sex"] = $post["sex"] == '男' ? 1 : ($post["sex"] == '女' ? 2 : 0);

        if(!preg_match('/\d{4}\-[0-9]{1,2}\-[0-9]{1,2}/is',$post["birthday"])){
            throw new \Exception("您填写的日期不合法",0);
        }

        UsersModel::where("id",Users::get("id"))->update([ "nickname"=>$post["username"], "sex"=>$post["sex"], "birthday"=>strtotime($post["birthday"]) ]);
        return true;
    }

    /**
     * 获取帮助内容
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getHelpList(){
        return array_map(function($res){
            $res["content"] = Tool::replaceContentImage(Tool::removeContentAttr($res["content"]));
            return $res;
        },$archives = ArchivesModel::where([ "pid"=>"60" ])->select()->toArray());
    }

    /**
     * 资金明细
     * @param $data
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getFundList($data){
        $size = Config::get("website.pageSize");
        $page = $data["page"]??1;

        $count = UsersLogModel::where("user_id",Users::get("id"))->where("action","in",[0,3,4])->count();
        $result = UsersLogModel::where([
            ["user_id","=",Users::get("id")],
            ["action","in",[0,3,4]]
        ])->page($page,$size)->order('id','DESC')->select()->toArray();

        $array = [ "list"=>[], "page"=>$page, "total"=>0, "size"=>$size ];
        $total = ceil($count / $size);
        $array["total"] = $total;
        if($total == $page -1){
            throw new BaseException("没有数据了哦！",-1,$array);
        }

        $list = [];
        foreach($result as $key=>$value){
            $list[$key]["action"] = $value["action"] == 0 ? "金额" : ( $value["action"] == 3 ? "退款" : "佣金" );
            $list[$key]["operation"] = $value["operation"] == 0 ? "+" : "-";
            $list[$key]["description"] = $value["description"];
            $list[$key]["amount"] = $value["amount"];
            $list[$key]['time'] = $value["create_time"];
        }

        $array["list"] = $list;
        return $array;
    }

    /**
     * 提现记录
     * @param $data
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getCashList($data){
        $size = Config::get("website.pageSize");
        $page = $data["page"]??1;

        $count = UsersWithdrawLogModel::where("user_id",Users::get("id"))->where("withdraw_type","1")->count();
        $result = UsersWithdrawLogModel::where("user_id",Users::get("id"))->where("withdraw_type","1")->page($page,$size)->order('id',"DESC")->select()->toArray();

        $array = [ "list"=>[], "page"=>$page, "total"=>0, "size"=>$size ];
        $total = ceil($count / $size);
        $array["total"] = $total;
        if($total == $page -1){
            throw new BaseException("没有数据了哦！",-1,$array);
        }

        $list = [];
        $status = ["0"=>"审核中","1"=>"已提现","2"=>"未通过"];
        foreach($result as $key=>$value){
            $list[$key]["description"] = $value["msg"];
            $list[$key]["amount"] = $value["price"];
            $list[$key]['status'] = $value["status"];
            $list[$key]['text'] = $status[$value["status"]];
            $list[$key]['time'] = $value["create_time"];
        }

        $array["list"] = $list;
        return $array;
    }

    public static function rechange(){

    }

    /**
     * 获取充值金额
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function rechangePrice(){
        $data = SettingModel::getArrayData("order_rechange");
        $array = [];
        if(!empty($data["list"])){
            foreach($data["list"] as $key=>$value){
                $array[$key] = [
                    "checked"=> $key==0,
                    "money"=>$value["num"]
                ];
            }

            $array[] = [
                "checked"=>false,
                "money"=>"其他"
            ];
        }

        return $array;
    }

    /**
     * 提现配置
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function settlement(){
        $setting = SettingModel::getArrayData("users");
        $setting["bank"] = explode("|",$setting["bank"]);
        return [ "bank"=>$setting["bank"], "money"=>Users::get("amount") ];
    }

    /**
     * 提交提现申请
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function settlementSave($data){
        $setting = SettingModel::getArrayData("users");

        if(UsersWithdrawLogModel::where(["user_id"=>Users::get("id"),"status"=>0,"withdraw_type"=>1])->count()){
            throw new \Exception("您还有提现申请未处理。",0);
        }

        if(empty($data["name"])){
            throw new \Exception("请填写持卡人。",0);
        }

        if(empty($data["code"])){
            throw new \Exception("请填写卡号。",0);
        }

        if(empty($data["price"])){
            throw new \Exception("请填写金额。",0);
        }

        if($data["price"] < $setting["amount"]){
            throw new \Exception("提现金额不能小于" . $setting["amount"],0);
        }

        if(Users::get("amount") < $data["price"]){
            throw new \Exception("提现失败，您的余额不足",0);
        }

        UsersWithdrawLogModel::create([ "user_id"=>Users::get("id"), "withdraw_type"=>1, "bank_name"=>$data["bank_type"], "bank_real_name"=>$data["name"], "type"=>1, "code"=>$data["code"], "price"=>$data["price"], "status"=>0, "create_time"=>time() ]);
        return true;
    }

    /**
     * 上传头像
     * @return string
     * @throws \Exception
     */
    public static function upload(){
        try{
            $fileInfo = UploadfiyService::upload("file",false,"public",["jpg","png","gif","jpeg","bmp"]);
            $path = '/'.trim($fileInfo["path"],"/");
            UsersModel::where(["id"=>Users::get("id")])->save([ 'avatar'=> $path ]);
            return Tool::thumb($path,'',true);
        }catch (\Exception $ex){
            throw new \Exception($ex->getMessage(),$ex->getCode());
        }
    }

}