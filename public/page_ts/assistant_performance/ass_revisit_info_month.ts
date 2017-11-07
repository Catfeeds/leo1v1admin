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

    $.do_ajax("/user_manage_new/get_last_change_type_info",{
        "userid" : -1
    },function(result){
       // alert(111); 
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
                /* $.do_ajax("/teacher_money/user_deal/get_teacher_interview_info",{
                   "teacherid"           : opt_data.teacherid,
                   "type" : "admin",
                   "start_time"       : "2017-06-20",
                   "end_time"         : "2017-08-018",
                   },function(resp){
                   console.log(resp.data);
                   var lesson_price=0;
                   var a = resp.data;
                   for(var i = 0;i < a.length; i++) {
                   console.log(a[i].lesson_price);
                   lesson_price = lesson_price+a[i].lesson_price;
                   }
                   var final_price = lesson_price*0.09;
                   $tr.find(".lesson_money").text(lesson_price); 
                   $tr.find(".final_money").text(final_price); 
                   
                   do_index++;
                   do_one();
                   });*/
                // $.do_ajax("/ajax_deal2/get_three_month_stu_num",{
                //     "teacherid"       : teacherid
                // },function(resp){
                //     console.log(resp.data);
                //     var data = resp;
                //     $tr.find(".person_num").text(data.person_num); 
                //     $tr.find(".have_order").text(data.have_order); 
                
                
                do_index++;
                do_one();
                // });

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


