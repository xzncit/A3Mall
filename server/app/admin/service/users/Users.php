<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\users;

use mall\utils\Tool;
use think\facade\Session;
use app\admin\service\Service;
use app\common\models\users\UsersGroup as UsersGroupModel;
use app\common\models\users\UsersTags as UsersTagsModel;
use app\admin\model\Users as UsersModel;
use app\common\models\users\UsersAddress as UsersAddressModel;
use app\common\models\Area as AreaModel;
use app\admin\model\users\UsersFavorite as UsersFavoriteModel;
use app\common\models\users\UsersLog as UsersLogModel;
use app\common\models\users\UsersBonus as UsersBonusModel;
use app\common\models\wechat\WechatUsers as WechatUsersModel;
use app\common\models\users\UsersComment as UsersCommentModel;
use app\common\models\users\UsersConsult as UsersConsultModel;
use app\common\models\users\UsersRechange as UsersRechangeModel;
use app\common\models\users\UsersWithdrawLog as UsersWithdrawLogModel;
use app\common\models\order\Order as OrderModel;
use app\common\models\order\OrderGoods as OrderGoodsModel;
use app\common\models\order\OrderLog as OrderLogModel;
use app\common\models\order\OrderCollection as OrderCollectionModel;
use app\common\models\order\OrderDelivery as OrderDeliveryModel;
use app\common\models\order\OrderRefundment as OrderRefundmentModel;
use app\common\models\order\OrderGroup as OrderGroupModel;

class Users extends Service {

    /**
     * 获取会员等级分组和会员tag
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getSearchData(){
        return [
            "cat"=>UsersGroupModel::select()->toArray(),
            "tags"=>UsersTagsModel::select()->toArray()
        ];
    }

    /**
     * 获取列表数据
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data,$condition=[]){
        $count = UsersModel::withSearch(["group_id","username"],[
            'group_id'=>$data['key']["cat_id"]??'',
            'username'=>$data['key']["title"]??''
        ])->withJoin("group")->where($condition)->count();
        $result = UsersModel::withSearch(["group_id","username"],[
            'group_id'=>$data['key']["cat_id"]??'',
            'username'=>$data['key']["title"]??''
        ])->withJoin("group")->where($condition)->page($data["page"]??1,$data["limit"]??10)->order("users.id","desc")->select()->toArray();

        return [ "count"=>$count, "data"=>array_map(function ($res){
            $res["nickname"] = getUserName($res);
            $tags = UsersTagsModel::where('id','in',$res['tags'])->select()->toArray();
            $arr = [];
            foreach($tags as $val){
                $arr[] = $val['name'];
            }

            $res['tags'] = implode(",",$arr);
            return $res;
        },$result) ];
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
        $row = UsersModel::where("id",$id)->find();
        return ["cat"=>UsersGroupModel::select()->toArray(), "data"=>$row??[] ];
    }

    /**
     * 保存会员数据
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public static function save($data){
        if(UsersModel::where("id",$data["id"])->count()){
            if(!empty($data["password"]) || !empty($data["confirm_password"])){
                if($data["password"] != $data["confirm_password"]){
                    throw new \Exception("您输入的两次密码不致。",0);
                }

                $data["password"] = md5($data["password"]);
            }else{
                unset($data['password'],$data['confirm_password']);
            }

            if(UsersModel::where("username",$data["username"])->where("id","<>",$data["id"])->count()){
                throw new \Exception("该用户名已存在，请更换用户名。",0);
            }

            UsersModel::where("id",$data["id"])->save($data);
        }else{
            if(empty($data["password"])){
                throw new \Exception("请填写密码",0);
            }else if(empty($data["confirm_password"])){
                throw new \Exception("请填写确认密码",0);
            }else if($data["password"] != $data["confirm_password"]){
                throw new \Exception("您输入的两次密码不致。",0);
            }

            if(UsersModel::where("username",$data["username"])->count()){
                throw new \Exception("该用户名已存在，请更换用户名。",0);
            }

            $data["password"] = md5($data["password"]);
            UsersModel::create($data);
        }

        return true;
    }

    /**
     * 删除
     * @param array|string $ids
     * @return bool
     * @throws \Exception
     */
    public static function delete($ids){
        try{
            UsersModel::startTrans();

            $array = array_map("intval",explode(",",$ids));
            foreach($array as $id) {
                $row = UsersModel::where('id', $id)->find();
                if (empty($row)) {
                    continue;
                }

                UsersModel::where("id",$id)->delete();
                UsersAddressModel::where("user_id", $id)->delete();
                WechatUsersModel::where("user_id", $id)->delete();
                UsersBonusModel::where("user_id", $id)->delete();
                UsersCommentModel::where("user_id", $id)->delete();
                UsersConsultModel::where("user_id", $id)->delete();
                UsersFavoriteModel::where("user_id", $id)->delete();
                UsersLogModel::where("user_id", $id)->delete();
                UsersRechangeModel::where("user_id", $id)->delete();
                UsersWithdrawLogModel::where("user_id", $id)->delete();

                $order = OrderModel::where("user_id", $id)->select();

                $orderId = [];
                foreach ($order as $value) {
                    $orderId[] = $value["id"];
                }

                if (OrderModel::where("id", "in", $orderId)->delete()) {
                    OrderGoodsModel::where("order_id", "in", $orderId)->delete();
                    OrderLogModel::where("order_id", "in", $orderId)->delete();
                }

                OrderCollectionModel::where("user_id", $id)->delete();
                OrderDeliveryModel::where("user_id", $id)->delete();
                OrderRefundmentModel::where("user_id", $id)->delete();
                OrderGroupModel::where("user_id", $id)->delete();
            }

            UsersModel::commit();
        }catch (\Exception $ex){
            UsersModel::rollback();
            throw new \Exception($ex->getMessage(),$ex->getCode());
        }

        return true;
    }

