/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-pay_money_stu_list.d.ts" />

$(function(){
    var show_name_key="stu_info_name_"+g_adminid;

    $( "#id_user_name" ).autocomplete({
        source: "/user_deal/get_item_list?list_flag=1&item_key="+show_name_key,
        minLength: 0,
        select: function( event, ui ) {
            $("#id_user_name").val(ui.item.value);
            load_data();
        }
    });


    Enum_map.append_option_list("grade", $("#id_grade"));
    Enum_map.append_option_list("stu_origin", $("#id_originid"));
    Enum_map.td_show_desc("grade", $(".td-grade"));
    Enum_map.td_show_desc("grade", $(".td-grade-up"));
    Enum_map.td_show_desc("relation_ship", $(".td-parent-type"));

    $("#id_grade").val(g_args.grade);
    $("#id_originid").val(g_args.originid);
    $("#id_user_name").val(g_args.user_name);
    $("#id_phone").val(g_args.phone);
    $("#id_seller_adminid").val(g_args.seller_adminid);

    $("#id_assistantid").val(g_args.assistantid);

    $.admin_select_user($("#id_assistantid"), "assistant", load_data);
    $.admin_select_user($("#id_seller_adminid"), "admin", load_data);

    $('.opt-change').set_input_change_event(load_data);


    //点击进入个人主页
    $('.opt-user').on('click',function(){
        var opt_data=$(this).get_opt_data();
        window.open(
            '/stu_manage?sid='+ opt_data.userid +"&return_url="+ encodeURIComponent(window.location.href)
        );
    });

    function load_data(){
        if ($.trim($("#id_user_name").val()) != g_args.user_name ) {
            $.do_ajax("/user_deal/set_item_list_add",{
                "item_key" :show_name_key,
                "item_name":  $.trim($("#id_user_name").val())
            },function(){});
        }

        $.reload_self_page({
            test_user   : $("#id_test_user").val(),
            originid    : $("#id_originid").val(),
            grade       : $("#id_grade").val(),
            user_name   : $("#id_user_name").val(),
            phone       : $("#id_phone").val(),
            assistantid : $("#id_assistantid").val(),
            order_type : $("#id_order_type").val(),
            seller_adminid : $("#id_seller_adminid").val()
        });
    }

});

