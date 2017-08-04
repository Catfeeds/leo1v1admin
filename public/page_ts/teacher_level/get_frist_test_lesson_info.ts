/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_level-get_frist_test_lesson_info.d.ts" />

var Cwhiteboard=null;
var notify_cur_playpostion =null;
$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			teacherid:	$('#id_teacherid').val(),
			subject:	$('#id_subject').val(),
			record_flag:	$('#id_record_flag').val()
        });
    }
    //audiojs 时间回调, 每秒3-4次
    //$(".tea_cw_url[data-v = 0], .stu_cw_url[data-v=0],.homework_url[data-v=0]" ) .parent().addClass("danger");
    //=======================================================
    notify_cur_playpostion = function (cur_play_time){
        Cwhiteboard.play_next( cur_play_time );
    };

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

    Enum_map.append_option_list("subject", $('#id_subject'));
    Enum_map.append_option_list("boolean", $('#id_record_flag'));

	$('#id_teacherid').val(g_args.teacherid);
	$('#id_subject').val(g_args.subject);
	$('#id_record_flag').val(g_args.record_flag);
    $.admin_select_user($("#id_teacherid"), "teacher", load_data);
    $(".opt-first-lesson-video").on("click",function(){
        var opt_data = $(this).get_opt_data();
        $.ajax({
            type     : "post",
            url      : "/tea_manage/get_lesson_reply",
            dataType : "json",
            data     : {"lessonid":opt_data.lessonid},
            success  : function(result){
                if(result.ret == 0){
                    console.log("http://admin.yb1v1.com/player/playback.html?draw="+encodeURIComponent(result.draw_url)
                                +"&audio="+encodeURIComponent(result.audio_url)
                                +"&start="+result.real_begin_time);
                    if ( false && !$.check_in_phone() ) {

                        // console.log("http://admin.yb1v1.com/player/playback.html?draw="+encodeURIComponent(result.draw_url)
                        //             +"&audio="+encodeURIComponent(result.audio_url)
                        //             +"&start="+result.real_begin_time);
                        window.open("http://admin.yb1v1.com/player/playback.html?draw="+encodeURIComponent(result.draw_url)
                                    +"&audio="+encodeURIComponent(result.audio_url)
                                    +"&start="+result.real_begin_time,"_blank");
                    }else{

                        var w = $.check_in_phone()?329 : 558;
                        var h = w/4*3;
                        var html_node = $("<div style=\"text-align:center;\"> "
                                          +"<div id=\"drawing_list\" style=\"width:100%\">"
                                          +"</div><audio preload=\"none\"></audio></div>"
                                         );
                        BootstrapDialog.show({
                            title    : '课程回放:lessonid:'+opt_data.lessonid+", 学生:" + result.stu_nick,
                            message  : html_node,
                            closable : true,
                            onhide   : function(dialogRef){
                            }
                        });
                        Cwhiteboard = get_new_whiteboard(html_node.find("#drawing_list"));
                        Cwhiteboard.loadData(w,h,result.real_begin_time,result.draw_url,result.audio_url,html_node);
                    }
                }else{
                    BootstrapDialog.alert(result.info);
                }
            }
        });
                
    });

    $(".opt-first-lesson-record").on("click",function(){
        var opt_data = $(this).get_opt_data();
        $.do_ajax( "/teacher_level/get_teacher_test_lesson_info_record",{
            "teacherid" :opt_data.teacherid,
            "lesson_type":2,
            "num":0
        },function(resp){
            var ret = resp.ret;
            if(ret ==-1){
                alert(resp.info);
                return;
            }else{
                var lessonid = resp.data;
                $.ajax({
                    type     : "post",
                    url      : "/tea_manage/get_lesson_reply",
                    dataType : "json",
                    data     : {"lessonid":lessonid},
                    success  : function(result){
                        if(result.ret == 0){
                            console.log("http://admin.yb1v1.com/player/playback.html?draw="+encodeURIComponent(result.draw_url)
                                        +"&audio="+encodeURIComponent(result.audio_url)
                                        +"&start="+result.real_begin_time);
                            if ( false && !$.check_in_phone() ) {

                                // console.log("http://admin.yb1v1.com/player/playback.html?draw="+encodeURIComponent(result.draw_url)
                                //             +"&audio="+encodeURIComponent(result.audio_url)
                                //             +"&start="+result.real_begin_time);
                                window.open("http://admin.yb1v1.com/player/playback.html?draw="+encodeURIComponent(result.draw_url)
                                            +"&audio="+encodeURIComponent(result.audio_url)
                                            +"&start="+result.real_begin_time,"_blank");
                            }else{

                                var w = $.check_in_phone()?329 : 558;
                                var h = w/4*3;
                                var html_node = $("<div style=\"text-align:center;\"> "
                                                  +"<div id=\"drawing_list\" style=\"width:100%\">"
                                                  +"</div><audio preload=\"none\"></audio></div>"
                                                 );
                                BootstrapDialog.show({
                                    title    : '课程回放:lessonid:'+lessonid+", 学生:" + result.stu_nick,
                                    message  : html_node,
                                    closable : true,
                                    onhide   : function(dialogRef){
                                    }
                                });
                                Cwhiteboard = get_new_whiteboard(html_node.find("#drawing_list"));
                                Cwhiteboard.loadData(w,h,result.real_begin_time,result.draw_url,result.audio_url,html_node);
                            }
                        }else{
                            BootstrapDialog.alert(result.info);
                        }
                    }
                });
            }

            

        });
    });


   

	$('.opt-change').set_input_change_event(load_data);
});










