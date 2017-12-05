/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/main_page-zs_teacher.d.ts" />

$(function(){

    function load_data(){
        $.reload_self_page ( {
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
        date_type_config : JSON.parse( g_args.date_type_config), 
        onQuery :function() {
            load_data();
        }
    });

    var curDate = new Date();   
    var curHour = curDate.getHours();      //获取当前小时数(0-23)
    if(g_args.acc_name=="zoe" || g_args.acc_name=="jim" || g_args.acc_name=="jack" || g_args.acc_name=="michelle"){
        $("#id_opt_date_type").show(); 
    }else{
        $("#id_opt_date_type").hide();
    }


    function show_top( $person_body_list) {
        
        $($person_body_list[0]).find("td").css(
            {
                "color" :"red"
            } 
        );
        $($person_body_list[1]).find("td").css(
            {
                "color" :"orange"
            } 
        );

        $($person_body_list[2]).find("td").css(
            {
                "color" :"blue"
            } 
        );

    }

    show_top( $("#id_per_count_list > tr")) ;

   

	$('.opt-change').set_input_change_event(load_data);
});



