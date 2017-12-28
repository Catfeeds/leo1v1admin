/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-tongji_cr.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type_config:	$('#id_date_type_config').val(),
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val(),
            history:	$('#id_is_history_data').val()
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

    $('#id_is_history_data').val(g_args.history);

    $("#download_data").on("click",function(){
        var report_type = g_data.type == 1?'月报':'周报';

        if(g_args.is_history_data ==1){
            var list_data=[
                ['报告类型','统计时段'],
                [report_type,g_data.create_time_range],
                ["例子数",g_data.all_example_info.example_num],
                ["有效例子",g_data.all_example_info.valid_example_num],
                ["已拨打例子",g_data.all_example_info.called_num],
                ["有效例子数占比",g_data.all_example_info.valid_rate+'%'],
                ["无效资源",g_data.all_example_info.invalid_example_num],
                ["无效例子数占比",g_data.all_example_info.invalid_rate+'%'],
                ["未接通",g_data.all_example_info.not_through_num],
                ["未接通例子数占比",g_data.all_example_info.not_through_rate+'%'],
                ['年级统计','结点'],
                ["例子数",g_data.all_example_info.example_num],
                ["高中例子",g_data.all_example_info.high_num],
                ["高中例子数占比",g_data.all_example_info.high_num_rate+'%'],
                ["初中例子",g_data.all_example_info.middle_num],
                ["初中例子数占比",g_data.all_example_info.middle_num_rate+'%'],
                ["小学例子",g_data.all_example_info.primary_num],
                ["小学例子数占比",g_data.all_example_info.primary_num_rate+'%'],
                ['微信运营','结点'],
                ["微信运营例子",g_data.all_example_info.wx_example_num],
                ["新签数",g_data.all_example_info.wx_order_count],
                ["新签金额",g_data.all_example_info.wx_order_all_money],
                ['微课统计','结点'],
                ["微课",'暂无数据'],
                ["微课例子",'暂无数据'],
                ["微课签单数",'暂无数据'],
                ["微课签单金额",'暂无数据'],
                ["公众号统计 ","节点"],
                ["公众号例子",g_data.all_example_info.pn_example_num],
                ["公众号签单数",g_data.all_example_info.pn_order_num],
                ["公众号签单金额",g_data.all_example_info.pn_order_money],
                ["人工统计","节点"],
                ["平均单线索价格",'投放金额/例子总量(去重)'],
                ["公开课次数",g_data.all_example_info.public_class_num],
                ["软文发布篇数",'人工统计'],
                ["群维护次数",'人工统计'],
                ["微博微信发布数",'人工统计']
            ];
        }else{
            var list_data=[
                ['报告类型','统计时段'],
                [report_type,g_data.create_time_range],
                ["例子数",g_data.all_example_info.example_num],
                ["有效例子",g_data.all_example_info.valid_example_num],
                ["已拨打例子",g_data.all_example_info.called_num],
                ["有效例子数占比",g_data.all_example_info.valid_rate+'%'],
                ["无效资源",g_data.all_example_info.invalid_example_num],
                ["无效例子数占比",g_data.all_example_info.invalid_rate+'%'],
                ["未接通",g_data.all_example_info.not_through_num],
                ["未接通例子数占比",g_data.all_example_info.not_through_rate+'%'],
                ['年级统计','结点'],
                ["例子数",g_data.all_example_info.example_num],
                ["高中例子",g_data.all_example_info.high_num],
                ["高中例子数占比",g_data.all_example_info.high_num_rate+'%'],
                ["初中例子",g_data.all_example_info.middle_num],
                ["初中例子数占比",g_data.all_example_info.middle_num_rate+'%'],
                ["小学例子",g_data.all_example_info.primary_num],
                ["小学例子数占比",g_data.all_example_info.primary_num_rate+'%'],
                ['微信运营','结点'],
                ["微信运营例子",g_data.wx_example_num],
                ["新签数",g_data.wx_order_info.wx_order_count],
                ["新签金额",g_data.wx_order_info.wx_order_all_money],
                ['微课统计','结点'],
                ["微课",'暂无数据'],
                ["微课例子",'暂无数据'],
                ["微课签单数",'暂无数据'],
                ["微课签单金额",'暂无数据'],
                ["公众号统计 ","节点"],
                ["公众号例子",g_data.pn_example_num],
                ["公众号签单数",g_data.pn_order_num],
                ["公众号签单金额",g_data.pn_order_money],
                ["人工统计","节点"],
                ["平均单线索价格",'投放金额/例子总量(去重)'],
                ["公开课次数",g_data.public_class_num],
                ["软文发布篇数",'人工统计'],
                ["群维护次数",'人工统计'],
                ["微博微信发布数",'人工统计']
            ];
        }


        $.do_ajax ( "/page_common/upload_xls_data",{
            xls_data :  JSON.stringify(list_data )
        },function(data){
            window.location.href= "/common_new/download_xls";
        });

    });



    $('.opt-change').set_input_change_event(load_data);
});
