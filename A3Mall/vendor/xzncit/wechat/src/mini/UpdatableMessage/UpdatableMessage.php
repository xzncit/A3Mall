<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\mini\UpdatableMessage;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class UpdatableMessage extends App {

    /**
     * updatableMessage.createActivityId
     * 创建被分享动态消息或私密消息的 activity_id。
     * @param $unionid      为私密消息创建activity_id时，指定分享者为unionid用户。其余用户不能用此activity_id分享私密消息。openid与unionid填一个即可。私密消息暂不支持云函数生成activity id。
     * @param $openid       为私密消息创建activity_id时，指定分享者为openid用户。其余用户不能用此activity_id分享私密消息。openid与unionid填一个即可。私密消息暂不支持云函数生成activity id。
     * @return array
     * @throws \Exception
     */
    public function createActivityId($unionid,$openid){
        return HttpClient::create()->get("cgi-bin/message/wxopen/activityid/create?access_token=ACCESS_TOKEN",[
            "unionid"=>$unionid,
            "openid"=>$openid
        ])->toArray();
    }

    /**
     * updatableMessage.setUpdatableMsg
     * 修改被分享的动态消息。
     * @param $activity_id      动态消息的 ID，通过 updatableMessage.createActivityId 接口获取
     * @param $target_state     动态消息修改后的状态（具体含义见后文）
     * @param $template_info    动态消息对应的模板信息
     * @return array
     * @throws \Exception
     */
    public function setUpdatableMsg($activity_id,$target_state,$template_info){
        return HttpClient::create()->postJson("cgi-bin/message/wxopen/updatablemsg/send?access_token=ACCESS_TOKEN",[
            "activity_id"=>$activity_id,"target_state"=>$target_state,"template_info"=>$template_info
        ])->toArray();
    }

}