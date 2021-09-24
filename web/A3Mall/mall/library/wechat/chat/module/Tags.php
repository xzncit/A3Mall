<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\library\wechat\chat\module;

use mall\library\wechat\chat\BasicWeChat;

class Tags extends BasicWeChat{

    /**
     * 获取粉丝标签列表
     */
    public function getTags(){
        $url = "https://api.weixin.qq.com/cgi-bin/tags/get?access_token=ACCESS_TOKEN";
        return $this->httpGet($url);
    }

    /**
     * 创建粉丝标签
     * @param string $name
     * @return array
     */
    public function createTags($name){
        $url = "https://api.weixin.qq.com/cgi-bin/tags/create?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, ['tag' => ['name' => $name]]);
    }

    /**
     * 更新粉丝标签
     * @param integer $id 标签ID
     * @param string $name 标签名称
     * @return array
     */
    public function updateTags($id, $name){
        $url = "https://api.weixin.qq.com/cgi-bin/tags/update?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, ['tag' => ['name' => $name, 'id' => $id]]);
    }

    /**
     * 删除粉丝标签
     * @param int $tagId
     * @return array
     */
    public function deleteTags($tagId){
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/delete?access_token=ACCESS_TOKEN';
        return $this->httpPost($url, ['tag' => ['id' => $tagId]]);
    }

    /**
     * 批量为用户打标签
     * @param array $openids
     * @param integer $tagId
     * @return array
     */
    public function batchTagging(array $openids, $tagId){
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token=ACCESS_TOKEN';
        return $this->httpPost($url, ['openid_list' => $openids, 'tagid' => $tagId]);
    }

    /**
     * 批量为用户取消标签
     * @param array $openids
     * @param integer $tagId
     * @return array
     */
    public function batchUntagging(array $openids, $tagId){
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchuntagging?access_token=ACCESS_TOKEN';
        return $this->httpPost($url, ['openid_list' => $openids, 'tagid' => $tagId]);
    }

    /**
     * 获取用户身上的标签列表
     * @param string $openid
     * @return array
     */
    public function getUserTagId($openid){
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/getidlist?access_token=ACCESS_TOKEN';
        return $this->httpPost($url, ['openid' => $openid]);
    }
}