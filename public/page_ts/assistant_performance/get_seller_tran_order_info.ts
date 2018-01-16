/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/assistant_performance-get_seller_tran_order_info.d.ts" />

function load_data(){
	  if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		    adminid:	$('#id_adminid').val(),
		    date_type_config:	$('#id_date_type_config').val(),
		    date_type:	$('#id_date_type').val(),
		    opt_date_type:	$('#id_opt_date_type').val(),
		    start_time:	$('#id_start_time').val(),
		    end_time:	$('#id_end_time').val()
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
    $('#id_adminid').val(g_args.adminid);
    $.admin_select_user($('#id_adminid'),"admin", load_data,false,{"main_type":1});

    //点击进入个人主页
    $('.opt-user').on('click',function(){
        var opt_data=$(this).get_opt_data();
        window.open(
            '/stu_manage?sid='+ opt_data.userid +"&return_url="+ encodeURIComponent(window.location.href)
        );
    });

   

	


	$('.opt-change').set_input_change_event(load_data);
});

