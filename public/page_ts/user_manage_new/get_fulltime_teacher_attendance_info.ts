/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-get_fulltime_teacher_attendance_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			attendance_type:	$('#id_attendance_type').val(),
			teacherid:	$('#id_teacherid').val(),
			adminid:	$('#id_adminid').val(),
			account_role:	$('#id_account_role').val(),
			fulltime_teacher_type:	$('#id_fulltime_teacher_type').val()
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
    
    Enum_map.append_option_list("attendance_type", $("#id_attendance_type"));
    Enum_map.append_option_list("fulltime_teacher_type", $("#id_fulltime_teacher_type"),false,[1,2]);
    Enum_map.append_option_list("account_role", $("#id_account_role"),false,[4,5]);
   

 

	$('#id_attendance_type').val(g_args.attendance_type);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_account_role').val(g_args.account_role);
	$('#id_fulltime_teacher_type').val(g_args.fulltime_teacher_type);
	$('#id_adminid').val(g_args.adminid);
       $.admin_select_user($('#id_teacherid'),
                        "teacher", load_data);
    $.admin_select_user(
        $('#id_adminid'),
        "admin", load_data,false,{"main_type":g_args.account_role});

    
    $.each( $(".opt-show-lessons-new"), function(i,item ){
        $(item).admin_select_teacher_free_time_new({
            "teacherid" : $(item).get_opt_data("teacherid")
        });
    });




    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id = opt_data.id;

        BootstrapDialog.confirm("确定要删除？", function(val){
            if (val) {
                $.do_ajax( '/user_deal/del_fulltime_teacher_attendance_info', {
                    'id' : id
                });
            } 
        });

    });


	$('.opt-change').set_input_change_event(load_data);
});








