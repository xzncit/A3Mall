<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace xzncit\microapp\Payment\Merchant;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class Merchant extends App{

    /**
     * 服务商进件接口
     * 在服务商完成担保支付开发者授权后，该接口通过获取进件页 url 来实现服务商的自行进件。使用 component_access_token 调用接口。
     * @param $component_access_token       授权码兑换接口调用凭证
     * @param $thirdparty_component_id      小程序第三方平台应用 id
     * @param $url_type                     链接类型：1-进件页面 2-账户余额页
     * @return array
     * @throws \Exception
     */
    public function addMerchant($component_access_token,$thirdparty_component_id,$url_type){
        return HttpClient::create()->postJson("api/apps/ecpay/saas/add_merchant",[
            "component_access_token"=>$component_access_token,
            "thirdparty_component_id"=>$thirdparty_component_id,
            "url_type"=>$url_type
        ])->toArray();
    }

    /**
     * 服务商代小程序进件
     * 接口用于服务商为授权的小程序提供进件及结算能力。使用第三方平台提供的服务商支付 secret 对请求进行加签。secret 可以在在第三方平台的设置->开发设置中查看。
     * @param $thirdparty_id        小程序第三方平台应用 id
     * @param $app_id               小程序的 app_id
     * @param $url_type             链接类型：1-进件页面 2-账户余额页
     * @param $sign                 签名，参考附录签名算法
     * @return array
     * @throws \Exception
     */
    public function getAppMerchant($thirdparty_id,$app_id,$url_type,$sign){
        return HttpClient::create()->postJson("api/apps/ecpay/saas/get_app_merchant",[
            "thirdparty_id"=>$thirdparty_id,
            "app_id"=>$app_id,
            "url_type"=>$url_type,
            "sign"=>$sign
        ])->toArray();
    }

    /**
     * 小程序为第三方进件
     * 接口用于小程序开发者为其他关联业务方提供进件及结算能力。使用前要求小程序开发者首先完成自身账户进件，使用开发者平台提供的支付 SALT 对请求进行加签。
     * @param $sub_merchant_id      商户 id，用于接入方自行标识并管理进件方。由开发者自行分配管理
     * @param $app_id               小程序的 app_id
     * @param $url_type             链接类型：1-进件页面 2-账户余额页
     * @param $sign                 签名，参考附录签名算法
     * @return array
     * @throws \Exception
     */
    public function appAddSubMerchant($sub_merchant_id,$app_id,$url_type,$sign){
        return HttpClient::create()->postJson("api/apps/ecpay/saas/app_add_sub_merchant",[
            "sub_merchant_id"=>$sub_merchant_id,
            "app_id"=>$app_id,
            "url_type"=>$url_type,
            "sign"=>$sign
        ])->toArray();
    }

    /**
     * 服务商为第三方进件
     * 接口用于服务商为其他关联业务方提供进件及结算能力。使用前要求服务商首先完成自身账户进件，使用第三方平台提供的服务商支付 SALT 对请求进行加签。SALT 可以在在第三方平台的设置->开发设置中查看。
     * @param $sub_merchant_id      商户 id，用于接入方自行标识并管理进件方。由开发者自行分配管理
     * @param $thirdparty_id        小程序第三方平台应用 id
     * @param $url_type             链接类型：1-进件页面 2-账户余额页
     * @param $sign                 签名，参考附录签名算法
     * @return array
     * @throws \Exception
     */
    public function addSubMerchant($sub_merchant_id,$thirdparty_id,$url_type,$sign){
        return HttpClient::create()->postJson("api/apps/ecpay/saas/add_sub_merchant",[
            "sub_merchant_id"=>$sub_merchant_id,
            "thirdparty_id"=>$thirdparty_id,
            "url_type"=>$url_type,
            "sign"=>$sign
        ])->toArray();
    }

    /**
     * 进件状态查询
     * 提供进件结果查询接口，开发者/服务商可以通过接口查询到分账方进件结果。请求需要使用开发者/第三方平台提供的支付 SALT 进行加签。
     * @param $app_id               小程序 AppID
     * @param $thirdparty_id        第三方平台服务商 id，非服务商模式留空
     * @param $merchant_id          小程序平台分配商户号
     * @param $sub_merchant_id      商户 id，用于接入方自行标识并管理进件方。由服务商自行分配管理
     * @param $sign                 签名，参考附录签名算法
     * @return array
     * @throws \Exception
     */
    public function queryMerchantStatus($app_id,$thirdparty_id,$merchant_id,$sub_merchant_id,$sign){
        return HttpClient::create()->postJson("api/apps/ecpay/saas/query_merchant_status",[
            "app_id"=>$app_id,
            "thirdparty_id"=>$thirdparty_id,
            "merchant_id"=>$merchant_id,
            "sub_merchant_id"=>$sub_merchant_id,
            "sign"=>$sign
        ])->toArray();
    }

}