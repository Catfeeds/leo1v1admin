/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-get_yxyx_member.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}

    $.reload_self_page ( {
        date_type     :    $('#id_date_type').val(),
        opt_date_type :    $('#id_opt_date_type').val(),
        start_time    :    $('#id_start_time').val(),
        end_time      :    $('#id_end_time').val(),

		    phone    : $('#id_phone').val(),
		    nickname : $('#id_nickname').val()
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
	$('#id_phone').val(g_args.phone);
	$('#id_nickname').val(g_args.nickname);


	$('.opt-change').set_input_change_event(load_data);
});



/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">nickname</span>
                <input class="opt-change form-control" id="id_nickname" />
            </div>
        </div>
*/
