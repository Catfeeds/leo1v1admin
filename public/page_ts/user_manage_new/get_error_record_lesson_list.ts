/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-get_error_record_lesson_list.d.ts" />
$(function(){
    function load_data(){
        $.reload_self_page ( {
			lesson_type:	$('#id_lesson_type').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val()
            
        });
    }

	Enum_map.append_option_list("contract_type",$("#id_lesson_type")); 

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

    
	$('#id_lesson_type').val(g_args.lesson_type);
    

    $(".opt-del").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.do_ajax_t("/xx/dsaf",{
            
        });
    });



	$('.opt-change').set_input_change_event(load_data);
});

