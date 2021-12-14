<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\platform;

use app\admin\service\Service;
use app\admin\model\Order as OrderModel;
use app\admin\model\Users as UsersModel;
use app\admin\model\GroupGoods as GoodsModel;
use app\common\models\users\UsersComment;
use app\common\models\users\UsersRechange;
use app\common\models\order\OrderRefundment;
use app\common\models\users\UsersSms;
use app\common\models\users\UsersWithdrawLog;
use mall\utils\Tool;
use think\App;
use think\facade\Db;
use think\facade\Request;

class Dashboard extends Service {

    /**
     * 获取统计信息
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getData(){
        // 会员订单总数和订单总购物额
        $order = OrderModel::field("COUNT(*) AS order_num,order_amount")->where("status",5)->find();
        $usersGoodsCount = OrderModel::withJoin("users")->field("COUNT(DISTINCT order.user_id) as count")->where("order.status",5)->find();

        // 订单排行
        $orderHot = OrderModel::withJoin("users")->field("SUM(order.order_amount) as total,COUNT(order.id) as count,users.username")->where("order.status",5)->group("order.user_id")->order("total","DESC")->limit(10)->select()->toArray();
        foreach($orderHot as $k=>$v){
            $orderHot[$k]['p'] = $k+1;
        }

        $data = OrderModel::field('SUM(order_amount) as amount,count(*) as total,create_time,FROM_UNIXTIME(create_time,"%Y%m%d") as days')
            ->where("status","in","2,5")
            ->group('days having create_time >= ' . strtotime(date('Y-m-d 00:00:00', strtotime("-30 day"))) . ' and create_time <= ' . strtotime(date('Y-m-d 23:59:59', time())))->select()->toArray();

        $count = $days = $amount = [];
        foreach ($data as $key => $val) {
            $days[] = $val['days'];
            $amount[] = $val['amount'];
            $count[] = $val['total'];
        }

        return [
            "order_total"=>OrderModel::count(),
            "goods_total"=>GoodsModel::count(),
            "users_total"=>UsersModel::count(),
            "users_day_count"=>UsersModel::whereDay("create_time")->count(),
            "order_pay_day_count"=>OrderModel::where("pay_status",1)->whereDay("create_time")->count(),
            "order_total_price"=>OrderModel::whereDay("create_time")->sum("order_amount"),
            "order_pay_total_price"=>OrderModel::where("pay_status",1)->whereDay("create_time")->sum("order_amount"),
            "recharge_total"=>UsersRechange::where("status",1)->whereDay("create_time")->sum("order_amount"),
            "order_no_pay_count"=>OrderModel::where(["pay_status"=>0,"status"=>1])->count(),
            "order_delivery_count"=>OrderModel::where(["pay_status"=>0,"status"=>2,"delivery_status"=>0])->count(),
            "order_refundment_count"=>OrderRefundment::where("pay_status",0)->count(),
            "users_bill_order_count"=>UsersWithdrawLog::where("status",0)->count(),
            "users_comment_total"=>UsersComment::count(),
            "days"=>implode(',',$days),
            "order_amount"=>implode(',',$amount),
            "order_count"=>implode(',',$count),
            "e"=>empty($order["order_amount"]) ? "0.00" : number_format($order["order_amount"],2),
            "f"=>empty($order["order_amount"]) ? "0.00" : number_format(($order["order_amount"] > 0 && $usersGoodsCount["count"] > 0 ? $order["order_amount"]/$usersGoodsCount["count"] : 0),2),
            "g"=>$orderHot
        ];
    }

    /**
     * 获取系统信息
     * @return array
     */
    public static function getSystemInfo(){
        $version = Db::query("SELECT VERSION() as version");
        return [ "version"=>$version[0]['version'], "think_ver"=>App::VERSION, "ip"=>gethostbyname(Request::host()) ];
    }

    /**
     * 获取缓存目录
     * @return array
     */
    public static function getCacheList(){
        $path = Tool::getRootPath() . 'runtime/';
        return ["code"=>0,"msg"=>"","data"=>[
            [
                "type"=>"db-sms","info"=>"清理己过期短信验证码",
                'size'=>Db::name("users_sms")->where("create_time","<=", strtotime("-1 day"))->count() . " 条"
            ],
            [
                "type"=>"file-cache","info"=>"数据缓存",
                'size'=>Tool::convert(
                    Tool::getDirSize($path . "admin/cache") +
                    Tool::getDirSize($path . "home/cache") +
                    Tool::getDirSize($path . "cache")
                )
            ],
            [
                "type"=>"file-log","info"=>"日志数据",
                'size'=>Tool::convert(
                    Tool::getDirSize($path . "admin/log") +
                    Tool::getDirSize($path . "home/log") +
                    Tool::getDirSize($path . "api/log")
                )
            ],
            [
                "type"=>"file-temp","info"=>"模板缓存",
                'size'=>Tool::convert(
                    Tool::getDirSize($path . "admin/temp") +
                    Tool::getDirSize($path . "home/temp")
                )
            ]
        ]];
    }

    /**
     * 清理缓存
     * @param $type
     * @return bool
     * @throws \think\db\exception\DbException
     */
    public static function clearCache($type){
        $path = Tool::getRootPath() . 'runtime/';
        if(!in_array($type, ["file-cache","file-log","file-temp","db-token","db-sms"])){
            throw new \Exception("非法操作！",0);
        }

        switch($type){
            case "db-sms":
                UsersSms::where("create_time","<=", strtotime("-1 day"))->delete();
                break;
            case "file-cache":
                Tool::deleteFile($path . "cache");
                Tool::deleteFile($path . "admin/cache");
                Tool::deleteFile($path . "home/cache");
                break;
            case "file-log":
                Tool::deleteFile($path . "admin/log");
                Tool::deleteFile($path . "home/log");
                Tool::deleteFile($path . "api/log");
                break;
            case "file-temp":
                Tool::deleteFile($path . "admin/temp");
                Tool::deleteFile($path . "home/temp");
                break;
        }

        return true;
    }

}