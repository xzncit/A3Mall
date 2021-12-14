<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\middleware;

use Closure;
use mall\basic\Users;
use mall\library\tools\jwt\Token;
use think\Request;
use think\Response;

class VerifyToken {

    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next){
        try{
            $token = Token::check();
            $result  = Token::parse($token,"id");
            if(!is_array($result)){
                throw new \Exception("您还未登录，请先登录",401);
            }

            Users::info($result["value"]);
        }catch(\Exception $ex){
            return json(["info"=>$ex->getMessage(),"status"=>"-1001"]);
        }

        return $next($request);
    }

}