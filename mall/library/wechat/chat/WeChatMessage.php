<?php
namespace mall\library\wechat\chat;


use mall\utils\Tool;
use think\facade\Db;

class WeChatMessage extends BasicWeChat {

    protected $appid;
    protected $openid;
    protected $encrypt;
    protected $fromOpenid;
    protected $receive;
    protected $wechat;

    public function __construct($wechat){
        $this->wechat = $wechat;
        $this->appid = $this->config["appid"];
        $this->openid = $this->wechat->getOpenid();
        $this->encrypt = $this->wechat->isEncrypt();
        $this->receive = $this->wechat->strToLower($this->wechat->getReceive());
        $this->fromOpenid = $this->receive['tousername'];
    }

    /**
     * 文件消息处理
     */
    public function text(){
        return $this->keys("wechat_keys#keys#{$this->receive['content']}", false);
    }

    /**
     * 事件消息处理
     */
    protected function event(){
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
                return $this->keys("wechat_keys#keys#{$this->receive['eventkey']}", false);
            case 'scancode_push': // 扫码推事件
            case 'scancode_waitmsg': // 扫码推事件且弹出“消息接收中”提示框
                if (empty($this->receive['scancodeinfo'])) return false;
                if (empty($this->receive['scancodeinfo']['scanresult'])) return false;
                return $this->keys("wechat_keys#keys#{$this->receive['scancodeinfo']['scanresult']}", false);
            case 'scan': // 用户已关注时的事件推送
                if (empty($this->receive['eventkey'])) return false;
                return $this->keys("wechat_keys#keys#{$this->receive['eventkey']}", false);
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
        list($table, $field, $value) = explode('#', $rule . '##');
        $data = Db::name($table)->where([$field => $value])->find();
        if (empty($data['type']) || (array_key_exists('status', $data) && empty($data['status']))) {
            return $isLast ? false : $this->keys('wechat_keys#keys#default', true);
        }
        switch (strtolower($data['type'])) {
            case 'keys':
                $content = empty($data['content']) ? $data['name'] : $data['content'];
                return $this->keys("wechat_keys#keys#{$content}", $isLast);
            case 'text':
                return $this->sendMessage('text', ['content' => $data['content']]);
            case 'image':
                if (empty($data['image_url']) || !($mediaId = $this->upload(Tool::thumb($data['image_url'],'',true), 'image'))) return false;
                return $this->sendMessage('image', ['media_id' => $mediaId]);
            case 'news':
                list($news, $articles) = [$this->news($data['news_id']), []];
                if (empty($news['articles'])) return false;
                foreach ($news['articles'] as $vo) array_push($articles, [
                    'url'   => createUrl("@api/wechat.news/view", [], false, true) . "?id={$vo['id']}",
                    'title' => $vo['title'], 'picurl' => Tool::thumb($vo['local_url'],"",true), 'description' => $vo['digest'],
                ]);
                return $this->sendMessage('news', ['articles' => $articles]);
            default:
                return false;
        }
    }

    /**
     * 发送消息到微信
     * @param string $type 消息类型（text|image|news）
     * @param array $data 消息内容数据对象
     */
    private function sendMessage($type, $data){
        switch (strtolower($type)) {
            case 'text': // 发送文本消息
                $reply = ['CreateTime' => time(), 'MsgType' => 'text', 'ToUserName' => $this->openid, 'FromUserName' => $this->fromOpenid, 'Content' => $data['content']];
                return $this->wechat->reply($reply, true, $this->encrypt);
            case 'image': // 发送图片消息
                return $this->buildMessage($type, ['MediaId' => $data['media_id']]);
            case 'news': // 发送图文消息
                $articles = [];
                foreach ($data['articles'] as $article) {
                    array_push($articles, ['PicUrl' => $article['picurl'], 'Title' => $article['title'], 'Description' => $article['description'], 'Url' => $article['url']]);
                }
                $reply = ['CreateTime' => time(), 'MsgType' => 'news', 'ToUserName' => $this->openid, 'FromUserName' => $this->fromOpenid, 'Articles' => $articles, 'ArticleCount' => count($articles)];
                return $this->wechat->reply($reply, true, $this->encrypt);
            default:
                return 'success';
        }
    }

    /**
     * 消息数据生成
     */
    private function buildMessage($type, $data = []){
        $reply = ['CreateTime' => time(), 'MsgType' => strtolower($type), 'ToUserName' => $this->openid, 'FromUserName' => $this->fromOpenid];
        if (!empty($data)) $reply[ucfirst(strtolower($type))] = $data;
        return $this->wechat->reply($reply, true, $this->encrypt);
    }

    /**
     * 同步粉丝状态
     */
    private function updateFansinfo($subscribe = true){
        if ($subscribe) {
            try {
                $user = Wechat::User()->getUserInfo($this->openid);
                if (!empty($user['subscribe_time'])) {
                    $user['subscribe_create_time'] = $user['subscribe_time'];
                }

                if (isset($user['tagid_list']) && is_array($user['tagid_list'])) {
                    $user['tagid_list'] = is_array($user['tagid_list']) ? join(',', $user['tagid_list']) : '';
                }

                unset($user['privilege'], $user['groupid']);
                return Db::name("wechat_users")->insert(array_merge($user,[
                    'subscribe' => '1', 'appid' => $this->appid
                ]));
            } catch (\Exception $e) {

                return false;
            }
        } else {
            return Db::name("wechat_users")->where([
                'openid' => $this->openid,
                'appid' => $this->appid
            ])->update(['subscribe' => '0']);
        }
    }

    /**
     * 通过图文ID读取图文信息
     * @param integer $id 本地图文ID
     * @param array $where 额外的查询条件
     * @return array
     */
    private function news($id, $where = []){
        $data = Db::name('wechat_news')->where(['id' => $id])->where($where)->find();
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
        $where = ['md5' => md5($url), 'appid' => $this->config["appid"]];
        if (($mediaId = Db::name('wechat_media')->where($where)->value('media_id'))) {
            return $mediaId;
        }

        $result = WeChat::Media()->addMaterial($url, $type, $videoInfo);

        $data = [
            'local_url' => $url, 'md5' => $where['md5'], 'appid' => $where['appid'], 'type' => $type,
            'media_url' => isset($result['url']) ? $result['url'] : '', 'media_id' => $result['media_id'],
        ];
        if(Db::name('wechat_media')->where($where)->count()){
            Db::name('wechat_media')->strict(false)->where($where)->update($data);
        }else{
            Db::name('wechat_media')->strict(false)->insert($data);
        }

        return $result['media_id'];
    }
}