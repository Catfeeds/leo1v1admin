/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/requirement-requirement_info_development.d.ts" />

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
            development_status: $('#id_development_status').val(),
            test_status      : $('#id_test_status').val(),
        });
    }

    Enum_map.append_option_list("require_class",$("#id_name"));
    Enum_map.append_option_list("require_priority",$("#id_priority"));
    Enum_map.append_option_list("require_significance",$("#id_significance"));
    Enum_map.append_option_list("require_status",$("#id_status"),false,[3,4,5]);
    Enum_map.append_option_list("require_development_status",$("#id_development_status"),false,[0,1,2,3,4]);
    Enum_map.append_option_list("require_test_status",$("#id_test_status"),false,[0,1,2,3,4]);


    $("#id_name").val(g_args.name);
    $("#id_priority").val(g_args.priority);
    $("#id_significance").val(g_args.significance);
    $("#id_status").val(g_args.status);
    $("#id_development_status").val(g_args.development_status);
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
    $(".opt-re-deal").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm("要重新处理提交人是["+opt_data.product_operator_nick+"]的产品方案吗?",function(val){
            if(val){
                $.do_ajax("/requirement/development_deal",{
                    "id" : opt_data.id
                });
            }
        });
    });

    $(".opt-deal").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm("要处理提交人是["+opt_data.product_operator_nick+"]的产品方案吗?",function(val){
            if(val){
                $.do_ajax("/requirement/development_deal",{
                    "id" : opt_data.id
                });
            }
        });
    });

    $(".opt-reject").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var development_reject         = $("<textarea />"); //驳回原因
        var arr = [
            ["驳回原因",    development_reject],
        ];
        $.show_key_value_table("驳回需求请求", arr, {
            label    :  "确认",
            cssClass :  'btn-waring',
            action   :   function(dialog){
                $.do_ajax("/requirement/development_reject",{
                    "id"           : opt_data.id,
                    'development_reject'       : development_reject.val(),
                });
            }
        },function(){
        });
    });

    $(".opt-do").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm("要开始开发提交人是["+opt_data.product_operator_nick+"]的产品方案吗?",function(val){
            if(val){
                $.do_ajax("/requirement/development_do",{
                    "id" : opt_data.id
                });
            }
        });
    });
    
    $(".opt-finish").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm("处理完成提交人是["+opt_data.product_operator_nick+"]的产品方案吗?",function(val){
            if(val){
                $.do_ajax("/requirement/development_finish",{
                    "id" : opt_data.id
                });
            }
        });
    });
	$('.opt-change').set_input_change_event(load_data);
});
