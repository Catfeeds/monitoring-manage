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
            <form action="{{ route('admin::relaxApplies.applying') }}" method="get" pjax-container>
                <div class="modal-body">
                    <div class="form">
                        <div class="form-group">
                            <label>家长名称</label>
                            <input type="text" class="form-control" placeholder="家长名称" name="parent_name" value="{{ request('parent_name') }}">
                        </div>
                        <div class="form-group">
                            <label>审核教师名称</label>
                            <input type="text" class="form-control" placeholder="教师名称" name="teacher_name" value="{{ request('teacher_name') }}">
                        </div>
                        <div class="form-group">
                            <label>申请时间</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" id="start" placeholder="开始时间" name="start" value="{{ request('start') }}">
                                <span class="input-group-addon" style="border-left: 0; border-right: 0;">-</span>
                                <input type="text" class="form-control" id="end" placeholder="结束时间" name="end" value="{{ request('end') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary submit">提交</button>
                    <button type="reset" class="btn btn-warning pull-left">撤销</button>
                </div>
            </form>
        </div>
    </div>
</div>
