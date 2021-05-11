<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\mini\Analysis;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class Analysis extends App {

    /**
     * analysis.getDailyRetain
     * 获取用户访问小程序日留存
     * @param $begin_date       开始日期。格式为 yyyymmdd
     * @param $end_date         结束日期，限定查询1天数据，允许设置的最大值为昨日。格式为 yyyymmdd
     * @return array
     * @throws \Exception
     */
    public function getDailyRetain($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getweanalysisappiddailyretaininfo?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * analysis.getMonthlyRetain
     * 获取用户访问小程序月留存
     * @param $begin_date       开始日期，为自然月第一天。格式为 yyyymmdd
     * @param $end_date         结束日期，为自然月最后一天，限定查询一个月数据。格式为 yyyymmdd
     * @return array
     * @throws \Exception
     */
    public static function getMonthlyRetain($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getweanalysisappidmonthlyretaininfo?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * analysis.getWeeklyRetain
     * 获取用户访问小程序周留存
     * @param $begin_date       开始日期，为周一日期。格式为 yyyymmdd
     * @param $end_date         结束日期，为周日日期，限定查询一周数据。格式为 yyyymmdd
     * @return array
     * @throws \Exception
     */
    public function getWeeklyRetain($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getweanalysisappidweeklyretaininfo?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * analysis.getDailySummary
     * 获取用户访问小程序数据概况
     * @param $begin_date       开始日期。格式为 yyyymmdd
     * @param $end_date         结束日期，限定查询1天数据，允许设置的最大值为昨日。格式为 yyyymmdd
     * @return array
     * @throws \Exception
     */
    public function getDailySummary($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getweanalysisappiddailysummarytrend?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * analysis.getDailyVisitTrend
     * 获取用户访问小程序数据日趋势
     * @param $begin_date       开始日期。格式为 yyyymmdd
     * @param $end_date         结束日期，限定查询1天数据，允许设置的最大值为昨日。格式为 yyyymmdd
     * @return array
     * @throws \Exception
     */
    public function getDailyVisitTrend($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getweanalysisappiddailyvisittrend?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * analysis.getMonthlyVisitTrend
     * 获取用户访问小程序数据月趋势(能查询到的最新数据为上一个自然月的数据)
     * @param $begin_date       开始日期，为自然月第一天。格式为 yyyymmdd
     * @param $end_date         结束日期，为自然月最后一天，限定查询一个月的数据。格式为 yyyymmdd
     * @return array
     * @throws \Exception
     */
    public function getMonthlyVisitTrend($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getweanalysisappidmonthlyvisittrend?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * analysis.getWeeklyVisitTrend
     * 获取用户访问小程序数据周趋势
     * @param $begin_date
     * @param $end_date
     * @return array
     * @throws \Exception
     */
    public function getWeeklyVisitTrend($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getweanalysisappidweeklyvisittrend?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * analysis.getPerformanceData
     * 获取小程序启动性能，运行性能等数据
     * @param $time
     * @param $module
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getPerformanceData($time,$module,$params){
        return HttpClient::create()->postJson("wxa/business/performance/boot?access_token=ACCESS_TOKEN",[
            "time"=>$time,"module"=>$module,"params"=>$params
        ])->toArray();
    }

    /**
     * analysis.getUserPortrait
     * 获取小程序新增或活跃用户的画像分布数据。时间范围支持昨天、最近7天、最近30天。
     * 其中，新增用户数为时间范围内首次访问小程序的去重用户数，活跃用户数为时间范围内访问过小程序的去重用户数。
     * @param $begin_date       开始日期。格式为 yyyymmdd
     * @param $end_date         结束日期，开始日期与结束日期相差的天数限定为0/6/29，分别表示查询最近1/7/30天数据，允许设置的最大值为昨日。格式为 yyyymmdd
     * @return array
     * @throws \Exception
     */
    public function getUserPortrait($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getweanalysisappiduserportrait?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * analysis.getVisitDistribution
     * 获取用户小程序访问分布数据
     * @param $begin_date       开始日期。格式为 yyyymmdd
     * @param $end_date         结束日期，限定查询 1 天数据，允许设置的最大值为昨日。格式为 yyyymmdd
     * @return array
     * @throws \Exception
     */
    public function getVisitDistribution($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getweanalysisappidvisitdistribution?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * analysis.getVisitPage
     * 访问页面。目前只提供按 page_visit_pv 排序的 top200。
     * @param $begin_date       开始日期。格式为 yyyymmdd
     * @param $end_date         结束日期，限定查询1天数据，允许设置的最大值为昨日。格式为 yyyymmdd
     * @return array
     * @throws \Exception
     */
    public function getVisitPage($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getweanalysisappidvisitpage?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }



}