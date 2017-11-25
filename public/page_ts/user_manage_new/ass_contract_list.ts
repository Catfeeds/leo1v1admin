/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-ass_contract_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type_config:	$('#id_date_type_config').val(),
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val(),

            studentid:	$('#id_studentid').val(),
            assistantid:	$('#id_assistantid').val(),
		    check_money_flag:	$('#id_check_money_flag').val(),
			have_init:	$('#id_have_init').val(),
			have_master:	$('#id_have_master').val(),

            contract_type:	$('#id_contract_type').val()
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

    Enum_map.append_option_list( "contract_type", $("#id_contract_type"));
    Enum_map.append_option_list( "boolean", $("#id_check_money_flag"));
    Enum_map.append_option_list( "boolean", $("#id_have_init"));
    Enum_map.append_option_list( "boolean", $("#id_have_master"));

    $('#id_studentid').val(g_args.studentid);
    $('#id_check_money_flag').val(g_args.check_money_flag);
    $('#id_assistantid').val(g_args.assistantid);
    $('#id_contract_type').val(g_args.contract_type);
	  $('#id_check_money_flag').val(g_args.check_money_flag);
	  $('#id_have_init').val(g_args.have_init);
	  $('#id_have_master').val(g_args.have_master);


    $.admin_select_user($("#id_studentid"),"student", load_data);
    $.admin_select_user($("#id_assistantid"),"assistant", load_data

                        ,false, {
                            select_btn_config: [
                                {
                                    "label": "[未分配]",
                                    "value": 0
                                }, {
                                    "label": "[已分配]",
                                    "value": -2
                                }, {
                                    "label": "[全部]",
                                    "value": -1
                                },
                            ]
                        }

                       );

    $('.opt-change').set_input_change_event(load_data);





    //点击进入个人主页
    $('.opt-user').on('click',function(){
        var userid = $(this).parent().data("userid");
        var nick   = $(this).parent().data("stu_nick");
        //$(this).attr('href','/stu_manage?sid = '+userid+'&nick='+nick+"&"  );
        window.open('/stu_manage?sid='+ userid+"&return_url="+ encodeURIComponent(window.location.href)) ;

    });



    $(" .opt-money-check").on("click",function(){
        var orderid=$(this).get_opt_data("orderid");
        var $check_money_flag = $("<select/>");
        var $check_money_desc = $("<textarea/>");
        Enum_map.append_option_list( "check_money_flag",  $check_money_flag ,true );

        var arr=[
            ["确认状态" , $check_money_flag],
            ["说明" ,  $check_money_desc],
        ];
        show_key_value_table("财务确认", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                do_ajax("/user_deal/order_check_money",{
                    "orderid" :orderid,
                    "check_money_flag" : $check_money_flag.val(),
                    "check_money_desc" : $check_money_desc.val()
                });
            }
        });

    });


    $("#id_show_all").on("click",function(){
        //
        var url= $(".page-opt-show-all" ).attr("data");
        if (!url) {
            alert("已经是全部了!");
            return ;
        }else{
            var page_num=0xFFFFFFFF+1;
            url=url.replace(/{Page}/, page_num  );
            $(this).attr("href",url);
        }

    });



    $(".opt-init_info").on("click",function(){
        var opt_data=$(this).get_opt_data();

        window.open('/stu_manage/init_info?sid='+ opt_data.userid);
        //
    });

    $(".opt-set_ass").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $(this).admin_select_user({
            "show_select_flag" : true,
            "type"             : "assistant",
            "onChange"         : function(val){
                var id = val;
                $.do_ajax( '/stu_manage/set_assistantid',{
                    'sid'         : opt_data.userid,
                    'assistantid' : id,
                    'sys_operator': opt_data.sys_operator
                });
            }
        });
    });

    $(".opt-set_ass_master").on("click",function(){
        var opt_data=$(this).get_opt_data();
       // alert(opt_data.userid);
       // alert(opt_data.sys_operator);
        $.do_ajax("/user_deal/set_ass_master",{
            "userid" :opt_data.userid,
            "account" : opt_data.sys_operator,
        });

    });


    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var id_week_lesson_num=$("<select><option value=\"0\">0</option><option value=\"1\">1</option><option value=\"2\">2</option><option value=\"3\">3</option><option value=\"4\">4</option><option value=\"5\">5</option><option value=\"6\">6</option><option value=\"7\">7</option><option value=\"8\">8</option><option value=\"9\">9</option><option value=\"10\">10</option></select>");
        var id_except_lesson_count=$("<select><option value=\"0\">0</option><option value=\"100\">1</option><option value=\"150\">1.5</option><option value=\"200\">2</option><option value=\"250\">2.5</option><option value=\"300\">3</option><option value=\"350\">3.5</option><option value=\"400\">4</option><option value=\"450\">4.5</option><option value=\"500\">5</option></select>");

        id_except_lesson_count.val(opt_data.except_lesson_count);
        id_week_lesson_num.val(opt_data.week_lesson_num);
        var arr=[
            ["每周课次", id_week_lesson_num],
            ["每次课时", id_except_lesson_count]
        ];
        $.show_key_value_table("修改交接单每周课时", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {

                $.do_ajax( '/ss_deal/update_student_init_lesson_info',{
                    "userid"             : opt_data.userid,
                    "week_lesson_num"    : id_week_lesson_num.val(),
                    "except_lesson_count": id_except_lesson_count.val()
                });
            }
        });

    });



    $(".opt-reject_list").on("click",function(){

        var orderid    = $(this).attr('data-orderid');
        
        var html_node    = $.obj_copy_node("#id_assign_log");

        BootstrapDialog.show({
            title: "驳回信息列表",
            message: html_node,
            closable: true
        });

        $.ajax({
            type: "post",
            url: "/ss_deal/get_reject_log",
            dataType: "json",
            data: {
                'orderid': orderid,
            },
            success: function (result) {
                if (result['ret'] == 0) {
                    var data = result['data'];

                    var html_str = "";
                    $.each(data, function (i, item) {
                        var cls = "success";

                        html_str += "<tr class=\"" + cls + "\" > <td>" + item.orderid + "<td>" + item.ass_id_str + "<td>" + item.reject_info + "<td>" + item.reject_date+ "</tr>";
                    });

                    html_node.find(".data-body").html(html_str);

                }
            }
        });

    });



});
