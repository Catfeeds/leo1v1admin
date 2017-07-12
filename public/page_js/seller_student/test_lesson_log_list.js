/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student-test_lesson_log_list.d.ts" />

$(function(){


    function load_data(){
        $.reload_self_page ( {
			userid:	$('#id_userid').val(),
			teacherid:	$('#id_teacherid').val(),
			del_flag:	$('#id_del_flag').val(),
			phone:	$('#id_phone').val(),
			subject:	$('#id_subject').val(),
			st_application_id:	$('#id_st_application_id').val(),
			test_lesson_status:	$('#id_test_lesson_status').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val()
        });
    }

    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config) ,
        onQuery :function() {
            load_data();
        }
    });

    Enum_map.append_option_list("subject",$("#id_subject"));
    Enum_map.append_option_list("test_lesson_status",$("#id_test_lesson_status"));


	$('#id_userid').val(g_args.userid);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_subject').val(g_args.subject);
	$('#id_start_time').val(g_args.start_time);
	$('#id_end_time').val(g_args.end_time);
	$('#id_date_type').val(g_args.date_type);
	$('#id_phone').val(g_args.phone);
	$('#id_test_lesson_status').val(g_args.test_lesson_status);
	$('#id_del_flag').val(g_args.del_flag);


    $.do_ajax( "/authority/get_group_user_list_ex", {
        groupid:1
    },function(data){
        var user_list=data.user_list;
        var $select=$("#id_st_application_id" );
        $.each(user_list, function(i,item){
            $select.append( "<option value="+item.adminid+"> "+item.admin_nick+ " </option>" );
        });
	    $('#id_st_application_id').val(g_args.st_application_id);
    });
    $.admin_select_user($("#id_userid"),"student",load_data);
    $.admin_select_user($("#id_teacherid"),"teacher",load_data);


	$('.opt-change').set_input_change_event(load_data);

    
    $(".opt-change-test_lesson_status").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var id_test_lesson_status=$("<select/>");
        var id_reason=$("<textarea/>");
        var id_del_flag=$("<select><option value=0>有效</option> <option value=1>多余记录</option</select>");
        var arr=[
            ["学生", opt_data.nick ] ,
            ["上课时间", opt_data.lesson_time] ,
            ["状态", id_test_lesson_status ] ,
            ["原因", id_reason     ] ,
            ["记录状态", id_del_flag] ,
        ];
        id_del_flag.val( opt_data.del_flag);

        Enum_map.append_option_list("test_lesson_status",id_test_lesson_status,true);
        id_reason.val(opt_data.reason);
        id_test_lesson_status .val(opt_data.test_lesson_status);
        $.show_key_value_table("修改状态", arr , {
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/seller_student/test_lesson_log_set_status",{
                    id : opt_data.id,
                    del_flag : id_del_flag.val(),
                    test_lesson_status: id_test_lesson_status.val() ,
                    reason: id_reason.val() 
                }) ;
                
            }
        });

        
    });


    
    $(".opt-show-user-opt-list").on("click",function(){
        var opt_data=$(this).get_opt_data();
	    
        $.wopen("/seller_student/test_lesson_log_list?"
                + "&phone=" + (""+opt_data.phone).split("-")[0] 
                +"&date_type="+g_args.date_type
                +"&opt_date_type="+g_args.opt_date_type
                +"&start_time="+g_args.start_time
                +"&end_time="+g_args.end_time   );

    });

});


