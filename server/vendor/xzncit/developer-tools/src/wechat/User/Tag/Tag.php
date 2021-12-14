<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat\User\Tag;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class Tag extends App {

    /**
     * 创建标签
     * @param string $name      标签名（30个字符以内）
     * @return array
     * @throws \Exception
     */
    public function create($name){
        return HttpClient::create()->postJson("cgi-bin/tags/create?access_token=ACCESS_TOKEN",[
            'tag' => ['name' => $name]
        ])->toArray();
    }

    /**
     * 获取公众号已创建的标签
     * @return array
     * @throws \Exception
     */
    public function get(){
        return HttpClient::create()->get("cgi-bin/tags/get?access_token=ACCESS_TOKEN")->toArray();
    }

    /**
     * 编辑标签
     * @param $name
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function update($name,$id){
        return HttpClient::create()->postJson("cgi-bin/tags/update?access_token=ACCESS_TOKEN",[
            'tag' => ['name' => $name, 'id' => $id]
        ])->toArray();
    }

    /**
     * 删除标签
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function delete($id){
        return HttpClient::create()->postJson("cgi-bin/tags/delete?access_token=ACCESS_TOKEN",[
            'tag' => ['id' => $id]
        ])->toArray();
    }

    /**
     * 获取标签下粉丝列表
     * @param int    $tagid
     * @param string $next_openid
     * @return array
     * @throws \Exception
     */
    public function getTagUser($tagid,$next_openid=""){
        return HttpClient::create()->postJson("cgi-bin/user/tag/get?access_token=ACCESS_TOKEN",[
            "tagid"=>$tagid,"next_openid"=>$next_openid
        ])->toArray();
    }

    /**
     * 批量为用户打标签
     * 标签功能目前支持公众号为用户打上最多20个标签。
     * @param array $openid_list
     * @param int   $tagid
     * @return array
     * @throws \Exception
     */
    public function batchtagging(array $openid_list,$tagid){
        return HttpClient::create()->postJson("cgi-bin/tags/members/batchtagging?access_token=ACCESS_TOKEN",[
            "openid_list"=>$openid_list,"tagid"=>$tagid
        ])->toArray();
    }

    /**
     * 批量为用户取消标签
     * @param array $openid_list
     * @param int   $tagid
     * @return array
     * @throws \Exception
     */
    public function batchuntagging(array $openid_list,$tagid){
        return HttpClient::create()->postJson("cgi-bin/tags/members/batchuntagging?access_token=ACCESS_TOKEN",[
            "openid_list"=>$openid_list,"tagid"=>$tagid
        ])->toArray();
    }

    /**
     * 获取用户身上的标签列表
     * @param $openid
     * @return array
     * @throws \Exception
     */
    public function getidlist($openid){
        return HttpClient::create()->postJson("cgi-bin/tags/getidlist?access_token=ACCESS_TOKEN",[
            "openid"=>$openid
        ])->toArray();
    }
}