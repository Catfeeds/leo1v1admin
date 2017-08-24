/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/requirement-requirement_info_test.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type_config:   $('#id_date_type_config').val(),
            date_type:  $('#id_date_type').val(),
            opt_date_type:  $('#id_opt_date_type').val(),
            start_time: $('#id_start_time').val(),
            end_time:   $('#id_end_time').val(),
            name             : $('#id_name').val(),
            priority         : $('#id_priority').val(),
            significance     : $('#id_significance').val(),
            status           : $('#id_status').val(),
            test_status      : $('#id_test_status').val(),
        });
    }

    Enum_map.append_option_list("require_class",$("#id_name"));
    Enum_map.append_option_list("require_priority",$("#id_priority"));
    Enum_map.append_option_list("require_significance",$("#id_significance"));
    Enum_map.append_option_list("require_status",$("#id_status"),false,[4,5]);
    Enum_map.append_option_list("require_test_status",$("#id_test_status"),false,[0,1,2,3,4]);

    $("#id_name").val(g_args.name);
    $("#id_priority").val(g_args.priority);
    $("#id_significance").val(g_args.significance);
    $("#id_status").val(g_args.status);
    $("#id_test_status").val(g_args.test_status);



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
    

    $(".opt-deal").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm("要测试提交人是["+opt_data.development_operator_nick+"]的代码吗?",function(val){
            if(val){
                $.do_ajax("/requirement/test_deal",{
                    "id" : opt_data.id
                });
            }
        });
    });

    $(".opt-reject").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var test_reject         = $("<textarea />"); //驳回原因
        var arr = [
            ["驳回原因",    test_reject],
        ];
        $.show_key_value_table("驳回研发请求", arr, {
            label    :  "确认",
            cssClass :  'btn-waring',
            action   :   function(dialog){
                $.do_ajax("/requirement/test_reject",{
                    "id"                : opt_data.id,
                    'test_reject'       : test_reject.val(),
                });
            }
        },function(){
        });
    });

    $(".opt-do").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm("要开始测试提交人是["+opt_data.development_operator_nick+"]的代码吗?",function(val){
            if(val){
                $.do_ajax("/requirement/test_do",{
                    "id" : opt_data.id
                });
            }
        });
    });
    
    $(".opt-finish").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm("测试完成提交人是["+opt_data.development_operator_nick+"]的代码吗?",function(val){
            if(val){
                $.do_ajax("/requirement/test_finish",{
                    "id" : opt_data.id
                });
            }
        });
    });



	$('.opt-change').set_input_change_event(load_data);
});
