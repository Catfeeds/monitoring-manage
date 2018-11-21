<?php

Route::group([
    'namespace' => 'App\Gardener\Controllers',
    'middleware' => ['api']
], function () {
    //登录接口
    Route::post('/auth/admin/login', 'AuthController@login');
    Route::post('/auth/admin/register', 'AuthController@register');

    //检查园丁重置密码验证码
    Route::post('/auth/admin/checkGardenerCode','AuthController@checkGardenerCode');
    Route::post('/auth/admin/reGardenerPwd','AuthController@reGardenerPwd');

    //检查班级编号
    Route::post('/auth/admin/checkSn','AuthController@checkClassSn');

    Route::group([
        'middleware' => ['auth:teacher']
    ], function () {

        //请假管理
        Route::get('/gard/relaxApplies/{collective}','RelaxApplyController@index');
        Route::get('/gard/relaxApply/{relaxApply}/show','RelaxApplyController@show');
        Route::put('/gard/relaxApply/{relaxApply}/agree','RelaxApplyController@agree');
        Route::put('/gard/relaxApply/{relaxApply}/refuse','RelaxApplyController@refuse');

        //校园新鲜事
        Route::get('/gard/articles', 'ArticleController@index');
        Route::get('/gard/articles/{article}/show', 'ArticleController@show');
        Route::post('/gard/articles', 'ArticleController@store');

        //点赞
        Route::get('/gard/zan/articles/{article}', 'ArticleController@zan');

        //取消点赞
        Route::get('/gard/unzan/articles/{article}', 'ArticleController@unzan');
        //评论
        Route::post('/gard/comment/articles/{article}', 'ArticleController@comment');

        //文章下的所有评论
        Route::get('/gard/comment/articles/{article}', 'ArticleController@comments');

        //新鲜事标签
        //全部标签
        Route::get('/gard/labels','ArticleController@labels');

        //用户的标签
        Route::get('/gard/me/labels','ArticleController@userlabels');

        //添加用户标签
        Route::post('/gard/me/labels','ArticleController@addlabel');

        //删除用户的标签
        Route::delete('/gard/me/lables/{userLabel}','ArticleController@dellabel');


        //课程管理
        Route::get('/gard/course/{collective}','CourseController@show');
        Route::post('/gard/course/{course}','CourseController@update');

        //食谱管理
        Route::get('/gard/recipe/{school}','RecipeController@show');
        Route::post('/gard/recipe/{recipe}','RecipeController@update');

        //家长留言
        Route::get('gard/messages/{collective}','MessageController@index');
        Route::get('gard/messages/{message}/show','MessageController@show');
        Route::put('/gard/messages/{message}/read','MessageController@read');

        //作业管理
        Route::get('/gard/homeworks','HomeworkController@index');
        Route::get('/gard/homework/{homework}','HomeworkController@show');
        Route::post('/gard/homework/store','HomeworkController@store');

        //帮助中心
        Route::get('/gard/me/help','MeController@help');

        //密码修改
        Route::post('/gard/me/rePwd','MeController@rePwd');

        //通知
        Route::post('gard/notice/storeClass','MessageNoticController@storeClass');
        Route::get('gard/notice/indexClass','MessageNoticController@indexClass');
        Route::get('gard/notice/{messageNotic}/detailClass','MessageNoticController@detailClass');

        //通讯录列表
        Route::get('/gard/maillist/{class_id}','MaillistController@index');

        //我的学校
        Route::get('/gard/me/school','MeController@school');
        Route::get('/gard/me/school','MeController@school');

        //老师资料
        Route::get('/gard/me/info','MeController@info');

        //老师修改资料
        Route::post('/gard/me/upinfo','MeController@upinfo');

        //班级列表
        Route::get('/gard/me/collectives','MeController@collectives');

        //加入班级
        Route::post('/gard/me/collective','MeController@addclass');

        //入园二维码
        Route::get('/gard/qrcode/{collective}','MeController@qrcode');


        //申请加入班级
        Route::post('/gard/me/addclass','MeController@addclass');


        //确定加入班级
        Route::post('/grad/me/add/collective/{class_id}','MeController@submitclass');

        //班级下的所有学生
        Route::get('/gard/me/students/{collective}','MeController@students');

        //学生详细信息
        Route::get('/gard/student/info/{student}','MeController@studentinfo');

        //学生信息编辑
        Route::post('/gard/student/edit/{student}','StudentController@edit');

        //学生创建
        Route::post('/gard/student/create','StudentController@store');

        //学生设置成毕业生和转校
        Route::delete('/gard/student/destory/{student}','MeController@studentdestory');

        //班级空间--创建相册
        Route::post('/gard/album','MeController@albumstore');

        //班级空间--删除相册
        Route::delete('/gard/album/{album}','MeController@albumdestroy');

        //班级空间--上传图片到相册
        Route::post('/gard/album/{album}/imgs','MeController@imgsadd');

        //班级空间--相册列表
        Route::get('/gard/album/{class_id}','MeController@albumlist');

        //班级空间--相册详情
        Route::get('/gard/album/detail/{album}','MeController@albumdetail');

        //班级文件
        Route::get('/gard/space/file/{class_id}','MeController@spacefile');

        //新闻栏目
        Route::get('/gard/category/{school_id}', 'ArticleController@category');

        //新闻详情
        Route::get('/gard/press/detail/{press}','ArticleController@detailpress');

        //栏目下的新闻列表
        Route::get('/gard/press/{category_id}', 'ArticleController@press');
    });
});
