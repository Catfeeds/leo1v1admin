/// <时间 path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test_boby-st.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			nick_phone:	$('#id_nick_phone').val(),
            date_type_config:	$('#id_date_type_config').val(),
			      date_type:	$('#id_date_type').val(),
			      opt_date_type:	$('#id_opt_date_type').val(),
			      start_time:	$('#id_start_time').val(),
			      end_time:	$('#id_end_time').val(),

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


	$('#id_nick_phone').val(g_args.nick_phone);


	$('.opt-change').set_input_change_event(load_data);
});



/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">nick_phone</span>
                <input class="opt-change form-control" id="id_nick_phone" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account_role</span>
                <input class="opt-change form-control" id="id_account_role" />
            </div>
        </div>
*/
