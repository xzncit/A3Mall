<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\users;

use app\admin\service\Service;
use app\common\models\users\Users as UsersModel;
use app\admin\model\users\UsersLog as UsersLogModel;
use app\admin\model\users\UsersWithdrawLog as UsersWithdrawLogModel;

class Finance extends Service {

    private static $type = ["1"=>"银行卡","2"=>"支付宝","3"=>"微信"];
    private static $status = ["0"=>"审核中","1"=>"已提现","2"=>"未通过"];

    /**
     * 获取列表数据
     * @param $data
     * @param array $condition
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data,$condition=[]){
        $count = UsersLogModel::withJoin("users")->where($condition)->count();
        $result = UsersLogModel::withJoin("users")->where($condition)->page($data["page"]??1,$data["limit"]??10)->order("users_log.id","desc")->select()->toArray();
        return [ "count"=>$count, "data"=> array_map(function ($res){
            $res["username"] = getUserName($res);
            return $res;
        },$result) ];
    }

    /**
     * 提现申请
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function apply($data){
        $key = $data["key"]??[];
        $condition = [];
        if(isset($key["status"])){
            if(in_array($key["status"],[1,2,3])){
                $condition[] = ["users_withdraw_log.status","=",$key["status"]-1];
            }
        }

        $count = UsersWithdrawLogModel::withJoin("users")->where($condition)->count();
        $result = UsersWithdrawLogModel::withJoin("users")->where($condition)->page($data["page"]??1,$data["limit"]??10)->order("id","desc")->select()->toArray();
        return [ "count"=>$count, "data"=>$data = array_map(function ($res){
            $res["username"] = getUserName($res);
            $res["type_name"] = self::getType($res["type"]);
            $res["status_name"] = self::getStatus($res["status"]);

            $str = '';
            if($res["type"] == 1){
                $str .= "<p>类型：" . ($res["withdraw_type"]==0?"佣金":"余额") . '</p>';
                $str .= "<p>卡号：" . $res["code"] . '</p>';
                $str .= "<p>开户地址：" . $res["address"] . '</p>';
                $str .= "<p>银行：" . $res["bank_name"] . '</p>';
            }else if($res["type"] == 2){
                $str .= "<p>用户名：" . $res["username"] . '</p>';
                $str .= "<p>支付宝：" . $res["account"] . '</p>';
            }else if($res["type"] == 3){
                $str .= "<p>用户名：" . $res["username"] . '</p>';
                $str .= "<p>微信：" . $res["account"] . '</p>';
            }

            $res["string"] = $str;
            return $res;
        },$result) ];
    }

    public static function detail($id){
        $row = UsersWithdrawLogModel::where(["id"=>$id])->find();
        if(empty($row)){
            throw new \Exception("您要访问的内容不存在",0);
        }

        $str = '&nbsp;&nbsp;';
        if($row["type"] == 1){
            $str .= "<span>卡号：" . $row["code"] . '</span>&nbsp;&nbsp;';
            $str .= "<span>开户地址：" . $row["address"] . '</span>&nbsp;&nbsp;';
            $str .= "<span>银行：" . $row["bank_name"] . '</span>&nbsp;&nbsp;';
        }else if($row["type"] == 2){
            $str .= "<span>用户名：" . $row["username"] . '</span>&nbsp;&nbsp;';
            $str .= "<span>支付宝：" . $row["account"] . '</span>&nbsp;&nbsp;';
        }else if($row["type"] == 3){
            $str .= "<span>用户名：" . $row["username"] . '</span>&nbsp;&nbsp;';
            $str .= "<span>微信：" . $row["account"] . '</span>&nbsp;&nbsp;';
        }

        $row["string"] = $str;
        $user = UsersModel::where(["id"=>$row["user_id"]])->find();
        $user["username"] = getUserName($user);
        return [ "id"=>$id,"user"=>$user,"row"=>$row ];
    }

    /**
     * 保存数据
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function save($data){
        $row = UsersWithdrawLogModel::where(["id"=>$data["id"]])->find();
        if(empty($row)){
            throw new \Exception("您要访问的内容不存在",0);
        }

        $u = UsersModel::where(["id"=>$row["user_id"]])->find();
        if($row["withdraw_type"] == 1){
            if($u["amount"] < $row["price"]){
                throw new \Exception("操作失败，余额不足！",0);
            }
        }else{
            if($u["spread_amount"] < $row["price"]){
                throw new \Exception("操作失败，余额不足！",0);
            }
        }

        try{
            UsersWithdrawLogModel::startTrans();
            UsersWithdrawLogModel::where(["id"=>$data["id"]])->save([ "msg"=>$data["msg"], "status"=>$data["status"], "update_time"=>time() ]);

            if($data["status"] == 1) {
                if ($row["withdraw_type"] == 1) {
                    UsersModel::where(["id" => $row["user_id"]])->dec("amount", $row["price"])->update();
                } else {
                    UsersModel::where(["id" => $row["user_id"]])->dec("spread_amount", $row["price"])->update();
                }
            }

            UsersWithdrawLogModel::commit();
            return true;
        }catch (\Exception $ex){
            UsersWithdrawLogModel::rollback();
            throw new \Exception($ex->getMessage(),$ex->getCode());
        }
    }

    /**
     * 获取支付方式
     * @param $type
     * @return string
     */
    public static function getType($type){
        return self::$type[$type];
    }

    /**
     * 获取状态
     * @param $status
     * @return string
     */
    public static function getStatus($status){
        return self::$status[$status];
    }

}