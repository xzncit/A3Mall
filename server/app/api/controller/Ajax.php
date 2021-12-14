<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\api\controller;

use Endroid\QrCode\QrCode;
use think\facade\Request;
use app\common\models\Setting as SettingModel;
use app\api\service\Version as VersionService;

class Ajax {

    /**
     * 返回备案信息
     * @return \think\response\Json
     */
    public function copy(){
        return json([
            "status"=>1,"info"=>"ok",
            "data"=>SettingModel::getArrayData("copyright")["copyright"]??[]
        ]);
    }

    /**
     * 获取二维码
     * @throws \Endroid\QrCode\Exceptions\ImageFunctionFailedException
     * @throws \Endroid\QrCode\Exceptions\ImageFunctionUnknownException
     * @throws \Endroid\QrCode\Exceptions\ImageTypeInvalidException
     */
    public function qrcode(){
        $data = trim(Request::param("data","","urldecode"));
        $content = empty($data) ? "text" : $data;

        $qr = new QrCode();
        $qr->setText($content)->setSize(300)->setPadding(10)->setImageType('png');
        return \think\Response::create($qr->get(),"html", 200, [
            'Content-Type' => $qr->getContentType()
        ])->send();
    }

    /**
     * APP升级
     * @return \think\response\Json
     */
    public function update(){
        try {
            return json(VersionService::getData(Request::param()));
        }catch (\Exception $ex){
            return json(["status"=>0,"info"=>"ok"]);
        }
    }

}