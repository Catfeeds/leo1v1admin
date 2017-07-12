/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-get_seller_ass_record_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
            accept_adminid:$('#id_accept_adminid').val(),
            require_adminid:$('#id_require_adminid').val()
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
    $('#id_accept_adminid').val(g_args.accept_adminid);
    $('#id_require_adminid').val(g_args.require_adminid);
    $.admin_select_user(
        $('#id_require_adminid'),
        "admin", load_data);
    $.admin_select_user(
        $('#id_accept_adminid'),
        "admin", load_data,false,{"main_type":4});


    if (window.location.pathname=="/tea_manage_new/get_seller_ass_record_info_ass" || window.location.pathname=="/tea_manage_new/get_seller_ass_record_info_ass/" || window.location.pathname=="/tea_manage_new/get_seller_ass_record_info_seller/" || window.location.pathname=="/tea_manage_new/get_seller_ass_record_info_seller") {
        $(".opt-edit").hide();
        $("#id_add_seller_and_ass_record").hide();
    }else{
        $(".opt-edit-new").hide();
        $(".opt-del").hide();
        $(".opt-confirm").hide();
    } 

    $(".opt-edit").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var id = opt_data.id;            
        if(opt_data.done_time >0){
            alert("该反馈已处理完成,不能修改处理方案!");
            return;
        }
        var id_record_scheme = $("<textarea />");
        var id_record_scheme_url = $("<div><input class=\"record_scheme_url\" id=\"record_scheme_url\" type=\"text\" readonly><span ><a class=\"upload_gift_pic\" id=\"id_upload_record_scheme\" href=\"javascript:;\">上传</a></span></div>");
        
        var arr = [
            [ "处理方案",  id_record_scheme],
            [ "相关图片(可不传)",  id_record_scheme_url] 
        ];

        id_record_scheme.val(opt_data.record_scheme);
        id_record_scheme_url.find("#record_scheme_url").val(opt_data.surl);      
        
        $.show_key_value_table("提交处理方案", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/user_deal/set_seller_ass_record_scheme", {                   
                    "id"                     : id,
                    "record_scheme"          : id_record_scheme.val(),
                    "record_scheme_url"      : id_record_scheme_url.find("#record_scheme_url").val()
                });
            }
        },function(){
            $.custom_upload_file('id_upload_record_scheme',1,function (up, info, file) {
                var res = $.parseJSON(info);                
                $("#record_scheme_url").val(res.key);
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);  
        
        });
	});
    
    $(".show_pic").on('click',function(){
        var url = $(this).data("url");
        $.wopen(url);
        
    });
    

    $(".opt-edit-new").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var id = opt_data.id;
        var record_type= opt_data.type;
        if(opt_data.accept_time >0){
            alert("该反馈已有处理方案,不能修改");
            return;
        }      
       
        var id_stu_score_info = $("<input />"); 
        var id_stu_character_info = $("<input />"); 
        var id_stu_request_test_lesson_demand 	 = $("<textarea />");
        var id_textbook = $("<input />");
        var id_record_info  = $("<textarea />");
        var id_is_change_teacher   = $("<select />");
        var id_tea_time = $("<input />"); 
        var id_record_info_url = $("<div><input class=\"record_info_url\" id=\"record_info_url\" type=\"text\" readonly><span ><a class=\"upload_gift_pic\" id=\"id_upload_record_info\" href=\"javascript:;\">上传</a></span></div>");
        Enum_map.append_option_list( "set_boolean", id_is_change_teacher,true);      
        id_textbook.val(opt_data.textbook);
        id_is_change_teacher.val(opt_data.is_change_teacher);       
        id_stu_request_test_lesson_demand.val(opt_data.stu_request_test_lesson_demand);       
        id_stu_score_info.val(opt_data.stu_score_info);
        id_stu_character_info.val(opt_data.stu_character_info);
        id_tea_time.val(opt_data.tea_time);
        id_record_info.val(opt_data.record_info);
        id_record_info_url.find("#record_info_url").val(opt_data.rurl);
        

        var arr = [
            ["教材版本",  id_textbook ],
            ["学生成绩",  id_stu_score_info ],
            ["学生性格",  id_stu_character_info ],
            ["试听需求",  id_stu_request_test_lesson_demand ],
            ["试听后是否更换过老师",  id_is_change_teacher ],
            ["老师给学生的上课时长(天)",  id_tea_time ],
            ["问题反馈",  id_record_info ],
            ["问题反馈(图片)",  id_record_info_url ],
        ];                       
        
        $.show_key_value_table("修改教学质量反馈", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                
                $.do_ajax("/user_deal/update_seller_ass_record_info", {
                    "id":id,
                    "textbook":id_textbook.val(),
                    "stu_score_info": id_stu_score_info.val(), 
                    "stu_character_info": id_stu_character_info.val(), 
                    "stu_request_test_lesson_demand": id_stu_request_test_lesson_demand.val(),                   
                    "record_info": id_record_info.val(),                   
                    "record_info_url": id_record_info_url.find("#record_info_url").val(),                 
                    "tea_time": id_tea_time.val(),                   
                    "is_change_teacher": id_is_change_teacher.val()
                });
            }
        },function(){
            $.custom_upload_file('id_upload_record_info',1,function (up, info, file) {
                var res = $.parseJSON(info);
                $("#record_info_url").val(res.key);
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);  
            if(record_type==2){
                id_is_change_teacher.parent().parent().hide();
                id_tea_time.parent().parent().hide();
                id_stu_character_info.parent().parent().hide();
                id_stu_score_info.parent().parent().hide();
            }else{
                id_stu_request_test_lesson_demand.parent().parent().hide();
            }

        });


	});
    
    $(".opt-del").on("click",function(){        
        var opt_data = $(this).get_opt_data();
        var id = opt_data.id;
        if(opt_data.accept_time >0){
            alert("该反馈已有处理方案,不能删除");
            return;
        }      


        BootstrapDialog.confirm("确定要删除？", function(val){
            if (val) {
                $.do_ajax( '/user_deal/del_seller_and_ass_require', {
                    'id' : id
                });
            } 
        });

    });

    $(".opt-del-new").on("click",function(){        
        var opt_data = $(this).get_opt_data();
        var id = opt_data.id;      
        BootstrapDialog.confirm("确定要删除？", function(val){
            if (val) {
                $.do_ajax( '/user_deal/del_seller_and_ass_require', {
                    'id' : id
                });
            } 
        });

    });

    
    $(".opt-confirm").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var record_type =opt_data.type;
        var id = opt_data.id;
        if(opt_data.accept_time ==0){
            alert("还没有处理方案");
            return;
        }

        if(opt_data.done_time >0){
            alert("该教学反馈已处理完成");
            return;
        }
        var id_is_done_flag = $("<select />");
        var id_is_resubmit_flag    = $("<select />");
        Enum_map.append_option_list( "set_boolean", id_is_done_flag,true,[1,2]);      
        Enum_map.append_option_list( "boolean", id_is_resubmit_flag,true);      
        var arr = [
            ["问题是否解决",  id_is_done_flag ],
            ["是否重新提交教学质量反馈",  id_is_resubmit_flag ],
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
        $.show_key_value_table("确认反馈结果", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                var is_resubmit_flag= id_is_resubmit_flag.val();
                var is_done_flag = id_is_done_flag.val();
                if(is_resubmit_flag==0){
                    $.do_ajax("/user_deal/done_seller_and_ass_require", {
                        "id":id,
                        "is_done_flag":id_is_done_flag.val(),
                        "is_resubmit_flag": id_is_resubmit_flag.val()
                    });
                }else{
                    var id_realname = $("<input readonly/>"); 
                    var id_nick = $("<input readonly/>"); 
                    var id_subject = $("<input readonly/>"); 
                    var id_grade = $("<input readonly/>"); 
                    var id_stu_score_info = $("<input />"); 
                    var id_stu_character_info = $("<input />"); 
                    var id_stu_request_test_lesson_demand 	 = $("<textarea />");
                    var id_textbook = $("<input />");
                    var id_record_info  = $("<textarea />");
                    var id_is_change_teacher   = $("<select />");
                    var id_tea_time = $("<input />"); 
                    var id_record_info_url = $("<div><input class=\"record_info_url\" id=\"record_info_url\" type=\"text\" ><span ><a class=\"upload_gift_pic\" id=\"id_upload_record_info\" href=\"javascript:;\">上传</a></span></div>");
                    Enum_map.append_option_list( "set_boolean", id_is_change_teacher,true);
                    id_realname.val(opt_data.realname);
                    id_nick.val(opt_data.nick);
                    id_subject.val(opt_data.subject_str);
                    id_grade.val(opt_data.grade_str);
                    id_textbook.val(opt_data.textbook);
                    id_is_change_teacher.val(opt_data.is_change_teacher);       
                    id_stu_request_test_lesson_demand.val(opt_data.stu_request_test_lesson_demand);       
                    id_stu_score_info.val(opt_data.stu_score_info);
                    id_stu_character_info.val(opt_data.stu_character_info);
                    id_tea_time.val(opt_data.tea_time);
                    id_record_info.val(opt_data.record_info);
                    id_record_info_url.find("#record_info_url").val(opt_data.rurl);
                    

                    var arr = [
                        [ "老师",  id_realname] ,
                        [ "学生",  id_nick] ,
                        [ "科目",  id_subject] ,
                        [ "年级",  id_grade] ,
                        ["教材版本",  id_textbook ],
                        ["学生成绩",  id_stu_score_info ],
                        ["学生性格",  id_stu_character_info ],
                        ["试听需求",  id_stu_request_test_lesson_demand ],
                        ["试听后是否更换过老师",  id_is_change_teacher ],
                        ["老师给学生的上课时长(天)",  id_tea_time ],
                        ["问题反馈",  id_record_info ],
                        ["问题反馈(图片)",  id_record_info_url ],
                    ];                       
                    
                    $.show_key_value_table("重新提交教学质量反馈", arr ,{
                        label    : '确认',
                        cssClass : 'btn-warning',
                        action   : function(dialog) {
                            
                            $.do_ajax("/user_deal/done_seller_and_ass_require", {
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
                                "stu_request_test_lesson_demand": id_stu_request_test_lesson_demand.val(),                   
                                "record_info": id_record_info.val(),                   
                                "record_info_url": id_record_info_url.find("#record_info_url").val(),                 
                                "tea_time": id_tea_time.val(),                   
                                "is_change_teacher": id_is_change_teacher.val(),
                                "lessonid":opt_data.lessonid,
                                "type":record_type
                            });
                        }
                    },function(){
                        $.custom_upload_file('id_upload_record_info',1,function (up, info, file) {
                            var res = $.parseJSON(info);
                            $("#record_info_url").val(res.key);
                        }, null,["png", "jpg",'jpeg','bmp','gif']);  
                        if(record_type==2){
                            id_is_change_teacher.parent().parent().hide();
                            id_tea_time.parent().parent().hide();
                            id_stu_character_info.parent().parent().hide();
                            id_stu_score_info.parent().parent().hide();
                        }else{
                            id_stu_request_test_lesson_demand.parent().parent().hide();
                        }

                    });

                }
                
            }
        },function(){
            if(id_is_done_flag.val()==1){
                id_is_resubmit_flag.parent().parent().hide(); 
            } 
        });


    });

    $("#id_add_seller_and_ass_record").on("click",function(){
        
        var id_lessonid  = $("<input/>");
        var id_type = $("<select><option value=\"1\">非试听课</option><option value=\"2\">试听课</option></select>");
        var id_add_time  = $("<input/>");
        var id_accept_time  = $("<input/>");
        var id_done_time  = $("<input/>");
        var id_adminid  = $("<input/>");
        var id_accept_adminid  = $("<input/>");
        var id_record_info  = $("<textarea />");
        var id_record_scheme = $("<textarea />");

        id_add_time.datetimepicker({
            lang:'ch',
            datepicker:true,
            timepicker:true,
            format:'Y-m-d H:i',
            step:30,
            onChangeDateTime :function(){
            }
        });
        id_accept_time.datetimepicker({
            lang:'ch',
            datepicker:true,
            timepicker:true,
            format:'Y-m-d H:i',
            step:30,
            onChangeDateTime :function(){
            }
        });

        id_done_time.datetimepicker({
            lang:'ch',
            datepicker:true,
            timepicker:true,
            format:'Y-m-d H:i',
            step:30,
            onChangeDateTime :function(){
            }
        });


        var arr = [
            ["课程id",id_lessonid]  ,
            ["课程类型",id_type]  ,
            ["反馈人",id_adminid ]  ,
            ["问题反馈",  id_record_info ],
            ["处理人",id_accept_adminid ]  ,
            [ "处理方案",  id_record_scheme],
            ["提交问题时间",id_add_time]  ,
            ["给出处理方案时间",id_accept_time]  ,
            ["问题解决时间",id_done_time]  ,
        ];

        $.show_key_value_table("添加反馈", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                
                $.do_ajax("/user_deal/add_seller_ass_record_info_new", {
                    "lessonid"    : id_lessonid.val(),
                    "type"     : id_type.val(),
                    "adminid"  : id_adminid.val(),
                    "accept_adminid"  :id_accept_adminid.val(),
                    "add_time" : id_add_time.val(),
                    "accept_time" : id_accept_time.val(),
                    "done_time"  : id_done_time.val(),
                    "record_info":id_record_info.val(),
                    "record_scheme":id_record_scheme.val()
                });
            }
        },function(){
            $.admin_select_user(
                id_adminid,
                "admin");
            $.admin_select_user(
                id_accept_adminid,
                "admin");

        });
    });

    $(".opt-edit-admin").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id_lessonid  = $("<input/>");
        var id_type = $("<select><option value=\"1\">非试听课</option><option value=\"2\">试听课</option></select>");
        var id_add_time  = $("<input/>");
        var id_accept_time  = $("<input/>");
        var id_done_time  = $("<input/>");
        var id_adminid  = $("<input/>");
        var id_accept_adminid  = $("<input/>");
        var id_record_info  = $("<textarea />");
        var id_record_scheme = $("<textarea />");

        id_add_time.datetimepicker({
            lang:'ch',
            datepicker:true,
            timepicker:true,
            format:'Y-m-d H:i',
            step:30,
            onChangeDateTime :function(){
            }
        });
        id_accept_time.datetimepicker({
            lang:'ch',
            datepicker:true,
            timepicker:true,
            format:'Y-m-d H:i',
            step:30,
            onChangeDateTime :function(){
            }
        });

        id_done_time.datetimepicker({
            lang:'ch',
            datepicker:true,
            timepicker:true,
            format:'Y-m-d H:i',
            step:30,
            onChangeDateTime :function(){
            }
        });
        var arr = [
            ["课程类型",id_type]  ,
            ["反馈人",id_adminid ]  ,
            ["问题反馈",  id_record_info ],
            ["处理人",id_accept_adminid ]  ,
            [ "处理方案",  id_record_scheme],
            ["提交问题时间",id_add_time]  ,
            ["给出处理方案时间",id_accept_time]  ,
            ["问题解决时间",id_done_time]  ,
        ];
        id_lessonid.val(opt_data.lessonid);
        id_type.val(opt_data.type);
        id_adminid.val(opt_data.adminid);
        id_record_info.val(opt_data.record_info);
        id_accept_adminid.val(opt_data.accept_adminid);
        id_record_scheme.val(opt_data.record_scheme);
        id_add_time.val(opt_data.add_time_str);
        id_accept_time.val(opt_data.accept_time_str);
        id_done_time.val(opt_data.done_time_str);

        $.show_key_value_table("修改反馈", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                
                $.do_ajax("/user_deal/update_seller_ass_record_info_new", {
                    "id" :opt_data.id,
                    "type"     : id_type.val(),
                    "adminid"  : id_adminid.val(),
                    "accept_adminid"  :id_accept_adminid.val(),
                    "add_time" : id_add_time.val(),
                    "accept_time" : id_accept_time.val(),
                    "done_time"  : id_done_time.val(),
                    "record_info":id_record_info.val(),
                    "record_scheme":id_record_scheme.val()
                });
            }
        },function(){
            $.admin_select_user(
                id_adminid,
                "admin");
            $.admin_select_user(
                id_accept_adminid,
                "admin");

        });


 
    });

	$('.opt-change').set_input_change_event(load_data);
});