    /**
     * 获取会员资料
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function financeDetail($id){
        if(!$user = UsersModel::where(["id"=>$id])->find()){
            throw new \Exception("您操作的会员不存在！", 0);
        }

        $user["username"] = getUserName($user);
        return [ "id"=>$id,"user"=>$user ];
    }

    /**
     * 财务明细
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function financeSave($data){
        $user = self::financeDetail($data["id"])["user"];
        $action = $data["action"]??"";
        $operation = $data["operation"]??"";
        $num = $data["num"]??0;

        if($operation == 1 && ($action == 0 && $user["amount"] < $num)){
            throw new \Exception("提现失败，用户余额不足",0);
        }

        if($operation == 1 && ($action == 1 && $user["point"] < $num)){
            throw new \Exception("提现失败，用户积分不足",0);
        }

        if($operation == 1 && ($action == 2 && $user["exp"] < $num)){
            throw new \Exception("提现失败，用户经验不足",0);
        }

        if($operation == 1 && ($action == 4 && $user["spread_amount"] < $num)){
            throw new \Exception("提现失败，用户佣金不足",0);
        }

        $field = "";
        $description = "管理员对您的";
        switch($action){
            case 0:
                $field = "amount";
                $description .= "【金额】";
                break;
            case 1:
                $field = "point";
                $description .= "【积分】";
                break;
            case 2:
                $field = "exp";
                $description .= "【经验】";
                break;
            case 4:
                $field = "spread_amount";
                $description .= "【佣金】";
                break;
        }

        if($operation == 1){
            $total = $user[$field] - $num;
            $description .= "执行了提现操作,";
        }else{
            $total = $user[$field] + $num;
            $description .= "执行了充值操作,";
        }

        $description .= "操作数值【".$num."】";
        $arr = [];
        $arr[$field] = $total;

        UsersModel::where(["id"=>$data["id"]])->update($arr);
        UsersLogModel::create([ "user_id"=>$data["id"], "admin_id"=>Session::get("system_user_id"), "action"=>$action, "operation"=>$operation, "description"=>$description, $field=>$num, "create_time"=>time()]);
        return true;
    }

    /**
     * 更新会员标签
     * @param $data
     * @return bool
     */
    public static function updateUsersTags($data){
        if(UsersModel::where("id",$data["user_id"])->count()){
            UsersModel::where("id",$data["user_id"])->save([ "tags"=>implode(",",$data["id"]) ]);
            return true;
        }

        return false;
    }

    /**
     * 获取会员收藏列表
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getFavoriteList($id){
        $count = UsersFavoriteModel::withJoin("goods")->where("users_favorite.user_id",$id)->count();
        $result = UsersFavoriteModel::withJoin("goods")->where("users_favorite.user_id",$id)->page($data["page"]??1,$data["limit"]??10)->order("users_favorite.id","desc")->select()->toArray();

        $array = [];
        foreach($result as $key=>$value){
            $goods = $value["goods"];
            $array[$key] = [
                "id"=>$goods["id"],
                "title"=>$goods["title"],
                "sell_price"=>$goods["sell_price"],
                "photo"=>Tool::thumb($goods["photo"]),
                "favorite_time"=>$value["create_time"]
            ];
        }

        return [ "data"=>$array, "count"=>$count ];
    }

    /**
     * 获取地址数据
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getAddressList($id){
        $result = UsersAddressModel::where("user_id",$id)->select()->toArray();
        foreach($result as $key=>$value){
            $result[$key]["create_time"] = date("Y-m-d H:i:s",$value["create_time"]);
            $result[$key]["province"] = AreaModel::getArea([$value["province"],$value["city"],$value["area"]],",");
        }

        return $result;
    }

}