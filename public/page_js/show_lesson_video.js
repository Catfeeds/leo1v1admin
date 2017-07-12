// SWITCH-TO: ../../old/template/tea_manage
$(function(){
    var lessonid=g_args.lessonid;
    console.log(g_args.lessonid);
    $.ajax({
        type     : "post",
        url      : "/tea_manage/get_lesson_reply",
        dataType : "json",
        data     : {"lessonid":lessonid},
        success  : function(result){
            if(result.ret == 0){
                if (!$.check_in_phone()  || true ) {
                    console.log("http://admin.yb1v1.com/player/playback.html?draw="+encodeURIComponent(result.draw_url)
                                +"&audio="+encodeURIComponent(result.audio_url)
                                +"&start="+result.real_begin_time);
                    window.open("http://admin.yb1v1.com/player/playback.html?draw="+encodeURIComponent(result.draw_url)
                                +"&audio="+encodeURIComponent(result.audio_url)
                                +"&start="+result.real_begin_time,"_self");
                }else{
                    var w = $.check_in_phone()?329 : 558;
                    var h = w/4*3;
                    var html_node = $("<div style=\"text-align:center;\"> "
                                      +"<div id=\"drawing_list\" style=\"width:100%\">"
                                      +"</div><audio preload=\"none\"></audio></div>"
                                     );
                    BootstrapDialog.show({
                        title    : '课程回放:lessonid:'+opt_data.lessonid+", 学生:" + opt_data.stu_nick,
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
