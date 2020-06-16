<?php
namespace mall\library\wechat\chat;

use mall\library\wechat\chat\lib\Prpcrypt;

class WeChatPush extends BasicWeChat {

    protected $postXml;
    protected $receive;
    protected $message;
    protected $encryptType;

    public function __construct(){
        parent::__construct();

        if(empty($this->config["token"])){
            throw new \Exception("公众号 Token 为空",0);
        }

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $this->postXml = file_get_contents("php://input");
            $this->encryptType = $_REQUEST["encrypt_type"];
            if($this->encryptType == 'aes'){
                if(empty($this->config["enaes_key"])){
                    throw new \Exception("公众号 EnAesKey 为空",0);
                }

                $prpcrypt = new Prpcrypt($this->config["enaes_key"]);
                $result = $this->xml2arr($this->postXml);
                $array = $prpcrypt->decrypt($result['Encrypt']);
                if (intval($array[0]) > 0) {
                    throw new \Exception($array[1], $array[0]);
                }

                list($this->postXml, $this->config["appid"]) = [$array[1], $array[2]];
            }

            $this->receive = $this->xml2arr($this->postXml);
        }else if($_SERVER['REQUEST_METHOD'] == "GET" && $this->checkSignature()){
            @ob_clean();
            exit($this->request["echostr"]);
        }else{
            throw new \Exception("请求出错！",0);
        }
    }

    /**
     * 消息是否需要加密
     * @return boolean
     */
    public function isEncrypt(){
        return $this->encryptType === 'aes';
    }

    /**
     * 设置文本消息
     * @param string $content 文本内容
     * @return $this
     */
    public function text($content = ''){
        $this->message = [
            'MsgType'      => 'text',
            'CreateTime'   => time(),
            'Content'      => $content,
            'ToUserName'   => $this->getOpenid(),
            'FromUserName' => $this->getToOpenid(),
        ];
        return $this;
    }

    /**
     * 设置回复图文
     * @param array $newsData
     * @return $this
     */
    public function news($newsData = []){
        $this->message = [
            'CreateTime'   => time(),
            'MsgType'      => 'news',
            'Articles'     => $newsData,
            'ToUserName'   => $this->getOpenid(),
            'FromUserName' => $this->getToOpenid(),
            'ArticleCount' => count($newsData),
        ];
        return $this;
    }

    /**
     * 设置图片消息
     * @param string $mediaId 图片媒体ID
     * @return $this
     */
    public function image($mediaId = ''){
        $this->message = [
            'MsgType'      => 'image',
            'CreateTime'   => time(),
            'ToUserName'   => $this->getOpenid(),
            'FromUserName' => $this->getToOpenid(),
            'Image'        => ['MediaId' => $mediaId],
        ];
        return $this;
    }

    /**
     * 回复消息
     */
    public function reply(array $data = [], $return = false, $isEncrypt = false){
        $xml = $this->arr2xml(empty($data) ? $this->message : $data);
        if ($this->isEncrypt() || $isEncrypt) {
            $prpcrypt = new Prpcrypt($this->config["enaes_key"]);
            $array = $prpcrypt->encrypt($xml, $this->config["appid"]);
            if ($array[0] > 0) {
                throw new \Exception('Encrypt Error.', '0');
            }
            list($timestamp, $encrypt) = [time(), $array[1]];
            $nonce = rand(77, 999) * rand(605, 888) * rand(11, 99);
            $tmpArr = [$this->config["token"], $timestamp, $nonce, $encrypt];
            sort($tmpArr, SORT_STRING);
            $signature = sha1(implode($tmpArr));
            $format = "<xml><Encrypt><![CDATA[%s]]></Encrypt><MsgSignature><![CDATA[%s]]></MsgSignature><TimeStamp>%s</TimeStamp><Nonce><![CDATA[%s]]></Nonce></xml>";
            $xml = sprintf($format, $encrypt, $signature, $timestamp, $nonce);
        }

        if ($return) {
            return $xml;
        }else{
            @ob_clean();
            echo $xml;
        }
    }

    /**
     * 验证来自微信服务器
     * @param string $str
     * @return bool
     */
    private function checkSignature($str = ''){
        $nonce = $this->request["nonce"];
        $timestamp = $this->request["timestamp"];
        $msg_signature = $this->request['msg_signature'];
        $signature = empty($msg_signature) ? $this->request["signature"] : $msg_signature;
        $tmpArr = [$this->config["token"], $timestamp, $nonce, $str];
        sort($tmpArr, SORT_STRING);
        return sha1(implode($tmpArr)) === $signature;
    }

    /**
     * 获取公众号推送对象
     * @param null|string $field 指定获取字段
     * @return array
     */
    public function getReceive($field = null){
        return $this->receive[$field];
    }

    /**
     * 获取当前微信OPENID
     * @return string
     */
    public function getOpenid(){
        return $this->receive["FromUserName"];
    }

    /**
     * 获取当前推送消息类型
     * @return string
     */
    public function getMsgType(){
        return $this->receive["MsgType"];
    }

    /**
     * 获取当前推送消息ID
     * @return string
     */
    public function getMsgId(){
        return $this->receive["MsgId"];
    }

    /**
     * 获取当前推送时间
     * @return integer
     */
    public function getMsgTime(){
        return $this->receive["CreateTime"];
    }

    /**
     * 获取当前推送公众号
     * @return string
     */
    public function getToOpenid(){
        return $this->receive["ToUserName"];
    }
}