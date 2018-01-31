/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-product_info.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
        deal_flag:	$('#id_deal_flag').val(),
        feedback_nick:	$('#id_feedback_nick').val(),
        date_type_config:	$('#id_date_type_config').val(),
        date_type:	$('#id_date_type').val(),
        opt_date_type:	$('#id_opt_date_type').val(),
        start_time:	$('#id_start_time').val(),
        end_time:	$('#id_end_time').val(),
        lesson_problem:	$('#id_lesson_problem').val(),
    });
}
$(function(){

    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

    Enum_map.append_option_list( "boolean", $("#id_deal_flag"));
    Enum_map.append_option_list( "lesson_problem", $("#id_lesson_problem"));

    $('#id_deal_flag').val(g_args.deal_flag);
    $('#id_feedback_nick').val(g_args.feedback_nick);
    $('#id_lesson_problem').val(g_args.lesson_problem);

    // $.admin_select_user($("#id_feedback_adminid"),"admin", load_data);

    //http://p.admin.leo1v1.com/tongji2/subject_transfer ['统计图']
    $('#id_submit').on("click",function(){
        var data         = $(this).get_opt_data();
        var $feedback_id = $("<input/>");
        var $describe    = $("<textarea>");
        var $lesson_url  = $("<input/>");
        var $lesson_problem = $("<select/>");
        var $reason      = $("<textarea>");
        var $solution    = $("<textarea>");
        var $lesson_problem_desc = $("<textarea>");
        var $student     = $("<input/>");
        var $teacher     = $("<input/>");
        var $deal_flag   = $('<select><option value="-1">未设置</option><option value="0">否</option><option value="1">是</option> </select>');
        var $remark      = $("<textarea/>");
        var $id_img_url  = $("<div><input class=\"change_reason_url\" id=\"id_img_url\" type=\"text\"readonly ><span ><a class=\"upload_gift_pic\" id=\"id_upload_lesson_img\" href=\"javascript:;\">上传</a> </span></div>");
        var $id_video_url = $("<div><input class=\"change_reason_url\" id=\"id_video_url\" type=\"text\"readonly ><span ><a class=\"upload_gift_pic\" id=\"id_upload_lesson_video\" href=\"javascript:;\">上传</a>  </span></div>");
        var $id_zip_url   = $("<div><input class=\"change_reason_url\" id=\"id_zip_url\" type=\"text\"readonly ><span ><a class=\"upload_gift_pic\" id=\"id_upload_lesson_zip\" href=\"javascript:;\">上传</a> </span></div>");

        Enum_map.append_option_list("lesson_problem", $lesson_problem, true);

        var tag = "<font color='red'>*</font>";
        var arr = [
            [tag+" 反馈人",$feedback_id],
            [tag+" 问题种类",$lesson_problem],
            [tag+" 问题种类描述",$lesson_problem_desc],
            [tag+" 是否解决",$deal_flag],
            [tag+" 问题描述",$describe],
            ["上课链接",$lesson_url],
            ["原因",$reason],
            ["解决方案",$solution],
            ["问题原因[图片]",$id_img_url],
            ["问题原因[视频|音频]",$id_video_url],
            ["问题原因[压缩包]",$id_zip_url],
            ["学生",$student],
            ["老师",$teacher],
            ["备注",$remark],
        ];

        $lesson_problem.on('change',function(){
            if($lesson_problem.val()==8){
                $lesson_problem_desc.parent().parent().css('display','table-row');
            }else{
                $lesson_problem_desc.parent().parent().css('display','none');
                $lesson_problem_desc.val('');
            }
        });

        $deal_flag.on('change',function(){
            if($deal_flag.val()==1){
                $.each(arr,function(i,item){
                    if(item[0]=='解决方案'){
                        $solution.parent().prev().html("<font color='red'>*</font> 解决方案");
                    }
                    if(item[0]=='原因'){
                        $reason.parent().prev().html("<font color='red'>*</font> 原因");
                    }

                });
                $lesson_url.parent().prev().html("上课链接");
            }else if($deal_flag.val()==0){
                $.each(arr,function(i,item){
                    if(item[0]=='上课链接'){
                        $lesson_url.parent().prev().html("<font color='red'>*</font> 上课链接");
                    }
                });
                $reason.parent().prev().html("原因");
                $solution.parent().prev().html("解决方案");
            }
        });


        $.show_key_value_table("录入反馈信息",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                // 检测文本填写信息
                if(!$feedback_id.val()){ alert('请填写反馈人姓名!'); return; }
                if($lesson_problem.val()==0){ alert('请选择问题种类!'); return; }
                if($deal_flag.val()==-1){ alert('请选择解决状态!'); return; }
                if(!$lesson_problem_desc.val() && $lesson_problem.val() == 8){ alert('请选择填写问题种类描述!'); return; }
                if($deal_flag.val()==0 && !$lesson_url.val()){alert('请填写上课链接!'); return;}
                if($deal_flag.val()==1){
                    if(!$reason.val()){alert('请填写原因!');return;}
                    if(!$solution.val()){alert('请填写解决方案!');return;}
                }
                if(!$describe.val()){ alert('请填写问题描述!'); return; }

                $.do_ajax("/ss_deal2/add_product_info",{
                    "feedback_nick" : $feedback_id.val(),
                    "describe"    : $describe.val(),
                    "lesson_url"  : $lesson_url.val(),
                    "reason"      : $reason.val(),
                    "solution"    : $solution.val(),
                    "student_id"  : $student.val(),
                    "teacher_id"  : $teacher.val(),
                    "deal_flag"   : $deal_flag.val(),
                    "remark"      : $remark.val(),
                    "img_url"     : $('#id_img_url').val(),
                    "video_url"   : $('#id_video_url').val(),
                    "zip_url"     : $('#id_zip_url').val(),
                    "lesson_problem" : $lesson_problem.val(),
                    "lesson_problem_desc" : $lesson_problem_desc.val(),
                },function(result){
                    BootstrapDialog.alert(result.info);
                    dialog.close();
                    load_data();
                });
            }
        },function(){



            $lesson_problem_desc.parent().parent().css('display','none');
            $.admin_select_user($student,"student");
            $.admin_select_user($teacher,"teacher");

            $lesson_url.css('width','90%');
            $student.next().css('width','20%');
            $teacher.next().css('width','20%');
            $deal_flag.css('width','40%');
            $lesson_problem.css('width','40%');
            $feedback_id.css('width','40%');

            $.custom_upload_file('id_upload_lesson_img',true,function (up, info, file) {
                var res = $.parseJSON(info);
                $("#id_img_url").val(res.key);
            }, null,["png", "jpg",'jpeg','bmp','gif']);

            $.custom_upload_file('id_upload_lesson_video',true,function (up, info, file) {
                var res = $.parseJSON(info);
                $("#id_video_url").val(res.key);
            }, null,["mp3","mp4"]);

            $.custom_upload_file('id_upload_lesson_zip',true,function (up, info, file) {
                var res = $.parseJSON(info);
                $("#id_zip_url").val(res.key);
            }, null,['zip','rar']);

        });
    });


    $('.opt-del').on("click",function(){
        var data         = $(this).get_opt_data();
        var $id = data.id;
        if(confirm('确定删除此条信息吗?')){
            $.do_ajax("/ss_deal2/del_product_info",{
                "id":$id,
            },function(result){
                load_data();
            });
        }
    });

    $('.opt-edit').on("click",function(){
        var data = $(this).get_opt_data();
        var $id  = data.id;
        $.do_ajax("/ss_deal2/get_product_info",{
            "id":$id,
        },function(result){
            var $feedback_id = $("<input/>");
            var $describe    = $("<textarea>");
            var $lesson_url  = $("<input />");
            var $lesson_problem_desc = $("<textarea>");

            var $reason      = $("<textarea>");
            var $solution    = $("<textarea>");
            var $student     = $("<input/>");
            var $teacher     = $("<input/>");
            var $lesson_problem = $("<select/>");
            var $deal_flag   = $('<select><option value="-1">未设置</option><option value="0">否</option><option value="1">是</option> </select>');
            var $remark      = $("<textarea/>");

            var $id_img_url = $("<div><input class=\"change_reason_url\" id=\"id_img_url\" type=\"text\"readonly ><span ><a class=\"upload_gift_pic\" id=\"id_upload_lesson_img\" style=\"dispaly:none\" href=\"javascript:;\">上传</a> <a target='_blank' id=\"id_download_lesson_img\" style=\"display:none\" href=\"javascript:;\">下载</a></span></div>");
            var $id_video_url = $("<div><input class=\"change_reason_url\" id=\"id_video_url\" type=\"text\"readonly ><span ><a class=\"upload_gift_pic\" id=\"id_upload_lesson_video\"  href=\"javascript:;\">上传</a>  <a target='_blank' id=\"id_download_lesson_video\" style=\"display:none\" href=\"javascript:;\">下载</a></span></div>");
            var $id_zip_url = $("<div><input class=\"change_reason_url\" id=\"id_zip_url\" type=\"text\"readonly ><span ><a class=\"upload_gift_pic\" id=\"id_upload_lesson_zip\"  href=\"javascript:;\">上传</a>  <a target='_blank' id=\"id_download_lesson_zip\" style=\"display:none\" href=\"javascript:;\">下载</a></span></div>");

            $lesson_problem.on('change',function(){
                if($lesson_problem.val()==8){
                    $lesson_problem_desc.parent().parent().css('display','table-row');
                }else{
                    $lesson_problem_desc.parent().parent().css('display','none');
                    $lesson_problem_desc.val('');
                }
            });

            Enum_map.append_option_list("lesson_problem", $lesson_problem, true);
            var tag = "<font color='red'>*</font>";
            var arr = [
                [tag+" 反馈人",$feedback_id],
                [tag+" 问题种类",$lesson_problem],
                [tag+" 其他原因描述",$lesson_problem_desc],
                [tag+" 是否解决",$deal_flag],
                [tag+" 问题描述",$describe],
                ["上课链接",$lesson_url],
                ["原因",$reason],
                ["解决方案",$solution],
                ["问题原因[图片]",$id_img_url],
                ["问题原因[视频|音频]",$id_video_url],
                ["问题原因[压缩包]",$id_zip_url],
                ["学生",$student],
                ["老师",$teacher],
                ["备注",$remark],
            ];



            $deal_flag.on('change',function(){
                if($deal_flag.val()==1){
                    $.each(arr,function(i,item){
                        if(item[0]=='解决方案'){
                            $solution.parent().prev().html("<font color='red'>*</font> 解决方案");
                        }
                        if(item[0]=='原因'){
                            $reason.parent().prev().html("<font color='red'>*</font> 原因");
                        }

                    });
                    $lesson_url.parent().prev().html("上课链接");
                }else if($deal_flag.val()==0){
                    $.each(arr,function(i,item){
                        if(item[0]=='上课链接'){
                            $lesson_url.parent().prev().html("<font color='red'>*</font> 上课链接");
                        }
                    });
                    $reason.parent().prev().html("原因");
                    $solution.parent().prev().html("解决方案");
                }
            });



            $.show_key_value_table("录入反馈信息",arr,{
                label    : "确认",
                cssClass : "btn-warning",
                action   : function(dialog) {
                    // 检测文本填写信息
                    if(!$feedback_id.val()){ alert('请填写反馈人姓名!'); return; }
                    if($lesson_problem.val()==0){ alert('请选择问题种类!'); return; }
                    if($deal_flag.val()==-1){ alert('请选择解决状态!'); return; }
                    if(!$describe.val()){ alert('请填写问题描述!'); return; }
                    if(!$lesson_problem_desc.val() && $lesson_problem.val() == 8){ alert('请选择填写问题种类描述!'); return; }

                    if($deal_flag.val()==0 && !$lesson_url.val()){alert('请填写上课链接!'); return;}
                    if($deal_flag.val()==1){
                        if(!$reason.val()){alert('请填写原因!');return;}
                        if(!$solution.val()){alert('请填写解决方案!');return;}
                    }

                    $.do_ajax("/ss_deal2/update_product_info",{
                        "feedback_nick" : $feedback_id.val(),
                        "describe"    : $describe.val(),
                        "lesson_url"  : $lesson_url.val(),
                        "reason"      : $reason.val(),
                        "solution"    : $solution.val(),
                        "student_id"  : $student.val(),
                        "teacher_id"  : $teacher.val(),
                        "deal_flag"   : $deal_flag.val(),
                        "remark"      : $remark.val(),
                        "id"          : $id,
                        "img_url"     : $("#id_img_url").val(),
                        "video_url"   : $("#id_video_url").val(),
                        "zip_url"     : $("#id_zip_url").val(),
                        "lesson_problem" : $lesson_problem.val(),
                        "lesson_problem_desc" : $lesson_problem_desc.val(),
                    },function(result){
                        BootstrapDialog.alert(result.info);
                        dialog.close();
                        load_data();
                    });
                }
            },function(){
                var data = result.data;
                $feedback_id.val(data.feedback_nick);
                $student.val(data.sid);
                $teacher.val(data.tid);
                $describe.val(data.describe_msg);
                $lesson_url.val(data.lesson_url);
                $reason.val(data.reason);
                $remark.val(data.remark);
                $solution.val(data.solution);
                $deal_flag.val(data.deal_flag);
                $('#id_zip_url').val(data.zip_url);
                $('#id_img_url').val(data.img_url);
                $('#id_video_url').val(data.video_url);
                $lesson_problem.val(data.lesson_problem);
                $lesson_problem_desc.val(data.lesson_problem_desc);

                if(data.lesson_problem == 8){
                    $lesson_problem_desc.parent().parent().css('display','table-row');
                    $lesson_problem_desc.val(data.lesson_problem_desc);
                }else{
                    $lesson_problem_desc.parent().parent().css('display','none');
                }

                if(data.zip_url){
                    $('#id_download_lesson_zip').css('display','table-cell');
                    $('#id_download_lesson_zip').attr('href',data.zip_str);
                }
                if(data.video_url){
                    $('#id_download_lesson_video').css('display','table-cell');
                    $('#id_download_lesson_video').attr('href',data.video_str);
                }
                if(data.img_url){
                    $('#id_download_lesson_img').css('display','table-cell');
                    $('#id_download_lesson_img').attr('href',data.img_str);
                }

                $.admin_select_user($student,"student");
                $.admin_select_user($teacher,"teacher");

                $lesson_url.css('width','90%');
                $feedback_id.next().css('width','20%');
                $student.next().css('width','20%');
                $teacher.next().css('width','20%');
                $deal_flag.css('width','40%');
                $lesson_problem.css('width','40%');
                $feedback_id.css('width','40%');


                $.custom_upload_file('id_upload_lesson_img',true,function (up, info, file) {
                    var res = $.parseJSON(info);
                    $("#id_img_url").val(res.key);
                }, null,["png", "jpg",'jpeg','bmp','gif']);

                $.custom_upload_file('id_upload_lesson_video',true,function (up, info, file) {
                    var res = $.parseJSON(info);
                    $("#id_video_url").val(res.key);
                }, null,["mp3","mp4"]);

                $.custom_upload_file('id_upload_lesson_zip',true,function (up, info, file) {
                    var res = $.parseJSON(info);
                    $("#id_zip_url").val(res.key);
                }, null,['zip','rar']);

            });
        });
    });

    $('#id_show').on("click",function(){
        $.do_ajax("/ajax_deal/getStatisticalChat",{
            "startTime": g_args.start_time ,
            "endTime"  : g_args.end_time,
        },function(result){
            $('.table-responsive').fadeToggle("slow");
            $('#container').fadeToggle("slow");
            $('#pie_container').fadeToggle("slow");

            var data_obj = result.data['column'];
            var data_pie = result.data['pie'];
            console.log(data_pie);
            Highcharts.chart('container', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: g_args.start_time+' ~ '+g_args.end_time+' 产品问题反馈记录'
                },
                xAxis: {
                    type: 'category'
                },
                yAxis: {
                    title: {
                        text: '问题数量'
                    }
                },
                legend: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.1f}'
                        }
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span >{point.name}</span>: <b>{point.y:.2f}</b><br/>'
                },
                series: [{
                    name: '问题类型',
                    colorByPoint: true,
                    data:data_obj
                }]
            });


            $('#pie_container').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                title: {
                    text: g_args.start_time+' ~ '+g_args.end_time+' 产品问题反馈记录'
                },
                tooltip: {
                    headerFormat: '{series.name}<br>',
                    pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f}%',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                        }
                    }
                },
                series: [{
                    type: 'pie',
                    name: '问题反馈数量',
                    data: data_pie
                }]
            });

        });

    });


    $('.opt-change').set_input_change_event(load_data);
});
