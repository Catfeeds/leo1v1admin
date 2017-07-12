/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-get_ass_change_teacher_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			accept_flag:	$('#id_accept_flag').val(),
			require_adminid:	$('#id_require_adminid').val()
        });
    }


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
    
	$('#id_require_adminid').val(g_args.require_adminid);
	$('#id_accept_flag').val(g_args.accept_flag);
    $.admin_select_user(
        $('#id_require_adminid'),
        "admin", load_data,false,{"main_type":1});

    if (window.location.pathname=="/user_manage_new/get_ass_change_teacher_info_ass" || window.location.pathname=="/user_manage_new/get_ass_change_teacher_info_ass/") {
        $(".opt-edit").hide();
    }else{
        $(".opt-edit-new").hide();
        $(".opt-del").hide();
        $(".opt-confirm").hide();
    } 

    $(".opt-edit").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var id = opt_data.id;
        var id_accept_reason = $("<textarea />");             
       // var id_record_teacher  = $("<textarea />");             
        var id_commend_teacher = $("<input />");             
        var id_accept_flag = $("<select />");            
        Enum_map.append_option_list( "set_boolean",id_accept_flag,true,[1,2]);
        var arr = [
            [ "是否接受",  id_accept_flag],
            [ "推荐老师",  id_commend_teacher],
            [ "备注(驳回理由)",  id_accept_reason] 
        ];

        id_accept_reason.val(opt_data.accept_reason);
        if(opt_data.commend_teacherid>0){
            id_commend_teacher.val(opt_data.commend_teacherid);
        }
        id_accept_flag.on("click",function(){
             if(id_accept_flag.val() ==1){
                id_accept_reason.parent().parent().show();
                id_commend_teacher.parent().parent().show();
            }else{
                id_accept_reason.parent().parent().show();
                id_commend_teacher.parent().parent().hide();
            }

        });
        
        $.show_key_value_table("推荐老师", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/user_deal/set_change_teacher_record_info", {
                    "id"                     : id,
                    "accept_reason"          : id_accept_reason.val(),
                    "commend_teacherid"      : id_commend_teacher.val(),
                    "accept_flag"            : id_accept_flag.val()
                });
            }
        },function(){
            $.admin_select_user(id_commend_teacher,"teacher");           
            if(id_accept_flag.val() ==1){
                id_accept_reason.parent().parent().show();
                id_commend_teacher.parent().parent().show();
            }else{
                id_accept_reason.parent().parent().show();
                id_commend_teacher.parent().parent().hide();
            }


        });
	});
    
    $(".show_pic").on('click',function(){
        var url = $(this).data("url");
        $.wopen(url);
        
    });
    

    $(".opt-edit-new").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var id = opt_data.id;
        if(opt_data.accept_flag >0 || opt_data.commend_teacherid>0){
            alert("该申请已有反馈,不能修改");
            return;
        }

        //alert(opt_data.url);
        var id_stu_score_info = $("<input />"); 
        var id_stu_character_info = $("<input />"); 
        var id_phone_location = $("<input />");
        var id_textbook = $("<input />");
        var id_change_reason = $("<textarea />");
        var id_except_teacher  = $("<textarea />");
        var id_change_reason_url = $("<div><input class=\"change_reason_url\" id=\"change_reason_url\" type=\"text\" readonly><span ><a class=\"upload_gift_pic\" id=\"id_upload_change_reason\" href=\"javascript:;\">上传</a></span></div>");

        id_stu_score_info.val(opt_data.stu_score_info);
        id_stu_character_info.val(opt_data.stu_character_info);
        id_phone_location.val(opt_data.phone_location);
        id_textbook.val(opt_data.textbook);
        id_change_reason.val(opt_data.change_reason);
        id_except_teacher.val(opt_data.except_teacher);
        id_change_reason_url.find("#change_reason_url").val(opt_data.url);
        var arr = [
            ["教材版本",  id_textbook ],
            ["学生成绩",  id_stu_score_info ],
            ["学生性格",  id_stu_character_info ],
            ["地区",  id_phone_location ],
            ["申请原因",  id_change_reason ],
            ["申请原因(图片)",  id_change_reason_url ],
            ["期望老师",  id_except_teacher ]
        ];                       

        $.show_key_value_table("修改申请", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/user_deal/update_teacher_require_deal", {
                    "id":id,
                    "textbook":id_textbook.val(),
                    "stu_score_info": id_stu_score_info.val(), 
                    "stu_character_info": id_stu_character_info.val(), 
                    "phone_location": id_phone_location.val(),                   
                    "change_reason": id_change_reason.val(),                   
                    "except_teacher": id_except_teacher.val(),
                    "change_reason_url" :id_change_reason_url.find("#change_reason_url").val()
                });
            }
        },function(){
            $.custom_upload_file('id_upload_change_reason',1,function (up, info, file) {
                var res = $.parseJSON(info);
                
                $("#change_reason_url").val(res.key);
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);  

        });

	});
    
    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id = opt_data.id;
        if(opt_data.accept_flag >0 || opt_data.commend_teacherid>0){
            alert("该申请已有反馈,不能删除");
            return;
        }

        BootstrapDialog.confirm("确定要删除？", function(val){
            if (val) {
                $.do_ajax( '/user_deal/del_teacher_require_deal', {
                    'id' : id
                });
            } 
        });

    });
    
    $(".opt-confirm").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id = opt_data.id;
        if(opt_data.accept_time ==0){
            alert("还没有处理方案");
            return;
        }

        if(opt_data.done_time >0){
            alert("该换老师申请已处理完成");
            return;
        }
        var id_is_done_flag = $("<select />");
        var id_is_resubmit_flag    = $("<select />");
        Enum_map.append_option_list( "set_boolean", id_is_done_flag,true,[1,2]);      
        Enum_map.append_option_list( "boolean", id_is_resubmit_flag,true);      
        var arr = [
            ["问题是否解决",  id_is_done_flag ],
            ["是否重新提交换老师申请",  id_is_resubmit_flag ],
        ];                       
        
        id_is_done_flag.val(1);
        id_is_resubmit_flag.val(0);
        
        id_is_done_flag.on("change",function(){
            if(id_is_done_flag.val()==1){
                id_is_resubmit_flag.parent().parent().hide(); 
            }else{
                id_is_resubmit_flag.parent().parent().show(); 
            }

        });
        $.show_key_value_table("确认结果", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                var is_resubmit_flag= id_is_resubmit_flag.val();
                var is_done_flag = id_is_done_flag.val();
                if(is_resubmit_flag==0){
                    $.do_ajax("/user_deal/done_change_teacher_require", {
                        "id":id,
                        "is_done_flag":id_is_done_flag.val(),
                        "is_resubmit_flag": id_is_resubmit_flag.val()
                    });
                }else{                                        
                    var id_realname = $("<input readonly/>");
                    var id_subject = $("<input readonly/>");
                    var id_grade = $("<input readonly/>");
                    var id_stu_score_info = $("<input />");
                    var id_stu_character_info = $("<input />");
                    var id_phone_location = $("<input />");
                    var id_textbook = $("<input />");
                    var id_change_teacher_reason_type = $("<select />");
                    var id_change_reason = $("<textarea />");
                    var id_except_teacher  = $("<textarea />");
                    var id_change_reason_url = $("<div><input class=\"change_reason_url\" id=\"change_reason_url\" type=\"text\"readonly ><span ><a class=\"upload_gift_pic\" id=\"id_upload_change_reason\" href=\"javascript:;\">上传</a></span></div>");
                    Enum_map.append_option_list( "change_teacher_reason_type", id_change_teacher_reason_type,true);
                    id_realname.val(opt_data.realname);
                    id_subject.val(opt_data.subject_str);
                    id_grade.val(opt_data.grade_str);
                    id_stu_score_info.val(opt_data.stu_score_info);
                    id_stu_character_info.val(opt_data.stu_character_info);
                    id_phone_location.val(opt_data.phone_location);
                    id_textbook.val(opt_data.textbook);
                    id_change_reason.val(opt_data.change_reason);
                    id_except_teacher.val(opt_data.except_teacher);
                    id_change_reason_url.find("#change_reason_url").val(opt_data.url);
                    id_change_teacher_reason_type.val(opt_data.change_teacher_reason_type);

                    var arr = [
                        [ "老师",  id_realname] ,
                        [ "科目",  id_subject] ,
                        [ "年级",  id_grade] ,
                        ["教材版本",  id_textbook ],
                        ["学生成绩",  id_stu_score_info ],
                        ["学生性格",  id_stu_character_info ],
                        ["地区",  id_phone_location ],
                        ["申请原因类型",  id_change_teacher_reason_type],
                        ["申请原因",  id_change_reason ],
                        ["申请原因(图片)",  id_change_reason_url ],
                        ["期望老师",  id_except_teacher ]
                    ];
                    
                    
                    $.show_key_value_table("重新提交教学质量反馈", arr ,{
                        label    : '确认',
                        cssClass : 'btn-warning',
                        action   : function(dialog) {
                            
                            $.do_ajax("/user_deal/done_change_teacher_require", {
                                "id":id,
                                "is_done_flag":is_done_flag,
                                "is_resubmit_flag": is_resubmit_flag,
                                "userid":opt_data.userid,
                                "teacherid":opt_data.teacherid,
                                "subject":opt_data.subject,
                                "grade":opt_data.grade,
                                "textbook":id_textbook.val(),
                                "stu_score_info": id_stu_score_info.val(),
                                "stu_character_info": id_stu_character_info.val(),
                                "phone_location": id_phone_location.val(),
                                "change_reason": id_change_reason.val(),
                                "change_teacher_reason_type": id_change_teacher_reason_type.val(),
                                "change_reason_url": id_change_reason_url.find("#change_reason_url").val(),
                                "except_teacher": id_except_teacher.val(),
                                "commend_type" :1
                            });
                        }
                    },function(){
                        $.custom_upload_file('id_upload_change_reason',1,function (up, info, file) {
                            var res = $.parseJSON(info);
                            
                            $("#change_reason_url").val(res.key);
                        }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);  

                    });

                }
                
            }
        },function(){
            if(id_is_done_flag.val()==1){
                id_is_resubmit_flag.parent().parent().hide(); 
            } 
        });


    });



	$('.opt-change').set_input_change_event(load_data);
});







