<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\mini\SubscribeMessage;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class SubscribeMessage extends App {

    /**
     * subscribeMessage.addTemplate
     * 组合模板并添加至帐号下的个人模板库
     * @param $tid          模板标题 id，可通过接口获取，也可登录小程序后台查看获取
     * @param $kidList      开发者自行组合好的模板关键词列表，关键词顺序可以自由搭配（例如 [3,5,4] 或 [4,5,3]），最多支持5个，最少2个关键词组合
     * @param $sceneDesc    服务场景描述，15个字以内
     * @return array
     * @throws \Exception
     */
    public function addTemplate($tid,$kidList,$sceneDesc){
        return HttpClient::create()->postJson("wxaapi/newtmpl/addtemplate?access_token=ACCESS_TOKEN",[
            "tid"=>$tid,"kidList"=>$kidList,"sceneDesc"=>$sceneDesc
        ])->toArray();
    }

    /**
     * subscribeMessage.deleteTemplate
     * 删除帐号下的个人模板
     * @param $priTmplId    要删除的模板id
     * @return array
     * @throws \Exception
     */
    public function deleteTemplate($priTmplId){
        return HttpClient::create()->postJson("wxaapi/newtmpl/deltemplate?access_token=ACCESS_TOKEN",[
            "priTmplId"=>$priTmplId
        ])->toArray();
    }

    /**
     * subscribeMessage.getCategory
     * 获取小程序账号的类目
     * @return array
     * @throws \Exception
     */
    public function getCategory(){
        return HttpClient::create()->get("wxaapi/newtmpl/getcategory?access_token=ACCESS_TOKEN")->toArray();
    }

    /**
     * subscribeMessage.getPubTemplateKeyWordsById
     * 获取模板标题下的关键词列表
     * @param $tid          模板标题 id，可通过接口获取
     * @return array
     * @throws \Exception
     */
    public function getPubTemplateKeyWordsById($tid){
        return HttpClient::create()->get("wxaapi/newtmpl/getpubtemplatekeywords?access_token=ACCESS_TOKEN",[
            "tid"=>$tid
        ])->toArray();
    }

    /**
     * subscribeMessage.getPubTemplateTitleList
     * 获取帐号所属类目下的公共模板标题
     * @param $ids          类目 id，多个用逗号隔开
     * @param $start        用于分页，表示从 start 开始。从 0 开始计数
     * @param $limit        用于分页，表示拉取 limit 条记录。最大为 30
     * @return array
     * @throws \Exception
     */
    public function getPubTemplateTitleList($ids,$start,$limit){
        return HttpClient::create()->get("wxaapi/newtmpl/getpubtemplatetitles?access_token=ACCESS_TOKEN",[
            "ids"=>$ids,"start"=>$start,"limit"=>$limit
        ])->toArray();
    }

    /**
     * subscribeMessage.getTemplateList
     * 获取当前帐号下的个人模板列表
     * @return array
     * @throws \Exception
     */
    public function getTemplateList(){
        return HttpClient::create()->get("wxaapi/newtmpl/gettemplate?access_token=ACCESS_TOKEN")->toArray();
    }

    /**
     * subscribeMessage.send
     * 发送订阅消息
     * @param $touser                   接收者（用户）的 openid
     * @param $template_id              所需下发的订阅模板id
     * @param $data                     模板内容，格式形如 { "key1": { "value": any }, "key2": { "value": any } }
     * @param null $miniprogram_state   跳转小程序类型：developer为开发版；trial为体验版；formal为正式版；默认为正式版
     * @param null $page                点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转。
     * @param string $lang              进入小程序查看”的语言类型，支持zh_CN(简体中文)、en_US(英文)、zh_HK(繁体中文)、zh_TW(繁体中文)，默认为zh_CN
     * @return array
     * @throws \Exception
     */
    public function send($touser,$template_id,$data,$miniprogram_state=null,$page=null,$lang="zh_CN"){
        $array = ["touser"=>$touser,"template_id"=>$template_id,"data"=>$data];
        is_null($page) || $array["page"] = $page;
        is_null($miniprogram_state) || $array["miniprogram_state"] = $miniprogram_state;
        return HttpClient::create()->postJson("cgi-bin/message/subscribe/send?access_token=ACCESS_TOKEN",$array)->toArray();
    }

}