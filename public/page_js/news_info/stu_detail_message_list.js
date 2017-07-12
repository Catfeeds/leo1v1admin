/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/news_info-stu_detail_message_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config : $('#id_date_type_config').val(),
			date_type        : $('#id_date_type').val(),
			opt_date_type    : $('#id_opt_date_type').val(),
			start_time       : $('#id_start_time').val(),
			end_time         : $('#id_end_time').val(),
			studentid        : $('#id_studentid').val()
        });
    }

    $('#id_date_range').select_date_range({
        'date_type'     : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });
	$('#id_studentid').val(g_args.studentid);
    $.admin_select_user( $("#id_studentid"), "student",  load_data, true) ;

    $("#add_message_info").on("click",function(){
	    var id_userid  = $("<input/>");
        var id_content = $("<textarea/>");
        var id_value   = $("<input/>")

        var arr = [
            ["用户",id_userid],
            ["内容",id_content],
            ["----","跳转地址没有可不填"],
            ["跳转地址",id_value],
        ];

        $.show_key_value_table("对用户发送消息",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/news_info/add_user_message",{
                    "userid"  : id_userid.val(),
                    "content" : id_content.val(),
                    "value"   : id_value.val(),
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                })

            }
        },function(){
            $.admin_select_user(id_userid,"student");
        });

    });


	$('.opt-change').set_input_change_event(load_data);
});
