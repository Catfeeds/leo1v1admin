/// <reference path="../typings/tsd.d.ts" />
; (function ($) {
    var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
        a256 = '',
        r64 = [256],
        r256 = [256],
        i = 0;
    var UTF8 = {
        /**
         * Encode multi-byte Unicode string into utf-8 multiple single-byte characters
         * (BMP / basic multilingual plane only)
         *
         * Chars in range U+0080 - U+07FF are encoded in 2 chars, U+0800 - U+FFFF in 3 chars
         *
         * @param {String} strUni Unicode string to be encoded as UTF-8
         * @returns {String} encoded string
         */
        encode: function (strUni) {
            // use regular expressions & String.replace callback function for better efficiency
            // than procedural approaches
            var strUtf = strUni.replace(/[\u0080-\u07ff]/g, // U+0080 - U+07FF => 2 bytes 110yyyyy, 10zzzzzz
                function (c) {
                    var cc = c.charCodeAt(0);
                    return String.fromCharCode(0xc0 | cc >> 6, 0x80 | cc & 0x3f);
                })
                .replace(/[\u0800-\uffff]/g, // U+0800 - U+FFFF = > 3 bytes 1110xxxx, 10yyyyyy, 10zzzzzz
                function (c) {
                    var cc = c.charCodeAt(0);
                    return String.fromCharCode(0xe0 | cc >> 12, 0x80 | cc >> 6 & 0x3F, 0x80 | cc & 0x3f);
                });
            return strUtf;
        },
        /**
         * Decode utf-8 encoded string back into multi-byte Unicode characters
         *
         * @param {String} strUtf UTF-8 string to be decoded back to Unicode
         * @returns {String} decoded string
         */
        decode: function (strUtf) {
            // note    : decode 3-byte chars first as decoded 2-byte strings could appear to be 3-byte char!
            var strUni = strUtf.replace(/[\u00e0-\u00ef][\u0080-\u00bf][\u0080-\u00bf]/g, // 3-byte chars
                function (c) { // (note parentheses for precence)
                    var cc = ((c.charCodeAt(0) & 0x0f) << 12) | ((c.charCodeAt(1) & 0x3f) << 6) | (c.charCodeAt(2) & 0x3f);
                    return String.fromCharCode(cc);
                })
                .replace(/[\u00c0-\u00df][\u0080-\u00bf]/g, // 2-byte chars
                function (c) { // (note parentheses for precence)
                    var cc = (c.charCodeAt(0) & 0x1f) << 6 | c.charCodeAt(1) & 0x3f;
                    return String.fromCharCode(cc);
                });
            return strUni;
        }
    };
    while (i < 256) {
        var c = String.fromCharCode(i);
        a256 += c;
        r256[i] = i;
        r64[i] = b64.indexOf(c);
        ++i;
    }
    function code(s, discard, alpha, beta, w1, w2) {
        s = String(s);
        var buffer = 0,
            i = 0,
            length = s.length,
            result = '',
            bitsInBuffer = 0;
        while (i < length) {
            var c = s.charCodeAt(i);
            c = c < 256 ? alpha[c] : -1;
            buffer = (buffer << w1) + c;
            bitsInBuffer += w1;
            while (bitsInBuffer >= w2) {
                bitsInBuffer -= w2;
                var tmp = buffer >> bitsInBuffer;
                result += beta.charAt(tmp);
                buffer ^= tmp << bitsInBuffer;
            }
            ++i;
        }
        if (!discard && bitsInBuffer > 0) result += beta.charAt(buffer << (w2 - bitsInBuffer));
        return result;
    }
    var Plugin = $.base64 = function (dir, input, encode) {
        return input ? Plugin[dir](input, encode) : dir ? null : this;
    };
    Plugin.btoa = Plugin.encode = function (plain, utf8encode) {
        plain = Plugin.raw === false || Plugin.utf8encode || utf8encode ? UTF8.encode(plain) : plain;
        plain = code(plain, false, r256, b64, 8, 6);
        return plain + '===='.slice((plain.length % 4) || 4);
    };
    Plugin.atob = Plugin.decode = function (coded, utf8decode) {
        coded = String(coded).split('=');
        var i = coded.length;
        do {
            --i;
            coded[i] = code(coded[i], true, r64, a256, 6, 8);
        } while (i > 0);
        coded = coded.join('');
        return Plugin.raw === false || Plugin.utf8decode || utf8decode ? UTF8.decode(coded) : coded;
    };
} (jQuery));


