<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\platform;

use mall\utils\Tool;
use think\App;
use app\admin\controller\Auth;
use think\facade\Db;
use think\facade\View;
use think\facade\Request;
use mall\response\Response;

class Index extends Auth {

    public function index(){
        // 会员订单总数和订单总购物额
        $order = Db::name("order")
            ->field("COUNT(*) AS order_num,order_amount")
            ->where("user_id > 0 AND status=5")->find();

        // 注册后还未购买过商品的会员
        $users_goods_count = Db::name("users")
            ->field("COUNT(DISTINCT o.user_id) as count")
            ->alias("u")
            ->join("order o","o.user_id=u.id","LEFT")
            ->where("o.user_id > 0 AND o.status=5")
            ->find();

        // 订单排行
        $fields = "SUM(o.order_amount) as total,COUNT(o.id) as count,u.username";
        $order_hot = Db::name("order")
            ->field($fields)
            ->alias("o")
            ->join("users u","o.user_id=u.id","LEFT")
            ->where("o.user_id > 0 AND o.status=5")
            ->group("o.user_id")->order("total DESC")
            ->limit(10)->select()->toArray();

        foreach($order_hot as $k=>$v){
            $order_hot[$k]['p'] = $k+1;
        }

        $stime = date('Y-m-d 00:00:00', strtotime("-30 day"));
        $etime = date('Y-m-d 23:59:59', time());
        $group = 'days having create_time >= ' . strtotime($stime);
        $group .= ' and create_time <= ' . strtotime($etime);

        $field = 'SUM(order_amount) as amount,count(*) as total,create_time,FROM_UNIXTIME(create_time,"%Y%m%d") as days';
        $data = Db::name("order")
            ->field($field)
            ->where("status","in",[2,5])
            ->group($group)->select()->toArray();

        $count = [];
        $days = [];
        $amount = [];
        foreach ($data as $key => $val) {
            $days[] = $val['days'];
            $amount[] = $val['amount'];
            $count[] = $val['total'];
        }

        return View::fetch("",[
            "order_total"=>Db::name("order")->count(),
            "goods_total"=>Db::name("goods")->count(),
            "users_total"=>Db::name("users")->count(),
            "users_comment_total"=>Db::name("users_comment")->count(),
            "days"=>implode(',',$days),
            "order_amount"=>implode(',',$amount),
            "order_count"=>implode(',',$count),
            "e"=>empty($order["order_amount"]) ? "0.00" : number_format($order["order_amount"],2),
            "f"=>empty($order["order_amount"]) ? "0.00" : number_format(($order["order_amount"] > 0 && $users_goods_count["count"] > 0 ? $order["order_amount"]/$users_goods_count["count"] : 0),2),
            "g"=>$order_hot
        ]);
    }

    public function info(){
        $version = Db::query("SELECT VERSION() as version");
        return View::fetch("info",[
            "version"=>$version[0]['version'],
            "think_ver"=>App::VERSION,
            "ip"=>gethostbyname(Request::host())
        ]);
    }

    public function cache(){
        if(Request::isAjax()){
            $type = Request::get("type","","trim,strip_tags");
            $path = Tool::getRootPath() . 'runtime/';
            if(empty($type)){
                return ["code"=>0,"msg"=>"","data"=>[
                    [
                        "type"=>"db-token","info"=>"清理前台登录状态（ Token ）",
                        'size'=>Db::name("users_token")->where("expire_time","<=", strtotime("-1 day"))->count() . " 条"
                    ],
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

            if(!in_array($type, ["file-cache","file-log","file-temp","db-token","db-sms"])){
                return Response::returnArray("非法操作！",0);
            }

            switch($type){
                case "db-token":
                    Db::name("users_token")->where("expire_time","<=", strtotime("-1 day"))->delete();
                    break;
                case "db-sms":
                    Db::name("users_sms")->where("create_time","<=", strtotime("-1 day"))->delete();
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

            return Response::returnArray("清理缓存成功！",1);
        }

        return View::fetch();
    }

}