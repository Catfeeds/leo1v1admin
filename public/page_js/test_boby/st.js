/// <时间 path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test_boby-st.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			nick_phone:	$('#id_nick_phone').val(),
        });
    }


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
