<?php

use think\facade\Route;

Route::post('api/wxapp/oauth', 'api/auth/oauth');//登录授权

Route::post('api/common/gettel', 'api/common/gettel');//电话解密
Route::post('api/common/image', 'api/common/image');//图片上传

Route::post('api/index/index', 'api/index/index');//首页
Route::post('api/activity/search', 'api/index/search');//活动列表
Route::post('api/activity/detail', 'api/index/detail');//活动详情

Route::post('api/forum/signup', 'api/forum/signup');//活动报名

Route::post('api/forum/announcement', 'api/forum/announcement');//公告列表
Route::post('api/forum/search', 'api/forum/search');//论坛列表
Route::post('api/forum/search_hf', 'api/forum/search_hf');//评论列表

Route::post('api/forum/forumsave', 'api/forum/forumsave');//论坛发布
Route::post('api/forum/forumhfsave', 'api/forum/forumhfsave');//评论


Route::post('api/user/info', 'api/user/info');//用户信息
Route::post('api/user/save', 'api/user/save');//会员卡信息提交

Route::post('api/merchant/search', 'api/merchant/search');//商家列表
Route::post('api/merchant/detail', 'api/merchant/detail');//商家详情

Route::post('api/index/info', 'api/index/info');//指南、关于学联
