<?php
namespace mall\library\wechat\chat\module;

use mall\library\wechat\chat\BasicWeChat;

class Menu extends BasicWeChat{

    /**
     * 自定义菜单查询接口
     * @return array
     */
    public function getMenu(){
        $url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=ACCESS_TOKEN";
        return $this->httpGet($url);
    }

    /**
     * 自定义菜单删除接口
     * @return array
     */
    public function delete(){
        $url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=ACCESS_TOKEN";
        return $this->httpGet($url);
    }

    /**
     * 自定义菜单创建
     * @param array $data
     * @return array
     */
    public function create(array $data){
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, $data);
    }

    /**
     * 创建个性化菜单
     * @param array $data
     * @return array
     */
    public function addConditional(array $data){
        $url = "https://api.weixin.qq.com/cgi-bin/menu/addconditional?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, $data);
    }

    /**
     * 删除个性化菜单
     * @param string $menuid
     * @return array
     */
    public function delConditional($menuid){
        $url = "https://api.weixin.qq.com/cgi-bin/menu/delconditional?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, ['menuid' => $menuid]);
    }

    /**
     * 测试个性化菜单匹配结果
     * @param string $openid
     * @return array
     */
    public function tryConditional($openid){
        $url = "https://api.weixin.qq.com/cgi-bin/menu/trymatch?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, ['user_id' => $openid]);
    }

}