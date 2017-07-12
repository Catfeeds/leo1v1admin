/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-get_relation_order_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			orderid:	$('#id_orderid').val(),
			contract_type:	$('#id_contract_type').val()
        });
    }


	$('#id_orderid').val(g_args.orderid);
	$('#id_contract_type').val(g_args.contract_type);


	$('.opt-change').set_input_change_event(load_data);
});
