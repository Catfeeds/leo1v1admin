/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-get_lesson_list.d.ts" />
$(function(){
    $('#id_start_date').val(g_args.start_date);
    $('#id_end_date').val(g_args.end_date);
    $('#id_lesson_type').val(g_args.lesson_type);

	function load_data( ){
        var start_date  = $("#id_start_date").val();
        var end_date    = $("#id_end_date").val();
        var lesson_type = $("#id_lesson_type").val();
	    var url         = window.location.pathname+"?start_date="+start_date+"&end_date="+end_date+"&lesson_type="+lesson_type;
	    window.location.href=url;
	}
	$(".opt-change").on("change",function(){
		load_data();
	});	
    
    set_date_time(load_data);
    
    var homework_status = '';
    var tea_status      = '';
    var stu_status      = '';
    var check_url       = '';
    var finish_url      = '';
    var html_tr         = '';
    $(".lesson_data").each(function(){
        homework_status = $(this).data("homework_status");
        tea_status      = $(this).data("tea_status");
        stu_status      = $(this).data("stu_status");
        check_url       = $(this).data("checkurl");
        finish_url      = $(this).data("finishurl");
        if(homework_status==1){
            $(this).children(".opt-up-homework").html("重传/预览作业");
        }
        if(tea_status==1){
            $(this).children(".opt-up-handout_tea").html("重传/预览老师讲义");
        }
        if(stu_status==1){
            $(this).children(".opt-up-handout_stu").html("重传/预览学生讲义");
        }
    });
    
    var get_grade = function(grade){
        if(grade<200){
            grade=100;
        }else if(grade<300){
            grade=200;
        }else{
            grade=300;
        }
        return grade;
    };
    
    var set_data = function(obj,type){
        var lessonid      = $(obj).parent().data("lessonid");
        var lesson_type   = $(obj).parent().data("lesson_type");
        var lesson_status = $(obj).parent().data("lesson_status");
        var grade         = $(obj).parent().data("grade");
        var subject       = $(obj).parent().data("subject");
        var new_grade     = get_grade(grade);
        $(".homework_info").data("lessonid",lessonid);
        $(".homework_info").data("lesson_type",lesson_type);
        $(".homework_info").data("lesson_status",lesson_status);
        $(".homework_info").data("grade",new_grade);
        $(".homework_info").data("subject",subject);
        $(".homework_info").data("type",type);
    };

    var up_list_info = function(){
        var lessonid      = $(".homework_info").data("lessonid");
        var lesson_type   = $(".homework_info").data("lesson_type");
        var lesson_status = $(".homework_info").data("lesson_status");
        var grade         = $(".homework_info").data("grade");
        var subject       = $(".homework_info").data("subject");
        var type          = $(".homework_info").data("type");
        var url = "/teacher_info/lesson_question_list?lessonid="+lessonid
            +"&lesson_type="+lesson_type
            +"&grade="+grade
            +"&subject="+subject
            +"&type="+type;
        window.location.href = url;
    };
    
    $("body").on("click",".opt-up-handout_list",function(){
        set_lesson_name();
    });
    $("body").on("click",".opt-up-homework_list",function(){
        up_list_info();
    });

    $(".opt-up-homework").on("click",function(){
        var lessonid      = $(this).get_opt_data("lessonid");
        var lesson_status = $(this).get_opt_data("lesson_status");
        set_data(this,1);
        var html_txt  = $('<div></div>').html(dlg_get_html_by_class('dlg_upload_homework_info'));
        var html_node = $("<div></div>").html(html_txt);
        
        $.getJSON('/teacher_info/get_pdf_homework', {
            'lessonid'    : lessonid
        }, function(result){
            if (result['ret'] != 0) {
                BootstrapDialog.alert(result['info']);
            }else{
                var pre_homework_info = result.homework_info;
                
                html_node.find(".opt-homework-url").attr('id','optid-homework-url'+lessonid);
                html_node.find(".homework_url").data('pdfurl', pre_homework_info.homework_url);
                html_node.find(".homework_url").data('homework_status', pre_homework_info.work_status);
                
                show_status_button(html_node,lesson_status);
                BootstrapDialog.show({
                    title           : '作业',
                    message         : html_node,
                    closeByBackdrop : false,
                    onshown         : function(){
                        custom_upload_file( 'optid-homework-url'+lessonid,
                                            false , setCompleteHomework, {
                                                "lessonid" : lessonid,
                                                "type"     : "homework"
                                            },
                                            ["pdf","zip"], setProgress );
                    },
                    buttons : [{
                        label    : '关闭',
                        cssClass : 'btn',
                        action   : function(dialog) {
                            dialog.close();
                        }
                    }]
                });
            }
        });
    });
    
    $("body").on('click','.homework_preview',function(){
        var pdfurl = $(this).parent('td').data('pdfurl');
        if (pdfurl == '') {
            BootstrapDialog.alert('作业未上传，无法预览');
            return;
        }
        
        var match = pdfurl.match(/.*\.(.*)?/);
        if (match[1].localeCompare('pdf') != 0) {
            BootstrapDialog.alert('作业不是pdf格式，无法预览');
            return;
        }
        custom_download(pdfurl); 
    });

    $("body").on('click','.opt-up-home_finish',function(){
        var finishurl = $(this).get_opt_data('finishurl');
        if (finishurl== '') {
            BootstrapDialog.alert('作业未上传，无法预览');
            return;
        }
        
        var match =finishurl .match(/.*\.(.*)?/);
        if (match[1].localeCompare('pdf') != 0) {
            BootstrapDialog.alert('作业不是pdf格式，无法预览');
            return;
        }
        custom_download(finishurl ); 
    });

    $("body").on('click','.opt-up-home_check',function(){
        var checkurl = $(this).get_opt_data('checkurl');
        if (checkurl== '') {
            BootstrapDialog.alert('作业未上传，无法预览');
            return;
        }
        
        var match =checkurl.match(/.*\.(.*)?/);
        if (match[1].localeCompare('pdf') != 0) {
            BootstrapDialog.alert('作业不是pdf格式，无法预览');
            return;
        }
        custom_download(checkurl); 
    });


    $(".opt-up-handout_tea").on("click",function(){
        var lessonid      = $(this).get_opt_data("lessonid");
        var lesson_status = $(this).get_opt_data("lesson_status");
        set_data(this,2);
        var html_node = $('<div></div>').html(dlg_get_html_by_class('dlg_upload_handout_tea_info'));
        
        opt_update_handout(lessonid,lesson_status,html_node);
    });
    $(".opt-up-handout_stu").on("click",function(){
        var lessonid      = $(this).get_opt_data("lessonid");
        var lesson_status = $(this).get_opt_data("lesson_status");
        set_data(this,2);
        var html_txt  = $('<div></div>').html(dlg_get_html_by_class('dlg_upload_handout_stu_info'));
        var html_node = $("<div></div>").html(html_txt);
        
        opt_update_handout(lessonid,lesson_status,html_node);
    });
    
    $("body").on('click','.courseware_preview', function(){
        var pdfurl = $(this).parent('td').data('pdfurl');
        if (pdfurl == '') {
            BootstrapDialog.alert('课件未上传，无法预览');
            return;
        }
        
        var match = pdfurl.match(/.*\.(.*)?/);
        if (match[1].localeCompare('pdf') != 0) {
            BootstrapDialog.alert('课件不是pdf格式，无法预览');
            return;
        }
        custom_download(pdfurl); 
    });

    var set_lesson_name = function(){
        var lessonid = $(".homework_info").data("lessonid");
        var html_txt  = $('<div></div>').html(dlg_get_html_by_class('dlg_upload_lesson_name'));
        get_lesson_info(lessonid,'handout',html_txt);
        BootstrapDialog.show({
            title           : '设置课程名称',
            message         : html_txt,
            closeByBackdrop : false,
            buttons         : [{
                label: '关闭',
                cssClass: 'btn',
                action: function(dialog) {
                    dialog.close();
                }
            },{
		        label: '确认',
		        cssClass: 'btn-primary',
		        action: function(dialog) {
                    var lesson_name = html_txt.find(".lesson_name").val();
                    
                    if(!lesson_name || lesson_name==''){
                        BootstrapDialog.alert('请输入课程标题');
                        return;
                    }
                    var url  = '/teacher_info/set_lesson_name';
                    var data = {
                        'lessonid'      : lessonid,
                        'lesson_name'   : lesson_name
                    };
                    $.getJSON(url, data, function(result){
                        up_list_info();
                    });
                }
            }]
        });
    };
    
    var get_lesson_info = function(lessonid,type,html_node) {
        var url = '';
        if(type == 'homework'){
            url = '/teacher_info/get_pdf_homework'; 
        }else{
            url = '/teacher_info/get_lesson_name_intro';
        }
        
        $.getJSON(url,{
            'lessonid' : lessonid
        }, function(result){
            if(result['ret'] != 0) {
                BootstrapDialog.alert(result['info']);
            }else{
                if(type == 'homework'){
                    var pre_homework_info = result['homework_info'];
                    html_node.find(".pdf_question_count").val(pre_homework_info['pdf_question_count']);
                }else{
                    var pre_lesson_info = result['lesson_info'];
                    html_node.find('.lesson_name').val( pre_lesson_info['lesson_name'] );
                    //遍历出所有知识点
                    var html_lesson_point = '';
                    $.each(pre_lesson_info['lesson_point'],function(i,item){
                        html_lesson_point += '<tr class="lesson_point"><td>课堂知识点_'+(i+1)+'</td>'+
                            '<td><input class="add_confirmed" value="'+item+'" maxlength="15"></td><td>'+
                            '<button class="btn btn-warning fa fa-close form-control delete_lesson_point"></button>' +
                            '</td></tr>';
                    });
                    html_node.find('.add_lesson_point').parents('tr').after(html_lesson_point);
                }
            }
        });
    };

    var show_lesson_info = function(lessonid,type,html_node){
        BootstrapDialog.show({
            title   : '上传成功',
            message : function(dialog){
                html_node.find('.set_lesson_info').on('click','.add_lesson_point', function(){
                    var point_num=html_node.find('.add_confirmed').length;
                    if(point_num<2){
                        html_node.find(".lesson_point:last").after('<tr class="lesson_point"><td>新添课堂知识点</td>'+
                        '<td><input class="add_confirmed" val="" maxlength="15"/></td><td>'+
                                                                   '<button class="btn btn-warning fa fa-close form-control delete_lesson_point"></button>' +
                        '</td></tr>');
                    }else{
                        BootstrapDialog.alert("最多有两个知识点！");
                        return;
                    }
                });
                
                html_node.find('.set_lesson_info').on('click', '.delete_lesson_point', function(){
                    $(this).parents('tr').remove();
                });
                
                return html_node;
            },
            closeByBackdrop : false,
            buttons         : [{
                label: '关闭',
                cssClass: 'btn',
                action: function(dialog) {
                    dialog.close();
                }
            },{
		        label: '确认',
		        cssClass: 'btn-primary',
		        action: function(dialog) {
                    var url  = '';
                    var data = null;

                    if(type=='homework'){
                        var count        = html_node.find('.pdf_question_count').val();
                        var homework_url = $('.bootstrap-dialog-body .homework_url').data('pdfurl');
                        
                        if( count==null || count==0 ){
                            BootstrapDialog.alert('请输入作业题目数量');
                            return;
                        }
                        
                        var re,r;
                        re = /\d*/i;
                        r = count.match(re);
                        if(r!=count){
                            BootstrapDialog.alert('请输入正确的作业题目数量');
                            return;
                        }
                        
                        url  = '/teacher_info/set_pdf_homework';
                        data = {
                            'lessonid'           : lessonid,
                            'pdf_question_count' : count,
                            'homework_url'       : homework_url
                        };
                    }else{
                        var lesson_name = html_node.find('.lesson_name').val();
                        var stu_cw_url  = $('.bootstrap-dialog-body .stu_cw_url').data('pdfurl');
                        var tea_cw_url  = $('.bootstrap-dialog-body .tea_cw_url').data('pdfurl');

                        var lesson_point = '';
                        var point_arr    = [];
                        var point        = '';
                        html_node.find('.add_confirmed').each(function(){
                            point = $(this).val();
                            if(point != ''){
                                point_arr.push($(this).val());
                            }
                        });
                        lesson_point = point_arr.join("|");

                        if (!lesson_name || !lesson_point ) {
                            BootstrapDialog.alert('请输入课程名,和至少一个知识点');
                            return;
                        }
                        
                        url  = '/teacher_info/set_lesson_name_intro';
                        data = {
                            'lessonid'     : lessonid,
                            'lesson_name'  : lesson_name,
                            'lesson_point' : lesson_point,
                            'tea_cw_url'   : tea_cw_url,
                            'tea_cw_name'  : lesson_name 
                        };
		            }
                    
                    $.getJSON(url, data, function(result){
                        BootstrapDialog.alert(result['info']);
                        window.location.reload();
                    });
			        dialog.close();
	            }
            }]
        });
    };

    var set_lesson_info = function(lessonid){
        var stu_cw_url = $('.bootstrap-dialog-body .stu_cw_url').data('pdfurl');
        var url        = '/teacher_info/set_lesson_name_intro';
        var data       = {
            'lessonid'   : lessonid,
            'stu_cw_url' : stu_cw_url,
            'type'       : 'stu'
        };
        
        $.getJSON(url, data, function(result){
            BootstrapDialog.alert(result['info']);
            window.location.reload();
        });
    };

    var opt_update_handout = function(lessonid,lesson_status,html_node){
        $.getJSON('/teacher_info/get_lesson_name_intro',{
            'lessonid': lessonid
        }, function(result){
            if(result['ret'] != 0) {
                BootstrapDialog.alert(result['info']); 
            }else{
                var pre_lesson_info = result['lesson_info'];
                
                html_node.find(".opt-teacher-url").attr('id', 'optid-teacher-url'+lessonid);
                html_node.find(".opt-student-url").attr('id', 'optid-student-url'+lessonid);
                html_node.find(".opt-teacher-url-server").attr('id', 'optid-teacher-url-server'+lessonid);

                html_node.find(".tea_cw_url").data('pdfurl', pre_lesson_info.tea_cw_url);
                html_node.find(".tea_cw_url").data('tea_status', pre_lesson_info.tea_cw_status);
                html_node.find(".stu_cw_url").data('pdfurl', pre_lesson_info.stu_cw_url);
                html_node.find(".stu_cw_url").data('stu_status', pre_lesson_info.stu_cw_status);

                show_status_button(html_node,lesson_status);
                
                BootstrapDialog.show({
                    title           : '讲义',
                    message         : html_node,
                    closeByBackdrop : false,
                    onshown         : function() {
                        try {
                            custom_upload_file( 'optid-teacher-url'+lessonid ,
                                                false , setCompleteTeacherCW, {
                                                    "lessonid" : lessonid,
                                                    "type"     : "handout"
                                                },
                                                ["pdf","zip"], setProgress ,self_upload_complete);
                            
                            custom_upload_file('optid-student-url'+lessonid,
                                               false , setCompleteStudentCW, {
                                                   "lessonid" : lessonid,
                                                   "type"     : "handout"
                                               },
                                               ["pdf","zip"], setProgress ,self_upload_complete);

                        } catch(err) {
                            html_node.find(".opt-teacher-url").hide();
                        } finally{

                        }
                        
                        self_upload('optid-teacher-url-server'+lessonid,
                                    "/common/upload_qiniu",{
                                        "lessonid" : lessonid,
                                        "type"     : "handout"
                                    },
                                    ["pdf","zip"],
                                    {
                                        "file_name_fix":"l_"+lessonid
                                    },  setProgress, self_upload_complete);
                    },
                    buttons : [{
                        label: '关闭',
                        cssClass: 'btn',
                        action: function(dialog) {
                            dialog.close();
                        }
                    }]
                });
            }
        });
    };

    var self_upload_complete=function(ret,ctminfo){
        var html_node= $('<div></div>').html(dlg_get_html_by_class('dlg_upload_'+ctminfo.type+'_ex'));
        
        $('.bootstrap-dialog-body .tea_cw_url').data('pdfurl',ret.file_name  );
        $('.bootstrap-dialog-body .tea_cw_url').data('tea_status', 1);
        $('.bootstrap-dialog-body .tea_cw_url').show();
        
        get_lesson_info(ctminfo.lessonid,ctminfo.type,html_node);
        show_lesson_info(ctminfo.lessonid,ctminfo.type,html_node);
    };

    var show_status_button = function(html_node,lesson_status){
        var tea_status      = html_node.find('.tea_cw_url').data('tea_status');
        var stu_status      = html_node.find('.stu_cw_url').data('stu_status');
        var homework_status = html_node.find('.homework_url').data('homework_status');
        if(lesson_status>=3){
            html_node.find('.opt-up').hide();
        }else{
            if(tea_status==0){
                html_node.find('.tea_cw').hide();
            }else{
                html_node.find('.opt-teacher-url').text('重新上传');
            }
            if(stu_status==0){
                html_node.find('.stu_cw').hide();
            }else{
                html_node.find('.opt-student-url').text('重新上传');
            }
            if(homework_status==0){
                html_node.find('.homework_cw').hide();
            }else{
                html_node.find('.opt-homework-url').text('重新上传');
            }
        }
    };
    
    var setCompleteHomework = function(up,info,file,ctminfo) {
        var html_txt  = $('<div></div>').html(dlg_get_html_by_class('dlg_upload_'+ctminfo.type+'_ex'));
        var html_node = $('<div></div>').html(html_txt);
        var res = $.parseJSON(info);
        var url;
        if (res.url) {
        } else {
            $('.bootstrap-dialog-body .homework_url').data('pdfurl', res.key);
            $('.bootstrap-dialog-body .homework_url').data('homework_status', 1);
            $('.bootstrap-dialog-body .homework_url').show();
        }

        get_lesson_info(ctminfo.lessonid,ctminfo.type,html_node);
        show_lesson_info(ctminfo.lessonid,ctminfo.type,html_node);
    };

    var setCompleteTeacherCW = function(up, info, file, ctminfo) {
        var html_txt  = $('<div></div>').html(dlg_get_html_by_class('dlg_upload_'+ctminfo.type+'_ex'));
        var html_node = $('<div></div>').html(html_txt);
        
        var res = $.parseJSON(info);
        var url;
        if (res.url) {
        } else {
            $('.bootstrap-dialog-body .tea_cw_url').data('pdfurl', res.key);
            $('.bootstrap-dialog-body .tea_cw_url').data('tea_status', 1);
            $('.bootstrap-dialog-body .tea_cw_url').show();
        }

        get_lesson_info(ctminfo.lessonid,ctminfo.type,html_node);
        show_lesson_info(ctminfo.lessonid,ctminfo.type,html_node);
    };

    var setCompleteStudentCW = function(up, info, file, ctminfo) {
        var res = $.parseJSON(info);
        var url;
        if (res.url) {
        } else {
            $('.bootstrap-dialog-body .stu_cw_url').data('pdfurl', res.key);
            $('.bootstrap-dialog-body .stu_cw_url').data('stu_status', 1);
            $('.bootstrap-dialog-body .stu_cw_url').show();
        }
        
        set_lesson_info(ctminfo.lessonid);
    };

    function setProgress(percentage)
    {
        $('.bootstrap-dialog-body .progress-bar').css('width', percentage+'%');
    }

    function check_lesson_status(lesson_status)
    {
        return lesson_status < 2 ? true : false;
    }

    var custom_download = function(file_url) {
        $.ajax({
            url: '/upload/get_pdf_download_url',
            type: 'GET',
            dataType: 'json',
            data: {'file_url': file_url},
            success: function(ret) {
                if (ret.ret != 0) {
                    BootstrapDialog.alert(ret.info);
                } else {
                    window.open('/pdf_viewer/?file='+ret.file, '_blank');
                }
            }
        });
    };
    
    $("tr").each(function(){
        var lesson_type = $(this).find(".lesson_data").data("lesson_type");
        var ass_comment_audit=$(this).find(".lesson_data").data("ass_comment_audit");
        var html_node   = "<a href=\"javascript:;\" class=\"btn fa opt-get_stu_performance\" >课堂评价</a>";
        if(lesson_type<1000 ){
            $(this).find(".opt-up-handout_stu").after(html_node);
        }
    });

    $(".opt-get_stu_performance").on("click",function(){
        var lessonid    = $(this).get_opt_data("lessonid");
        var lesson_type = $(this).get_opt_data("lesson_type");

        if(lesson_type!=2){
            set_stu_performance(lessonid);
        }else{
            /**
             * var url="/teacher_info/tea_comment?lessonid="+lessonid;
             * window.open(url);
             */
        }
    });

    var set_stu_performance=function(lessonid){
        var $total_judgement        = $("<select></select>");
        var $homework_situation     = $("<select></select>");
        var $content_grasp          = $("<select></select>");
        var $lesson_interact        = $("<select></select>");
        var $point_note_list        = $("<textarea/> ");
        var $point_note_list2       = $("<textarea/> ");
        var $stu_comment            = $("<textarea/> ");
        var point_name      = '';
        var point_name2     = '';
        var point_stu_desc  = '';
        var point_stu_desc2 = '';

        Enum_map.append_option_list( "performance", $total_judgement,true);
        Enum_map.append_option_list( "homework_situation", $homework_situation,true);
        Enum_map.append_option_list( "content_grasp", $content_grasp,true);
        Enum_map.append_option_list( "lesson_interact", $lesson_interact,true);
        $.do_ajax("/tea_manage/get_stu_performance",{
            "lessonid":lessonid
        },function(result){
            if(result.total_judgement){
                $total_judgement.val(result.total_judgement);
                $homework_situation.val(result.homework_situation);
                $content_grasp.val(result.content_grasp);
                $lesson_interact.val(result.lesson_interact);
                $stu_comment.val(result.stu_comment);

                if(result.point_note_list!=''){
                    point_name      = result.point_name[0];
                    point_stu_desc  = result.point_stu_desc[0];
                    if(result.point_name[1]){
                        point_name2     = result.point_name[1];
                        point_stu_desc2 = result.point_stu_desc[1];
                    }
                }
                $point_note_list.val(point_stu_desc);
                $point_note_list2.val(point_stu_desc2);
            }else{
                point_name=result[0];
                if(result[1]){
                    point_name2=result[1];
                }
            }

            var arr=[
                ["课堂评价", $total_judgement] ,
                ["作业情况", $homework_situation] ,
                ["内容掌握情况", $content_grasp] ,
                ["课堂互动情况", $lesson_interact] ,
                ["总体评价", $stu_comment] ,
            ];

            if(point_name!=''){
                arr.push(["", "对知识点进行评价"]);
                arr.push([point_name, $point_note_list]);
                if(point_name2!=''){
                    arr.push([point_name2, $point_note_list2]);
                }
            }

            $.show_key_value_table("课堂评价", arr ,{
                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog) {
                    $.do_ajax("/tea_manage/set_stu_performance", {
                        "lessonid"           : lessonid,
                        "total_judgement"    : $total_judgement.val(),
                        "homework_situation" : $homework_situation.val(),
                        "content_grasp"      : $content_grasp.val(),
                        "lesson_interact"    : $lesson_interact.val(),
                        "point_name"         : point_name,
                        "point_stu_desc"     : $point_note_list.val(),
                        "point_name2"        : point_name2,
                        "point_stu_desc2"    : $point_note_list2.val(),
                        "stu_comment"        : $stu_comment.val()
                    },function(result){
                        window.location.reload();
                    });
                }
            },function(){
                if(point_name==''){
                    $point_note_list.parent().parent().hide();
                }
                if(point_name2==''){
                    $point_note_list2.parent().parent().hide();
                }
            });
        });
    };

});

