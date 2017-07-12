/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-tea_lesson_count_total_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			check_adminid          : $('#id_check_adminid').val(),
			has_check_adminid_flag : $('#id_has_check_adminid_flag').val(),
			date_type              : $('#id_date_type').val(),
			opt_date_type          : $('#id_opt_date_type').val(),
			start_time             : $('#id_start_time').val(),
			end_time               : $('#id_end_time').val(),
			confirm_flag           : $('#id_confirm_flag').val(),
			pay_flag               : $('#id_pay_flag').val()
        });
    }

	Enum_map.append_option_list("boolean",$("#id_confirm_flag")); 
	Enum_map.append_option_list("boolean",$("#id_pay_flag")); 
	Enum_map.append_option_list("boolean",$("#id_has_check_adminid_flag")); 
	$('#id_confirm_flag').val(g_args.confirm_flag);
	$('#id_pay_flag').val(g_args.pay_flag);
	$('#id_has_check_adminid_flag').val(g_args.has_check_adminid_flag);
	$('#id_check_adminid').val(g_args.check_adminid);

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

    $.admin_select_user( $("#id_check_adminid"),"admin", load_data ,false, {"main_type" : 3});

    $(".opt-show").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/user_manage_new/tea_lesson_count_detail_list"
                +"?teacherid="+ opt_data.teacherid
                + "&start_time="+ g_args.start_time
                + "&end_time="+ g_args.end_time
               );
    });

    $(".opt-tea").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/human_resource/index?teacherid="+ opt_data.teacherid );
    });

	$('.opt-change').set_input_change_event(load_data);
    $("#id_reset_lesson_count_all").on("click",function(){
        var row_list = $("#id_tbody tr");
        var do_index = 0;
	    
        function do_one() {
            if (do_index < row_list.length ) {
                var $tr      = $(row_list[do_index]);
                var opt_data = $tr.find(".opt-show").get_opt_data();
                $tr.find(".status").text("开始．．．");
                $.do_ajax("/user_deal/reset_already_lesson_count",{
                    "teacherid"  : opt_data.teacherid,
                    "start_time" : g_args.start_time,
                    "end_time"   : g_args.end_time
                },function(){
                    $tr.find(".status").text("完成");
                    do_index++;
                    do_one();
                });
            }
        };
        do_one();
    });

    $("#id_show_money_all").on("click",function(){
        var row_list=$("#id_tbody tr");
        var do_index=0;
        var all_money=0;
        var test_money=0;
        var l1v1_money=0;
        var lesson_money=0;
        var $id_money_info=$("#id_money_info");
        function do_one() {
            if (do_index < row_list.length ) {
                var $tr=$(row_list[do_index]);
                var opt_data=$tr.find(".opt-show").get_opt_data();
                $tr.find(".status").text("开始．．．");
                $.do_ajax("/user_manage_new/get_teacher_all_money",{
                    "teacherid"  : opt_data.teacherid,
                    "start_time" : g_args.start_time,
                    "end_time"   : g_args.end_time
                },function(resp){
                    $tr.find(".status").text("完成");
                    $tr.find(".l1v1_money").text( resp.l1v1_money );
                    $tr.find(".all_money").text( resp.all_money);
                    $tr.find(".all_info").text( resp.all_money);
                    $tr.find(".all_lesson_price").text( resp.all_lesson_price);
                    $tr.find(".test_money").text( resp.test_money);
                    
                    var $data_div=$tr.find(".opt-show").parent();
                    $data_div.data("all_money" ,resp.all_money);
                    $data_div.data("l1v1_money" ,resp.l1v1_money);
                    $data_div.data("test_money" ,resp.test_money);
                    $data_div.data("all_lesson_price" ,resp.all_lesson_price);
                    
                    if (opt_data.real_money_all_count != resp.all_money) {
                        $tr.find("td").addClass("error_money");
                    }

                    all_money+=resp.all_money;
                    test_money+=resp.test_money;
                    l1v1_money+=resp.l1v1_money;
                    lesson_money+=resp.lesson_money;

                    $id_money_info.text("总金额:"+all_money+",--1v1:"+l1v1_money+",--试听:"+test_money+",--课程收入:"+lesson_money);
                    do_index++;
                    check_lesson_price($tr);
                    do_one();
                });
            }else{
            }
        };
        do_one();

    });

    function check_lesson_price(obj){
        var all_lesson_money=obj.find(".opt-div").data("all_lesson_money");
        var all_lesson_price=obj.find(".opt-div").data("all_lesson_price");
        if(all_lesson_money!=all_lesson_price){
            obj.find(".lesson_price").css({"background-color":"blue","color":"red"});
        }
    }
    
    $(".opt-un_confirm").on("click",function(){
        var opt_data=$(this).get_opt_data();
	    BootstrapDialog.confirm(
            "["+opt_data.realname+"]取消确认总金额?", 
            function(val) {
                if (val) {
                    $.do_ajax("/user_deal/teacher_month_money_un_confirm",{
                        "logtime": g_args.start_time,
                        "teacherid": opt_data.teacherid
                    });
                }
            });

	    
    });
    $(".opt-pay").on("click",function(){
        var opt_data=$(this).get_opt_data();
	    BootstrapDialog.confirm(
            "["+opt_data.realname+"]已支付["+ opt_data.real_money_all_count +"]元?", 
            function(val) {
                if (val) {
                    $.do_ajax("/user_deal/teacher_month_money_pay_flag",{
                        "logtime": g_args.start_time,
                        "teacherid": opt_data.teacherid,
                        "pay_flag": 1
                    });
                }
            });
    });

    $(".opt-un_pay").on("click",function(){
        var opt_data=$(this).get_opt_data();
	    BootstrapDialog.confirm(
            "["+opt_data.realname+"]取消支付?", 
            function(val) {
                if (val) {
                    $.do_ajax("/user_deal/teacher_month_money_pay_flag",{
                        "logtime": g_args.start_time,
                        "teacherid": opt_data.teacherid,
                        "pay_flag": 0
                    });
                }
            });
    });


    
    $(".opt-confirm").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var all_money=opt_data["all_money"];
        if (all_money==undefined ) {
            alert ("请先 点击[计算].");
            return ;
        }

	    BootstrapDialog.confirm(
            "["+opt_data.realname+"]确认总金额:"+all_money+"?", 
            function(val) {
                if (val) {
                    $.do_ajax("/user_deal/update_teacher_month_money",{
                        "logtime": g_args.start_time,
                        "teacherid": opt_data.teacherid,
                        all_count:opt_data.all_count*100,
                        l1v1_count:opt_data.l1v1_lesson_count*100,
                        test_count:opt_data.test_lesson_count*100,
                        money_all_count:opt_data["all_money"]*100,
                        money_l1v1_count:opt_data["l1v1_money"]*100,
                        money_test_count:opt_data["test_money"]*100,

                    });
                }
            });
    });

    
    $(".opt-money").on("click",function(){
	    
        var opt_data=$(this).get_opt_data();
        var $tr=$(this).parent().parent().parent();

        $tr.find(".status").text("开始．．．");
        $.do_ajax("/user_manage_new/get_teacher_all_money",{
            "teacherid"    : opt_data.teacherid,
            "start_time"   :g_args.start_time,
            "end_time"    :g_args.end_time
        },function(resp){
            $tr.find(".status").text("完成");
            $tr.find(".l1v1_money").text( resp.l1v1_money );
            $tr.find(".all_money").text( resp.all_money);
            $tr.find(".all_info").text( resp.all_money);
            $tr.find(".all_lesson_price").text( resp.all_lesson_price);
            $tr.find(".test_money").text( resp.test_money);
            
            var $data_div=$tr.find(".opt-show").parent();
            $data_div.data("all_money" ,resp.all_money);
            $data_div.data("l1v1_money" ,resp.l1v1_money);
            $data_div.data("test_money" ,resp.test_money);
            $data_div.data("all_lesson_price" ,resp.all_lesson_price);
            
            if (opt_data.real_money_all_count != resp.all_money) {
                $tr.find("td").addClass("error_money");
            }
            
        });
    });

    
    $("#id_set_check_adminid").on("click",function(){
        $.admin_select_user( $("<div></div>"),"admin",  function(id){
            
            var opt_list=$(".opt-select-user" );
            var teacherid_list=[];
            opt_list.each(function(){
                var $input=$(this);
                if ($input.iCheckValue())  {
                    teacherid_list.push($input.data("teacherid")) ;
                }
            });

            $.do_ajax("/user_deal/set_teacher_check_adminid", {
                "teacherid_list" : JSON.stringify(teacherid_list),
                "check_adminid" : id
            });

        },true, {"main_type" : 3});

    });

    if (window.location.pathname =="/user_manage_new/tea_lesson_count_total_list_tea" ) {
        $("#id_has_check_adminid_flag").parent().parent().hide();
        $("#id_check_adminid").parent().parent().hide();
        $("#id_pay_flag").parent().parent().hide();
        $("#id_set_check_adminid").parent().parent().hide();
        $("#id_show_money_all").parent().parent().hide();
        $(".opt-pay").hide();
        $(".opt-un_pay").hide();
    }

});


