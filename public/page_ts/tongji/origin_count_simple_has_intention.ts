/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji-origin_count_simple_has_intention.d.ts" />

function load_data(){
    $.reload_self_page ( {
              origin:    $('#id_origin').val(),
              origin_ex:    $('#id_origin_ex').val(),
              seller_groupid_ex:    $('#id_seller_groupid_ex').val(),
              admin_revisiterid:    $('#id_admin_revisiterid').val(),
              groupid:    $('#id_groupid').val(),
              tmk_adminid:    $('#id_tmk_adminid').val(),
              check_field_id:    $('#id_check_field_id').val(),
              date_type_config:    $('#id_date_type_config').val(),
              date_type:    $('#id_date_type').val(),
              opt_date_type:    $('#id_opt_date_type').val(),
              start_time:    $('#id_start_time').val(),
              end_time:    $('#id_end_time').val()
    });
}




$(function(){
//    $(".common-table").tbody_scroll_table(500);



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
    $('#id_origin').val(g_args.origin);
    $('#id_origin_ex').val(g_args.origin_ex);
    $('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);
    $("#id_seller_groupid_ex").init_seller_groupid_ex();

    $('#id_admin_revisiterid').val(g_args.admin_revisiterid);
    $('#id_groupid').val(g_args.groupid);
    $('#id_tmk_adminid').val(g_args.tmk_adminid);
    $('#id_check_field_id').val(g_args.check_field_id);

    $.admin_select_user(
        $('#id_tmk_adminid'),
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


      $('.opt-change').set_input_change_event(load_data);
    $(".common-table").table_group_level_4_init();

    if ($.get_action_str()=="origin_count_bd") {
        $("#id_origin_ex").parent().parent().hide();
    }


});
