<header class="main-header">

    <!-- Logo -->
    <a href="{{ route('admin::main') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">{!! config('admin.logo-mini') !!}</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">{!! config('admin.logo') !!}</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="{{ Admin::user()->avatar }}" class="user-image" alt="User Image">
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{ Admin::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="{{ Admin::user()->avatar }}" class="img-circle" alt="User Image">

                            <p>
                                {{ Admin::user()->name }}
                                <small>Member since {{ Admin::user()->created_at }}</small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ route('admin::profile') }}" class="btn btn-default btn-flat">个人中心</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('admin::logout') }}" class="btn btn-default btn-flat">退出</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        @if(Tanmo\Admin\Facades\Admin::user()->isAdmin())
        <div class="btn-group pull-right" style="padding: 13px 13px;">
            <span style="color:white;">当前学校：<span style="color:red;">{{ getSchoolName() }}</span></span>
        </div>
        <div class="btn-group pull-right" style="padding: 10px 10px;">
            <a href="" style="border:0px;"
               data-toggle="modal"
               data-target="#school-modal"
               title="选择学校" class="btn btn-sm btn-default">
                <i class="fa fa-simplybuilt"></i>&nbsp;&nbsp;选择学校
            </a>
        </div>
        @endif
    </nav>
</header>
@if(Tanmo\Admin\Facades\Admin::user()->isAdmin())
<div class="modal fade" id="school-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">选择学校</h4>
            </div>
            <form  action="{{ route('admin::setSchool') }}" method="post">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form">
                        <div class="form-group">
                            <label>选择学校</label>
                            <select class="form-control" id="select_school" style="width: 100%;" name="school_id" data-placeholder="选择学校"  >
                                <option value="">请选择</option>
                                @foreach(getSchoolList() as $school)
                                    <option value="{{ $school->id }}" {{ getShowSchoolId()==$school->id?'selected':'' }} >{{ $school->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset"  class="btn btn-warning pull-left">清空</button>
                    <input type="submit" class="btn btn-primary" value="确定" data-loading-text="<i class='fa fa-spinner fa-spin'></i> 提交" />
                </div>
            </form>
        </div>
    </div>
</div>
@endif
