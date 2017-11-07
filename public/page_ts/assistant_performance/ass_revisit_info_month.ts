/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/assistant_performance-ass_revisit_info_month.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
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

    var row_list=$("#id_tbody tr");
    var do_index=0;
	
    function do_one() {
        if (do_index < row_list.length ) {
            var $tr=$(row_list[do_index]);
            var opt_data=$tr.find(".row-data");
            var userid = opt_data.data("userid");
            if(userid>0){
                alert(userid);
                $.do_ajax("/ajax_deal2/get_ass_revisit_info_detail",{
                    "userid"       : userid,
                    "start_time"   : g_args.start_time,
                    "account"      : 
                },function(resp){
                    console.log(resp.data);
                    var data = resp;
                    $tr.find(".person_num").text(data.person_num); 
                    $tr.find(".have_order").text(data.have_order); 
                
                
                do_index++;
                do_one();
                });
            }else{
                do_index++;
                do_one();
            }
        }else{
        }
    };
    do_one();





	$('.opt-change').set_input_change_event(load_data);
});


