<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat\Server;

use xzncit\core\App;
use xzncit\core\base\Prpcrypt;
use xzncit\core\exception\ConfigNotFoundException;
use xzncit\core\http\Request;
use xzncit\core\http\Response;
use xzncit\core\message\Raw;
use xzncit\core\Service;

class Server extends App {

    protected $receive;

    /**
     * Message constructor.
     * @param Service $app
     */
    public function __construct(Service $app){
        parent::__construct($app);
        $this->initialization();
    }

    protected function initialization(){
        switch(Request::getMethod()){
            case "POST":
                $data = file_get_contents("php://input");
                $this->receive = Response::xml2arr($data);
                if($this->isEncryptAES()){
                    if(empty($this->app->config["enaeskey"])){
                        throw new ConfigNotFoundException("wechat enaeskey cannot be empty!");
                    }

                    $prpcrypt = new Prpcrypt($this->app->config["enaeskey"]);
                    $array = $prpcrypt->decrypt($this->receive['Encrypt']);
                    if (intval($array[0]) > 0) {
                        throw new \Exception($array[1], $array[0]);
                    }

                    $this->receive = Response::xml2arr($array[1]);
                }
                break;
            case "GET":
                if(!$this->checkSignature()){
                    throw new \Exception("error verifying signature！",0);
                }

                @ob_clean();
                exit(Request::request("echostr"));
            default:
                throw new \Exception("Your current request is illegal！",0);
        }
    }

    /**
     * 被动回复处理
     * @param \Closure $callback
     * @return mixed
     */
    public function push(\Closure $callback){
        $result =  $callback($this->getReceive());
        if(gettype($result) === "object"){
            if($result instanceof Raw){
                return $result;
            }

            return $result->setAttribute([
                "ToUserName"   => $this->getOpenid(),
                "FromUserName" => $this->getToOpenid()
            ])->setEncryptMode($this->isEncryptAES());
        }
    }

    /**
     * 获取公众号推送对象
     * @param null|string $field 指定获取字段
     * @return array
     */
    public function getReceive($field = null){
        return empty($field) ? $this->receive : $this->receive[$field];
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

    /**
     * 检查是否为加密模式
     * @return bool
     */
    protected function isEncryptAES(){
        return Request::request("encrypt_type","") == "aes";
    }

    /**
     * 验证来自微信服务器数据
     * @param string $str
     * @return bool
     */
    private function checkSignature($str = ''){
        $msg_signature = Request::request("msg_signature");
        $array = [$this->app->config["token"], Request::request("timestamp"), Request::request("nonce"), $str];
        sort($array, SORT_STRING);
        return sha1(implode($array)) === (empty($msg_signature) ? Request::request("signature") : $msg_signature);
    }

}