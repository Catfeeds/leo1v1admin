/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-test_lesson_order_fail_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val(),
            cur_require_adminid:	$('#id_cur_require_adminid').val(),
            origin_userid_flag:	$('#id_origin_userid_flag').val(),
            test_lesson_order_fail_flag:	$('#id_test_lesson_order_fail_flag').val(),
            order_flag:	$('#id_order_flag').val(),
            userid:	$('#id_userid').val()
        });
    }

  Enum_map.append_option_list("boolean",$("#id_origin_userid_flag"));
  Enum_map.append_option_list("boolean",$("#id_order_flag"));
  Enum_map.append_option_list("test_lesson_order_fail_flag",$("#id_test_lesson_order_fail_flag"));

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
  $('#id_cur_require_adminid').val(g_args.cur_require_adminid);
  $('#id_origin_userid_flag').val(g_args.origin_userid_flag);
  $('#id_order_flag').val(g_args.order_flag);
  $('#id_test_lesson_order_fail_flag').val(g_args.test_lesson_order_fail_flag);
  $('#id_userid').val(g_args.userid);

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
    $.admin_select_user($('#id_userid'), "student", load_data );

    var set_select_option_list=function(){
        $("body").on("change","#edit_flag_one",function(){
            if($('#edit_flag_one').val() == 0){
                $('#edit_flag').html("<option value='0'>未设置</option>");
            }else{
                Enum_map.append_child_option_list("test_lesson_order_fail_flag", $(this),$("#edit_flag"),true);
            }
        });
    };
    set_select_option_list();

    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var $test_lesson_order_fail_flag_one=$("<select id='edit_flag_one' />");
        var $test_lesson_order_fail_flag=$("<select id='edit_flag' />");
        var $test_lesson_order_fail_desc =$("<textarea/>");
        var arr=[
            ["上课时间", opt_data.lesson_start ] ,
            ["学生", opt_data.student_nick ] ,
            ["老师", opt_data.teacher_nick] ,
            ["签约失败一级分类", $test_lesson_order_fail_flag_one ] ,
            ["签约失败二级分类", $test_lesson_order_fail_flag ] ,
            ["签约失败说明", $test_lesson_order_fail_desc ] ,
        ];

        Enum_map.append_option_list("test_lesson_order_fail_flag_one",$test_lesson_order_fail_flag_one, true);
        if(opt_data.test_lesson_order_fail_flag_one == 0){
            $test_lesson_order_fail_flag.html("<option value='0'>未设置</option>");
        }else{
            Enum_map.append_child_option_list("test_lesson_order_fail_flag",$test_lesson_order_fail_flag_one,$("#edit_flag"),true);
        }
        // Enum_map.append_option_list("test_lesson_order_fail_flag",$test_lesson_order_fail_flag, true);
        $test_lesson_order_fail_flag_one.val( opt_data.test_lesson_order_fail_flag_one);
        $test_lesson_order_fail_flag.val( opt_data.test_lesson_order_fail_flag);
        $test_lesson_order_fail_desc.val( opt_data.test_lesson_order_fail_desc);
        var dlg=$.show_key_value_table( "签约失败设置", arr , {
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax( "/ss_deal/set_order_fail_info", {
                    "require_id" : opt_data.require_id,
                    "test_lesson_order_fail_flag" : $test_lesson_order_fail_flag.val(),
                    "test_lesson_order_fail_desc" : $test_lesson_order_fail_desc.val(),
                });
            }});
    });


  $('.opt-change').set_input_change_event(load_data);
    if (g_args.hide_cur_require_adminid) {
        $("#id_cur_require_adminid").parent().parent().hide();
    }
    if(g_account=='龚隽'){
        download_show();
    }
});
