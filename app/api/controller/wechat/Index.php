<?php
namespace app\api\controller\wechat;

use mall\library\wechat\chat\WeChatMessage;
use mall\library\wechat\chat\WeChatPush;

class Index {

    public function index(){
        try{
            $wechat = new WeChatPush();
            $receive = $wechat->strToLower($wechat->getReceive());

            $WeChatMessage = new WeChatMessage($wechat);
            if (method_exists($WeChatMessage, ($method = $receive['msgtype']))) {
                return $WeChatMessage->$method();
            }
        }catch(\Exception $e){
            return $e->getMessage();
        }

        return 'success';
    }
}
