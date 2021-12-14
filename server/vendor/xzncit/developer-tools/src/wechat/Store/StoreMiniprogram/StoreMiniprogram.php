<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat\Store\StoreMiniprogram;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class StoreMiniprogram extends App {

    public function getCategoryList(){
        return HttpClient::create()->get("wxa/get_merchant_category?access_token=ACCESS_TOKEN")->toArray();
    }

    /**
     * 创建门店小程序
     * 说明：创建门店小程序提交后需要公众号管理员确认通过后才可进行审核。如果主管理员24小时超时未确认，才能再次提交。
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function create(array $data){
        return HttpClient::create()->postJson("wxa/apply_merchant?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 查询门店小程序审核结果
     * @return array
     * @throws \Exception
     */
    public function getStoreStatusInfo(){
        return HttpClient::create()->get("wxa/get_merchant_audit_info?access_token=ACCESS_TOKEN")->toArray();
    }

    /**
     * 修改门店小程序信息
     * @param string $headimg_mediaid
     * @param string $intro
     * @return array
     * @throws \Exception
     */
    public function update($headimg_mediaid,$intro=""){
        return HttpClient::create()->postJson("wxa/modify_merchant?access_token=ACCESS_TOKEN",[
            "headimg_mediaid"=>$headimg_mediaid,"intro"=>$intro
        ])->toArray();
    }

    /**
     * 从腾讯地图拉取省市区信息
     * @return array
     * @throws \Exception
     */
    public function getDistrict(){
        return HttpClient::create()->get("xa/get_district?access_token=ACCESS_TOKEN")->toArray();
    }

    /**
     * 在腾讯地图中搜索门店
     * @param int    $districtid    对应拉取省市区信息接口 中的id字段
     * @param string $keyword       搜索的关键词
     * @return array
     * @throws \Exception
     */
    public function searchMapStore($districtid,$keyword){
        return HttpClient::create()->postJson("wxa/search_map_poi?access_token=ACCESS_TOKEN",[
            "districtid"=>$districtid,"keyword"=>$keyword
        ])->toArray();
    }

    /**
     * 在腾讯地图中创建门店
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function createMapStore(array $data){
        return HttpClient::create()->postJson("wxa/create_map_poi?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 添加门店
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function add(array $data){
        return HttpClient::create()->postJson("wxa/add_store?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 更新门店信息
     * @param $poi_id               为门店小程序添加门店，审核成功后返回的门店id
     * @param $hour                 自定义营业时间，格式为10:00-12:00
     * @param $contract_phone       自定义联系电话
     * @param $pic_list             门店图片，可传多张图片 pic_list 字段是一个 json
     * @param $card_id              卡券id，如果不想修改的话，设置为空
     * @return array
     * @throws \Exception
     */
    public function updateStore($poi_id,$hour,$contract_phone,$pic_list,$card_id=null){
        $data = ["poi_id"=>$poi_id,"hour"=>$hour,"contract_phone"=>$contract_phone,"pic_list"=>$pic_list];
        empty($card_id) || $data["card_id"] = $card_id;
        return HttpClient::create()->postJson("wxa/update_store?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 获取单个门店信息
     * @param int|string $poi_id       为门店小程序添加门店，审核成功后返回的门店id
     * @return array
     * @throws \Exception
     */
    public function getStoreInfo($poi_id){
        return HttpClient::create()->postJson("wxa/get_store_info?access_token=ACCESS_TOKEN",[
            "poi_id"=>$poi_id
        ])->toArray();
    }

    /**
     * 获取门店信息列表
     * @param $offset       获取门店列表的初始偏移位置，从0开始计数
     * @param $limit        获取门店个数
     * @return array
     * @throws \Exception
     */
    public function getStoreList($offset,$limit){
        return HttpClient::create()->postJson("wxa/get_store_list?access_token=ACCESS_TOKEN",[
            "offset"=>$offset,"limit"=>$limit
        ])->toArray();
    }

    /**
     * 删除门店
     * @param int|string $poi_id       为门店小程序添加门店，审核成功后返回的门店id
     * @return array
     * @throws \Exception
     */
    public function deleteStore($poi_id){
        return HttpClient::create()->postJson("wxa/del_store?access_token=ACCESS_TOKEN",[
            "poi_id"=>$poi_id
        ])->toArray();
    }

    /**
     * 升级流程 --- 从门店管理迁移到门店小程序
     * 创建门店小程序审核成功后，拉取待迁移门店列表(poi/getpoilist是现网已经有的api，只是增加了三个新字段)：
     * @param $upgrade_comment
     * @param $mapid
     * @param $poi_id
     * @return array
     * @throws \Exception
     */
    public function getPoiList($upgrade_comment,$mapid,$poi_id){
        return HttpClient::create()->postJson("cgi-bin/poi/getpoilist?access_token=ACCESS_TOKEN",[
            "upgrade_comment"=>$upgrade_comment,"mapid"=>$mapid,"poi_id"=>$poi_id
        ])->toArray();
    }

    /**
     * 业务接口-门店小程序卡券
     * @param int|string $poi_id   门店id
     * @return array
     * @throws \Exception
     */
    public function getStoreCard($poi_id){
        return HttpClient::create()->postJson("card/storewxa/get?access_token=ACCESS_TOKEN",[
            "poi_id"=>$poi_id
        ])->toArray();
    }

    /**
     * 设置门店小程序配置的卡券
     * @param $poi_id
     * @param $card_id
     * @return array
     * @throws \Exception
     */
    public function setStoreCard($poi_id,$card_id){
        return HttpClient::create()->postJson("card/storewxa/set?access_token=ACCESS_TOKEN",[
            "poi_id"=>$poi_id,"card_id"=>$card_id
        ])->toArray();
    }

}