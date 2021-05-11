<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat\Subscription;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

/**
 * 订阅通知
 * Class Subscription
 * @package xzncit\wechat\Subscription
 */
class Subscription extends App {

    /**
     * 选用模板
     * 从公共模板库中选用模板，到私有模板库中
     * @param string $tid           模板标题 id，可通过getPubTemplateTitleList接口获取，也可登录公众号后台查看获取
     * @param array $kidList        开发者自行组合好的模板关键词列表，关键词顺序可以自由搭配（例如 [3,5,4] 或 [4,5,3]），最多支持5个，最少2个关键词组合
     * @param string $sceneDesc     服务场景描述，15个字以内
     * @return mixed|string|null
     * @throws \Exception
     */
    public function addTemplate($tid,array $kidList,$sceneDesc){
        return HttpClient::create()->postJson("wxaapi/newtmpl/addtemplate?access_token=ACCESS_TOKEN",[
            "tid"=>$tid,"kidList"=>$kidList,"sceneDesc"=>$sceneDesc
        ])->toArray();
    }

    /**
     * 删除模板
     * 删除私有模板库中的模板
     *
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
     * 获取公众号类目
     * @return array
     * @throws \Exception
     */
    public function getCategory(){
        return HttpClient::create()->get("wxaapi/newtmpl/getcategory?access_token=ACCESS_TOKEN")->toArray();
    }

    /**
     * 获取模板中的关键词
     * @param $tid  模板标题 id，可通过接口获取
     * @return array
     * @throws \Exception
     */
    public function getPubTemplateKeyWordsById($tid){
        return HttpClient::create()->get("wxaapi/newtmpl/getpubtemplatekeywords?access_token=ACCESS_TOKEN",[
            "tid"=>$tid
        ])->toArray();
    }

    /**
     * 获取类目下的公共模板
     * 获取类目下的公共模板，可从中选用模板使用
     * @param string $ids      类目 id，多个用逗号隔开
     * @param number $start    用于分页，表示从 start 开始，从 0 开始计数
     * @param number $limit    用于分页，表示拉取 limit 条记录，最大为 30
     * @return array
     * @throws \Exception
     */
    public function getPubTemplateTitleList($ids,$start,$limit){
        return HttpClient::create()->get("wxaapi/newtmpl/getpubtemplatetitles?access_token=ACCESS_TOKEN",[
            "ids"=>$ids,"start"=>intval($start),"limit"=>intval($limit)
        ])->toArray();
    }

    /**
     * 获取私有模板列表
     * @return array
     * @throws \Exception
     */
    public function getTemplateList(){
        return HttpClient::create()->get("wxaapi/newtmpl/gettemplate?access_token=ACCESS_TOKEN")->toArray();
    }

    /**
     * 发送订阅通知
     * @param string $touser
     * @param string $template_id
     * @param array $data
     * @param string $page
     * @param array $miniprogram
     * @return array
     * @throws \Exception
     */
    public function send($touser="",$template_id="",$data=[],$page="",$miniprogram=[]){
        return HttpClient::create()->postJson("cgi-bin/message/subscribe/bizsend?access_token=ACCESS_TOKEN",[
            "touser"=>$touser,"template_id"=>$template_id,"page"=>$page,
            "miniprogram"=>$miniprogram,"data"=>$data
        ])->toArray();
    }

    
}