<div class="modal fade" id="filter-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">选择班级</h4>
            </div>
            <form action="{{ route('admin::gardenpays.index') }}" method="get" pjax-container>
                <div class="modal-body">

                    <div class="form-group">

                        <div class="col-sm-4">
                            <select class="form-control grade" style="width: 100%;" name="grade_id" data-placeholder=""  >
                                <option value="" selected>请选择年级</option>
                                @foreach($grades as $grade)
                                    <option value="{{$grade->id}}" >{{$grade->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control class_id" style="width: 100%;" name="class_id" data-placeholder=""  >
                                <option value="" selected>请选择班级</option>

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

<script>
    $("#filter-modal .submit").click(function () {
        $("#filter-modal").modal('toggle');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    });
    $(".grade").change(function () {

        var id = $(this).val();
        $.ajax({
            type:"get",
            dataType:"json",
            data:{
                _token:LA.token
            },
            url:"/admin/collectives/"+id,
            success: function(data){
                console.log(data)
                var str=" <option value=\"\">请选择班级</option>";
                for(var i=0;i<data.length;i++){
                    str = str+" <option value='"+data[i].id+"' >"+data[i].name+"</option>"
                }
                $(".class_id").html(str);
            }
        });

    });

</script>