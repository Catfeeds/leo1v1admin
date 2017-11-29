/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji-test_lesson_detail_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			lesson_flag:	$('#id_lesson_flag').val()
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
	$('#id_lesson_flag').val(g_args.lesson_flag);


    
    setInterval(function(){
        $(".common-table") .removeClass("table-striped");
    },1000);

    

    $("#tbody .opt-st_demand").each(function(){
        var opt_data=$(this).get_opt_data();
        if (opt_data.teacherid) {
            if(opt_data.confirm_flag ==0 || opt_data.confirm_flag ==1 ) {
                
            }else{
                $(this).closest("tr"). addClass("danger");
            }
            
        }else{
            $(this).closest("tr"). addClass("warning");
        }

        
    });
    //opt-st_demand
    $(".opt-st_demand").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.do_ajax("/seller_student/get_user_info", {
            "phone"  : opt_data.phone
        },function(resp){
            BootstrapDialog.alert( resp.data.st_demand );
        });
        //opt_data.lessonid;
	    
    });


	  $('.opt-change').set_input_change_event(load_data);
    if(g_adminid==748){
        download_show();
    }
});

