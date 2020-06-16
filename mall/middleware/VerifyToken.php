<?php
namespace mall\middleware;

use Closure;
use think\facade\Db;
use think\Request;
use think\Response;

class VerifyToken {

    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next){
        $token = $request->header("Auth-Token");
        if(empty($token) || Db::name("users_token")->where(["token"=>$token])->count() <= 0){
            return json(["info"=>"您还没有登录，请先登录","status"=>0]);
        }

        return $next($request);
    }

}