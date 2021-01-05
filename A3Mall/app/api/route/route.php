<?php
use think\facade\Route;
use think\facade\Request;
use think\Response;
use mall\middleware\AllowOrigin;
use mall\middleware\VerifyToken;

Route::group(function() {
    Route::any("wechat.index/", "api/wechat.index/index");
    Route::any("wechat/notify", "api/wechat.index/notify");
});

Route::group(function(){
    Route::any('oauth', 'api/wechat.OAuth/index');
    Route::any("auth", "api/wechat.OAuth/auth");
    Route::any('config', 'api/wechat.index/config');
    Route::get('index', 'api/wap.index/index');
    Route::get('custom', 'api/wap.index/custom');
    Route::get('index/list', 'api/wap.index/get_list');
    Route::get('search', 'api/wap.search/index');
    Route::get('search/keywords', 'api/wap.search/keywords');
    Route::get('search/list', 'api/wap.search/get_list');
    Route::get('category', 'api/wap.category/index');
    Route::get('goods/list', 'api/wap.goods/index');
    Route::get('products/hot', 'api/wap.products/hot');
    Route::get('products/recommend', 'api/wap.products/recommend');
    Route::get('comments/list', 'api/wap.comments/index');
    Route::post('public/login', 'api/wap.users/login');
    Route::get('send_sms', 'api/wap.users/send_sms');
    Route::post('register', 'api/wap.users/register');
    Route::post('forget', 'api/wap.users/forget');
    Route::get('goods/view', 'api/wap.goods/view');
    Route::get('news', 'api/wap.news/index');
    Route::get('news/view', 'api/wap.news/view');
    Route::get('regiment', 'api/wap.regiment/index');
    Route::get('regiment/view', 'api/wap.regiment/view');
    Route::get('second', 'api/wap.second/index');
    Route::get('second/view', 'api/wap.second/view');
    Route::get('special', 'api/wap.special/index');
})->middleware(AllowOrigin::class);

Route::group(function(){
    Route::get('point/list', 'api/wap.point/index');
    Route::get('point/receive', 'api/wap.point/receive');
    Route::get('goods/favorite', 'api/wap.goods/favorite');
    Route::get('special/view', 'api/wap.special/view');
    Route::get('cart', 'api/wap.cart/index');
    Route::post('cart/add', 'api/wap.cart/add');
    Route::post('cart/change', 'api/wap.cart/change');
    Route::post('cart/delete', 'api/wap.cart/delete');
    Route::get('ucenter/favorite', 'api/wap.ucenter/favorite');
    Route::get('ucenter/favorite_delete', 'api/wap.ucenter/favorite_delete');
    Route::get('ucenter/coupon', 'api/wap.ucenter/coupon');
    Route::get('ucenter/coupon/goods', 'api/wap.ucenter/goods');
    Route::get('ucenter/point', 'api/wap.ucenter/point');
    Route::get('ucenter/get_setting', 'api/wap.ucenter/get_setting');
    Route::post('ucenter/setting', 'api/wap.ucenter/setting');
    Route::get('ucenter/info', 'api/wap.ucenter/info');
    Route::get('ucenter/wallet', 'api/wap.ucenter/wallet');
    Route::get('ucenter/address', 'api/wap.ucenter/address');
    Route::get('ucenter/address/list', 'api/wap.ucenter/address_list');
    Route::post('ucenter/address/save', 'api/wap.ucenter/address_editor');
    Route::post('ucenter/address/set_address', 'api/wap.ucenter/set_default_address');
    Route::get('ucenter/address/delete', 'api/wap.ucenter/address_delete');
    Route::get('ucenter/help', 'api/wap.ucenter/help');
    Route::get('order/create', 'api/wap.order/create');
    Route::get('order/confirm', 'api/wap.order/confirm');
    Route::post('order/create', 'api/wap.order/create');
    Route::post('order/detail', 'api/wap.order/detail');
    Route::post('order/express', 'api/wap.order/express');
    Route::get('order/list', 'api/wap.order/get_list');
    Route::get('order/payment', 'api/wap.order/payment');
    Route::post('order/refund', 'api/wap.order/refund');
    Route::post('order/apply_refund', 'api/wap.order/apply_refund');
    Route::post('order/delivery', 'api/wap.order/delivery');
    Route::post('order/confirm_delivery', 'api/wap.order/confirm_delivery');
    Route::post('order/do_evaluate', 'api/wap.order/do_evaluate');
    Route::post('order/evaluate', 'api/wap.order/evaluate');
    Route::get('order/cancel', 'api/wap.order/cancel');
    Route::get('order/service', 'api/wap.order/service');
    Route::get('order/info', 'api/wap.order/info');
    Route::get('bonus', 'api/wap.bonus/index');
    Route::get('bonus/receive', 'api/wap.bonus/receive');
    Route::get('ucenter/wallet/fund', 'api/wap.ucenter/fund');
    Route::get('ucenter/wallet/cashlist', 'api/wap.ucenter/cashlist');
    Route::get('ucenter/settlement', 'api/wap.ucenter/settlement');
    Route::post('ucenter/settlement_save', 'api/wap.ucenter/settlement_save');
    Route::post('ucenter/avatar', 'api/wap.ucenter/avatar');
})->middleware(AllowOrigin::class)->middleware(VerifyToken::class);

Route::miss(function(){
    if(Request::isOptions()){
        return Response::create('ok')->code(200)->header([
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Origin'   => '*',
            'Access-Control-Allow-Headers'  => 'Auth-Token, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With',
            'Access-Control-Allow-Methods'  => 'GET,POST,PATCH,PUT,DELETE,OPTIONS',
            'Access-Control-Max-Age'        =>  '1728000'
        ]);
    }else{
        return Response::create()->code(404);
    }
});