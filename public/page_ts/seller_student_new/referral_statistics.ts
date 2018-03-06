/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-referral_statistics.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type_config:	$('#id_date_type_config').val(),
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val(),
            principal:$('#id_principal').val(),
            groupid:$('#id_group').val(),
            create:$('#id_create').val(),
            allocation:$('#id_allocation').val(),
            type:$('#id_type').val(),
            search:$('#id_search').val()
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


    Enum_map.append_option_list("referral_type",$("#id_type"));
    $('.opt-change').set_input_change_event(load_data);
    $('#id_principal').val(g_args.principal);
    $('#id_group').val(g_args.groupid);
    $('#id_create').val(g_args.create);
    $('#id_allocation').val(g_args.allocation);
    $('#id_type').val(g_args.type);
    $('#id_search').val(g_args.search);


    //@desn:循环获取试听及签单情况
    var do_index = 0;
    function load_row_data (){
        var row_list = $("#id_tbody .referral-tr");
        function do_one() {
            if (do_index < row_list.length ) {
                var $tr      = $(row_list[do_index]);
                var opt_data = $tr.find(".opt-show").get_opt_data();
                $.do_ajax("/seller_student_new/get_referral_info",{
                    "userid"    : opt_data['userid']
                },function(data){
                    pushData($tr,data)
                    do_index++;
                    do_one();
                });
            }
        };
        do_one();
    };

    load_row_data ();

    function pushData(obj,data){
        obj.find(".is_test_require").text(data["is_test_lesson"]);
        obj.find(".is_test_succ").text(data["is_test_succ"]);
        obj.find(".is_order").text(data["is_order"]);
        obj.find(".order_money").text(data["order_money"]);
    }

    $("#id_jump").on("click",function(){
        window.location.href = '/seller_student_new/referral_statistics_by_layer';
    });


});
