/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-agent_user_wechat.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            phone: $("#id_phone").val(),
            id: g_args.id
        });
    }


    $('#id_phone').val(g_args.phone);


    $('.opt-change').set_input_change_event(load_data);

    //电话列表
    $(".opt-telphone").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var phone = opt_data.p1_phone == null ? opt_data.p2_phone:opt_data.p1_phone;
        $.wopen('/tq/get_list?phone=' + phone);

    });

});
