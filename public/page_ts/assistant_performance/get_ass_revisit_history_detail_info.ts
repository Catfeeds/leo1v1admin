/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/assistant_performance-get_ass_revisit_history_detail_info.d.ts" />

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

    $("#id_get_data").on("click",function(){
        var row_list=$("#id_tbody tr");
        var do_index=0;

        function do_one() {
            if (do_index < row_list.length ) {
                var $tr=$(row_list[do_index]);
                var opt_data=$tr.find(".row-data");
                var userid = opt_data.data("userid");
                var type_flag = opt_data.data("type_flag");
                $.do_ajax("/ajax_deal2/get_ass_stu_month_revisit_data",{
                    "userid" : userid,
                    "start_time": g_args.start_time,
                    "adminid"    :g_args.adminid,
                    "type_flag"  : type_flag
                },function(resp){
                    console.log(resp.data);
                    var data = resp;
                    $tr.find(".revisit_value").text(data.revisit_value);
                    var num = $("#id_num").text();
                    console.log(num);
                    console.log(data.revisit_value);
                    num = num+data.revisit_value;
                    console.log(num);
                    $("#id_num").text(num);
                    
                    do_index++;
                    do_one();
                });
            }else{
            }
        };
        do_one();

    });



	


	$('.opt-change').set_input_change_event(load_data);
});

