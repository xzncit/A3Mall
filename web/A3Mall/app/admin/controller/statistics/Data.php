<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\statistics;

use app\admin\controller\Auth;
use think\facade\Request;
use think\facade\Db;
use think\facade\View;

class Data extends Auth {

    public function order(){
        $calendar = $this->calendar();
        $condition = 'o.pay_status=1 AND o.pay_time between "' . strtotime($calendar["start"]) . '" AND "' . strtotime($calendar["end"]) . '"';
        $result = Db::name("order")
            ->alias("o")
            ->field("COUNT(o.id) as num,o.province,a.name")
            ->join("area a","o.province=a.id","LEFT")
            ->where($condition)->group('o.province')->select()->toArray();

        $mapdata = [];
        $total = 0;
        foreach ($result as $key => $row) {
            $mapdata[$key]["name"] = preg_replace("/(\s|省|市|自治区|壮族|回族|维吾尔|特别行政区)/", '', $row['name']);
            $mapdata[$key]["num"] = $row['num'];
            $total += $row['num'];
        }

        return View::fetch("",[
            "data"=>$mapdata,
            "total"=>$total,
            "start_time"=>$calendar["start"],
            "end_time"=>$calendar["end"],
            "str_time"=>$calendar["str"]
        ]);
    }

    public function register(){
        $calendar = $this->calendar();

        $group = 'days having create_time >= ' . strtotime($calendar["start"]);
        $group .= ' and create_time <= ' . strtotime($calendar["end"]);

        $field = 'COUNT(id) as count,create_time,FROM_UNIXTIME(create_time,"%Y%m%d") as days';
        $data = Db::name("users")
            ->field($field)
            ->group($group)->select()->toArray();

        $count = [];
        foreach ($data as $key => $val) {
            $count[$val['days']] = intval($val['count']);
        }

        return View::fetch("",[
            "keys"=>"'" . implode("','", array_keys($count)) . "'",
            "values"=>"'" . implode("','", array_values($count)) . "'",
            "start_time"=>$calendar["start"],
            "end_time"=>$calendar["end"],
            "str_time"=>$calendar["str"]
        ]);
    }

    public function sale(){
        $calendar = $this->calendar();

        $group = 'days having create_time >= ' . strtotime($calendar["start"]);
        $group .= ' and create_time <= ' . strtotime($calendar["end"]);

        $field = 'SUM(amount) as amount,create_time,FROM_UNIXTIME(create_time,"%Y%m%d") as days';
        $data = Db::name("order_collection")
            ->field($field)
            ->where(["pay_status"=>1])
            ->group($group)->select()->toArray();

        $count = [];
        foreach ($data as $key => $val) {
            $count[$val['days']] = intval($val['amount']);
        }

        return View::fetch("",[
            "keys"=>"'" . implode("','", array_keys($count)) . "'",
            "values"=>"'" . implode("','", array_values($count)) . "'",
            "start_time"=>$calendar["start"],
            "end_time"=>$calendar["end"],
            "str_time"=>$calendar["str"]
        ]);
    }

    public function spanding(){
        $calendar = $this->calendar();

        $group = 'days having create_time >= ' . strtotime($calendar["start"]);
        $group .= ' and create_time <= ' . strtotime($calendar["end"]);

        $field = 'SUM(amount)/count(*) as total,create_time,FROM_UNIXTIME(create_time,"%Y%m%d") as days';
        $data = Db::name("order_collection")
            ->field($field)
            ->where(["pay_status"=>1])
            ->group($group)->select()->toArray();

        $count = [];
        foreach ($data as $key => $val) {
            $count[$val['days']] = intval($val['total']);
        }

        return View::fetch("",[
            "keys"=>"'" . implode("','", array_keys($count)) . "'",
            "values"=>"'" . implode("','", array_values($count)) . "'",
            "start_time"=>$calendar["start"],
            "end_time"=>$calendar["end"],
            "str_time"=>$calendar["str"]
        ]);
    }

    private function calendar() {
        $start_time = Request::param("start_time");
        $end_time = Request::param("end_time");
        if (empty($start_time)) {
            $start_time = date("Y-m-d", strtotime("-7 day"));
        }

        if (empty($end_time)) {
            $end_time = date("Y-m-d");
        }

        if($start_time > $end_time){
            $start_time = date("Y-m-d", strtotime("-7 day"));
            $end_time = date("Y-m-d");
        }

        $str_time = date("Y-m-d", strtotime($start_time)) . ' -- ' . date("Y-m-d", strtotime($end_time));
        $stime = date('Y-m-d 00:00:00', strtotime($start_time));
        $etime = date('Y-m-d 23:59:59', strtotime($end_time));
        $cle = strtotime($etime) - strtotime($stime);
        $num = ceil($cle / 86400);
        return [
            "start" => $stime, "end" => $etime, "days" => $num, "str" => $str_time
        ];
    }
}