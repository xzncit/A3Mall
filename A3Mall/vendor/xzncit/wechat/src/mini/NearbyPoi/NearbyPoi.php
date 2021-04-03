<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\mini\NearbyPoi;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class NearbyPoi extends App {

    /**
     * nearbyPoi.add
     * 添加地点
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function add(array $data){
        return HttpClient::create()->postJson("wxa/addnearbypoi?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 删除地点
     * @param $poi_id       附近地点 ID
     * @return array
     * @throws \Exception
     */
    public function delete($poi_id){
        return HttpClient::create()->postJson("wxa/delnearbypoi?access_token=ACCESS_TOKEN",[
            "poi_id"=>$poi_id
        ])->toArray();
    }

    /**
     * @param int $page         起始页id（从1开始计数）
     * @param int $page_rows    每页展示个数（最多1000个）
     * @return array
     * @throws \Exception
     */
    public function getList($page=1,$page_rows=100){
        return HttpClient::create()->get("wxa/getnearbypoilist?access_token=ACCESS_TOKEN",[
            "page"=>$page,"page_rows"=>$page_rows
        ])->toArray();
    }

    /**
     * nearbyPoi.setShowStatus
     * 展示/取消展示附近小程序
     * @param $poi_id       附近地点 ID
     * @param $status       是否展示
     * @return array
     * @throws \Exception
     */
    public function setShowStatus($poi_id,$status){
        return HttpClient::create()->postJson("wxa/setnearbypoishowstatus?access_token=ACCESS_TOKEN",[
            "poi_id"=>$poi_id,"status"=>$status
        ])->toArray();
    }

}