<script>
    $('#copy_recipe').click(function () {
        var date = $('#start').val();
        if(!date) {
            date = formatDate(new Date());
        }
        date = formatDate(getFirstDayOfWeek(new Date(date)));
        swal({
                title: "确认复制食谱?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确认",
                closeOnConfirm: false,
                cancelButtonText: "取消"
            },
            function () {
                $.ajax({
                    method: 'post',
                    url: '{{ route('admin::recipes.getPrevWeek') }}',
                    data: {
                        date: date,
                        _token: LA.token
                    },
                    success: function (data) {
                        if (typeof data === 'object') {
                            if (data.content) {
                                setContent(data.tags,data.content);
                                swal('复制成功!', '', 'success');
                            } else {
                                swal('上周无食谱记录', '', 'error');
                            }
                        }
                    }
                });
            });
    });

    $("#downloadFood").click(function(){
        window.location.href="/excel_template/recipe.xls";
    });

    function uploadFoods(){
        swal({
            title: "文件上传中，请稍等",
            type: "info",
            showConfirmButton: false,
            closeOnConfirm: false
        });
        $.ajaxFileUpload({
            url: '{{ route('admin::recipes.import') }}',
            secureuri: false,
            fileElementId: ['foodData'],//file控件id
            data: {
                _token: LA.token
            },
            dataType : 'text',
            success: function (data) {
                var data = eval('('+data+')');
                if (typeof data === 'object') {
                    if (data.status) {
                        setContent(data.tags,data.content);
                        swal(data.message, '', 'success');
                    } else {
                        swal(data.message, '', 'error');
                    }
                }
            }
        })
        $('#foodData').val('');
    }

    function setContent(tags,content) {
        $.each(tags, function (k, v) {
            $('#'+k).text(v);
            $("input[name=" + k + "]").val(v);
        });

        $.each(content,function (k,v) {
            $.each(v,function (k1,v1) {
                $("textarea[name=" + k + "_" + k1 + "]").val(v1);
            })
        })
    }

    function getMyDay(date){
        var week;
        if(date.getDay()==0) week="周日"
        if(date.getDay()==1) week="周一"
        if(date.getDay()==2) week="周二"
        if(date.getDay()==3) week="周三"
        if(date.getDay()==4) week="周四"
        if(date.getDay()==5) week="周五"
        if(date.getDay()==6) week="周六"
        return week;
    }

    /**
     * 获取本周周一的日期
     * @param date
     * @returns {Date}
     */
    function getFirstDayOfWeek (date) {
        var day = date.getDay() || 7;
        return new Date(date.getFullYear(), date.getMonth(), date.getDate() + 1 - day);
    };

    /**
     * 转换日期
     * @param date
     * @returns {string}
     */
    function formatDate(date) {
        var y = date.getFullYear();
        var m = date.getMonth() + 1;
        m = m < 10 ? ('0' + m) : m;
        var d = date.getDate();
        d = d < 10 ? ('0' + d) : d;
        // var h = date.getHours();
        // var minute = date.getMinutes();
        // minute = minute < 10 ? ('0' + minute) : minute;
        // var second= date.getSeconds();
        // second = minute < 10 ? ('0' + second) : second;
        return y + '-' + m + '-' + d;
    }
</script>