<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat\Statistics;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class Statistics extends App {

    /**
     * 获取用户增减数据
     * 最大时间跨度7天
     * @param string $begin_date    获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param string $end_date      获取数据的结束日期，end_date允许设置的最大值为昨日
     * @return array
     * @throws \Exception
     */
    public function getUserSummary($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getusersummary?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * 获取累计用户数据
     * 最大时间跨度7天
     * @param string $begin_date    获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param string $end_date      获取数据的结束日期，end_date允许设置的最大值为昨日
     * @return array
     * @throws \Exception
     */
    public function getUserCumulate($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getusercumulate?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * 获取图文群发每日数据
     * 最大时间跨度1天
     * @param string $begin_date    获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param string $end_date      获取数据的结束日期，end_date允许设置的最大值为昨日
     * @return array
     * @throws \Exception
     */
    public function getArticleSummary($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getarticlesummary?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * 获取图文群发总数据
     * 最大时间跨度1天
     * @param string $begin_date    获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param string $end_date      获取数据的结束日期，end_date允许设置的最大值为昨日
     * @return array
     * @throws \Exception
     */
    public function getArticleTotal($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getarticletotal?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * 获取图文统计数据
     * 最大时间跨度3天
     * @param string $begin_date    获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param string $end_date      获取数据的结束日期，end_date允许设置的最大值为昨日
     * @return array
     * @throws \Exception
     */
    public function getUserRead($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getuserread?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * 获取图文统计分时数据
     * 最大时间跨度1天
     * @param string $begin_date    获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param string $end_date      获取数据的结束日期，end_date允许设置的最大值为昨日
     * @return array
     * @throws \Exception
     */
    public function getUserReadHour($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getuserreadhour?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * 获取图文分享转发数据
     * 最大时间跨度7天
     * @param string $begin_date    获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param string $end_date      获取数据的结束日期，end_date允许设置的最大值为昨日
     * @return array
     * @throws \Exception
     */
    public function getUserShare($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getusershare?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * 获取图文分享转发分时数据
     * 最大时间跨度1天
     * @param string $begin_date    获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param string $end_date      获取数据的结束日期，end_date允许设置的最大值为昨日
     * @return array
     * @throws \Exception
     */
    public function getUserShareHour($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getusersharehour?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * 获取消息发送概况数据
     * 最大时间跨度7天
     * @param string $begin_date    获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param string $end_date      获取数据的结束日期，end_date允许设置的最大值为昨日
     * @return array
     * @throws \Exception
     */
    public function getUpStreamMsg($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getupstreammsg?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * 获取消息分送分时数据
     * 最大时间跨度1天
     * @param string $begin_date    获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param string $end_date      获取数据的结束日期，end_date允许设置的最大值为昨日
     * @return array
     * @throws \Exception
     */
    public function getUpStreamMsgHour($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getupstreammsghour?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * 获取消息发送周数据
     * 最大时间跨度30天
     * @param string $begin_date    获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param string $end_date      获取数据的结束日期，end_date允许设置的最大值为昨日
     * @return array
     * @throws \Exception
     */
    public function getUpStreamMsgWeek($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getupstreammsgweek?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * 获取消息发送月数据
     * 最大时间跨度30天
     * @param string $begin_date    获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param string $end_date      获取数据的结束日期，end_date允许设置的最大值为昨日
     * @return array
     * @throws \Exception
     */
    public function getUpStreamMsgMonth($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getupstreammsgmonth?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * 获取消息发送分布数据
     * 最大时间跨度15天
     * @param string $begin_date    获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param string $end_date      获取数据的结束日期，end_date允许设置的最大值为昨日
     * @return array
     * @throws \Exception
     */
    public function getUpStreamMsgDist($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getupstreammsgdist?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * 获取消息发送分布周数据
     * 最大时间跨度30天
     * @param string $begin_date    获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param string $end_date      获取数据的结束日期，end_date允许设置的最大值为昨日
     * @return array
     * @throws \Exception
     */
    public function getUpStreamMsgDistWeek($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getupstreammsgdistweek?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * 获取消息发送分布月数据
     * 最大时间跨度30天
     * @param string $begin_date    获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param string $end_date      获取数据的结束日期，end_date允许设置的最大值为昨日
     * @return array
     * @throws \Exception
     */
    public function getUpStreamMsgDistMonth($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getupstreammsgdistmonth?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * 获取公众号分广告位数据
     * 最大时间跨度90天
     * @param int    $page             返回第几页数据
     * @param int    $page_size        当页返回数据条数
     * @param string $start_date       获取数据的开始时间 yyyy-mm-dd
     * @param string $end_date         获取数据的结束时间 yyyy-mm-dd
     * @param string $ad_slot   广告位类型名称，如果不传递广告位类型名称，将默认返回全部类型广告位（除返佣商品广告）的数据。
     * @return array
     * @throws \Exception
     */
    public function getPublisherAdposGeneral($page,$page_size,$start_date,$end_date,$ad_slot=""){
        $params = [
            "page"=>$page,
            "page_size"=>$page_size,
            "start_date"=>$start_date,
            "end_date"=>$end_date
        ];

        if(!empty($ad_slot)){
            $params["ad_slot"] = $ad_slot;
        }

        return HttpClient::create()->get("publisher/stat?action=publisher_adpos_general&access_token=ACCESS_TOKEN",$params)->toArray();
    }

    /**
     * 获取公众号返佣商品数据
     * 最大时间跨度60天
     * @param int    $page          返回第几页数据
     * @param int    $page_size     当页返回数据条数
     * @param string $start_date    获取数据的开始时间 yyyy-mm-dd
     * @param string $end_date      获取数据的结束时间 yyyy-mm-dd
     * @return array
     * @throws \Exception
     */
    public function getPublisherCpsGeneral($page,$page_size,$start_date,$end_date){
        return HttpClient::create()->get("publisher/stat?action=publisher_cps_general&access_token=ACCESS_TOKEN",[
            "page"=>$page,"page_size"=>$page_size,"start_date"=>$start_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * 获取公众号结算收入数据及结算主体信息
     * @param $page         数据返回页数
     * @param $page_size    每页返回数据条数
     * @param $start_date   获取数据的开始时间 yyyy-mm-dd
     * @param $end_date     获取数据的结束时间 yyyy-mm-dd
     * @return array
     * @throws \Exception
     */
    public function getPublisherSettlement($page,$page_size,$start_date,$end_date){
        return HttpClient::create()->get("publisher/stat?action=publisher_settlement&access_token=ACCESS_TOKEN",[
            "page"=>$page,"page_size"=>$page_size,"start_date"=>$start_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * 获取接口分析数据
     * 最大时间跨度30天
     * @param $begin_date       获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param $end_date         获取数据的结束日期，end_date允许设置的最大值为昨日
     * @return array
     * @throws \Exception
     */
    public function getInterFaceSummary($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getinterfacesummary?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }

    /**
     * 获取接口分析分时数据
     * 最大时间跨度1天
     * @param $begin_date       获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param $end_date         获取数据的结束日期，end_date允许设置的最大值为昨日
     * @return array
     * @throws \Exception
     */
    public function getInterFaceSummaryHour($begin_date,$end_date){
        return HttpClient::create()->postJson("datacube/getinterfacesummary?access_token=ACCESS_TOKEN",[
            "begin_date"=>$begin_date,"end_date"=>$end_date
        ])->toArray();
    }
}