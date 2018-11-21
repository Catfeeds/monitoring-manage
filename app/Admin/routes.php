<?php

Admin::registerAdminRoutes();

Route::group([
    'namespace' => 'App\Admin\Controllers',
    'prefix' => 'admin',
    'middleware' => ['web', 'admin'],
    'as' => 'admin::'
], function () {
    Route::get('/', 'HomeController@index')->name('main');
    Route::post('/upload_image', 'UploadController@uploadImage')->name('upload.upload_image');
    Route::put('/upload/cover', 'UploadController@deleteCover')->name('upload.delete_cover');

    //校园新闻文章搜索
    Route::group([
        'namespace'=>'Press'
    ],function(){
        Route::post('press/search','PressController@search');
    });

    Route::group([
        'namespace' => 'Works'
    ],function (){
        Route::get('/recipes/checkDate','RecipeController@checkDate')->name('recipes.checkDate');
    });

    /// 校园管理
    Route::group([
        'namespace' => 'Campus'
    ],function (){
        Route::get('/grades/getCollectives/{grade}','GradeController@getCollectives')->name('grades.getCollectives');
        Route::get('grades/checkName','GradeController@checkName')->name('grades.checkName');
        Route::get('collectives/checkName','CollectiveController@checkName')->name('collectives.checkName');
    });

    Route::group([
        'middleware' => ['admin.check_permission']
    ],function (){

        Route::post('/setSchoolId', 'HomeController@setSchoolId')->name('setSchool');

        /// 首页管理
        Route::group([
            'namespace' => 'Home'
        ], function () {
            Route::resource('banners', 'BannerController')->except('show');
            //Route::resource('navigations', 'NavigationController')->except('show');
        });

        /// 校园管理
        Route::group([
           'namespace' => 'Campus'
        ],function (){
            Route::resource('/grades','GradeController')->only(['index','destroy','store','update']);
            Route::resource('/collectives','CollectiveController')->only(['index','destroy','store','update','show']);
            Route::resource('/videoOnline', 'VideoOnlineController')->only(['index']);
            Route::get('/videoOnline/export', 'VideoOnlineController@export')->name('videoOnline.export');
        });

        /// 校务管理
        Route::group([
            'namespace' => 'Works'
        ],function (){
            Route::post('/courses/{collective}/import','CourseController@import')->name('courses.import');
            Route::get('/courses/{collective}/export','CourseController@export')->name('courses.export');
            Route::get('/courses/{collective}/exportTemplate','CourseController@exportTemplate')->name('courses.exportTemplate');
            Route::post('/courses/getPrevWeek','CourseController@getPrevWeek')->name('courses.getPrevWeek');
            Route::post('/recipes/import','RecipeController@import')->name('recipes.import');
            Route::post('/recipes/getPrevWeek','RecipeController@getPrevWeek')->name('recipes.getPrevWeek');
            Route::post('/courses/submit','CourseController@submit')->name('courses.submit');
            Route::get('/courses/{collective}/setCourse','CourseController@setCourse')->name('courses.setCourse');
            Route::resource('/notics','MessageNoticController')->except(['show']);
            Route::resource('/messages','MessageController')->only(['index','destroy']);
            Route::get('/relaxApplies/applying','RelaxApplyController@applying')->name('relaxApplies.applying');
            Route::get('/relaxApplies/finish','RelaxApplyController@finish')->name('relaxApplies.finish');
            Route::put('/relaxApplies/{relaxApply}/agreed','RelaxApplyController@agreed')->name('relaxApplies.agreed');
            Route::put('/relaxApplies/{relaxApply}/refused','RelaxApplyController@refused')->name('relaxApplies.refused');
            Route::resource('/relaxApplies','RelaxApplyController')->only(['destroy']);
            Route::resource('/payConfigs', 'PayConfigController')->only(['index', 'update']);
            Route::resource('/recipes','RecipeController')->except(['show']);
            Route::resource('/courses','CourseController')->except(['show']);
            Route::resource('/homeworks','HomeworkController')->except(['show']);
        });

        ///基础设置
        Route::group([
            'namespace' =>'Base'
        ],function(){
            Route::post('/versions/{version}', 'VersionController@update')->name('versions.update');
            Route::resource('helps','HelpController')->only(['index','destroy','store','update']);
            Route::resource('/abouts', 'AboutController')->only(['index', 'update']);
            Route::resource('/versions', 'VersionController')->only(['index']);
            Route::resource('/platBanners', 'BannerController')->except('show');
            //Route::resource('/platNotics','PlatformNoticController')->except(['show']);
        });

        ///学校管理
        Route::group([
            'namespace' =>'School'
        ],function(){
            Route::resource('schools','SchoolController');
        });

        //学生管理
        Route::group([
            'namespace'=>'Student'
        ],function(){
            Route::get('students/reduction/{student}', 'StudentController@reduction')->name('students.reduction');
            Route::resource('students','StudentController');
            Route::get('/student/exports','StudentController@export')->name('student.export');
            Route::delete('student/parent/{user}/{student}','StudentController@del')->name('parent.del');
        });

        //用户（家长）管理
        Route::group([
            'namespace'=>'User'
        ],function (){
            Route::resource('user','UserController');
        });

        //认证管理
        Route::group([
            'namespace'=>'Auth'
        ],function (){
            Route::get('parent_auth','AuthController@index')->name('parent_auth.index');
            Route::put('parent_auth/{auth}/refuse','AuthController@refuse')->name('parent_auth.refuse');
            Route::put('parent_auth/{auth}/agree','AuthController@agree')->name('parent_auth.agree');
        });

        //教师管理
        Route::group([
            'namespace'=>'Teacher'
        ],function(){
            Route::get('teachers/reduction/{teacher}', 'TeacherController@reduction')->name('teachers.reduction');
            Route::resource('teachers','TeacherController');
            Route::get('/teacherApplies/applying','TeacherApplyController@applying')->name('teacherApplies.applying');
            Route::get('/teacherApplies/finish','TeacherApplyController@finish')->name('teacherApplies.finish');
           // Route::resource('teacherApplies','TeacherApplyController')->only(['index']);
            Route::put('/teacherApplies/{teacherApply}/agreed','TeacherApplyController@agreed')->name('teacherApplies.agreed');
            Route::put('/teacherApplies/{teacherApply}/refused','TeacherApplyController@refused')->name('teacherApplies.refused');
            Route::resource('/teacherApplies','TeacherApplyController')->only(['destroy']);
        });


        //财务管理
        Route::group([
            'namespace'=>'Charge'
        ],function(){
            Route::resource('charges','ChargeController');


        });

        //硬件管理
        Route::group([
            'namespace'=>'Camera'
        ],function(){
            Route::resource('cameras','CameraController');
        });
        //收费记录
        Route::group([
            'namespace'=>'Order'
        ],function(){
            Route::resource('orders','OrderController');
        });

        //校园新鲜事管理
        Route::group([
            'namespace'=>'Article'
        ],function(){
            Route::resource('articles','ArticleController');

            //标签管理
            Route::resource('labels','LabelController');
        });

        //校园新闻分类管理
        Route::group([
            'namespace'=>'Classify'
        ],function(){
            Route::resource('classify','ClassifyController');
        });

        //校园新闻内容管理
        Route::group([
            'namespace'=>'Press'
        ],function(){
            Route::resource('press','PressController');
        });


        //意见反馈
        Route::group([
            'namespace' => 'Compre'
        ],function(){
            //意见反馈
            Route::resource('feedbacks','FeedbackController');

        });

        //班级空间
        Route::group([
            'namespace'=>'Space'
        ],function(){
            //班级空间
            Route::resource('spaces','SpaceController');
        });

        //园所缴费
        Route::group([
            'namespace'=>'GardenPay'
        ],function(){
            Route::resource('gardenpays','GardenPayController');
        });

    });
});