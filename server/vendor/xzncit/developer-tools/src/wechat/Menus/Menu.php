<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat\Menus;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

/**
 * 自定义菜单类
 * Class Menu
 * @package xzncit\wechat\Menus
 */
class Menu extends App {

    /**
     * 创建自定义菜单
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function create($data){
        return HttpClient::create()->postJson("cgi-bin/menu/create?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 查询自定义菜单
     * @return array
     * @throws \Exception
     */
    public function query(){
        return HttpClient::create()->get("cgi-bin/get_current_selfmenu_info?access_token=ACCESS_TOKEN")->toArray();
    }

    /**
     * 删除菜单
     * @return array
     * @throws \Exception
     */
    public function delete(){
        return HttpClient::create()->get("cgi-bin/menu/delete?access_token=ACCESS_TOKEN")->toArray();
    }

    /**
     * 创建个性化菜单
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function addConditional($data){
        return HttpClient::create()->postJson("cgi-bin/menu/addconditional?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 删除个性化菜单
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function delConditional($data){
        return HttpClient::create()->postJson("cgi-bin/menu/delconditional?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 测试个性化菜单匹配结果
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function tryMatch($data){
        return HttpClient::create()->postJson("cgi-bin/menu/trymatch?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 查询自定义菜单的结构
     * @return array
     * @throws \Exception
     */
    public function getMenuStruct(){
        return HttpClient::create()->get("cgi-bin/menu/get?access_token=ACCESS_TOKEN")->toArray();
    }

}