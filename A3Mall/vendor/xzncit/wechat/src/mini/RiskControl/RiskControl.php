<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\mini\RiskControl;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class RiskControl extends App {

    /**
     * riskControl.getUserRiskRank
     * 根据提交的用户信息数据获取用户的安全等级 risk_rank，无需用户授权
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function getUserRiskRank(array $data){
        return HttpClient::create()->postJson("wxa/getuserriskrank?access_token=ACCESS_TOKEN",$data)->toArray();
    }

}