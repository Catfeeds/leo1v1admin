/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-tea_wages_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page({
			      date_type           : $('#id_date_type').val(),
			      opt_date_type       : $('#id_opt_date_type').val(),
			      start_time          : $('#id_start_time').val(),
			      end_time            : $('#id_end_time').val(),
			      teacher_ref_type    : $('#id_teacher_ref_type').val(),
			      teacher_money_type  : $('#id_teacher_money_type').val(),
			      teacher_type        : $('#id_teacher_type').val(),
			      level               : $('#id_level').val(),
			      show_data           : $('#id_show_data').val(),
			      show_type           : $('#id_show_type').val(),
			      reference           : $('#id_reference').val(),
        });
    }
    $.admin_select_user($("#id_reference"),"teacher",function(){load_data();});
    $("#id_reference").val(g_args.reference);

    $('#id_date_range').select_date_range({
        'date_type'      : g_args.date_type,
        'opt_date_type'  : g_args.opt_date_type,
        'start_time'     : g_args.start_time,
        'end_time'       : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config), 
        onQuery          : function() {
            load_data();
        }
    });

    $("#id_show_type").val(g_args.show_type);
	  $('.opt-change').set_input_change_event(load_data);
    $(".opt-show").on("click",function(){
        var opt_data = $(this).get_opt_data();
        $.wopen("/user_manage_new/tea_wages_info"
                +"?teacherid="+ opt_data.teacherid
                + "&start_time="+ g_args.start_time
                + "&end_time="+ g_args.end_time
               );
    });

    $(".opt-tea").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/human_resource/index?teacherid="+opt_data.teacherid);
    });

    Enum_map.append_option_list("teacher_type",$("#id_teacher_type"));
    Enum_map.append_option_list("teacher_ref_type",$("#id_teacher_ref_type"));
    Enum_map.append_option_list("teacher_money_type",$("#id_teacher_money_type"));
    Enum_map.append_option_list("level",$("#id_level"));
    $("#id_teacher_type").val(g_args.teacher_type);
    $("#id_teacher_ref_type").val(g_args.teacher_ref_type);
    $("#id_teacher_money_type").val(g_args.teacher_money_type);
    $("#id_level").val(g_args.level);
    $("#id_show_data").val(g_args.show_data);

    $(".opt-money").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var $tr      = $(this).parent().parent().parent();

        $tr.find(".status").text("开始．．．");
        $.do_ajax("/teacher_money/get_teacher_total_money",{
            "teacherid"  : opt_data.teacherid,
            "start_time" : g_args.start_time,
            "end_time"   : g_args.end_time,
            "type"       : "admin"
        },function(result){
            var data = result.data[0];

            $tr.find(".lesson_price_tax").text(data.lesson_price_tax);
            $tr.find(".lesson_price").text(data.lesson_price);
            $tr.find(".lesson_normal").text(data.lesson_normal);
            $tr.find(".lesson_trial").text(data.lesson_trial);
            $tr.find(".lesson_reward").text(data.lesson_reward);
            $tr.find(".lesson_reward_ex").text(data.lesson_reward_ex);
            $tr.find(".lesson_cost").text(data.lesson_cost);
            $tr.find(".lesson_cost_tax").text(data.lesson_cost_tax);
            $tr.find(".lesson_total").text(data.lesson_total);

            if(opt_data.lesson_total != data.lesson_total){
                $tr.find("td").addClass("error_money");
            }

            $tr.find(".status").text("完成");
        });
    });

    $("#id_show_money_all").on("click",function(){
        var row_list      = $("#id_tbody tr");
        var id_money_info = $("#id_money_info");
        var do_index      = 0;

        var teacher_money       = 0;
        var teacher_money_tax   = 0;
        var teacher_trial       = 0;
        var teacher_normal      = 0;
        var teacher_reward      = 0;
        var teacher_reward_ex   = 0;
        var teacher_cost        = 0;
        var teacher_cost_normal = 0;
        var teacher_cost_tax    = 0;
        var teacher_full_money  = 0;

        $("#id_lesson_price").val(0);
        $("#id_teacher_cost").val(0);
        $("#id_teacher_cost_tax").val(0);
        $("#id_teacher_money_tax").val(0);
        $("#id_teacher_money").val(0);
        $("#id_teacher_normal").val(0);
        $("#id_teacher_trial").val(0);
        $("#id_teacher_reward").val(0);
        $("#id_teacher_reward_ex").val(0);
        $("#id_teacher_ref_money_1").val(0);
        $("#id_teacher_ref_money_2").val(0);
        $("#id_teacher_ref_money_3").val(0);

        function do_one() {
            if (do_index < row_list.length ) {
                var $tr              = $(row_list[do_index]);
                var opt_data         = $tr.find(".opt-show").get_opt_data();
                var teacher_ref_type = opt_data.teacher_ref_type;

                $tr.find(".status").text("开始．．．");
                $.do_ajax("/teacher_money/get_teacher_total_money",{
                    "teacherid"  : opt_data.teacherid,
                    "start_time" : g_args.start_time,
                    "end_time"   : g_args.end_time,
                    "type"       : "admin",
                    "show_type"  : $("#id_show_type").val()
                },function(result){
                    var data = result.data[0];
                    var id_teacher_ref_money = "#id_teacher_ref_money_"+teacher_ref_type;
                    var id_lesson_ref_money  = "#id_lesson_ref_money_"+teacher_ref_type;
                    var teacher_ref_money    = parseFloat($(id_teacher_ref_money).val());
                    var lesson_ref_money     = parseFloat($(id_lesson_ref_money).val());

                    $tr.find(".lesson_ref_money").text(data.lesson_ref_money);
                    $tr.find(".teacher_ref_money").text(data.teacher_ref_money);
                    $tr.find(".teacher_ref_rate").text(data.teacher_ref_rate);
                    teacher_ref_money   += parseFloat(data.teacher_ref_money);
                    lesson_ref_money    += parseFloat(data.lesson_ref_money);
                    $(id_teacher_ref_money).val(teacher_ref_money);
                    $(id_lesson_ref_money).val(lesson_ref_money);
                    $tr.find(".lesson_ref_money").text(data.lesson_ref_money);

                    $tr.find(".lesson_price_tax").text(data.lesson_price_tax);
                    $tr.find(".lesson_price").text(data.lesson_price);
                    $tr.find(".lesson_cost").text(data.lesson_cost);
                    $tr.find(".lesson_cost_normal").text(data.lesson_cost_normal);
                    $tr.find(".lesson_total").text(data.lesson_total);

                    teacher_money       += parseFloat(data.lesson_price);
                    teacher_money_tax   += parseFloat(data.lesson_price_tax);
                    teacher_trial       += parseFloat(data.lesson_trial);
                    teacher_normal      += parseFloat(data.lesson_normal);
                    teacher_reward      += parseFloat(data.lesson_reward);
                    teacher_reward_ex   += parseFloat(data.lesson_reward_ex);
                    teacher_cost        += parseFloat(data.lesson_cost);
                    teacher_cost_normal += parseFloat(data.lesson_cost_normal);
                    teacher_cost_tax    += parseFloat(data.lesson_cost_tax);
                    teacher_full_money  += parseFloat(data.lesson_full_reward);

                    $("#id_teacher_money").val(teacher_money);
                    $("#id_teacher_money_tax").val(teacher_money_tax);
                    $("#id_teacher_trial").val(teacher_trial);
                    $("#id_teacher_normal").val(teacher_normal);
                    $("#id_teacher_reward").val(teacher_reward);
                    $("#id_teacher_reward_ex").val(teacher_reward_ex);
                    $("#id_teacher_cost").val(teacher_cost);
                    $("#id_teacher_cost_normal").val(teacher_cost_normal);
                    $("#id_teacher_cost_tax").val(teacher_cost_tax);
                    $("#id_teacher_full_money").val(teacher_full_money);

                    if (opt_data.lesson_total != data.lesson_total) {
                        $tr.find("td").addClass("error_money");
                    }
                    $tr.find(".status").text("完成");
                });
                do_index++;
                do_one();
            }
        };

        $.do_ajax("/user_manage_new/get_lesson_price",{
            "start_time"         : g_args.start_time,
            "end_time"           : g_args.end_time,
            "teacher_money_type" : $("#id_teacher_money_type").val(),
        },function(result){
            $("#id_lesson_price").val(result.lesson_price);
            do_one();
        });
    });

    $("#id_reset_lesson_count_all").on("click",function(){
        var row_list=$("#id_tbody tr");
        var do_index=0;

        function do_one(  ) {
            if (do_index < row_list.length ) {
                var $tr=$(row_list[do_index]);
                var opt_data=$tr.find(".opt-show").get_opt_data();
                $tr.find(".status").text("开始．．．");
                $.do_ajax("/user_deal/reset_already_lesson_count",{
                    "teacherid"    : opt_data.teacherid,
                    "start_time"   :g_args.start_time,
                    "end_time"    :g_args.end_time
                },function(){
                    $tr.find(".status").text("完成");
                    do_index++;
                    do_one();
                });
            }
        };
        do_one();
    });





});
