<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat\Card\SpecialTicket;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class SpecialTicket extends App {

    /**
     * 创建门票,可用于创建会议/演出门票/景区门票/电影票/飞机票
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function create(array $data){
        return HttpClient::create()->postJson("card/create?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 更新会议门票
     * 支持调用“更新会议门票”接口update 入场时间、区域、座位等信息。
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function updateMeetingTicket(array $data){
        return HttpClient::create()->postJson("card/meetingticket/updateuser?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 更新电影票
     * 领取电影票后通过调用“更新电影票”接口update电影信息及用户选座信息。
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function updateMovieTicket(array $data){
        return HttpClient::create()->postJson("card/movieticket/updateuser?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 更新飞机票信息接口
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function updateBoardingPass(array $data){
        return HttpClient::create()->postJson("card/boardingpass/checkin?access_token=ACCESS_TOKEN",$data)->toArray();
    }

}