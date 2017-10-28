/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test_lesson_opt-test_opt_list.d.ts" />

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
        test_lesson_type : $('#id_test_lesson_type').val(),
        action           : $('#id_action').val(),
        test_opt_type    : $('#id_test_opt_type').val(),
        adminid          : $('#id_adminid').val(),
        user_name        : $("#id_user_name").val(),
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
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });
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

    Enum_map.append_option_list("test_lesson_type", $("#id_test_lesson_type"));
    Enum_map.append_option_list("action", $("#id_action"));
    $('#id_test_lesson_type').val(g_args.test_lesson_type);
    $('#id_action').val(g_args.action);
    $('#id_adminid').val(g_args.adminid);
    $("#id_user_name").val(g_args.user_name);
    if($('#id_action').val() == 1){
        Enum_map.append_option_list("test_opt_type", $("#id_test_opt_type"));
        if(g_args.test_opt_type>0){
            $('#id_test_opt_type').val(g_args.test_opt_type);
        }else{
            $('#id_test_opt_type').val(-1);
        }
    }else if($('#id_action').val() == 2){
        Enum_map.append_option_list("test_opt_type_new", $("#id_test_opt_type"));
        if(g_args.test_opt_type>0){
            $('#id_test_opt_type').val(g_args.test_opt_type);
        }else{
            $('#id_test_opt_type').val(-1);
        }
    }
    $('.opt-change').set_input_change_event(load_data);
});
