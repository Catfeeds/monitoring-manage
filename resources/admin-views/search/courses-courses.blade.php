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
            <form action="{{ route('admin::courses.index') }}" method="get" pjax-container>
                <div class="modal-body">
                    <div class="form">
                        <div class="form-group">
                            <label>选择年级</label>
                            <select class="form-control grade" id="grade" style="width: 100%;" name="grade_id" data-placeholder="选择年级"  >
                                <option value="">请选择</option>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}" {{ request('grade_id')==$grade->id?'selected':'' }} >{{ $grade->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>选择班级</label>
                            <select class="form-control" style="width: 100%;" id="collective" name="id" data-placeholder="选择班级"  >
                                <option value="">请选择班级</option>
                            </select>
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