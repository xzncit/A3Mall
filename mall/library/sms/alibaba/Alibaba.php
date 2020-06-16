<?php
namespace mall\library\sms\alibaba;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class Alibaba {

    public function __construct($setting=[]){
        if(empty($setting["accessKeyId"])){
            throw new \Exception("短信配置 accessKeyId 为空");
        }

        if(empty($setting["accessSecret"])){
            throw new \Exception("短信配置 accessSecret 为空");
        }

        AlibabaCloud::accessKeyClient($setting["accessKeyId"],$setting["accessSecret"])
            ->regionId('cn-hangzhou')
            ->asDefaultClient();
    }

    /**
     * 发送短信
     * $data = [
     *  "PhoneNumbers"  => 接收短信的手机号码
     *  "SignName"      => 短信签名名称
     *  "TemplateCode"  => 短信模板名称   SMS_153055065
     *  "TemplateParam" => 短信模板变量   {"code":"1111"}
     * ]
     */
    public function send(array $data=[]){
        if(is_array($data["PhoneNumbers"])){
            $data["PhoneNumbers"] = implode(",",$data["PhoneNumbers"]);
        }

        $query = [
            "RegionId"=>"cn-hangzhou",
            "PhoneNumbers"=>$data["PhoneNumbers"],
            "SignName"=>$data["SignName"],
            "TemplateCode"=>$data["TemplateCode"],
            "TemplateParam"=>$data["TemplateParam"]
        ];
        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options(['query' => $query])
                ->request();
            return $result->toArray();
        } catch (ClientException $e) {
            throw new \Exception($e->getMessage(),$e->getCode());
        } catch (ServerException $e) {
            throw new \Exception($e->getMessage(),$e->getCode());
        }
    }


}