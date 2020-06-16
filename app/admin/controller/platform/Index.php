<?php
namespace app\admin\controller\platform;

use mall\basic\Order;
use mall\utils\Date;
use mall\utils\Tool;
use think\App;
use app\admin\controller\Auth;
use think\facade\Db;
use think\facade\View;
use think\facade\Request;
use mall\response\Response;

class Index extends Auth {

    public function index(){
        $goods = Db::name("goods")->where(["status"=>0])->order('sale DESC')->limit(5)->select()->toArray();
        $order = Db::name("order")
            ->field("o.*,u.username,p.name as payment_name")
            ->alias("o")
            ->join("users u","o.user_id=u.id","LEFT")
            ->join("payment p","o.pay_type=p.id","LEFT")
            ->where("1=1")->order('o.id DESC')->limit(10)->select()->toArray();
        return View::fetch("",[
            "goods"=>array_map(function ($res){
                $res["photo"] = Tool::thumb($res["photo"]);
                $res["url"] = createUrl("products.index/editor",["id"=>$res["id"]]);
                return $res;
            },$goods),
            "order"=>array_map(function ($res){
                $res['create_time'] = Date::format($res["create_time"]);
                $res['url'] = createUrl("order.index/detail",["id"=>$res["id"]]);
                $res['distribution_status_name'] = Order::getStatusText(Order::getStatus($res));
                $res['pay_status_name'] = Order::getPaymentStatusText($res["pay_status"]);
                $res['order_type_name'] = Order::getOrderTypeText($res['type']);
                return $res;
            },$order)
        ]);
    }

    public function info(){
        $version = Db::query("SELECT VERSION() as version");
        return View::fetch("info",[
            "version"=>$version[0]['version'],
            "think_ver"=>App::VERSION,
            "app_version"=>'1.0',
            "ip"=>$_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT']
        ]);
    }

    public function cache(){
        if(Request::isAjax()){
            $type = Request::get("type","","trim,strip_tags");
            $path = (new App())->getRuntimePath();
            if(empty($type)){
                return ["code"=>0,"msg"=>"","data"=>[
                    [
                        "type"=>"cache","info"=>"数据缓存",
                        'size'=>Tool::convert(
                            Tool::getDirSize($path . "admin/cache") +
                            Tool::getDirSize($path . "home/cache")
                        )
                    ],
                    [
                        "type"=>"log","info"=>"日志数据",
                        'size'=>Tool::convert(
                            Tool::getDirSize($path . "admin/log") +
                            Tool::getDirSize($path . "admin/log")
                        )
                    ],
                    [
                        "type"=>"temp","info"=>"模板缓存",
                        'size'=>Tool::convert(
                            Tool::getDirSize($path . "admin/temp") +
                            Tool::getDirSize($path . "home/temp")
                        )
                    ]
                ]];
            }

            if(!in_array($type, ["cache","log","temp"])){
                return Response::returnArray("非法操作！",0);
            }

            switch($type){
                case "cache":
                    Tool::deleteFile($path . "admin/cache");
                    Tool::deleteFile($path . "home/cache");
                    break;
                case "log":
                    Tool::deleteFile($path . "admin/log");
                    Tool::deleteFile($path . "admin/log");
                    break;
                case "temp":
                    Tool::deleteFile($path . "admin/temp");
                    Tool::deleteFile($path . "home/temp");
                    break;
            }

            return Response::returnArray("清理缓存成功！",1);
        }

        return View::fetch();
    }


}