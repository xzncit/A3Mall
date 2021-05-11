<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\mini\PluginManager;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class PluginManager extends App {

    public function applyPlugin($action,$plugin_appid,$reason){
        return HttpClient::create()->postJson("wxa/plugin?access_token=ACCESS_TOKEN",[
            "action"=>$action,"plugin_appid"=>$plugin_appid,"reason"=>$reason
        ])->toArray();
    }

    /**
     * pluginManager.getPluginDevApplyList
     * 获取当前所有插件使用方（供插件开发者调用）
     * @param $action           此接口下填写 "dev_apply_list"
     * @param $page             要拉取第几页的数据
     * @param $num              每页的记录数
     * @return array
     * @throws \Exception
     */
    public function getPluginDevApplyList($action,$page,$num){
        return HttpClient::create()->postJson("wxa/devplugin?access_token=ACCESS_TOKEN",[
            "action"=>$action,"page"=>$page,"num"=>$num
        ])->toArray();
    }

    /**
     * pluginManager.getPluginList
     * 查询已添加的插件
     * @param $action
     * @return array
     * @throws \Exception
     */
    public function getPluginList($action){
        return HttpClient::create()->postJson("wxa/plugin?access_token=ACCESS_TOKEN",[
            "action"=>$action
        ])->toArray();
    }

    /**
     * pluginManager.setDevPluginApplyStatus
     * 修改插件使用申请的状态（供插件开发者调用）
     * @param $action       修改操作
     * @param $appid        使用者的 appid。同意申请时填写
     * @param $reason       拒绝理由。拒绝申请时填写
     * @return array
     * @throws \Exception
     */
    public function setDevPluginApplyStatus($action,$appid,$reason){
        return HttpClient::create()->postJson("wxa/devplugin?access_token=ACCESS_TOKEN",[
            "action"=>$action,"appid"=>$appid,"reason"=>$reason
        ])->toArray();
    }

    /**
     * pluginManager.unbindPlugin
     * 删除已添加的插件
     * @param $action           此接口下填写 "unbind"
     * @param $plugin_appid     插件 appId
     * @return array
     * @throws \Exception
     */
    public function unbindPlugin($action,$plugin_appid){
        return HttpClient::create()->postJson("wxa/plugin?access_token=ACCESS_TOKEN",[
            "action"=>$action,"plugin_appid"=>$plugin_appid
        ])->toArray();
    }

}