<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat\Store;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class Store extends App {

    /**
     * 创建门店
     * 创建门店接口是为商户提供创建自己门店数据的接口，门店数据字段越完整，商户页面展示越丰富，越能够吸引更多用户，并提高曝光度。
     * 创建门店接口调用成功后会返回errcode 0、errmsg ok，会实时返回唯一的poiid。
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function create(array $data){
        return HttpClient::create()->postJson("cgi-bin/poi/addpoi?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 查询门店信息
     * 创建门店后获取poi_id 后，商户可以利用poi_id，查询具体某条门店的信息。
     * 若在查询时，update_status 字段为1，表明在5 个工作日内曾用update 接口修改过门店扩展字段，
     * 该扩展字段为最新的修改字段，尚未经过审核采纳，因此不是最终结果。最终结果会在5 个工作日内，最终确认是否采纳，
     * 并前端生效（但该扩展字段的采纳过程不影响门店的可用性，即available_state仍为审核通过状态）
     * 注：修改扩展字段将会推送审核，但不会影响该门店的生效可用状态。
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function getInfo(array $data){
        return HttpClient::create()->postJson("cgi-bin/poi/getpoi?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 查询门店列表
     * 商户可以通过该接口，批量查询自己名下的门店list，并获取已审核通过的poiid、商户自身sid 用于对应、商户名、分店名、地址字段。
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function getList(array $data){
        return HttpClient::create()->postJson("cgi-bin/poi/getpoilist?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 修改门店服务信息
     * 商户可以通过该接口，修改门店的服务信息，包括：sid、图片列表、营业时间、推荐、特色服务、简介、人均价格、
     * 电话8个字段（名称、坐标、地址等不可修改）修改后需要人工审核。
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function update(array $data){
        return HttpClient::create()->postJson("cgi-bin/poi/updatepoi?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 删除门店
     * 商户可以通过该接口，删除已经成功创建的门店。请商户慎重调用该接口。
     * @param $poi_id
     * @return array
     * @throws \Exception
     */
    public function delete($poi_id){
        return HttpClient::create()->postJson("cgi-bin/poi/delpoi?access_token=ACCESS_TOKEN",[
            "poi_id"=>$poi_id
        ])->toArray();
    }

    /**
     * 门店类目表
     * 类目名称接口是为商户提供自己门店类型信息的接口。门店类目定位的越规范，能够精准的吸引更多用户，提高曝光率。
     * @return array
     * @throws \Exception
     */
    public function getCategoryList(){
        return HttpClient::create()->get("cgi-bin/poi/getwxcategory?access_token=ACCESS_TOKEN")->toArray();
    }
}