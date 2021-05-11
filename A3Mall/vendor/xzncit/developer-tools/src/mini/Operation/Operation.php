<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\mini\Operation;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class Operation extends App {

    /**
     * operation.getFeedback
     * 获取用户反馈列表
     * @param int $page       分页的页数，从1开始
     * @param int $num        分页拉取的数据数量
     * @param null|int $type  数值 1 - 8，详细说明看微信小程序文档
     * @return array
     * @throws \Exception
     */
    public function getFeedback($page,$num,$type=null){
        $data = ["page"=>$page,"num"=>$num];
        is_null($type) || $data["type"] = $type;
        return HttpClient::create()->get("wxaapi/feedback/list?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * operation.getFeedbackmedia
     * 获取 mediaId 图片
     * @param $record_id    用户反馈信息的 record_id, 可通过 getFeedback 获取
     * @param $media_id     图片的 mediaId
     * @return array
     * @throws \Exception
     */
    public function getFeedbackmedia($record_id,$media_id){
        return HttpClient::create()->get("cgi-bin/media/getfeedbackmedia?access_token=ACCESS_TOKEN",[
            "record_id"=>$record_id,"media_id"=>$media_id
        ])->toArray();
    }

    /**
     * 错误查询详情
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function getJsErrDetail(array $data){
        return HttpClient::create()->postJson("wxaapi/log/jserr_detail?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * operation.getJsErrList
     * 错误查询列表
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function getJsErrList(array $data){
        return HttpClient::create()->postJson("wxaapi/log/jserr_list?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * operation.getJsErrSearch
     * 错误查询, 接口即将废弃，请采用新接口 getJsErrList
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function getJsErrSearch(array $data){
        return HttpClient::create()->postJson("wxaapi/log/jserr_search?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * operation.getPerformance
     * 性能监控
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function getPerformance(array $data){
        return HttpClient::create()->postJson("wxaapi/log/get_performance?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * operation.getSceneList
     * 获取访问来源
     * @return array
     * @throws \Exception
     */
    public function getSceneList(){
        return HttpClient::create()->get("wxaapi/log/get_scene?access_token=ACCESS_TOKEN")->toArray();
    }

    /**
     * operation.getVersionList
     * 获取客户端版本
     * @return array
     * @throws \Exception
     */
    public function getVersionList(){
        return HttpClient::create()->get("wxaapi/log/get_client_version?access_token=ACCESS_TOKEN")->toArray();
    }

    /**
     * operation.realtimelogSearch
     * 实时日志查询
     * @return array
     * @throws \Exception
     */
    public function realtimelogSearch(){
        return HttpClient::create()->get("wxaapi/userlog/userlog_search?access_token=ACCESS_TOKEN")->toArray();
    }

}