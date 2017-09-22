/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-test_lesson_frist_call_time.d.ts" />

function load_data(){
    $.reload_self_page ( {
        order_by_str                   : g_args.order_by_str,
			  date_type_config  :	$('#id_date_type_config').val(),
			  date_type         :	$('#id_date_type').val(),
			  opt_date_type     :	$('#id_opt_date_type').val(),
			  start_time        :	$('#id_start_time').val(),
			  end_time          :	$('#id_end_time').val(),
			  seller_groupid_ex :	$('#id_seller_groupid_ex').val(),
        lesson_user_online_status :	$('#id_lesson_user_online_status').val(),

    });
}


$(function(){

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

	  Enum_map.append_option_list("set_boolean",$("#id_lesson_user_online_status"));


	  $('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);

    $("#id_seller_groupid_ex").init_seller_groupid_ex();

	  $('.opt-change').set_input_change_event(load_data);



    $('#id_lesson_user_online_status').val(g_args.lesson_user_online_status);

    if (window.location.pathname=="/tongji2/test_lesson_frist_call_time_master" || window.location.pathname=="/tongji2/test_lesson_frist_call_time_master/") {
        $("#id_seller_groupid_ex").parent().parent().hide();
    }  


    $(".opt-telphone").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen('/tq/get_list?phone=' + opt_data.phone);

    });

        $(".opt-log-list").on("click", function () {
        var lessonid     = $(this).parent().data("lessonid");
        var teacherid    = $(this).parent().data("teacherid");
        // var stu_id       = $(this).parent().data("studentid");
        var stu_id       = $(this).parent().data("userid");
        var lesson_start = $(this).parent().data("lesson_start");
        var lesson_end   = $(this).parent().data("lesson_end");
        var lesson_type  = $(this).get_opt_data("lesson_type");
        var html_node    = $.obj_copy_node("#id_lesson_log");

        $.do_ajax("/lesson_manage/get_lesson_user_list_for_login_log", {
            "lessonid": lessonid
        }, function (ret) {
            var html_str = "";
            $.each(ret.list,function () {
                var userid = this[0];
                var name = this[1];
                html_str += "<option value=\"" + userid + "\">" + name + "</option>";
            });
            html_node.find(".opt-userid").html(html_str);

        });


        html_node.find(".form-control").on("change", function () {
            var userid = html_node.find(".opt-userid").val();
            var server_type = html_node.find(".opt-server-type").val();
            load_data_ex(lessonid, userid, server_type);
        });

        BootstrapDialog.show({
            title: "进出列表",
            message: html_node,
            closable: true
        });

        var load_data_ex = function (lessonid, userid, server_type) {
            $.ajax({
                type: "post",
                url: "/supervisor/lesson_get_log",
                dataType: "json",
                data: {
                    'lessonid': lessonid,
                    "userid": userid,
                    "server_type": server_type,
                    "teacher_id": teacherid,
                    "stu_id": stu_id,
                    "lesson_start": lesson_start,
                    "lesson_end": lesson_end
                },
                success: function (result) {
                    if (result['ret'] == 0) {
                        var data = result['data'];

                        var html_str = "";
                        $.each(data, function (i, item) {
                            var cls = "warning";
                            if (item.opt_type == "login") {
                                cls = "success";
                            }
                            if (item.opt_type == "register") {
                                cls = "warning";
                            }

                            if (item.opt_type == "logout") {
                                cls = "danger";
                            }



                            var rule_str = "";
                            if (item.userid == stu_id) {
                                rule_str = "学生";
                            } else if (item.userid == teacherid) {
                                rule_str = "老师";
                            }

                            html_str += "<tr class=\"" + cls + "\" > <td>" + item.opt_time + "<td>" + rule_str + "<td>" + item.userid + "<td>" + item.server_type + "<td>" + item.opt_type + "<td>" + item.server_ip + "</tr>";
                        });

                        html_node.find(".data-body").html(html_str);

                    }
                }
            });

        };

        load_data_ex(lessonid, -1, -1);
    });

    $(".opt-edit").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var $assess  = $("<textarea rows='' cols=''></textarea>");

        $assess.val(opt_data.assess);
        var arr=[
            ["主管评价",  $assess],
        ];

        $.do_ajax("/tongji2/check_up_group_adminid",{},function(ret){
            if(ret == 1){
                $.show_key_value_table("主管评价", arr ,{
                    label: '确认',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                        $.do_ajax("/ajax_deal/test_lesson_assess_edit",{
                            "lessonid":opt_data.lessonid,
                            "assess" : $assess.val() ,
                        })
                    }
                })
            }else{
                alert('您不是主管,无权限!')
            }
        })
    });



});


