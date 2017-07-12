/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/supervisor-lesson_all_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            st_application_nick:	$('#id_st_application_nick').val(),
            require_adminid:	$('#id_require_adminid').val(),
            userid:	$('#id_userid').val(),
            teacherid:	$('#id_teacherid').val(),
            assistantid:	$('#id_assistantid').val(),
            lessonid:	$('#id_lessonid').val(),
            ip:	$('#id_ip').val(),
            lesson_type_str:	$('#id_lesson_type_str').val(),
            port:	$('#id_port').val(),
            region:	$('#id_region').val(),
            server_type_str:	$('#id_server_type_str').val(),
            account:	$('#id_account').val(),
            lesson_time:	$('#id_lesson_time').val(),
            room_id:	$('#id_room_id').val()
        });
    }


    $('#id_st_application_nick').val(g_args.st_application_nick);
    $('#id_require_adminid').val(g_args.require_adminid);
    $('#id_userid').val(g_args.userid);
    $('#id_teacherid').val(g_args.teacherid);
    $('#id_assistantid').val(g_args.assistantid);
    $('#id_lessonid').val(g_args.lessonid);
    $('#id_ip').val(g_args.ip);
    $('#id_lesson_type_str').val(g_args.lesson_type_str);
    $('#id_port').val(g_args.port);
    $('#id_region').val(g_args.region);
    $('#id_server_type_str').val(g_args.server_type_str);
    $('#id_account').val(g_args.account);
    $('#id_lesson_time').val(g_args.lesson_time);
    $('#id_room_id').val(g_args.room_id);



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

        $.do_ajax(
            "/supervisor/get_lesson_conditions_js",
            {
                date:                g_args.date,
                st_application_nick: $('#id_stu_info').attr('st_application_nick'),
                userid:              $('#id_stu_info').attr('data-userid'),
                teacherid:           $('#id_stu_info').attr('data-teacherid'),
                run_flag:            g_args.run_flag,
                assistantid:         $('#id_stu_info').attr('data-assistantid'),
                require_adminid:     $('#id_stu_info').attr('require_adminid'),
            },
            function (data) {

                if (data["reload_flag"]) {
                    alert(12);
                    alert("need_reload");
                    // load_data();
                }
                if (data['ret'] == 0) {
                    var condition_list= data['condition_list'];

                    if (opt_wb_tr.length !=  data["lesson_count"] ) {
                        alert("lesson_count_err");
                        // load_data();
                    }

                    $.each(opt_wb_tr, function (i, item) {
                        var $item=$(item);
                        var $opt_div=$item.find("div:last >div");
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
    // get_condition();





    $('.opt-change').set_input_change_event(load_data);

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

    $('#id_btn_student_phone').on("click",function(){
        var stu_phone = $(this).parent().attr('data-stu_phone');
        do_phone(stu_phone);
    });
    $('#id_btn_teacher_phone').on("click",function(){
        var tea_phone = $(this).parent().attr('data-tea_phone');
        do_phone(tea_phone);
    });


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

    // alert(getQueryString("room_id"));
    var opt_data = $(this).get_opt_data();



});
