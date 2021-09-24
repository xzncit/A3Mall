<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\api\controller\wap;

use Endroid\QrCode\QrCode;
use think\facade\Request;

class Ajax {

    public function qrcode(){
        $data = trim(Request::param("data","","urldecode"));
        $content = empty($data) ? "text" : $data;

        $qr = new QrCode();
        $qr->setText($content)->setSize(300)->setPadding(10)->setImageType('png');
        return \think\Response::create($qr->get(),"html", 200, [
            'Content-Type' => $qr->getContentType()
        ])->send();
    }

}