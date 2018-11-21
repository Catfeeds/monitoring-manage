<div class="modal fade" id="filter-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">筛选</h4>
            </div>
            <form action="{{ route('admin::parent_auth.index') }}" method="get" pjax-container>
                <div class="modal-body">
                    <div class="form">
                        <div class="form-group">
                            <div class="form-group">
                                <label>申请人</label>
                                <input type="text" class="form-control" placeholder="申请人" name="user_name" value="{{ request('user_name') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <label>班级</label>
                                <select class="form-control" name="class_id">
                                    <option value="">请选择</option>
                                @foreach($classe as $key => $val)
                                    <option value="{{$val->id}}" {{ request('class_id') == $val->id ? 'selected':'' }}>{{$val->name}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <label>学生姓名</label>
                                <input type="text" class="form-control" placeholder="学生姓名" name="student_name" value="{{ request('student_name') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <label>审核人</label>
                                <input type="text" class="form-control" placeholder="审核人" name="operator_name" value="{{ request('operator_name') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <label>申请状态</label>
                                <select class="form-control" name="status">
                                    <option value="">请选择</option>
                                    <option value='1' {{ request('status')==1?'selected':'' }}>申请中</option>
                                    <option value="2" {{ request('status')==2?'selected':'' }}>已完成</option>
                                    <option value="3" {{ request('status')==3?'selected':'' }}>已拒绝</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>申请时间</label>
                            <div class="form-group">
                                <input type="text" id="" class="form-control time-select " style="display: inline;width: 47%" autocomplete="off" placeholder="起始时间" name="start" value="{{ request('start') }}">&nbsp;&nbsp;&nbsp;
                                <span style="display: inline;width: 10%;">--</span>&nbsp;&nbsp;&nbsp;
                                <input type="text" id="" class="form-control time-select" style="display: inline;width: 47%" autocomplete="off"  placeholder="结束时间" name="end" value="{{ request('end') }}">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary submit" >提交</button>
                    <button type="reset" class="btn btn-warning pull-left">撤销</button>
                </div>
            </form>
        </div>
    </div>
</div>
