<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace mall\library\tools\jwt;

use DateTimeImmutable;
use DateTimeZone;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Validation\Constraint\IdentifiedBy;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\ValidAt;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use think\facade\Request;

class Token {

    /**
     * 生成token
     * @param string $name
     * @param string $value
     * @param array $options
     * @return string
     */
    public static function get($name,$value,$options=[]){
        $config = Config::get();
        $now   = new DateTimeImmutable();
        $time = time();
        $builder = $config->builder()
            ->issuedBy(env("website.app_web_url"))
            ->permittedFor(env("website.app_web_url"))
            ->identifiedBy(env('jwt.sign'))
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now->modify('-1 second'))
            ->expiresAt($now->modify('+15 day'))
            ->withClaim($name, str_replace("=", "", base64_encode(implode("|",[$value,$time,md5(env('jwt.sign') . $value . $time)]))));

        if(!empty($options)){
            $builder->withHeader($options["name"], $options["value"]);
        }

        return (string)$builder->getToken($config->signer(), $config->signingKey());
    }

    public static function check($token=""){
        $token = empty($token) ? Request::header("Auth-Token") : $token;
        $value = trim(ltrim($token, 'Bearer'));
        if(empty($value)){
            throw new \Exception("您还未登录，请登录",401);
        }

        if(self::verify($value)){
            return $value;
        }
    }

    /**
     * 检测
     * @param string $data
     * @return bool
     */
    public static function verify($data){
        $config = Config::get();
        $token = $config->parser()->parse($data);

        if(!($token instanceof Plain)){
            throw new \Exception("验证token未通过",401);
        }

        // 验证是否过期
        $claims = $token->claims();
        $jti = (string)$claims->get('jti');
        $iss = (string)$claims->get('iss');
        //$aud = $claims->get('aud');

         $nbf = $claims->get('nbf');
         $exp = $claims->get('exp');
         $now = new \DateTimeImmutable();

         // 未分配
         if($nbf > $now) {
             throw new \Exception("token还未生效",4001);
         }

         // 己过期
         if($exp < $now) {
             throw new \Exception("token己过期",401);
         }

        $timezone = new DateTimeZone('Asia/Shanghai');
        $now = new SystemClock($timezone);
        $validateAt = new ValidAt($now);

        //验证jwt id是否匹配
        $validationIdentifiedBy = new IdentifiedBy($jti);

        // 验证签发人url是否正确
        $validationIssuedBy = new IssuedBy($iss);

        $validationSignedWith = new SignedWith($config->signer(),$config->signingKey());
        $config->setValidationConstraints(
            $validateAt,$validationIdentifiedBy,$validationIssuedBy,$validationSignedWith
        );
        $constraints = $config->validationConstraints();

        try {
            $config->validator()->assert($token, ...$constraints);
            return true;
        } catch (RequiredConstraintsViolated $e) {
            throw new \Exception($e->getMessage(),401);
        }
    }

    /**
     * 解析
     * @param string $data
     * @param string $field
     * @return false|\Lcobucci\JWT\Token\DataSet
     */
    public static function parse($data,$field=""){
        $config = Config::get();
        $token = $config->parser()->parse($data);
        if(!($token instanceof Plain)){
            return false;
        }

        if(empty($field)){
            return $token->claims();
        }

        $value = $token->claims()->get($field);
        return self::parseData($value);
    }

    public static function parseData($value){

        $string = base64_decode($value);
        $arr = explode('|', $string);
        if (count($arr) != 3) {
            return 1;
        }

        list($value,$time,$md5) = $arr;
        if(md5(env('jwt.sign').$value.$time) == $md5){
            return ["value"=>$value,"time"=>$time];
        }

        return 2;
    }
}