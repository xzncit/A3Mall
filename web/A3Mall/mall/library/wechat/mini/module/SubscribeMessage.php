<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace mall\library\wechat\mini\module;

use mall\library\wechat\mini\BasicWeMini;

class SubscribeMessage extends BasicWeMini {

    /**
     * 组合模板并添加至帐号下的个人模板库
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function addTemplate($data){
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/addtemplate?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, $data);
    }

    /**
     * 删除帐号下的个人模板
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function deleteTemplate($data){
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/deltemplate?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, $data);
    }

    /**
     * 获取小程序账号的类目
     * @return mixed
     * @throws \Exception
     */
    public function getCategory(){
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/getcategory?access_token=ACCESS_TOKEN";
        return $this->httpGet($url);
    }

    /**
     * 获取模板标题下的关键词列表
     * @param $tid
     * @return mixed
     * @throws \Exception
     */
    public function getPubTemplateKeyWordsById($tid){
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/getpubtemplatekeywords?access_token=ACCESS_TOKEN&tid={$tid}";
        return $this->httpGet($url);
    }

    /**
     * 获取帐号所属类目下的公共模板标题
     * @param $ids      类目 id，多个用逗号隔开
     * @param $start    用于分页，表示从 start 开始。从 0 开始计数。
     * @param $limit    用于分页，表示拉取 limit 条记录。最大为 30。
     * @return mixed
     * @throws \Exception
     */
    public function getPubTemplateTitleList($ids,$start,$limit){
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/getpubtemplatetitles?access_token=ACCESS_TOKEN&ids={$ids}&start={$start}&limit={$limit}";
        return $this->httpGet($url);
    }

    /**
     * 获取当前帐号下的个人模板列表
     */
    public function getTemplateList(){
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/gettemplate?access_token=ACCESS_TOKEN";
        return $this->httpGet($url);
    }

    /**
     * 发送订阅消息
     */
    public function send($data){
        $url = "https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, $data);
    }

}