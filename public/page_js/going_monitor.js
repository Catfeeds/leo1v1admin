// SWITCH-TO:   ../../template/supervisor/going_monitor.html
$(function(){

    function loadData(nick, date)
    {
        var url = "/supervisor/monitor?nick="+nick+"&date="+date;
        window.location.href = url;
    }

    $(".stu_search").on("click", function(){
        loadData(
            $("#id_search_nick").val(),
            $("#datetimepicker21").val()
		);
    });

    function get_condition()
    {
        $.ajax({
			type     :"post",
			url      :"/supervisor/get_going_lesson_conditions",
			dataType :"json",
			data     :{"page_num": g_page_num},
			success  : function(data){
                if(data['ret'] == 0){
                    var opt_wb_tr = $('.wb_monitor_item');
                    reload_page();
                    $.each(opt_wb_tr, function(i, item){
                        var info     = data['condition_list'][i];
                        var cond = JSON.parse(info['lesson_condition']);
                        //wb_tea
                        var wb_tea_span = $(item).find(".wb_tea span");
                        $(wb_tea_span[0]).html(cond['tea']['xmpp_dis']);

                        //wb_stu
                        var wb_stu_span = $(item).find(".wb_stu span");
                        $(wb_stu_span[0]).html(cond['stu']['xmpp_dis']);

                        //wb_par
                        var wb_par_span = $(item).find(".wb_par span");
                        $(wb_par_span[0]).html(cond['par']['xmpp_dis']);

                        //wb_ad
                        var wb_ad_span = $(item).find(".wb_ad span");
                        $(wb_ad_span[0]).html(cond['ad']['xmpp_dis']);
                                               
                        if(info['lesson_status'] == 1){
                            //wb_stu
                            if(cond['stu']['xmpp'] == 1){
                                $(wb_stu_span[1]).removeClass("warn");
                                $(wb_stu_span[1]).addClass("ing");
                            }else{
                                $(wb_stu_span[1]).addClass("warn");
                                $(wb_stu_span[1]).removeClass("ing");
                            }
                            //wb_tea
                            if(cond['tea']['xmpp'] == 1){
                                $(wb_tea_span[1]).removeClass("warn");
                                $(wb_tea_span[1]).addClass("ing");
                            }else{
                                $(wb_tea_span[1]).addClass("warn");
                                $(wb_tea_span[1]).removeClass("ing");
                            }
                             //wb_par
                            if(cond['par']['xmpp'] == 1){
                                $(wb_par_span[1]).addClass("ing");
                            }else{
                                $(wb_par_span[1]).removeClass("ing");
                            }
                            
                            //wb_ad
                            if(cond['ad']['xmpp'] == 1){
                                $(wb_ad_span[1]).addClass("ing");
                            }else{
                                $(wb_ad_span[1]).removeClass("ing");
                            }
                                                       
                        }else if(info['lesson_status'] == 2){
                            //wb_stu
                            $(wb_stu_span[1]).removeClass("warn");
                            $(wb_stu_span[1]).removeClass("ing");
                            //wb_tea
                            $(wb_tea_span[1]).removeClass("warn");
                            $(wb_tea_span[1]).removeClass("ing");
                            //wb_par
                            $(wb_par_span[1]).removeClass("ing");
                            //wb_ad
                            $(wb_ad_span[1]).removeClass("ing");
                        }

                    });

                    var opt_audio_tr = $(".audio_monitor_item");
                    $.each(opt_audio_tr, function(i, item){
                        var info     = data['condition_list'][i];
                        var cond = JSON.parse(info['lesson_condition']);
                        //audio_tea
                        var audio_tea_span = $(item).find(".audio_tea span");
                        $(audio_tea_span[0]).html(cond['tea']['webrtc_dis']);

                        //audio_stu
                        var audio_stu_span = $(item).find(".audio_stu span");
                        $(audio_stu_span[0]).html(cond['stu']['webrtc_dis']);

                        //audio_par
                        var audio_par_span = $(item).find(".audio_par span");
                        $(audio_par_span[0]).html(cond['par']['webrtc_dis']);

                        //audio_ad
                        var audio_ad_span = $(item).find(".audio_ad span");
                        $(audio_ad_span[0]).html(cond['ad']['webrtc_dis']);

                        if(info['lesson_status'] == 1){
                            //audio_stu
                            if(cond['stu']['webrtc'] == 1){
                                $(audio_stu_span[1]).removeClass("warn");
                                $(audio_stu_span[1]).addClass("ing");
                            }else{
                                $(audio_stu_span[1]).addClass("warn");
                                $(audio_stu_span[1]).removeClass("ing");
                            }
                            //audio_tea
                            if(cond['tea']['webrtc'] == 1){
                                $(audio_tea_span[1]).removeClass("warn");
                                $(audio_tea_span[1]).addClass("ing");
                            }else{
                                $(audio_tea_span[1]).addClass("warn");
                                $(audio_tea_span[1]).removeClass("ing");
                            }
                             //audio_par
                            if(cond['par']['webrtc'] == 1){
                                $(audio_par_span[1]).addClass("ing");
                            }else{
                                $(audio_par_span[1]).removeClass("ing");
                            }
                            
                            //audio_ad
                            if(cond['ad']['webrtc'] == 1){
                                $(audio_ad_span[1]).addClass("ing");
                            }else{
                                $(audio_ad_span[1]).removeClass("ing");
                            }
                        }else if(info['lesson_status'] == 2){
                             //audio_stu
                            $(audio_stu_span[1]).removeClass("warn");
                            $(audio_stu_span[1]).removeClass("ing");
                            //audio_tea
                            $(audio_tea_span[1]).removeClass("warn");
                            $(audio_tea_span[1]).removeClass("ing");
                            //audio_par
                            $(audio_par_span[1]).removeClass("ing");
                            //audio_ad
                            $(audio_ad_span[1]).removeClass("ing");
                        }

                    });
			    }
                //判断是否需要重载页面
                function reload_page(){
                    var cond_arr = data['condition_list'];
                    if(opt_wb_tr.length != cond_arr.length){
                        window.location.reload();
                    }
                    for(var idx =0; idx < cond_arr.length; idx++){
                        if(cond_arr[idx].lessonid != $(opt_wb_tr[idx]).data("lessonid") ){
                            window.location.reload();
                        }
                    }
                }
            }
		});
    }
    get_condition();
    setInterval(get_condition,3000);

    $(".done_e").on("click", function(){
        window.location.href = "/supervisor";
    });
    


});
