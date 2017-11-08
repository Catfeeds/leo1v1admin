/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/assistant_performance-ass_revisit_info_month.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		assistantid:	$('#id_assistantid').val()
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

	$('#id_assistantid').val(g_args.assistantid);
    $.admin_select_user($("#id_assistantid"), "assistant",function(){
        load_data();
    });


    var row_list=$("#id_tbody tr");
    var do_index=0;
	
    function do_one() {
        if (do_index < row_list.length ) {
            var $tr=$(row_list[do_index]);
            var opt_data=$tr.find(".row-data");
            var userid = opt_data.data("userid");
            var account = opt_data.data("account");
            if(userid>0){
                $.do_ajax("/ajax_deal2/get_ass_revisit_info_detail",{
                    "userid"       : userid,
                    "start_time"   : g_args.start_time,
                    "account"      : account
                },function(resp){
                    var data = resp;
                    $tr.find(".first_need").text(data.first_need); 
                    $tr.find(".first_real").text(data.first_real); 
                    if(data.first_need == data.first_real){
                        $tr.find(".first_real").css("color","green"); 
                    }else{
                        $tr.find(".first_real").css("color","red"); 
                    }
                    $tr.find(".second_need").text(data.second_need); 
                    $tr.find(".second_real").text(data.second_real); 
                    if(data.second_need == data.second_real){
                        $tr.find(".second_real").css("color","green"); 
                    }else{
                        $tr.find(".second_real").css("color","red"); 
                    }

                
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


