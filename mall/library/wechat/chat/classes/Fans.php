<?php
namespace mall\library\wechat\chat\classes;

use mall\library\wechat\chat\WeConfig;
use think\facade\Db;
use mall\library\wechat\chat\WeChat;

class Fans {

    public static function syncFans($next = '', $done = 0){
        $appid = WeConfig::get("wechat.appid");
        while (!is_null($next) && is_array($result = Wechat::User()->getUserList($next)) && !empty($result['data']['openid'])) {
            foreach (array_chunk($result['data']['openid'], 100) as $openids) {
                if (is_array($list = Wechat::User()->getBatchUserInfo($openids)) && !empty($list['user_info_list'])) {
                    foreach ($list['user_info_list'] as $user) {
                        self::saveFans($user, $appid);
                    }
                }
            }
            $next = $result['total'] > $done ? $result['next_openid'] : null;
        }

        return $done;
    }

    public static function syncBlack($next = '', $done = 0){
        while (!is_null($next) && is_array($result = WeChat::User()->getBlackList($next)) && !empty($result['data']['openid'])) {
            $done += $result['count'];
            foreach (array_chunk($result['data']['openid'], 100) as $chunk) {
                Db::name('wechat_users')->where(['is_black' => '0'])->whereIn('openid', $chunk)->update(['is_black' => '1']);
            }

            $next = $result['total'] > $done ? $result['next_openid'] : null;
        }

        return empty($result['total']) ? 0 : $result['total'];
    }

    public static function syncTags($index = 0){
        $appid = WeConfig::get("wechat.appid");
        if (is_array($list = Wechat::Tags()->getTags()) && !empty($list['tags'])) {
            $count = count($list['tags']);
            foreach ($list['tags'] as &$tag) {
                $tag['appid'] = $appid;
            }

            Db::name('wechat_users_tags')->where(['appid' => $appid])->delete();
            Db::name('wechat_users_tags')->insertAll($list['tags']);
        }

        return $count;
    }

    public static function saveFans($user,$appid){
        if (!empty($user['subscribe_time'])) {
            $user['subscribe_create_time'] = $user['subscribe_time'];
        }

        if (isset($user['tagid_list']) && is_array($user['tagid_list'])) {
            $user['tagid_list'] = is_array($user['tagid_list']) ? join(',', $user['tagid_list']) : '';
        }

        unset($user['privilege'], $user['groupid']);
        $data = array_merge($user,[
            'subscribe' => '1', 'appid' => $appid
        ]);
        $condition = ["openid"=>$user["openid"],"appid"=>$appid];
        if(Db::name("wechat_users")->where($condition)->count()){
            unset($data["subscribe_create_time"]);
            return Db::name("wechat_users")->where($condition)->update($data);
        }else{
            return Db::name("wechat_users")->insert($data);
        }
    }
}