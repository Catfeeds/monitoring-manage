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
            <form action="{{ route('admin::videoOnline.index') }}" method="get" pjax-container>
                <div class="modal-body">
                    <div class="form">
                        <div class="form-group">
                            <label>学生名称</label><input type="text" class="form-control" placeholder="学生名称" name="name" value="{{ request('name') }}">
                        </div>
                        <div class="form-group">
                            <label>选择年级</label>
                            <select class="form-control grade" style="width: 100%;" name="grade_id" data-placeholder="选择年级"  >
                                <option value="">请选择</option>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}" {{ request('grade_id')==$grade->id?'selected':'' }} >{{ $grade->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>班级名称</label><input type="text" class="form-control" placeholder="班级名称" name="collective_name" value="{{ request('collective_name') }}">
                        </div>
                        <div class="form-group">
                            <label>家长名称</label><input type="text" class="form-control" placeholder="家长名称" name="parent_name" value="{{ request('parent_name') }}">
                        </div>
                        <div class="form-group">
                            <label>家长手机号</label><input type="text" class="form-control" placeholder="手机号" name="phone" value="{{ request('phone') }}">
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