$(function () {
    Enum_map.append_checkbox_list("lesson_error", $(".add_error_info"), "error_info");

    $("#id_tongji").on("click", function () {
        do_ajax("/supervisor/get_tongji", {
            "date": $("#id_date").val()
        }, function (ret) {
            var arr = [
                ["进入课堂总人数", ret.join_lesson_user_count]
            ];
            show_key_value_table("统计数据", arr);
        });
    });

    if (check_in_phone()) {
        $("#id_lesson_state_title").attr("colspan", 3);
    }

    $.each($(".opt-stu-info"), function () {
        $(this).attr("href", '/stu_manage?sid=' + $(this).data("stu_id") + "&return_url=" + encodeURIComponent(window.location.href));
        return true;
    });


    function loadData(nick, date) {
        var url = "/supervisor/monitor?nick=" + nick + "&date=" + date;
        window.location.href = url;
    }
    $("#id_pre_day, #id_next_day").on("click", function () {
        var id = $(this).attr("id");
        var opt_date = $("#id_date").val();
        var day_opt_value = 1;
        if (id == "id_pre_day") {
            day_opt_value = -1;
        }
        var obj_time = new Date(opt_date).getTime() / 1000 + 86400 * day_opt_value;
        var obj_date = DateFormat(obj_time, "yyyy-MM-dd");

        loadData($("#id_search_nick").val(), obj_date);
    });

    //时间控件
    $('#id_date').datetimepicker({
        lang: 'ch',
        timepicker: false,
        format: 'Y-m-d',
        onChangeDateTime: function () {
            loadData(
                $("#id_search_nick").val(),
                $("#id_date").val()
            );
        }
    });
    //时间控件-over

    var date = g_date;
    var nick = g_nick;

    $("#id_date").val(date);
    $("#id_search_nick").val(nick),
        $("#id_search").on("click", function () {
            loadData(
                $("#id_search_nick").val(),
                $("#id_date").val()
            );
        });

    var get_strong_str = function (str) {
        if (str) {
            return "<strong>" + str + "</strong>";
        } else {
            return "<strong>0</strong>";
        }
    };

    function get_condition() {
        $.ajax({
            type: "post",
            url: "/supervisor/get_lesson_conditions",
            dataType: "json",
            data: { "date": g_date, "nick": g_nick, "page_num": g_page_num },
            success: function (data) {
                if (data['ret'] == 0) {
                    var opt_wb_tr = $('.wb_monitor_item');
                    reload_page();
                    $.each(opt_wb_tr, function (i, item) {
                        var info = data['condition_list'][i];
                        try {
                            var cond = JSON.parse(info['lesson_condition']);
                        } catch (e) {
                            return;
                        }
                        //wb_tea
                        var wb_tea_span = $(item).find(".wb_tea ");
                        $(wb_tea_span).html(get_strong_str(cond['tea']['xmpp_dis']));

                        //wb_stu
                        var wb_stu_span = $(item).find(".wb_stu ");
                        $(wb_stu_span).html(get_strong_str(cond['stu']['xmpp_dis']));

                        //wb_par
                        var wb_par_span = $(item).find(".wb_par ");
                        $(wb_par_span).html(get_strong_str(cond['par']['xmpp_dis']));

                        //wb_ad
                        var wb_ad_span = $(item).find(".wb_ad ");
                        $(wb_ad_span).html(get_strong_str(cond['ad']['xmpp_dis']));

                        if (info['lesson_status'] == 1) {
                            //wb_stu
                            if (cond['stu']['xmpp'] == 1) {
                                $(wb_stu_span).removeClass("bg-red");
                                $(wb_stu_span).addClass("bg-green");
                            } else {
                                $(wb_stu_span).addClass("bg-red");
                                $(wb_stu_span).removeClass("bg-green");
                            }
                            //wb_tea
                            if (cond['tea']['xmpp'] == 1) {
                                $(wb_tea_span).removeClass("bg-red");
                                $(wb_tea_span).addClass("bg-green");
                            } else {
                                $(wb_tea_span).addClass("bg-red");
                                $(wb_tea_span).removeClass("bg-green");
                            }
                            //wb_par
                            if (cond['par']['xmpp'] == 1) {
                                $(wb_par_span).addClass("bg-green");
                            } else {
                                $(wb_par_span).removeClass("bg-green");
                            }

                            //wb_ad
                            if (cond['ad']['xmpp'] == 1) {
                                $(wb_ad_span).addClass("bg-green");
                            } else {
                                $(wb_ad_span).removeClass("bg-green");
                            }

                        } else if (info['lesson_status'] == 2) {
                            //wb_stu
                            $(wb_stu_span).removeClass("bg-red");
                            $(wb_stu_span).removeClass("bg-green");
                            //wb_tea
                            $(wb_tea_span).removeClass("bg-red");
                            $(wb_tea_span).removeClass("bg-green");
                            //wb_par
                            $(wb_par_span).removeClass("bg-green");
                            //wb_ad
                            $(wb_ad_span).removeClass("bg-green");
                        }

                    });

                    var opt_audio_tr = $(".audio_monitor_item");
                    $.each(opt_audio_tr, function (i, item) {
                        var info = data['condition_list'][i];
                        try {
                            var cond = JSON.parse(info['lesson_condition']);
                        } catch (e) {
                            return;
                        }
                        //audio_tea
                        var audio_tea_span = $(item).find(".audio_tea ");
                        $(audio_tea_span).html(get_strong_str(cond['tea']['webrtc_dis']));

                        //audio_stu
                        var audio_stu_span = $(item).find(".audio_stu ");
                        $(audio_stu_span).html(get_strong_str(cond['stu']['webrtc_dis']));

                        //audio_par
                        var audio_par_span = $(item).find(".audio_par ");
                        $(audio_par_span).html(get_strong_str(cond['par']['webrtc_dis']));

                        //audio_ad
                        var audio_ad_span = $(item).find(".audio_ad ");
                        $(audio_ad_span).html(get_strong_str(cond['ad']['webrtc_dis']));

                        if (info['lesson_status'] == 1) {
                            //audio_stu
                            if (cond['stu']['webrtc'] == 1) {
                                $(audio_stu_span).removeClass("bg-red");
                                $(audio_stu_span).addClass("bg-green");
                            } else {
                                $(audio_stu_span).addClass("bg-red");
                                $(audio_stu_span).removeClass("bg-green");
                            }
                            //audio_tea
                            if (cond['tea']['webrtc'] == 1) {
                                $(audio_tea_span).removeClass("bg-red");
                                $(audio_tea_span).addClass("bg-green");
                            } else {
                                $(audio_tea_span).addClass("bg-red");
                                $(audio_tea_span).removeClass("bg-green");
                            }
                            //audio_par
                            if (cond['par']['webrtc'] == 1) {
                                $(audio_par_span).addClass("bg-green");
                            } else {
                                $(audio_par_span).removeClass("bg-green");
                            }

                            //audio_ad
                            if (cond['ad']['webrtc'] == 1) {
                                $(audio_ad_span).addClass("bg-green");
                            } else {
                                $(audio_ad_span).removeClass("bg-green");
                            }
                        } else if (info['lesson_status'] == 2) {
                            //audio_stu
                            $(audio_stu_span).removeClass("bg-red");
                            $(audio_stu_span).removeClass("bg-green");
                            //audio_tea
                            $(audio_tea_span).removeClass("bg-red");
                            $(audio_tea_span).removeClass("bg-green");
                            //audio_par
                            $(audio_par_span).removeClass("bg-green");
                            //audio_ad
                            $(audio_ad_span).removeClass("bg-green");
                        }
                    });
                }
                //判断是否需要重载页面
                function reload_page() {
                    var cond_arr = data['condition_list'];
                    if (opt_wb_tr.length != cond_arr.length) {
                        window.location.reload();
                    }
                    for (var idx = 0; idx < cond_arr.length; idx++) {
                        if (cond_arr[idx].lessonid != $(opt_wb_tr[idx]).data("lessonid")) {
                            window.location.reload();
                        }
                    }
                }
            }
        });
    }
    get_condition();

    $(".opt-qr").on("click", function () {
        var text = $(this).parent().data("ip") + "H" + $(this).parent().data("port") + "H" + $(this).parent().data("room_id");
        var dlg = BootstrapDialog.show({
            title: "手机监听二维码",
            message: "<div style=\"text-align:center\"><img src=\"/common/get_qr?text=" + text + "\"></img>",
            closable: true
            , closeByBackdrop: false
        });
        dlg.getModalDialog().css("width", "300px");
    });

    //log
    $(".opt-log-list").on("click", function () {
        var lessonid = $(this).parent().data("lessonid");
        var teacherid = $(this).parent().data("teacherid");
        var stu_id = $(this).parent().data("stu_id");
        var lesson_start = $(this).parent().data("lesson_start");
        var lesson_end = $(this).parent().data("lesson_end");
        var lesson_type = $(this).get_opt_data("lesson_type");

        var html_node = obj_copy_node("#id_lesson_log");

        do_ajax("/lesson_manage/get_lesson_user_list_for_login_log", {
            "lessonid": lessonid
        }, function (ret) {
            var html_str = "";
            $.each(ret.list, function () {
                var userid = this[0];
                var name = this[1];
                html_str += "<option value=\"" + userid + "\">" + name + "</option>";
            });
            html_node.find(".opt-userid").html(html_str);

        });


        html_node.find(".form-control").on("change", function () {

            var userid = html_node.find(".opt-userid").val();
            var server_type = html_node.find(".opt-server-type").val();
            load_data(lessonid, userid, server_type);


        });

        BootstrapDialog.show({
            title: "进出列表",
            message: html_node,
            closable: true
        });

        var load_data = function (lessonid, userid, server_type) {

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

        load_data(lessonid, -1, -1);
        //load_data(0,0,0);
    });

    $("#id_enter_student_room_qr").on("click", function () {
        var text = "123.57.153.80:5061:s_" + DateFormat(new Date().getTime() / 1000, "yyyy_MM_dd");
        var dlg = BootstrapDialog.show({
            title: "手机监听二维码",
            message: "<div style=\"text-align:center\"><img src=\"/common/get_qr?text=" + text + "\"></img>",
            closable: true
        });
        dlg.getModalDialog().css("width", "300px");
    });

    $('#id_enter_student_room_faq').on('click', function () {

        var html_node = $('<div></div>').html(dlg_get_html_by_class('dlg_show_faq'));
        BootstrapDialog.show({
            title: "FAQ",
            message: html_node,
            buttons: [{
                label: '返回',
                action: function (dialog) {
                    dialog.close();
                }
            }]
        });

    });

    $("#id_enter_student_room").on("click", function () {
        var html_node = $(" <div  style=\"width:1024px\"  > <div id=\"drawing_list\" style=\"width:100%\"  > </div> </div> ");
        BootstrapDialog.show({
            title: '在线听课-学生试音室',
            message: html_node,
            closable: true,
            onhide: function (dialogRef) {
                $("#id_frame").attr("src", "");
            }
        });


        //XMPP
        var servers = {
            "ip": "123.57.153.80",
            "webrtc_port": "20061"
        };

        var BOSH_SERVICE = 'http://' + servers['ip'] + ':5280/http-bind';
        var connection = new Strophe.Connection(BOSH_SERVICE);

        var passwd = 'millions';
        var server = servers['ip'];
        var jid = 'millions@' + server;
        var bridge_id = "s_" + DateFormat(new Date().getTime() / 1000, "yyyy_MM_dd");

        connection.connect(jid, passwd,
            get_onConnect(server, bridge_id, connection,
                html_node.find("#drawing_list")
            ));

        if (!check_in_phone()) {
            //WEBRTC
            var url = "/supervisor/video?bridge_id=" + bridge_id + "&bridge_pin=1234&user_id=supervisor&server=" + servers['ip'] + "&port=" + servers['webrtc_port'];
            $("#id_frame").attr("src", url);
        }
    });


    //播放
    $(".opt-play").on("click", function () {

        var now = (new Date()).getTime() / 1000;
        var lesson_start = $(this).parent().data("lesson_start");
        var lesson_end = $(this).parent().data("lesson_end");

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
            title: '在线听课' +
            "[" + $(this).closest("tr").find(".td-info-stu").text() + "]====[" +
            $(this).closest("tr").find(".td-info-tea").text() + "]",

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



                    if (!check_in_phone() && server_type == 1) {
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


    var get_onConnect = function (server, bridge_id, connection, obj_drawing_list) {
        var whiteboard = get_new_whiteboard(obj_drawing_list, check_in_phone() ? 320 : 700,
            check_in_phone() ? 270 : 520);
        return function (status) {

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

    $(".opt-set-server").on("click", function () {
        var courseid = $(this).get_opt_data("courseid");
        $.ajax({
            url: '/stu_manage/get_course_server',
            type: 'POST',
            data: {
                'courseid': courseid
            },
            dataType: 'json',
            success: function (data) {
                if (data['ret'] == 0) {
                    var html_node = dlg_need_html_by_id("id_dlg_set_server");

                    html_node.find("#id_region").val(data['info'][0]);
                    html_node.find("#id_server").val(data['info'][1]);
                    BootstrapDialog.show({
                        title: '选择服务器',
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
                                    var region = html_node.find("#id_region").val();
                                    var server = html_node.find("#id_server").val();
                                    if (region == -1 || server == -1) {
                                        alert("请选择地区以及服务器!");
                                        return;
                                    }
                                    $.ajax({
                                        url: '/stu_manage/set_course_server',
                                        type: 'POST',
                                        data: {
                                            'courseid': courseid,
                                            'region': region,
                                            'id': server
                                        },
                                        dataType: 'json',
                                        success: function (data) {
                                            if (data['ret'] == 0) {
                                                window.location.reload();
                                            } else {
                                                alert(data['info']);
                                            }
                                        }
                                    });

                                }
                            }]
                    });


                }
            }
        });
    });


    $(".opt-lesson").each(function () {
        var lesson_type = $(this).get_opt_data("lesson_type");
        var lessonid = $(this).get_opt_data("lessonid");

        $(this).attr("href", "/tea_manage/lesson_list?lessonid=" + lessonid);
        //alert(lesson_type);

    });

    $(".opt-user-need-rejoin").on("click", function () {
        var stu_id = $(this).get_opt_data("stu_id");
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

        show_key_value_table("通知用户重进", arr, {
            label: '确认',
            cssClass: 'btn-warning',
            action: function (dialog) {
                do_ajax("/common/notity_rejoin_server", {
                    "server_ip": ip,
                    "roomid": room_id,
                    "userid": id_user_id.val()
                }, function () {
                    alert("让userid:" + id_user_id.val() + "重进，完毕");
                    window.location.reload();
                });
            }
        });



    });
    $(".opt-user-send-xmpp-message").on("click", function () {
        var stu_id = $(this).get_opt_data("stu_id");
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

        show_key_value_table("弹幕", arr, {
            label: '确认',
            cssClass: 'btn-warning',
            action: function (dialog) {
                do_ajax("/common/send_xmpp_message", {
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


    $('.opt-set-server-type').on('click', function () {
        var lessonid = $(this).get_opt_data("lessonid");
        $.getJSON('/lesson_manage/get_server_type', { 'lessonid': lessonid }, function (result) {
            if (result['ret'] != 0) {
                BootstrapDialog.alert('活取当次课音频服务器失败');
                return;
            }

            var html_node = $('<div></div>').html(dlg_get_html_by_class('opt-set-audio-server-type'));
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
                            $.getJSON('/lesson_manage/set_server_type', {
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

    $.each($(".td-info-stu"), function (i, item) {
        var lesson_type = $(this).data("lesson_type");
        var lessonid = $(this).data("lessonid");
        var courseid = $(this).data("courseid");
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

    setInterval(get_condition, 3000);

    $("#id_change_tea_server").on("click", function () {
        var html_node = "<div class=\"dlg_show_faq\"><div><table class=\"table table-bordered table-striped\">"
            + "<tr>"
            + "<td>课程ID</td>"
            + "<td>课程类型</td>"
            + "<td>语音通道</td>"
            + "<td>老师版本</td>"
            + "<td>学生版本</td>"
            + "</tr>";

        var data_list = $("tr");
        var update_lessonid = '';
        data_list.find(".lesson_data").each(function () {
            var lessonid = $(this).data("lessonid");
            var lesson_type_str = $(this).data("lesson_type_str");
            var server_type_str = $(this).data("server_type_str");
            var tea_user_agent = $(this).parents("td").siblings(".tea_user_agent").text();
            var stu_user_agent = $(this).parents("td").siblings(".stu_user_agent").text();
            var lesson_start = $(this).data("lesson_start");
            //获取客户端版本
            var tea_agent = get_user_agent(tea_user_agent);
            var stu_agent = get_user_agent(stu_user_agent);
            //判断课程开始时间
            var time = Math.round(new Date().getTime() / 1000);

            if (tea_agent >= 3 && stu_agent >= 3 && lesson_start > time) {
                update_lessonid += lessonid + ",";
            }
            var tea_user_agent_str = get_user_agent_str(tea_user_agent);
            var stu_user_agent_str = get_user_agent_str(stu_user_agent);
            html_node += "<tr>"
                + "<td>" + lessonid + "</td>"
                + "<td>" + lesson_type_str + "</td>"
                + "<td>" + server_type_str + "</td>"
                + "<td>" + tea_agent + "|" + tea_user_agent_str + "</td>"
                + "<td>" + stu_agent + "|" + stu_user_agent_str + "</td>"
                + "</tr>";
        });

        html_node += "</table></div></div>";
        BootstrapDialog.show({
            title: "设置课堂语音",
            message: html_node,
            buttons: [{
                label: '默认',
                cssClass: 'btn-warning',
                action: function (dialog) {
                    update_server_type(0, update_lessonid);
                    dialog.close();
                }
            }, {
                    label: '理优',
                    cssClass: 'btn-warning',
                    action: function (dialog) {
                        update_server_type(1, update_lessonid);
                        dialog.close();
                    }
                }, {
                    label: '声网',
                    cssClass: 'btn-warning',
                    action: function (dialog) {
                        update_server_type(2, update_lessonid);
                        dialog.close();
                    }
                }, {
                    label: '返回',
                    action: function (dialog) {
                        dialog.close();
                    }
                }]
        });
    });

    var get_user_agent = function (str) {
        if (str.indexOf("system_version") != -1) {
            var obj = JSON.parse(str);
            return obj["version"].substr(0, 1);
        } else {
            if (str != '') {
                var arr = str.split('.');
                var rea = arr[0].charAt(arr[0].length - 1);
                return rea;
            } else {
                return 3;
            }

        }
    };

    var get_user_agent_str = function (obj) {
        if (obj.indexOf("system_version") != -1) {
            obj = JSON.parse(obj);
            return obj.device_mode + "-" + obj.system_version + "-" + obj.version;
        } else {
            return obj;
        }
    };

    var update_server_type = function (type, val) {
        $.ajax({
            url: '/supervisor/update_server_type',
            type: 'POST',
            data: {
                'type': type,
                'update_lessonid': val
            },
            dataType: 'json',
            success: function (data) {
                BootstrapDialog.alert(data['info']);
            }
        });
    };

    $(".opt-add-error").on("click", function () {
        var lessonid = $(this).parent().data("lessonid");
        var courseid = $(this).parent().data("courseid");
        var lesson_type = $(this).parent().data("lesson_type");
        var lesson_start = $(this).parent().data("lesson_start");
        var lesson_end = $(this).parent().data("lesson_end");
        var teacherid = $(this).parent().data("teacherid");
        var tea_nick = $(this).parent().data("tea_nick");
        var stu_id = $(this).parent().data("stu_id");
        var stu_nick = $(this).parent().data("stu_nick");

        var html_node = $('<div></div>').html(dlg_get_html_by_class('dlg_add_error_info'));

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
                    do_ajax("/lesson_manage/add_error_lessonid", {
                        "lessonid": lessonid,
                        "courseid": courseid,
                        "lesson_type": lesson_type,
                        "lesson_start": lesson_start,
                        "lesson_end": lesson_end,
                        "teacherid": teacherid,
                        "tea_nick": tea_nick,
                        "stu_id": stu_id,
                        "stu_nick": stu_nick,
                        "error_info": error_info,
                        "error_info_other": error_info_other
                    }, function (result) {
                        if (result.ret < 0) {
                            alert(result.info);
                        } else {
                            var url = "/lesson_manage/error_info?lessonid=" + lessonid;
                            window.location.href = url;
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

    $.each($(".opt-change-time"), function (i, item) {
        $(item).admin_set_lesson_time({
            "lessonid": $(item).get_opt_data("lessonid")
        });
    });

    $(".opt-qr-pad-at-time").on("click", function () {

        var lessonid = $(this).get_opt_data("lessonid");
        var url = $(this).data("type");
        var title = $(this).attr("title");
        //得到 
        do_ajax("/tea_manage/get_lesson_xmpp_audio", {
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

});
