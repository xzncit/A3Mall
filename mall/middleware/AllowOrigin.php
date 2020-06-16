<?php
namespace mall\middleware;

use Closure;
use think\Config;
use think\Request;
use think\Response;

class AllowOrigin {

    protected $cookieDomain;
    protected $headers = [
        'Access-Control-Allow-Credentials' => 'true',
        'Access-Control-Allow-Origin'   => '*',
        'Access-Control-Allow-Headers'  => 'Auth-Token, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With',
        'Access-Control-Allow-Methods'  => 'GET,POST,PATCH,PUT,DELETE,OPTIONS',
        'Access-Control-Max-Age'        =>  '1728000'
    ];

    public function __construct(Config $config){
        $this->cookieDomain = $config->get('cookie.domain', '');
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next){
        $header = $this->headers;
        $origin = $request->header('origin');

        if (!empty($origin) && ('' == $this->cookieDomain || strpos($origin, $this->cookieDomain))) {
            $header['Access-Control-Allow-Origin'] = $origin;
        } else {
            $header['Access-Control-Allow-Origin'] = '*';
            //$header['Access-Control-Allow-Origin'] = 'http://localhost:8080';
        }

        if ($request->method(true) == 'OPTIONS') {
            return Response::create()->code(204)->header($header);
        }

        return $next($request)->header($header);
    }

}