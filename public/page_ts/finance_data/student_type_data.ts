/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/finance_data-student_type_data.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {

    });
}
$(function(){




	$('.opt-change').set_input_change_event(load_data);
});
