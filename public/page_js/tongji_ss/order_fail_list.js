/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-order_fail_list.d.ts" />
$(function(){
    function load_data(){
        $.reload_self_page ( {
            cur_require_adminid: $('#id_cur_require_adminid').val(),
            date_type:           $('#id_date_type').val(),
            opt_date_type:       $('#id_opt_date_type').val(),
            start_time:          $('#id_start_time').val(),
            end_time:            $('#id_end_time').val(),
            require_admin_type:  $('#id_require_admin_type').val(),
            origin_userid_flag:  $('#id_origin_userid_flag').val()
        });
    }

  Enum_map.append_option_list("boolean",$("#id_origin_userid_flag"));
  Enum_map.append_option_list("account_role",$("#id_require_admin_type"));

    $('#id_date_range').select_date_range({
        'date_type' :      g_args.date_type,
        'opt_date_type' :  g_args.opt_date_type,
        'start_time'    :  g_args.start_time,
        'end_time'      :  g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :          function() {
            load_data();
        }
    });
  $('#id_cur_require_adminid').val(g_args.cur_require_adminid);
  $('#id_origin_userid_flag').val(g_args.origin_userid_flag);
  $('#id_require_admin_type').val(g_args.require_admin_type);

    $.admin_select_user(
        $('#id_cur_require_adminid'),
        "admin", load_data ,false, {
            " main_type": -1,
            select_btn_config: [
                {
                    "label": "[已分配]",
                    "value": -2
                }]
        }
    );



  $('.opt-change').set_input_change_event(load_data);
    var get_row_date_query_str=function( a_link )  {
    };


    $(".opt-desc").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var url= "/seller_student_new/test_lesson_order_fail_list?start_time="+g_args.start_time +
            "&end_time="+g_args.end_time +
            "&opt_date_type="+g_args.opt_date_type +
            "&test_lesson_order_fail_flag=" + opt_data.test_lesson_order_fail_flag
            ;
        $.wopen(url);


    });

});
