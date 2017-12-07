/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test-p_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            nick_phone:	$('#id_nick_phone').val(),
            account_role:	$('#id_account_role').val()
        });
    }


    $('#id_nick_phone').val(g_args.nick_phone);
    $('#id_account_role').val(g_args.account_role);
    $.enum_multi_select( $('#id_account_role'), 'account_role', function(){load_data();} )


    $('.opt-change').set_input_change_event(load_data);


    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();
        alert(opt_data.name );
    });



});
