/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji-seller_time.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			groupid:	$('#id_groupid').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val()
        });
    }

    $("#id_date_range").select_date_range({
        "opt_date_type" : g_args.opt_date_type,
        "start_time"    : g_args.start_time,
        "end_time"      : g_args.end_time,
        onQuery :function() {
            load_data();
        }
    });

    $('#id_groupid').val(g_args.groupid);
    

	$('.opt-change').set_input_change_event(load_data);
});



