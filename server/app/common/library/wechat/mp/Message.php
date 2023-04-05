<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\library\wechat\mp;

use app\common\library\wechat\Factory;
use app\common\service\wechat\WechatUser;
use mall\utils\Tool;
use think\facade\Db;
use xzncit\core\message\News;
use xzncit\core\message\Raw;
use xzncit\core\message\Text;
use xzncit\core\message\Image as ImageUtils;

class Message {

    protected $receive = [];

    public function __construct($data){
        $this->receive = $data;
    }

    public function text(){
        return $this->keys("wechat_keys#keys#{$this->receive['content']}");
    }

    public function event(){
        switch (strtolower($this->receive['event'])) {
            case 'subscribe': // 用户关注
                $this->updateFansinfo(true);
                if (isset($this->receive['eventkey']) && is_string($this->receive['eventkey'])) {
                    //扫描带参数二维码事件
                    if (($key = preg_replace('/^qrscene_/i', '', $this->receive['eventkey']))) {
                        return $this->keys("wechat_keys#keys#{$key}", false, true);
                    }
                }

                return $this->keys('wechat_keys#keys#subscribe', true);
            case 'unsubscribe': // 取消关注事件
                return $this->updateFansinfo(false);
            case 'click': // 关键字事件
                return $this->keys("wechat_keys#keys#{$this->receive['eventkey']}");
            case 'scancode_push': // 扫码推事件
            case 'scancode_waitmsg': // 扫码推事件且弹出“消息接收中”提示框
                if (empty($this->receive['scancodeinfo'])) return false;
                if (empty($this->receive['scancodeinfo']['scanresult'])) return false;
                return $this->keys("wechat_keys#keys#{$this->receive['scancodeinfo']['scanresult']}");
            case 'scan': // 用户已关注时的事件推送
                if (empty($this->receive['eventkey'])) return false;
                return $this->keys("wechat_keys#keys#{$this->receive['eventkey']}");
            default:
                return false;
        }
    }

    /**
     * 关键字处理
     * @param string $rule 关键字规则
     * @param boolean $isLast 重复回复消息处理
     */
    private function keys($rule, $isLast = false){
        list($table, $field, $value) = explode("#", $rule);
        $data = Db::name($table)->where([$field => $value])->find();
        if (empty($data["type"]) || (array_key_exists("status", $data) && $data["status"] == 1)) {
            return $isLast ? false : $this->keys("wechat_keys#keys#defaults", true);
        }

        switch (strtolower($data["type"])) {
            case "keys":
                $content = empty($data["content"]) ? $data["name"] : $data["content"];
                return $this->keys("wechat_keys#keys#{$content}", $isLast);
            case "text":
                return new Text($data['content']);
            case 'image':
                if (empty($data['image_url']) || !($mediaId = $this->upload(Tool::getRootPath().'public/' . $data['image_url'], 'image'))) {
                    return false;
                }

                return new ImageUtils($mediaId);
            case 'news':
                $news = $this->news($data['news_id']);
                if (empty($news['articles'])) {
                    return false;
                }

                $articles = [];
                foreach ($news['articles'] as $vo){
                    $articles[] = [
                        'Url'   => createUrl("api/wechat.news/view", [], false, true) . "?id={$vo['id']}",
                        'Title' => $vo['title'],
                        'PicUrl' => Tool::thumb($vo['local_url'],"",true),
                        'Description' => $vo['digest'],
                    ];
                }

                return new News($articles);
            default:
                return new Raw("您要查找的内容不存在");
        }
    }

    /**
     * 同步粉丝状态
     */
    private function updateFansinfo($subscribe = true){
        try {
            Db::startTrans();
            if($subscribe){
                $user = Factory::wechat()->user->getUserInfo($this->receive["FromUserName"]);
                WechatUser::register($user);
            }else{
                Db::name("wechat_users")->where([ 'openid' => $this->receive["FromUserName"] ])->update(['subscribe' => '0']);
            }

            Db::commit();
            return true;
        }catch (\Exception $ex){
            Db::rollback();
            return false;
        }
    }

    /**
     * 通过图文ID读取图文信息
     * @param integer $id 本地图文ID
     * @param array $where 额外的查询条件
     * @return array
     */
    private function news($id, $condition = []){
        $data = Db::name('wechat_news')->where(['id' => $id])->where($condition)->find();
        list($data['articles'], $articleIds) = [[], explode(',', $data['article_id'])];
        $articles = Db::name('wechat_news_article')->whereIn('id', $articleIds)->select()->toArray();

        foreach ($articleIds as $article_id) {
            foreach ($articles as $article) {
                if (intval($article['id']) === intval($article_id)) array_push($data['articles'], $article);
                unset($article['create_time'], $article['create_time']);
            }
        }

        return $data;
    }

    private function upload($url, $type = 'image', $videoInfo = []){
        $condition = ['md5' => md5($url)];
        if (($mediaId = Db::name('wechat_media')->where($condition)->value('media_id'))) {
            return $mediaId;
        }

        $result = Factory::wechat()->materials->add($url,$type,false,$videoInfo);
        $data = [
            'local_url' => $url, 'md5' => $condition['md5'], 'type' => $type,
            'media_url' => isset($result['url']) ? $result['url'] : '', 'media_id' => $result['media_id'],
        ];

        if(Db::name('wechat_media')->where($condition)->count()){
            Db::name('wechat_media')->strict(false)->where($condition)->update($data);
        }else{
            Db::name('wechat_media')->strict(false)->insert($data);
        }

        return $result['media_id'];
    }

}