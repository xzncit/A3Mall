<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace xzncit\mini\Live\Role;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class Role extends App {

    private $error = [
        "-1"        => "系统错误",
        "400001"    => "微信号不合规",
        "400002"    => "微信号需要实名认证，仅设置主播角色时可能出现",
        "400003"    => "添加角色达到上限（管理员10个，运营者500个，主播500个）",
        "400004"    => "重复添加角色",
        "400005"    => "主播角色删除失败，该主播存在未开播的直播间"
    ];

    /**
     * 设置成员角色
     * 调用此接口设置小程序直播成员的管理员、运营者和主播角色
     * @param $username     微信号
     * @param $role         取值[1-管理员，2-主播，3-运营者]，设置超级管理员将无效
     * @return array
     * @throws \Exception
     */
    public function add($username,$role){
        return HttpClient::create()->postJson("wxaapi/broadcast/role/addrole?access_token=ACCESS_TOKEN",[
            "username"=>$username,"role"=>$role
        ])->toArray();
    }

    /**
     * 解除成员角色
     * 调用此接口移除小程序直播成员的管理员、运营者和主播角色
     * @param $username     微信号
     * @param $role         取值[1-管理员，2-主播，3-运营者]，删除超级管理员将无效
     * @return array
     * @throws \Exception
     */
    public function delete($username,$role){
        return HttpClient::create()->postJson("wxaapi/broadcast/role/deleterole?access_token=ACCESS_TOKEN",[
            "username"=>$username,"role"=>$role
        ])->toArray();
    }

    /**
     * 查询成员列表
     * 调用此接口查询小程序直播成员列表
     * @param $role         取值 [-1-所有成员， 0-超级管理员，1-管理员，2-主播，3-运营者]
     * @param $offset       起始偏移量
     * @param $limit        查询个数，最大30，默认10
     * @param $keyword      搜索的微信号，不传返回全部
     * @return array
     * @throws \Exception
     */
    public function getList($role,$offset,$limit,$keyword){
        return HttpClient::create()->postJson("wxaapi/broadcast/role/getrolelist?access_token=ACCESS_TOKEN",[
            "role"=>$role,"offset"=>$offset,"limit"=>$limit,"keyword"=>$keyword
        ])->toArray();
    }

    /**
     * 获取错误信息
     * @param $code
     * @return string
     */
    public function getError($code){
        if($code == 0) return "ok";
        return isset($this->error[$code]) ? $this->error[$code] : "未知错误";
    }

}