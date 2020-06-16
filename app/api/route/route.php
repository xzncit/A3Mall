<?php
use think\facade\Route;
use think\facade\Request;
use think\Response;
use mall\middleware\AllowOrigin;
use mall\middleware\VerifyToken;

Route::group(function(){
    Route::get('index', 'api/wap.index/index');
    Route::get('index/list', 'api/wap.index/get_list');
    Route::get('search', 'api/wap.search/index');
    Route::get('list', 'api/wap.search/get_list');
    Route::get('category', 'api/wap.category/index');
    Route::get('goods/list', 'api/wap.goods/index');
    Route::get('products/hot', 'api/wap.products/hot');
    Route::get('products/recommend', 'api/wap.products/recommend');
    Route::post('public/login', 'api/wap.users/login');
    Route::get('send_sms', 'api/wap.users/send_sms');
    Route::post('register', 'api/wap.users/register');
    Route::post('forget', 'api/wap.users/forget');
    Route::get('goods/view', 'api/wap.goods/view');
    Route::get('goods/favorite', 'api/wap.goods/favorite');
    Route::get('news', 'api/wap.news/index');
    Route::get('news/view', 'api/wap.news/view');
    Route::get('point', 'api/wap.point/index');
    Route::get('point/view', 'api/wap.point/view');
    Route::get('group', 'api/wap.group/index');
    Route::get('group/view', 'api/wap.group/view');
    Route::get('second', 'api/wap.second/index');
    Route::get('second/view', 'api/wap.second/view');
})->middleware(AllowOrigin::class);

Route::group(function(){
    Route::get('cart', 'api/wap.cart/index');
    Route::post('cart/add', 'api/wap.cart/add');
    Route::post('cart/change', 'api/wap.cart/change');
    Route::post('cart/delete', 'api/wap.cart/delete');
    Route::get('ucenter/favorite', 'api/wap.ucenter/favorite');
    Route::get('ucenter/favorite_delete', 'api/wap.ucenter/favorite_delete');
    Route::get('ucenter/coupon', 'api/wap.ucenter/coupon');
    Route::get('ucenter/coupon/goods', 'api/wap.ucenter/goods');
    Route::get('ucenter/point', 'api/wap.ucenter/point');
    Route::post('ucenter/setting', 'api/wap.ucenter/setting');
    Route::get('ucenter/info', 'api/wap.ucenter/info');
    Route::get('ucenter/address', 'api/wap.ucenter/address');
    Route::get('ucenter/address/list', 'api/wap.ucenter/address_list');
    Route::post('ucenter/address/save', 'api/wap.ucenter/address_editor');
    Route::get('ucenter/address/delete', 'api/wap.ucenter/address_delete');
    Route::get('ucenter/address/delete', 'api/wap.ucenter/address_delete');
    Route::get('ucenter/help', 'api/wap.ucenter/help');
    Route::get('order/create', 'api/wap.order/create');
    Route::get('order/confirm', 'api/wap.order/confirm');
    Route::post('order/create', 'api/wap.order/create');
    Route::post('order/detail', 'api/wap.order/detail');
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
    Route::get('bonus', 'api/wap.bonus/index');
    Route::get('bonus/receive', 'api/wap.bonus/receive');
    Route::get('special', 'api/wap.special/index');
    Route::get('special/view', 'api/wap.special/view');
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