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
use think\Request;
use think\Response;
use mall\basic\Token;

class VerifyToken {

    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next){
        try{
            Token::check();
        }catch(\Exception $ex){
            return json(["info"=>$ex->getMessage(),"status"=>"-1001"]);
        }

        return $next($request);
    }

}