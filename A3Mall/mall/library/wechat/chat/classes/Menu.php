<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\library\wechat\chat\classes;

use mall\library\wechat\chat\WeChat;

class Menu {

    public static function save($data=[]){
        if(empty($data)){
            try{
                WeChat::Menu()->delete();
            }catch(\Exception $e){
                throw new \Exception($e->getMessage(),0);
            }
        }else{
            try{
                $value = self::buildMenuData($data);
                WeChat::Menu()->create(['button' => $value]);
                return $value;
            }catch(\Exception $e){
                throw new \Exception($e->getMessage(),0);
            }
        }
    }

    /**
     * 菜单数据处理
     * @param array $list
     * @return array
     */
    private static function buildMenuData(array $list){
        foreach ($list as &$vo) {
            unset($vo['active'], $vo['show']);
            if (empty($vo['sub_button'])) {
                $vo = self::buildMenuItemData($vo);
            } else {
                $item = ['name' => $vo['name'], 'sub_button' => []];
                foreach ($vo['sub_button'] as &$sub) {
                    unset($sub['active'], $sub['show']);
                    array_push($item['sub_button'], self::buildMenuItemData($sub));
                }
                $vo = $item;
            }
        }
        return $list;
    }

    /**
     * 单个微信菜单数据处理
     * @param array $item
     * @return array
     */
    private static function buildMenuItemData(array $item){
        switch (strtolower($item['type'])) {
            case 'pic_weixin':
            case 'pic_sysphoto':
            case 'scancode_push':
            case 'location_select':
            case 'scancode_waitmsg':
            case 'pic_photo_or_album':
                return [
                    'name' => $item['name'], 'type' => $item['type'],
                    'key' => isset($item['key']) ? $item['key'] : $item['type']
                ];
            case 'click':
                if (empty($item['key'])) {
                    throw new \Exception("匹配规则存在空的选项",0);
                }
                return ['name' => $item['name'], 'type' => $item['type'], 'key' => $item['key']];
            case 'view':
                return ['name' => $item['name'], 'type' => $item['type'], 'url' => $item['url']];
            case 'miniprogram':
                return [
                    'name' => $item['name'], 'type' => $item['type'],
                    'url' => $item['url'], 'appid' => $item['appid'],
                    'pagepath' => $item['pagepath']
                ];
        }
    }
}