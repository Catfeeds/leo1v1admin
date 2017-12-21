/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-seller_edit_log_list.d.ts" />
function load_data(){
    if ( window["g_load_data_flag"]) {return;}

    var show_name_key="stu_info_name_"+g_adminid;
    if ($.trim($("#id_user_name").val()) != g_args.user_name ) {
        $.do_ajax("/user_deal/set_item_list_add",{
            "item_key" :show_name_key,
            "item_name":  $.trim($("#id_user_name").val())
        },function(){});
    }
    $.reload_self_page ( {
        date_type_config : $('#id_date_type_config').val(),
        date_type        : $('#id_date_type').val(),
        opt_date_type    : $('#id_opt_date_type').val(),
        start_time       : $('#id_start_time').val(),
        end_time         : $('#id_end_time').val(),
        adminid          : $('#id_adminid').val(),
        origin_ex        : $("#id_origin_ex").val(),
        user_name        : $("#id_user_name").val(),
        uid              : $('#id_uid').val(),
        hand_get_adminid : $('#id_hand_get_adminid').val(),
    });
}
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
    Enum_map.append_option_list("hand_get_adminid",$("#id_hand_get_adminid"));
    $('#id_adminid').val(g_args.adminid);
    $('#id_origin_ex').val(g_args.origin_ex);
    $("#id_user_name").val(g_args.user_name);
    $('#id_uid').val(g_args.uid);
    $('#id_hand_get_adminid').val(g_args.hand_get_adminid);

    $.admin_select_user(
        $('#id_adminid'),
        "admin", load_data ,false, {
            " main_type": 2,
            select_btn_config: [
                {
                    "label": "[已分配]",
                    "value": -2
                }, {
                    "label": "[未分配]",
                    "value": 0
                }]
        }
    );

    $.admin_select_user(
        $('#id_uid'),
        "admin", load_data ,false, {
            " main_type": 2,
            select_btn_config: [
                {
                    "label": "[已分配]",
                    "value": -2
                }, {
                    "label": "[未分配]",
                    "value": 0
                }]
        }
    );
    var jump_url_1="/tq/get_list_by_phone";
    $(".opt-return-back-list").on("click",function(){
        var opt_data= $(this).get_row_opt_data();
        $.wopen(jump_url_1+"?"
                +"phone="+opt_data.phone
               );
    });

    $(".show_phone").on("click",function(){
        var phone = $(this).data("phone");
        BootstrapDialog.alert(phone);
    });

    $('.opt-change').set_input_change_event(load_data);
});
