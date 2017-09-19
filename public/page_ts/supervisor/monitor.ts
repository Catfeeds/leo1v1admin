/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/supervisor-monitor.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date                :	$('#id_date').val(),
            st_application_nick :	$('#id_st_application_nick').val(),
            userid              :	$('#id_userid').val(),
            teacherid           :	$('#id_teacherid').val() ,
            run_flag            :	$('#id_run_flag').val(),
            assistantid         :	$('#id_assistantid').val(),
            test_seller_id      : $("#id_test_seller_id").val(),
        });
    }

    $('#id_date').val(g_args.date);
    $('#id_st_application_nick').val(g_args.st_application_nick);
    $('#id_userid').val(g_args.userid);
    $('#id_teacherid').val(g_args.teacherid);
    $('#id_run_flag').val(g_args.run_flag);
    $('#id_assistantid').val(g_args.assistantid);
    $("#id_test_seller_id").val(g_args.test_seller_id);

    $('.opt-change').set_input_change_event(load_data);

    $.admin_select_user($("#id_teacherid"),"teacher", load_data);

    $.admin_select_user($("#id_userid"),"student", load_data);

    $.admin_select_user($("#id_assistantid"),"assistant" ,load_data);


    $.admin_select_user(
        $('#id_test_seller_id'),
        "admin", load_data ,false, {
            "main_type": 2, //分配用户
            select_btn_config: [{
                "label": "所有非销售",
                "value":  -3
            },{
                "label": "所有销售",
                "value":  -2
            }]
        }
    );
    if(group_type == 0){
        $('#id_seller_new').attr('style','display:none');
    }
    $("#id_tongji").on("click", function () {
        $.do_ajax("/supervisor/get_tongji", {
            "date": $("#id_date").val()
        }, function (ret) {
            var arr = [
                ["进入课堂总人数", ret.join_lesson_user_count]
            ];
            $.show_key_value_table("统计数据", arr);

        });
    });

    $("#id_pre_day, #id_next_day").on("click", function () {
        var id = $(this).attr("id");
        var opt_date = $("#id_date").val();
        var day_opt_value = 1;
        if (id == "id_pre_day") {
            day_opt_value = -1;
        }
        var obj_time = new Date(opt_date).getTime() / 1000 + 86400 * day_opt_value;
        var obj_date = $.DateFormat(obj_time, "yyyy-MM-dd");
        $("#id_date").val(obj_date);
        load_data();
    });

    $('#id_date').datetimepicker({
        lang: 'ch',
        timepicker: false,
        format: 'Y-m-d',
        onChangeDateTime: function () {
            load_data();
        }
    });



    var get_strong_str = function (str) {
        if (str) {
            return "<strong>" + str + "</strong>";
        } else {
            return "<strong>0</strong>";
        }
    };

    var show_lesson_status =function  ($item,info,  user_type_class, from_type_str ) {

        var span = $item.find(".wb_"+user_type_class+"  > ."+from_type_str+"_count > span");
        span.html(get_strong_str(info.cond[user_type_class][from_type_str+'_dis']));
        if (info['lesson_status'] == 1) {
            if ( !( info.on_sheng_wang  &&  from_type_str=="webrtc" ) )  {

                if (info.cond[user_type_class ][from_type_str] == 1) {
                    span.removeClass("bg-red");
                    span.addClass("bg-green");
                } else {
                    if ( user_type_class == "tea"|| user_type_class == "stu" ) {
                        span.addClass("bg-red");
                        span.removeClass("bg-green");
                    }
                }
            }
        }else{
            span.removeClass("bg-red");
            span.removeClass("bg-green");
        }

    };

    var opt_wb_tr = $('.wb_monitor_item');
    function get_condition() {
        $.do_ajax_t(
            "/supervisor/get_lesson_conditions_js",
            {
                date:g_args.date,
                st_application_nick:g_args.st_application_nick,
                userid:g_args.userid,
                teacherid:g_args.teacherid,
                run_flag:g_args.run_flag,
                assistantid:g_args.assistantid,
                require_adminid:g_args.require_adminid
            },
            function (data) {
                if (data["reload_flag"]) {
                    $("#id_show_reload_msg").text("有更新，需要刷新");
                    //load_data();
                }
                if (data['ret'] == 0) {
                    var condition_list= data['condition_list'];

                    if (opt_wb_tr.length !=  data["lesson_count"] ) {
                        $("#id_show_reload_msg").text("有更新，需要刷新");
                            //load_data();
                    }

                    $.each(opt_wb_tr, function (i, item) {
                        var $item=$(item);
                        var $opt_div=$item.find("td:last >div");
                        var server_type_str=$opt_div.data("server_type_str");
                        var lessonid=$opt_div.data( "lessonid");

                        var info = condition_list[lessonid];
                        if (!info) {
                            load_data();
                        }
                        try {
                            info.cond = JSON.parse(info['lesson_condition']);
                        } catch (e) {
                            return;
                        }
                        if(  /声网/.test(server_type_str) ) {
                            info.on_sheng_wang=true;
                        }

                        show_lesson_status($item,info ,"tea","xmpp"  );
                        show_lesson_status($item,info ,"tea","webrtc"  );

                        show_lesson_status($item,info ,"stu","xmpp"  );
                        show_lesson_status($item,info ,"stu","webrtc"  );

                        show_lesson_status($item,info ,"stu","xmpp"  );
                        show_lesson_status($item,info ,"stu","webrtc"  );

                        show_lesson_status($item,info ,"par","xmpp"  );
                        show_lesson_status($item,info ,"par","webrtc"  );

                        show_lesson_status($item,info ,"ad","xmpp"  );
                        show_lesson_status($item,info ,"ad","webrtc"  );
                    });
                }
            });
    }
    if(group_type == 1){//组长&主管
        setInterval(get_condition, 300000);
    }else{
        get_condition();
        setInterval(get_condition, 3000);
    }

    /*
    //其它参数变化, 服务器地址,语音通道,时间更改,
    var ws = $.websocket("ws://" + window.location.hostname + ":9501/", {
        events: {
            "reload_page": function (e) {
                //
                window.location.reload();
            }
        }
        , open: function () {
            ws.send("reload_monitor_page_bind");
        }
    });
    */

    $.each($(".opt-stu-info"), function (i, item) {

        var $opt_div    = $(this).parent(). parent()  .find("td:last >div");
        var lesson_type = $opt_div.data("lesson_type");
        var lessonid    = $opt_div.data("lessonid");
        var courseid    = $opt_div.data("courseid");
        if (lesson_type >= 1000) {
            var link = $("<a href=\"javascript:;\"  >学生列表</a>");
            link.on("click", function () {
                link.admin_select_dlg_ajax({
                    "opt_type": "list", // or "list"
                    "url": "/small_class/get_small_class_user_list",
                    //其他参数
                    "args_ex": {
                        "courseid": courseid,
                        "lesson_type": lesson_type,
                        "lessonid": lessonid
                    },

                    /*
                     select_primary_field : "id",
                     select_display       : "nick",
                     select_no_select_value  :  -1  , // 没有选择是，设置的值
                     select_no_select_title  :  "[全部]"  , // "未设置"
                     */

                    //字段列表
                    'field_list': [
                        {
                            title: "userid",
                            width: 50,
                            field_name: "userid"
                        }, {
                            title: "昵称",
                            field_name: "student_nick",
                            //width :50,
                            render: function (val, item) {
                                return "<a href = \"/stu_manage?sid=" + item["userid"] + "\" target=_blank >" + val + " </a>";
                            }
                        }, {
                            "field_name": "user_agent_short",
                            "title": "客户端版本"
                        }, {
                            "field_name": "user_login_time",
                            "title": "学生登陆次数"
                        }],
                    //查询列表
                    filter_list: [
                        [
                            {
                                size_class: "col-md-8",
                                title: "userid,姓名",
                                'arg_name': "user_name",
                                type: "input"
                            }

                        ]
                    ],

                    //"auto_close"       : true,
                    //选择
                    "onChange": null,
                    //加载数据后，其它的设置
                    "onLoadData": function (dlg, result) {
                        var con_stu_all = result.data.con_stu_all;
                        var con_stu_login = result.data.con_stu_login;
                        dlg.setTitle("学生列表(学生总数：" + con_stu_all + "到达率:" + con_stu_login + "/" + con_stu_all + ") ");
                    }
                });
            });
            $(this).html(link);
        }
    });


    $.each($(".opt-change-time"), function (i, item) {
        $(item).admin_set_lesson_time({
            "lessonid": $(item).get_opt_data("lessonid"),
            "reset_lesson_count":0
        });
    });


    $(".opt-qr-pad-at-time").on("click", function () {
        var lessonid = $(this).get_opt_data("lessonid");
        var url = $(this).data("type");
        var title = $(this).attr("title");
        //得到
        $.do_ajax("/tea_manage/get_lesson_xmpp_audio", {
            "lessonid": lessonid
        }, function (result) {

            var data = result.data;

            var args = "title=lessonid:" + lessonid + "&beginTime=" + data.lesson_start + "&endTime=" + data.lesson_end + "&roomId=" + data.roomid + "&xmpp=" + data.xmpp + "&webrtc=" + data.webrtc + "&ownerId=" + data.teacherid + "&type=" + data.type + "&audioService=" + data.audioService;

            var args_64 = $.base64.encode(args);

            console.log(args);

            var text = encodeURIComponent(url + args_64);

            var dlg = BootstrapDialog.show({
                title: title,
                message: "<div style = \"text-align:center\"><img width=\"350px\" src=\"/common/get_qr?text=" + text + "\"></img>",
                closable: true
            });
            dlg.getModalDialog().css("width", "600px");

        });

    });

    $('.xmpp_count').tooltip({
        "title":"笔画"
    });
    $('.audio_count').tooltip({
        "title":function(){
            return "声音" ;
        }
    });

    $(".opt-set-server").on("click", function () {

        var opt_data=$(this).get_opt_data();
        var $server=$ ("<select >  <option value=\"h_01\">杭州</option> <option value=\"q_01\">青岛</option>   <option value=\"b_01\">北京</option> "
                       + "<option value=\"a_01\" >青岛_27</option> "
                       +" </select>");
        var arr=[
            ["服务器", $server]
        ];
        $server.val(opt_data.current_server);
        $.show_key_value_table("选择服务器", arr, {
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax( '/ajax_deal2/set_lesson_current_server',{
                    "courseid" : opt_data.courseid,
                    "current_server" :  $server.val()
                });
            }
        });

    });

    $(".opt-log-list").on("click", function () {
        var lessonid     = $(this).parent().data("lessonid");
        var teacherid    = $(this).parent().data("teacherid");
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

    $(".opt-lesson").each(function () {
        var lesson_type = $(this).get_opt_data("lesson_type");
        var lessonid = $(this).get_opt_data("lessonid");

        $(this).attr("href", "/tea_manage/lesson_list?lessonid=" + lessonid);
        //alert(lesson_type);

    });

    $(".opt-user-need-rejoin").on("click", function () {
        var stu_id = $(this).get_opt_data("userid");
        var teacherid = $(this).get_opt_data("teacherid");
        var ip = $(this).get_opt_data("ip");
        var room_id = $(this).get_opt_data("room_id");

        var id_user_type = $("<select   >"
            + " <option value=" + teacherid + " >  老师 "
            + " <option value=" + stu_id + " >  学生 "
            + " </select> ");
        var id_user_id = $("<input>");

        id_user_id.val(id_user_type.val());
        id_user_type.on("change", function () {
            id_user_id.val(id_user_type.val());
        });


        var arr = [
            ["类型", id_user_type],
            ["uid", id_user_id]
        ];

        console.log(ip);
        console.log(room_id);
        console.log(id_user_id.val());

        $.show_key_value_table("通知用户从所有xmpp服务器重进", arr, {
            label: '确认',
            cssClass: 'btn-warning',
            action: function (dialog) {
                $.do_ajax("/common/notity_rejoin_server", {
                    "server_ip": ip,
                    "roomid": room_id,
                    "userid": id_user_id.val()
                }, function () {
                    alert("让userid:" + id_user_id.val() + " 所有xmpp服务器重进，完毕");
                    //window.location.reload();
                });
            }
        });

    });

    $(".opt-user-send-xmpp-message").on("click", function () {
        var stu_id = $(this).get_opt_data("studentid");
        var teacherid = $(this).get_opt_data("teacherid");
        var ip = $(this).get_opt_data("ip");
        var room_id = $(this).get_opt_data("room_id");

        var id_user_type = $("<select   >"
                             + " <option value=" + teacherid + " >  老师 "
                             + " <option value=" + stu_id + " >  学生 "
                             + " </select> ");
        var id_user_id = $("<input>");
        var id_message = $("<textarea>");

        id_user_id.val(id_user_type.val());
        id_user_type.on("change", function () {
            id_user_id.val(id_user_type.val());
        });


        var arr = [
            ["类型", id_user_type],
            ["uid", id_user_id],
            ["弹幕消息", id_message]
        ];

        console.log(ip);
        console.log(room_id);
        console.log(id_user_id.val());

        $.show_key_value_table("弹幕", arr, {
            label: '确认',
            cssClass: 'btn-warning',
            action: function (dialog) {
                $.do_ajax("/common/send_xmpp_message", {
                    "server_ip": ip,
                    "roomid": room_id,
                    "userid": id_user_id.val(),
                    "message": id_message.val()
                }, function () {
                    alert("发送完毕");
                    dialog.close();
                });
            }
        });



    });

    $(".opt-add-error").on("click", function () {
        var opt_data     = $(this).get_opt_data();
        var lessonid     = opt_data.lessonid;
        var courseid     = opt_data.courseid;
        var lesson_type  = opt_data.lesson_type;
        var lesson_start = opt_data.lesson_start;
        var lesson_end   = opt_data.lesson_end;
        var teacherid    = opt_data.teacherid;
        var tea_nick     = opt_data.teacher_nick;
        var stu_id       = opt_data.userid;
        var stu_nick     = opt_data.student_nick;

        var html_node = $('<div></div>').html($.dlg_get_html_by_class('dlg_add_error_info'));
        BootstrapDialog.show({
            title: "选择错误类型",
            message: html_node,
            buttons: [{
                label: '确认',
                cssClass: 'btn-warning',
                action: function (dialog) {
                    var error_info = "";
                    $("[name='error_info']:checked").each(function () {
                        error_info += $(this).val() + ",";
                    });
                    var error_info_other = html_node.find(".add_error_info_other").val();
                    if(error_info_other==''){
                        BootstrapDialog.alert("请填写异常原因！");
                        return ;
                    }
                    $.do_ajax("/lesson_manage/add_error_lessonid", {
                        "lessonid"         : lessonid,
                        "courseid"         : courseid,
                        "lesson_type"      : lesson_type,
                        "lesson_start"     : lesson_start,
                        "lesson_end"       : lesson_end,
                        "teacherid"        : teacherid,
                        "tea_nick"         : tea_nick,
                        "stu_id"           : stu_id,
                        "stu_nick"         : stu_nick,
                        "error_info"       : error_info,
                        "error_info_other" : error_info_other
                    }, function (result) {
                        if (result.ret < 0) {
                            alert(result.info);
                        } else {
                            var url = "/lesson_manage/error_info?lessonid=" + lessonid;
                            //window.location.href = url;
                            window.open(url,"_blank");
                        }
                    });
                }
            }, {
                    label: '返回',
                    action: function (dialog) {
                        dialog.close();
                    }
                }]
        });
    });


    $(".opt-lesson-info").on("click",function(){
        var opt_data=$(this).get_opt_data();
      $.do_ajax("/user_manage_new/get_lesson_info_for_monitor",{
            "lessonid" : opt_data.lessonid
        },function(ret){
            var data=ret.data;
            var $id_btn_student_phone=$("<button class=\"btn btn-warning \">通过 andirod 手机拨打 </button>");
            var $id_btn_teacher_phone=$("<button class=\"btn btn-warning\">通过 andirod 手机拨打 </button>");
            var do_phone=function(phone ) {
                //同步...
                var lesson_info = JSON.stringify({
                    cmd: "noti_phone",
                    phone: phone
                });
                $.ajax({
                    type: "get",
                    url: "http://admin.yb1v1.com:9501/pc_phone_noti_user_lesson_info",
                    dataType: "text",
                    data: {
                        'username': g_account,
                        "lesson_info": lesson_info
                    }
                });
            };

            $id_btn_student_phone.on("click",function(){
                do_phone( data.stu_phone);
            });
            $id_btn_teacher_phone.on("click",function(){
                do_phone( data.tea_phone);
            });

            var arr = [
                ["lessonid", data.lessonid],
                ["courseid", data.courseid],
                ["room_id", opt_data.room_id ],
                ["科目", data.subject_str ],
                ["年级", data.grade_str ],
                ["老师id", opt_data.teacherid],
                ["老师", data.tea_nick],
                ["老师电话", data.tea_phone],
                ["拨打老师电话", $id_btn_teacher_phone],
                ["老师版本", data.tea_user_agent],
                ["老师网络", data.tea_situation],
                ["学生id", opt_data.userid],
                ["学生", data.stu_nick],
                ["学生电话", data.stu_phone],
                ["拨打学生电话", $id_btn_student_phone ],
                ["学生版本", data.stu_user_agent],
                ["学生网络", data.stu_situation],

            ];
            $.show_key_value_table("统计数据", arr);

        });
    });


    $('.opt-set-server-type').on('click', function () {
        var lessonid = $(this).get_opt_data("lessonid");
        $.do_ajax('/lesson_manage/get_server_type', { 'lessonid': lessonid }, function (result) {
            if (result['ret'] != 0) {
                BootstrapDialog.alert('获取当次课音频服务器失败');
                return;
            }

            var html_node = $('<div></div>').html($.dlg_get_html_by_class('opt-set-audio-server-type'));
            html_node.find(".lessonid").val(result['lessonid']);
            html_node.find(".opt-audio-server-type").val(result['server_type']);
            BootstrapDialog.show({
                title: "设置音频服务",
                message: html_node,
                buttons: [{
                    label: '返回',
                    action: function (dialog) {
                        dialog.close();
                    }
                }, {
                    label: '确认',
                    cssClass: 'btn-warning',
                    action: function (dialog) {
                        var server_type = html_node.find('.opt-audio-server-type').val();
                        $.do_ajax('/lesson_manage/set_server_type', {
                            'lessonid': lessonid, 'server_type': server_type
                        }, function (result) {
                            BootstrapDialog.alert(result['info']);
                        });
                        dialog.close();
                    }
                }]
            });
        });
    });

    $(".opt-play").on("click", function () {
        var opt_data=$(this).get_opt_data();

        var now = (new Date()).getTime() / 1000;
        var lesson_start = opt_data.lesson_start;
        var lesson_end = opt_data.lesson_end;

        if (lesson_start - 600 > now) {
            alert("还没开始");
            return;
        }
        if (lesson_end + 600 < now) {
            alert("已经结束");
            return;
        }

        var html_node = $(" <div  style=\"width:1024px\"  > <div id=\"drawing_list\" style=\"width:100%\"  > </div> </div> ");
        BootstrapDialog.show({
            title: '在线听课' + "[" +  opt_data.student_nick + "]====[" + opt_data.teacher_nick + "]",

            message: html_node,
            closable: true,
            onhide: function (dialogRef) {
                $("#id_frame").attr("src", "");
            }
        });


        var lessonid = $(this).parent().data('lessonid');
        var servers;
        var bridge_id = $(this).parent().data('room_id');
        var courseid = $(this).parent().data('courseid');
        var server_type = $(this).parent().data('server_type');
        var lesson_type = $(this).parent().data('lesson_type');
        if (server_type == 0) {
            if (lesson_type < 1000) {
                server_type = 1;
            } else {
                server_type = 2;
            }
        }

        $.ajax({
            type: "post",
            url: "/supervisor/get_servers",
            dataType: "json",
            data: { 'courseid': courseid },
            success: function (result) {
                if (result['ret'] == 0) {
                    //XMPP
                    servers = result['data'];
                    //servers['ip']="192.168.31.246";
                    //servers['ip']="120.26.58.183";

                    var BOSH_SERVICE = 'http://' + servers['ip'] + ':5280/http-bind';
                    var connection = new Strophe.Connection(BOSH_SERVICE);
                    var passwd = 'millions';
                    var server = servers['ip'];
                    var jid = 'millions@' + server;

                    connection.connect(jid, passwd,
                        get_onConnect(server, bridge_id, connection,
                            html_node.find("#drawing_list")
                        ));


                    //同步...
                    var lesson_info = JSON.stringify({
                        cmd: "noti_lesson_info",
                        server_type: server_type,
                        bridge_id: bridge_id,
                        ip: servers["ip"],
                        port: servers["webrtc_port"]
                    });
                    $.ajax({
                        type: "get",
                        url: "http://admin.yb1v1.com:9501/pc_phone_noti_user_lesson_info",
                        dataType: "text",
                        data: {
                            'username': g_account,
                            "lesson_info": lesson_info
                        }
                    });



                    if (!$.check_in_phone() && server_type == 1) {
                        //WEBRTC
                        var url = "/supervisor/video?bridge_id=" + bridge_id + "&bridge_pin=1234&user_id=supervisor&server=" + servers['ip'] + "&port=" + servers['webrtc_port'];
                        $("#id_frame").attr("src", url);
                    }

                } else {
                    alert(result['info']);
                }
            }
        });

    });

    console.log(" start ...." );
    var get_onConnect = function (server, bridge_id, connection, obj_drawing_list) {
        var whiteboard = get_new_whiteboard(obj_drawing_list, $.check_in_phone() ? 320 : 700,
            $.check_in_phone() ? 270 : 520);
        return function (status) {
            console.log("===== 1111: 11" );

            if (status == Strophe.Status.CONNECTING) {
                console.log('Strophe is connecting.');
            } else if (status == Strophe.Status.CONNFAIL) {
                console.log('Strophe failed to connect.');
            } else if (status == Strophe.Status.DISCONNECTING) {
                console.log('Strophe is disconnecting.');
            } else if (status == Strophe.Status.DISCONNECTED) {
                console.log('Strophe is disconnected.');
            } else if (status == Strophe.Status.CONNECTED) {
                console.log('Strophe is connected.');
                var conference = bridge_id + "@conference." + server;
                connection.muc.join(conference,
                    "admin_user",
                    function (msg, room) {
                        var txt = $(msg).text();
                        if (txt[0] == "<") {
                            var svg = $(txt);
                            var item_data = get_item_data(svg);
                            whiteboard.play(item_data);
                        }
                        return true;
                    },
                    function (pres) {
                        return true;
                    });
            }
        };
    };

    var get_item_data = function (svg) {
        var item_data = {};
        //item_data.pageid=Math.floor(svg.attr("y").baseVal.value/768)+1;
        item_data.pageid = Math.floor(svg.attr("y") / 768) + 1;
        var opt_item = svg.children(":first");
        item_data.opt_type = $(opt_item)[0].tagName;
        var opt_args = {};

        var stroke_info = opt_item.attr("stroke");
        if (typeof stroke_info != "undefined" && stroke_info.indexOf("#") == -1) {
            stroke_info = "#" + stroke_info;
        }

        switch (item_data.opt_type) {
            case "path":
                opt_args = {
                    fill: "none",
                    stroke: stroke_info,
                    "stroke-width": opt_item.attr("stroke-width"),
                    "stroke-dasharray": opt_item.attr("stroke-dasharray"),
                    "d": opt_item.attr("d")
                };

                break;
            case "image":
                opt_args = {
                    x: opt_item.attr("x")
                    , y: opt_item.attr("y")
                    , "width": opt_item.attr("width")
                    , "height": opt_item.attr("height")
                    , "url": opt_item.text()
                };
                break;
            case "eraser":
                opt_args = {
                    fill: "none"
                    , stroke: stroke_info
                    , "stroke-width": opt_item.attr("stroke-width")
                    , "stroke-dasharray": opt_item.attr("stroke-dasharray")
                    , "d": opt_item.attr("d")
                    , "stroke-color": "FFFFFF"
                };

                break;
            default:
                console.log("ERROR:" + item_data.opt_type);
                break;
        }

        item_data.opt_args = opt_args;
        return item_data;
    };

    if(window.location.pathname=="/supervisor/monitor_seller") {
        $("#id_st_application_nick").parent().parent().hide();
        $("#id_assistantid").parent().parent().hide();
        if(self_groupid != 0 && is_group_leader_flag == 0){
            $("#id_teacherid").parent().parent().hide();
            $("#id_userid").parent().parent().hide();
        }

    }

    if(window.location.pathname=="/supervisor/monitor_ass") {
        $("#id_st_application_nick").parent().parent().hide();
        $("#id_assistantid").parent().parent().hide();
    }



    //获取url参数信息
    var getQueryString = function (name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) {
            return unescape(r[2]);
        } else {
            return null;
        }
    }

    var opt_data = $(this).get_opt_data();

    var date                = getQueryString('date');
    var st_application_nick = getQueryString('st_application_nick');
    var userid              = getQueryString('userid');
    var teacherid           = getQueryString('teacherid');
    var run_flag            = getQueryString('run_flag');
    var assistantid         = getQueryString('assistantid');


    //点击课程汇总页面
  $('.opt-lesson-all').on('click',function(){
      var opt_data=$(this).get_opt_data();
      var js_pot = JSON.stringify(opt_data);
      window.open('/supervisor/lesson_all_info?lessonid='+opt_data.lessonid);
  });



});
