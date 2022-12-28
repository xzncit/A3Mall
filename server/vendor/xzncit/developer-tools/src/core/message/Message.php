<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\core\message;


use xzncit\core\base\Prpcrypt;
use xzncit\core\Config;
use xzncit\core\http\Response;

class Message {

    /**
     * @var array
     */
    protected $attribute = [];

    /**
     * @var string
     */
    protected $message = "";

    /**
     * @var bool
     */
    protected $encryptAES = false;

    /**
     * @param $name
     * @return mixed|null
     */
    public function getAttribute($name){
        return isset($this->attribute[$name]) ? $this->attribute[$name] : null;
    }

    /**
     * @param array|string $name
     * @param mixed|null   $value
     * @return $this
     */
    public function setAttribute($name,$value=null){
        if(!is_array($name)){
            $this->setAttrValue($name,$value);
        }else{
            foreach($name as $properties=>$item){
                $this->setAttrValue($properties,$item);
            }
        }

        return $this;
    }

    /**
     * @param $name
     * @param $value
     */
    protected function setAttrValue($name,$value){
        if(property_exists($this,$name)){
            $this->$name = $value;
        }else{
            $this->attribute[$name] = $value;
        }
    }

    /**
     * @param false $encryptAES
     */
    public function setEncryptMode($encryptAES = false){
        $this->encryptAES = $encryptAES;
        return $this;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function send(){
        $result = Response::arr2xml($this->attribute);

        if($this->encryptAES){
            $config = Config::get();
            $prpcrypt = new Prpcrypt($config["enaeskey"]);
            $array = $prpcrypt->encrypt($result, $config["appid"]);
            if ($array[0] > 0) {
                throw new \Exception('Encrypt Error.', '0');
            }

            list($timestamp, $encrypt) = [time(), $array[1]];
            $nonce = rand(77, 999) * rand(605, 888) * rand(11, 99);
            $tmpArr = [$config["token"], $timestamp, $nonce, $encrypt];
            sort($tmpArr, SORT_STRING);
            $signature = sha1(implode($tmpArr));
            $format = "<xml><Encrypt><![CDATA[%s]]></Encrypt><MsgSignature><![CDATA[%s]]></MsgSignature><TimeStamp>%s</TimeStamp><Nonce><![CDATA[%s]]></Nonce></xml>";
            $result = sprintf($format, $encrypt, $signature, $timestamp, $nonce);
        }

        return $result;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name){
        return $this->getAttribute($name);
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function __set($name,$value){
        return $this->setAttribute($name,$value);
    }
}