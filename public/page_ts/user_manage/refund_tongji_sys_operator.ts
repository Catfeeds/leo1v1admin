/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-refund_tongji_sys_operator.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
        $.reload_self_page ( {
        order_by_str : g_args.order_by_str,
        date_type_config:   $('#id_date_type_config').val(),
        date_type:  $('#id_date_type').val(),
        opt_date_type:  $('#id_opt_date_type').val(),
        start_time: $('#id_start_time').val(),
        end_time:   $('#id_end_time').val(),
        sys_operator      : $("#id_sys_operator").val(),
        account_role      : $("#id_account_role").val(),
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


    $("#id_account_role").val(g_args.account_role);
    $("#id_sys_operator").val(g_args.sys_operator);
    $(".apply_num").on("click",function(){
        var sys_operator = $(this).data("sys");
        window.open(
             '/user_manage/refund_list?is_test_user=0&sys_operator='+sys_operator
         );
    });

    $(".one_month").on("click",function(){
         var opt_data=$(this).get_opt_data();
         var adminid = $(this).data("id");
         window.open(
             '/user_manage/contract_list?adminid='+ adminid + "&contract_status=3&has_money=1"
         );
    });

    $(".detail_info").on("click",function(){
        var adminid = $(this).data("userid");
        $.do_ajax("/ss_deal/get_admin_info_by_id", {
            "adminid" : adminid,
        }, function (ret) {
            if(ret != 0){
                var gender = ret.data.gender;
                var account_role = ret.data.account_role;
                var account = ret.data.account;
                var age     = ret.data.age;
                var id_account = $("<input readonly='readonly'/>");  
                var id_age = $("<input readonly='readonly'/>");  
                var id_gender  = $("<input readonly='readonly'/>");   
                var id_account_role = $("<input readonly='readonly' />");

                Enum_map.append_option_list("gender", id_gender, true);
                Enum_map.append_option_list("account_role", id_account_role,true);

                id_account.val(account);
                id_gender.val(gender);
                id_age.val(age);
                id_account_role.val(account_role);

                var arr = [
                    ["姓名", id_account],
                    ["性别", id_gender],
                    ["年龄", id_age],
                    ["类型", id_account_role],
                ];

                $.show_key_value_table("下单人信息", arr, {
                    cssClass :  'btn-waring',
                    action   :   function(dialog){
                    }
                });


            }
        });
    });

    $('.opt-change').set_input_change_event(load_data);
});
