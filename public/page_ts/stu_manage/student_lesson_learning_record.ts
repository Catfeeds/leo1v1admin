///<reference path="../common.d.ts" />
///<reference path="../g_args.d.ts/stu_manage-student_lesson_learning_record.d.ts" />
var Cwhiteboard=null;
var notify_cur_playpostion =null;
function load_data(){
	  if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		    order_by_str : g_args.order_by_str,
		    sid:	g_args.sid,
		    date_type_config:	$('#id_date_type_config').val(),
		    date_type:	$('#id_date_type').val(),
		    opt_date_type:	$('#id_opt_date_type').val(),
		    start_time:	$('#id_start_time').val(),
		    end_time:	$('#id_end_time').val(),
		    subject:	$('#id_subject').val(),
		    grade:	$('#id_grade').val(),
	      cw_status:	$('#id_cw_status').val(),
		    preview_status:	$('#id_preview_status').val(),
		    current_id:	$(".current").data("id")
		});

}

$(function(){

    //audiojs 时间回调, 每秒3-4次
    //$(".tea_cw_url[data-v = 0], .stu_cw_url[data-v=0],.homework_url[data-v=0]" ) .parent().addClass("danger");
    //=======================================================
    notify_cur_playpostion = function (cur_play_time){
        Cwhiteboard.play_next( cur_play_time );
    };

        var get_new_whiteboard = function (obj_drawing_list){
        var ret = {
            "obj_drawing_list" : obj_drawing_list,
            "get_page"         : function ( pageid,show_pageid){
                var me        = this;
                var div_id    = "drawing_"+ pageid ;
                var page_info = me.draw_page_list[pageid];
                if (!page_info){
                    var tmp_div = $(  "<div  class=\"page_item\"  id=\""+div_id+ "\"/>");
                    if ( show_pageid &&  pageid != show_pageid ) {
                        tmp_div.hide();
                    }

                    obj_drawing_list.append( tmp_div );
                    me.draw_page_list[ pageid] = {
                        pageid    : pageid
                        ,opt_list : [] //svg_obj_list
                        ,draw : window.SVG(tmp_div[0]).size(me.width, me.height )
                    };

                    page_info = me.draw_page_list[pageid];
                    page_info.draw.attr("viewBox", "0,0,1024,768" );
                    //page_info.draw.
                    var text = page_info.draw.text(""+pageid+"/"+me.max_pageid );
                    //1024', '768
                    text.attr({
                        x : 969,
                        y : 732
                    });
                }
                return page_info;
            }
            ,"play_one_svg" : function(item_data,show_pageid){

                var me = this;

                if(item_data.svg_id){
                    $("#"+ item_data.svg_id) .show();
                    return;
                }

                var page_info = me.get_page(item_data.pageid,show_pageid);


                var draw = page_info.draw;


                var opt_args = item_data.opt_args;
                var id       = "";
                switch( item_data.opt_type  ) {
                case "path"  :
                   var path = draw.path( opt_args.d );
                    // console.log('path:'+path);
                   path.fill( opt_args.fill   ).stroke({ width : opt_args["stroke-width"]  }).attr({
                        "stroke" : opt_args.stroke
                    });
                    id           = path.id();

                    break;

                case "image"  :
                    var image = draw.image(opt_args.url,opt_args.width,opt_args.height  );
                    image.attr({ x : opt_args.x , y:  opt_args.y });
                    id             = image.id();
                    break;

                case "eraser"  :
                    var eraser = draw.path( opt_args.d );
                    eraser.fill( opt_args.fill   ).stroke({ width : opt_args["stroke-width"] , dasharray:opt_args["stroke-dasharray"], color:opt_args["stroke-color"] }).attr({
                        "stroke" : opt_args.stroke
                    });
                    id=eraser.id();
                default          :
                    console.log( "ERROR : " +  item_data.opt_type );
                    break;
                }
                item_data.svg_id = id;

            }

            ,"init_to_play" : function(){
                var me = this;

                me.draw_page_list = [];
                me.play_index     = 0;
                me.play_pageid    = -1;
                me.play_svg       = null ;
                me.get_page(1);
                me.show_page(1);

            }
            , "play_next_front" : function(  cur_play_time ){
                var me          = this;
                var front_flag  = false;
                var show_pageid = -1;
                var opt_list    = [];
                while ( me.play_index< me.svg_data_list.length  ){
                    var item_data = me.svg_data_list[me.play_index];
                    if (item_data.timestamp <= cur_play_time ){ //时间到了已经
                        console.log(cur_play_time);
                        show_pageid = item_data.pageid;
                        opt_list.push(item_data);
                        me.play_index++;
                        front_flag = true;
                    }else{
                        break;
                    }
                }
                if(show_pageid != -1){
                    me.show_page(show_pageid);
                    $.each(opt_list,function(i,item_data){
                        me.play_one_svg(item_data ,show_pageid);
                    });
                }
                return front_flag;
            }

            , "play_next_back" : function (cur_play_time) {

                //后退处理

                var me             = this;
                var a_show_page_id = -1;
                var opt_list       = [];
                while ( me.play_index>0 ){
                    var item_data = me.svg_data_list[me.play_index-1];
                    if (item_data.timestamp > cur_play_time ){
                        opt_list.push(item_data);
                        a_show_page_id = item_data.pageid;
                        me.play_index--;
                    }else{
                        break;
                    }
                }

                if(a_show_page_id != -1){
                    me.show_page(a_show_page_id);
                    $.each(opt_list,function(i,item_data){
                        $("#"+ item_data.svg_id) .hide();
                    });
                }
            }

            ,"play_next" :  function( cur_play_time){
                var me = this;
                //前进处理
                var front_flag = me.play_next_front(cur_play_time);
                if (!front_flag){
                    me.play_next_back(cur_play_time);
                }
            }

            ,"show_page" : function( pageid ){
                console.log("PAGEID : "+pageid);
                var me              = this;
                if ( me.play_pageid != pageid ){
                    var div_id = "drawing_"+ pageid ;
                    me.obj_drawing_list.find("div").hide();
                    me.obj_drawing_list.find("#"+div_id ).show();
                    me.play_pageid = pageid;
                }
            }

            ,"loadData" : function(  w, h, lession_start_time , xml_file, mp3_file,html_node ){
                var me           = this;
                var audio_node   = html_node.find("audio" );
                me.svg_data_list = [];
                me.height        = h;
                me.width         = w;
                me.max_pageid    = 0;
                me.start_time    = lession_start_time ;
                $.get(xml_file,function(xml){
                    var svg_list = $(xml).find("svg") ;

                    svg_list.each(function(){
                        var item_data={};
                        var item         = $(this);


                        item_data["pageid"]= Math.floor(item.attr("y")/768)+1;
                        if (me.max_pageid <  item_data["pageid"]){
                            me.max_pageid = item_data["pageid"];
                        }

                        item_data["timestamp"]= item.attr("timestamp")-me.start_time;
                        var opt_item        = item.children(":first");
                        item_data["opt_type"]= $(opt_item)[0].tagName;

                        var stroke_info = opt_item.attr("stroke");
                        if( typeof stroke_info != "undefined" && stroke_info.indexOf("#") == -1){
                            stroke_info = "#" + stroke_info;
                        }
                        var opt_args={};
                        switch( item_data["opt_type"]) {

                        case "path" :
                            //<path fill = "none" stroke="0bceff" stroke-width="4" d="M458.0 235.5Q458.0 235.5 457.0 237.5M457.0 237.5Q456.0 239.5 456.0 242.5M456.0 242.5Q456.0 245.5 455.5 253.2M455.5 253.2Q455.0 261.0 453.0 273.5M453.0 273.5Q451.0 286.0 447.8 301.0M447.8 301.0Q444.5 316.0 441.2 333.0M441.2 333.0Q438.0 350.0 435.8 367.8M435.8 367.8Q433.5 385.5 433.2 402.5"></path>
                            opt_args     = {
                                fill            : opt_item.attr("fill")
                                ,stroke         : stroke_info
                                ,"stroke-width" : opt_item.attr("stroke-width")
                                ,"d"            : opt_item.attr("d")
                                ,"stroke-dasharray" : opt_item.attr("stroke-dasharray")
                                ,"stroke-color" : "FFFFFF"
                            }; break;
                        case "image" :
                            opt_args = {
                                x         : opt_item.attr("x")
                                ,y        : opt_item.attr("y")
                                ,"width"  : opt_item.attr("width")
                                ,"height" : opt_item.attr("height")
                                ,"url"    : opt_item.text()
                            };
                            break;
                        case "eraser" :
                            opt_args  = {
                                fill                : opt_item.attr("fill")
                                ,stroke             : stroke_info
                                ,"stroke-width"     : opt_item.attr("stroke-width")
                                ,"stroke-dasharray" : opt_item.attr("stroke-dasharray")
                                ,"d"                : opt_item.attr("d")
                                ,"stroke-color"     : "white"
                            };
                            break;


                        default :
                            console.log( "ERROR : " +  item_data["opt_type"]);
                            break;


                        }

                        item_data["opt_args"] = opt_args;

                        me.svg_data_list.push( item_data );
                    });

                    me.init_to_play();

                    //加载mp3
                    audiojs.events.ready(function(){
                        var as = audiojs.createAll({}, audio_node  );
                        //reset width
                        //
                        html_node.find(".audiojs").css("width",""+w+"px" );
                        html_node.find(".scrubber").css("width", (w-174).toString()+"px" );
                        //
                        as[0].load( mp3_file  );
                    });
                });
            }
        };
        // console.log(ret);
        return ret;
    };


    window["g_load_data_flag"]=1;
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

    var get_arr_from_obj = function(objj){
        var arr = []
        for (var i in objj) {
            arr.push(parseInt(objj[i])); //属性
            //arr.push(object[i]); //值
        }       
        return arr;
    };
    var subject_list_arr =get_arr_from_obj(window["g_subject_list"]);
    var grade_list_arr =get_arr_from_obj(window["g_grade_list"]);
    Enum_map.append_option_list("subject",$("#id_subject"),false,subject_list_arr);
    Enum_map.append_option_list("grade",$("#id_grade"),false,grade_list_arr);


	  $('#id_grade').val(g_args.grade);
	  $('#id_subject').val(g_args.subject);
    $('#id_cw_status').val(g_args.cw_status);
	  $('#id_preview_status').val(g_args.preview_status);

    $("#id_search").on("click",function(){
        window["g_load_data_flag"] = 0;
        load_data();
        
    });
      

   
    $('.stu_tab04 td').on('click', function() {
        $(this).addClass('current');
        $(this).siblings().removeClass('current');
        $(this).siblings().css({
            "background-color":"white",
        });
        $(this).siblings().find("a").css({
            "color":"#000",
        });
        $(".current").css({
            "background-color":"#00E5EE",
        });
        $(".current a").css({
            "color":"white",
        });
        var current_id =  $(".current").data("id");
        if(current_id==5){
            $("#id_add_stu_score").parent().show();
        }else{
            $("#id_add_stu_score").parent().hide();
        }
        window["g_load_data_flag"] = 0;
        load_data();
       



        // var show_id = $(this).attr('data-id');
        // $(show_id).removeClass('hide');
        // $(this).siblings().each(function(){
        //     var hide_id = $(this).attr('data-id');
        //     $(hide_id).addClass('hide');
        // });
    });
    $('.stu_tab04 td').each(function(){
        var current_id = $(this).data("id");
        if(current_id==g_args.current_id){
            $(this).addClass('current');
            $(this).siblings().removeClass('current');
            $(this).siblings().css({
                "background-color":"white",
            });
            $(this).siblings().find("a").css({
                "color":"#000",
            });
            $(".current").css({
                "background-color":"#00E5EE",
            });
            $(".current a").css({
                "color":"white",
            });
            if(current_id==5){
                $("#id_add_stu_score").parent().show();
            }else{
                $("#id_add_stu_score").parent().hide();
            }

        }
    });
    var current_id =  $(".current").data("id");
    if(current_id==5){
        $("#id_add_stu_score").parent().show();
    }else{
        $("#id_add_stu_score").parent().hide();
    }
    $(".preview_table_flag,.lesson_table_flag,.performance_table_flag").each(function(){
        var class_id =$(this).data("class_id");
        if(current_id==class_id){
            $(this).show();
        }else{
            $(this).hide(); 
        }
    });
   



    $(".current").css({
        "background-color":"#00E5EE",
    });
    $(".current a").css({
        "color":"white",
    });
   
    $("#id_cw_status,#id_preview_status").change(function(){
        window["g_load_data_flag"] = 0;
        load_data();
    });
   
    $('.opt-change').set_input_change_event(load_data);
    $('#id_grade').change(function(){
        var grade=$(this).val();
        var vv = $(this).find("option:selected").text();
        var htm = "<label class=\"fa fa-times\"></label>"+vv;
        if(grade==-1){
            $("#id_grade_show").hide();
        }else{
            $("#id_grade_show").html(htm);
            $("#id_grade_show").show();
        }
    });
    $('#id_subject').change(function(){
        var subject=$(this).val();
        var vv = $(this).find("option:selected").text();
        var htm = "<label class=\"fa fa-times\"></label>"+vv;
        if(subject==-1){
            $("#id_subject_show").hide();
        }else{
            $("#id_subject_show").html(htm);
            $("#id_subject_show").show();
        }
    });
    if(g_args.grade==-1){
        $("#id_grade_show").hide();
    }else{
        var vv = $("#id_grade").find("option:selected").text();
        var htm = "<label class=\"fa fa-times\"></label>"+vv;
        $("#id_grade_show").html(htm);
        $("#id_grade_show").show();
    }
    if(g_args.subject==-1){
        $("#id_subject_show").hide();
    }else{
        var vv = $("#id_subject").find("option:selected").text();
        var htm = "<label class=\"fa fa-times\"></label>"+vv;
        $("#id_subject_show").html(htm);
        $("#id_subject_show").show();
    }

    $("#id_grade_show").on("click",function(){
        $(this).hide();
        $("#id_grade").val(-1);
        window["g_load_data_flag"] = 0;
        load_data();
        
    });

    $("#id_subject_show").on("click",function(){
        $(this).hide();
        $("#id_subject").val(-1);
        window["g_load_data_flag"] = 0;
        load_data();
    });

    $(".show_cw_content").on("click",function(){
        var url = $(this).data("url");
        $.wopen(url); 
    });
    $("#id_show_all").on("click",function(){
        alert(111);
    });
    $(".show_lesson_detail").on("click",function(){
        var lessonid = $(this).data("lessonid");
        alert(lessonid);
    });
    $(".show_login_info").on("click",function(){
         var lessonid = $(this).data("lessonid");
         var userid = $(this).data("userid");
         var role = $(this).data("role");
        var title = "登录日志";
        var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>角色</td><td>进出</td><td>时间</td></tr></table></div>");

        $.do_ajax('/ajax_deal2/get_lesson_opt_detail_info',{
            "lessonid" : lessonid,
            "userid"   : userid
        },function(resp) {
            var list = resp.data;
            $.each(list,function(i,item){              
                html_node.find("table").append("<tr><td>"+role+"</td><td>"+item["opt_type_str"]+"</td><td>"+item["opt_time_str"]+"</td></tr>");
            });
        });

        var dlg=BootstrapDialog.show({
            title:title, 
            message :  html_node   ,
            closable: true, 
            buttons:[{
                label: '返回',
                cssClass: 'btn',
                action: function(dialog) {
                    dialog.close();

                }
            }],
            onshown:function(){
                
            }

        });

        dlg.getModalDialog().css("width","600px");


    });

    $(".show_stu_score_detail").on("click",function(){
        var effect = $(this).data("effect"); 
        var quality = $(this).data("quality"); 
        var interact = $(this).data("interact"); 
        var stability = $(this).data("stability");
        var title = "打分详情";
        var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>类型</td><td>得分</td></tr><tr><td>上课效果</td><td>"+effect+"</td></tr><tr><td>课件质量</td><td>"+quality+"</td></tr><tr><td>课堂互动</td><td>"+interact+"</td></tr><tr><td>系统稳定性</td><td>"+stability+"</td></tr></table></div>");     

        var dlg=BootstrapDialog.show({
            title:title, 
            message :  html_node   ,
            closable: true, 
            buttons:[{
                label: '返回',
                cssClass: 'btn',
                action: function(dialog) {
                    dialog.close();

                }
            }],
            onshown:function(){
                
            }

        });

        dlg.getModalDialog().css("width","400px");


    });

    $(".show_lesson_video").on("click",function(){
        var lessonid = $(this).data("lessonid");
        
        $.ajax({
            type     : "post",
            url      : "/tea_manage/get_lesson_reply",
            dataType : "json",
            data     : {"lessonid":lessonid},
            success  : function(result){
                if(result.ret == 0){
                    console.log("http://admin.leo1v1.com/player/playback.html?draw="+encodeURIComponent(result.draw_url)
                                +"&audio="+encodeURIComponent(result.audio_url)
                                +"&start="+result.real_begin_time);
                    if ( false && !$.check_in_phone() ) {

                        // console.log("http://admin.leo1v1.com/player/playback.html?draw="+encodeURIComponent(result.draw_url)
                        //             +"&audio="+encodeURIComponent(result.audio_url)
                        //             +"&start="+result.real_begin_time);
                        window.open("http://admin.leo1v1.com/player/playback.html?draw="+encodeURIComponent(result.draw_url)
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
            
      
        
    });





});
