<?php

Route::group([
    'namespace' => 'App\Api\Controllers',
    'middleware' => ['api'],
], function () {
    //注册接口
    Route::post('/auth/register_one', 'AuthController@register_one');
    Route::post('/auth/register_two', 'AuthController@register_two');
    //登录接口
    Route::post('/auth/login', 'AuthController@login');

    //支付成功通知地址
    Route::post('/api/pay/finish', 'Order\OrderController@finish')->name('alipay.paid_notify');
    //微信支付成功通知地址
    Route::post('/api/pay/success', 'Order\OrderController@success')->name('wechatpay.paid_notify');

    //发送验证码-注册账号
    Route::post('/api/register', 'SendMessage@register');
    Route::post('/api/gardenerRegister', 'SendMessage@gardenerRegister');

    //发送验证码-身份验证
    Route::post('/api/authentication', 'SendMessage@confirm');
    Route::post('/api/checkConfirmCode', 'SendMessage@checkConfirmCode');

    //发送验证码-忘记密码
    Route::post('/api/forget', 'SendMessage@forget');
    Route::post('/api/checkForgetCode', 'SendMessage@checkForgetCode');
    Route::post('/api/gardenerForget', 'SendMessage@forgetGardener');
    Route::post('/api/checkGardenerCode', 'SendMessage@checkGardenerCode');

    //重置密码
    Route::post('/auth/rePwd', 'AuthController@rePwd');

    Route::group([
        'middleware' => ['auth:api']
    ], function () {

        //邀请家人
        Route::group([
            'namespace' => 'User'
        ],function (){
            Route::get('/api/family/{student}','UserController@family');
            Route::post('/api/phoneInvite/{student}','UserController@phoneInvite');
        });

        ///校务管理
        Route::group([
            'namespace' => 'Works'
        ], function () {
            Route::post('/api/messages', 'MessageController@store');
            Route::get('/api/relaxApplies', 'RelaxApplyController@index');
            Route::post('/api/relaxApplies', 'RelaxApplyController@store');
            Route::get('/api/relaxApply/{relaxApply}/show', 'RelaxApplyController@show');
            Route::put('/api/relaxApply/{relaxApply}/cancel', 'RelaxApplyController@cancel');
            Route::get('/api/notifications', 'NotificationsController@index');
            Route::get('/api/notifications/{student}/show/{messageNotic}', 'NotificationsController@show');
            Route::get('/api/notifications/read', 'NotificationsController@read');
            Route::get('/api/payConfig/{school}', 'PayConfigController@show');
            Route::get('/api/recipe/{school}', 'RecipeController@show');
            Route::get('/api/course/{collective}', 'CourseController@show');
            Route::get('/api/homeworks', 'HomeworkController@index');
            Route::get('/api/homework/{student}/show/{homework}', 'HomeworkController@show');
            Route::put('/api/homework/read', 'HomeworkController@read');
        });

        Route::group([
            'namespace' => 'Auth'
        ], function () {
            //添加孩子
            Route::post('/api/add_child', 'AuthController@addChild');
            //申请与孩子绑定
            Route::post('/api/parent_auth', 'AuthController@apply');
            Route::post('/api/checkSchoolNum', 'AuthController@checkSchoolNum');
        });

        Route::group([
            'namespace' => 'Home'
        ], function () {
            Route::get('/api/banners/{school_id}', 'BannerController@show');
            Route::get('/api/navigations/{school_id}', 'NavigationController@show');

            //帮助中心
            Route::get('/api/helps', 'MeController@helps');

            //关于我们
            Route::get('/api/about', 'MeController@about');

            //服务购买
            Route::get('/api/charges/', 'MeController@charges');

            //修改密码
            Route::post('/api/me/password', 'MeController@password');
//            Route::post('/api/me/phonepassword', 'MeController@phonepassword');

            //我的学校
            Route::get('/api/me/schools/{school}', 'MeController@schools');

            //我的孩子
            Route::get('/api/me/students', 'MeController@students');

            //我的孩子
            Route::get('/api/me/students/{student}/show', 'MeController@showStudent');

            //我的老师
            Route::get('/api/me/{student}/teachers', 'MeController@teachers');


            //通讯录
            Route::get('/api/me/maillist/{student}', 'MeController@maillist');

            //我的订单
            Route::get('/api/me/orders', 'MeController@orders');


        });

        Route::group([
            'namespace' => 'Article'
        ], function () {
            Route::get('/api/articles/{school_id}', 'ArticleController@index');
            Route::get('/api/articles/{article}/show', 'ArticleController@show');
            Route::post('/api/articles/{school_id}', 'ArticleController@store');
            Route::delete('/api/articles/{article}', 'ArticleController@destroy');
            //点赞
            Route::get('/api/zan/articles/{article}', 'ArticleController@zan');

            //取消点赞
            Route::get('/api/unzan/articles/{article}', 'ArticleController@unzan');
            //评论
            Route::post('/api/comment/articles/{article}', 'ArticleController@comment');

            //文章下的所有评论
            Route::get('/api/comment/articles/{article}', 'ArticleController@comments');

            //我的动态
//            Route::get('/api/user/articles', 'ArticleController@myarticles');

            //新闻栏目
            Route::get('/api/category/{school_id}', 'ArticleController@category');

            //新闻详情
            Route::get('/api/press/detail/{press}','ArticleController@detailpress');

            //栏目下的新闻列表
            Route::get('/api/press/{category_id}', 'ArticleController@press');

            //搜索文章
            Route::post('/api/press/search/', 'ArticleController@search');

            //全部标签
            Route::get('/api/labels','ArticleController@labels');

            //用户的标签
            Route::get('/api/me/labels','ArticleController@userlabels');

            //添加用户标签
            Route::post('/api/me/labels','ArticleController@addlabel');
            //删除用户的标签
            Route::delete('/api/me/lables/{userLabel}','ArticleController@dellabel');
        });

        Route::group([
            'namespace' => 'Feedback'
        ], function () {
            Route::post('/api/feedback/{school_id}', 'FeedbackController@store');
        });

        Route::group([
            'namespace' => 'Camera'
        ], function () {
            Route::get('/api/camera/{class_id}', 'CameraController@show');
        });

        Route::group([
            'namespace' => 'Base'
        ], function () {
            Route::get('/api/version', 'VersionController@version');
            Route::get('/api/platBanners', 'BannerController@show');
        });

        Route::group([
            'namespace' => 'Order'
        ], function () {
            Route::post('/api/orders/{charge}', 'OrderController@store');
            Route::get('/api/pay/orders/{order}', 'OrderController@adbpay');
            Route::get('/api/alipay/orders/{order}', 'OrderController@alipay');
        });

        Route::get('/auth/user', 'AuthController@show');
        Route::post('/auth/user/update', 'AuthController@update');

        Route::group([
            'namespace' => 'Student'
        ], function () {
            Route::post('/api/student/{student}/update', 'StudentController@update');
        });

        //班级空间
        Route::group([
            'namespace' => 'Space'
        ], function () {
            Route::get('/api/space/album/detail/{album}', 'SpaceController@albumdetail');
            Route::get('/api/space/album/{class_id}', 'SpaceController@albumlist');
            Route::get('/api/space/file/{class_id}','SpaceController@spacefile');
            Route::post('/api/space/{album}/imgs','SpaceController@imgsadd');

            //班级缴费
            Route::get('/api/gardenpay/{class_id}', 'SpaceController@pay');
        });

        Route::group([
            'namespace' => 'Collectives'
        ], function () {
            Route::post('/api/getClassFromSn', 'CollectiveController@getFromSn');
        });
    });

    Route::group([
        'middleware' => ['auth:teacher']
    ], function () {

        Route::group([
            'namespace' => 'Home'
        ], function () {
            //关于我们
            Route::get('/gard/about', 'MeController@about');
            Route::get('/gard/banners/{school_id}', 'BannerController@show');
        });

        Route::group([
            'namespace' => 'Base'
        ], function () {
            Route::get('/gard/version', 'VersionController@gard');
        });

    });
});
