/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/main_page2-market.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
      date_type_config:	$('#id_date_type_config').val(),
      date_type:	$('#id_date_type').val(),
      opt_date_type:	$('#id_opt_date_type').val(),
      start_time:	$('#id_start_time').val(),
      end_time:	$('#id_end_time').val()
        });
    }


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


  $('.opt-change').set_input_change_event(load_data);
    //@desn:修改合同活动总配额
    $("#id_edit_order_sum_activity_quota").on("click",function(){
        var market_quota = $("<input/>");
        market_quota.val($(this).parent().find("span").text() );
        var arr=[
            ["总配额" , market_quota ],
        ];
        $.show_key_value_table("总配额编辑", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/main_page2/config_order_sum_activity_quato",{
                    "opt_time" :g_args.start_time,
                    "market_quota" : market_quota.val(),
                });
            }
        });


    });
    //@desn:修改活动描述[名称] [预算配额]
    $(".opt-edit_order_activity_detail").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var order_activity_desc = $("<input/>");
        var market_quota= $("<input/>");
        order_activity_desc.val($(this).parent().find("span").text());
        market_quota.val($(this).parent().next().find("span").text() );
        var id = $(this).parent().prev().find("span").text();
        console.log(id);
        var arr=[
            ["活动名称" , order_activity_desc ],
            ["预算配额" , market_quota ],
        ];
        $.show_key_value_table("配额编辑", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/main_page2/config_order_activity_detail",{
                    "id":id,
                    "order_activity_desc":order_activity_desc.val(),
                    "market_quota" : market_quota.val()
                });
            }
        });


    });
    $(".opt_add_order_activity").on('click',function(){
        var order_activity_desc = $("<input/>");
        var market_quota = $("<input/>");
        var arr=[
            ['活动名称',order_activity_desc],
            ["预算配额",market_quota],
        ];
        $.show_key_value_table("添加活动", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/main_page2/add_order_activity",{
                    "opt_time" :g_args.start_time,
                    "order_activity_desc" : order_activity_desc.val(),
                    "market_quota" : market_quota.val(),
                });
            }
        });


    });
    
    $("#id_edit_seller_diff_money_def").on("click",function(){
        var $seller_diff_money_def = $("<input/>");
        $seller_diff_money_def.val($(this).parent().find("span").text() );
        var arr=[
            ["配额" , $seller_diff_money_def ],
        ];
        $.show_key_value_table("配额编辑", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal2/config_date_set",{
                    "config_date_type" :1,
                    "opt_time" :g_args.start_time,
                    "value" : $seller_diff_money_def.val()
                });
            }
        });


    });

    //修改助教配额
    $("#id_edit_teach_assistant_diff_money_def").on("click",function(){
        var $teach_assistant_diff_money_def = $("<input/>");
        $teach_assistant_diff_money_def.val($(this).parent().find("span").text() );
        var arr=[
            ["配额" , $teach_assistant_diff_money_def ],
        ];
        $.show_key_value_table("配额编辑", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal2/teach_assistant_config_date_set",{
                    "config_date_type" :2,
                    "opt_time" :g_args.start_time,
                    "value" : $teach_assistant_diff_money_def.val()
                });
            }
        });


    });

});
