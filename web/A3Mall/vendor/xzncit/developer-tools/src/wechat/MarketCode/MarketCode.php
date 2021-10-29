<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat\MarketCode;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class MarketCode extends App {

    /**
     * 申请二维码接口
     * @param int    $code_count           申请码的数量(≥10000，≤20000000，10000的整数倍)
     * @param string $isv_application_id   外部单号(相同isv_application_id视为同一申请单)
     * @return array
     * @throws \Exception
     */
    public function applycode($code_count,$isv_application_id){
        return HttpClient::create()->postJson("intp/marketcode/applycode?access_token=ACCESS_TOKEN",[
            "code_count"=>$code_count,"isv_application_id"=>$isv_application_id
        ])->toArray();
    }

    /**
     * 查询二维码申请单接口
     * @param int    $application_id       申请单号
     * @param string $isv_application_id   外部单号
     * @return array
     * @throws \Exception
     */
    public function applycodequery($application_id,$isv_application_id){
        return HttpClient::create()->postJson("intp/marketcode/applycodequery?access_token=ACCESS_TOKEN",[
            "application_id"=>$application_id,"isv_application_id"=>$isv_application_id
        ])->toArray();
    }

    /**
     * 下载二维码包接口
     * @param int $application_id       申请单号
     * @param int $code_start           开始位置
     * @param int $code_end             结束位置
     * @return array
     * @throws \Exception
     */
    public function applycodedownload($application_id,$code_start,$code_end){
        return HttpClient::create()->postJson("intp/marketcode/applycodedownload?access_token=ACCESS_TOKEN",[
            "application_id"=>$application_id,"code_start"=>$code_start,"code_end"=>$code_end
        ])->toArray();
    }

    /**
     * 激活二维码接口
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function codeactive(array $data){
        return HttpClient::create()->postJson("intp/marketcode/codeactive?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 查询二维码激活状态接口
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function codeactivequery(array $data){
        return HttpClient::create()->postJson("intp/marketcode/codeactivequery?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * code_ticket换code接口
     * @param string $openid            用户的openid
     * @param string $code_ticket       跳转时带上的code_ticket参数
     * @return array
     * @throws \Exception
     */
    public function tickettocode($openid,$code_ticket){
        return HttpClient::create()->postJson("intp/marketcode/codeactivequery?access_token=ACCESS_TOKEN",[
            "openid"=>$openid,"code_ticket"=>$code_ticket
        ])->toArray();
    }

}