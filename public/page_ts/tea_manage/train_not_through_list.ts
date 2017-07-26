/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-train_not_through_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config : $('#id_date_type_config').val(),
			date_type        : $('#id_date_type').val(),
			opt_date_type    : $('#id_opt_date_type').val(),
			start_time       : $('#id_start_time').val(),
			end_time         : $('#id_end_time').val()
        });
    }

    $('#id_date_range').select_date_range({
        'date_type'      : g_args.date_type,
        'opt_date_type'  : g_args.opt_date_type,
        'start_time'     : g_args.start_time,
        'end_time'       : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

    $("#id_wx_notice").on("click",function(){
	    $.do_ajax("/tea_manage_new/send_not_through_notice",{
            "start_time" : g_args.start_time,
            "end_time"   : g_args.end_time,
        },function(result){
            if(result.ret==0){
                window.location.reload();
            }else{
                BootstrapDialog.alert(result.info);
            }
        })

    });


	$('.opt-change').set_input_change_event(load_data);
});
