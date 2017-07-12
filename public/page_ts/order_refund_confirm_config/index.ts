

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/order_refund_confirm_config-index.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			key1:	$('#id_key1').val(),
			key2:	$('#id_key2').val(),
			key3:	$('#id_key3').val()
        });
    }


	$('#id_key1').val(g_args.key1);
	$('#id_key2').val(g_args.key2);
	$('#id_key3').val(g_args.key3);


	$('.opt-change').set_input_change_event(load_data);
});



/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">key1</span>
                <input class="opt-change form-control" id="id_key1" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">key2</span>
                <input class="opt-change form-control" id="id_key2" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">key3</span>
                <input class="opt-change form-control" id="id_key3" />
            </div>
        </div>
*/
