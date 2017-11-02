/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-get_yxyx_member.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}

    $.reload_self_page ( {
        date_type     :    $('#id_date_type').val(),
        opt_date_type :    $('#id_opt_date_type').val(),
        start_time    :    $('#id_start_time').val(),
        end_time      :    $('#id_end_time').val(),

		    phone : $('#id_phone').val(),
    });
}

$(function(){

    $('#id_date_range').select_date_range({
        'date_type'     : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

    $('.opt-a td').css('cursor','pointer');
    $('.opt-a td').on('click',function(){
        var id = $(this).parent().attr('data-id');
        window.open("http://self.admin.yb1v1.com/agent/get_yxyx_member_detail"+location.search+"&id="+id);
    });


	$('#id_phone').val(g_args.phone);
	$('.opt-change').set_input_change_event(load_data);
});
