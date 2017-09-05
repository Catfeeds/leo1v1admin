/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-teacher_test_lesson_info.d.ts" />
var notify_cur_playpostion =null;
function load_data(){
    $.reload_self_page ( {
        order_by_str: g_args.order_by_str,
		page_count:	$('#id_page_count').val(),
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		teacherid:	$('#id_teacherid').val(),
		subject:	$('#id_subject').val(),
		teacher_subject:	$('#id_teacher_subject').val(),
		identity:	$('#id_identity').val(),
		grade_part_ex:	$('#id_grade_part_ex').val(),
		tea_status:	$('#id_tea_status').val(),
		qzls_flag:	$('#id_qzls_flag').val(),
		create_now:	$('#id_create_now').val(),
		teacher_account:	$('#id_teacher_account').val(),
		fulltime_teacher_type:	$('#id_fulltime_teacher_type').val()
    });
}

$(function(){
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
    Enum_map.append_option_list("grade", $("#id_grade_part_ex") );
    Enum_map.append_option_list("subject", $("#id_subject") );
    Enum_map.append_option_list("subject", $("#id_teacher_subject") );
    Enum_map.append_option_list("identity", $("#id_identity") );
    Enum_map.append_option_list("boolean", $("#id_create_now") ,false,[1]);
    Enum_map.append_option_list("fulltime_teacher_type", $("#id_fulltime_teacher_type") ,false,[1,2]);

	$('#id_page_count').val(g_args.page_count);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_subject').val(g_args.subject);
	$('#id_teacher_subject').val(g_args.teacher_subject);
	$('#id_identity').val(g_args.identity);
	$('#id_grade_part_ex').val(g_args.grade_part_ex);
	$('#id_tea_status').val(g_args.tea_status);
	$('#id_qzls_flag').val(g_args.qzls_flag);
	$('#id_create_now').val(g_args.create_now);

	$('#id_teacher_account').val(g_args.teacher_account);
	$('#id_fulltime_teacher_type').val(g_args.fulltime_teacher_type);
    
    $.admin_select_user($("#id_teacher_account"), "interview_teacher", load_data);
    $.admin_select_user( $("#id_teacherid"), "teacher", load_data);

   

   
    $.each($("tr"),function(i,item){
        var success_lesson = $(this).children().find(".data").data("success_lesson");
        var have_order     = $(this).children().find(".data").data("have_order");
        var order_number     = $(this).children().find(".data").data("order_number");
        var is_freeze      = $(this).children().find(".data").data("is_freeze");
        var limit          = $(this).children().find(".data").data("limit_plan_lesson_type");
        var order_per      = $(this).children().find(".data").data("order_per");
        var lesson_num= $(this).children().find(".data").data("lesson_num");

        
      /*  if(order_per>=30 && tea_right==2){
            $(this).hide();
        }else if(order_per>=20 && g_tea_subject>0){
            $(this).hide();
            if(order_per>=25 && lesson_num>10){
                $(this).show();
            }
        }
*/
        if(is_freeze>0){
            $(this).addClass("bg_orange");
        }else if(limit>0){
            $(this).addClass("bg_orange_red");
        }else if(success_lesson>=10 && order_number<1){
            $(this).addClass("bg_red");
            $(this).find(".status_str").text("预警");
        }
        if(g_teacher_test_status==4){
            if(success_lesson<10 || order_number>=1){
                $(this).hide();
            } 
        }

        
    });

   
   
    $(".content_show").each(function(){
        var content = $(this).data("content");
        var len = content.length;
        if(len >=10){
            var con = content.substr(0,9)+"...";
        }else{
             con = content;
        }
        $(this).html(con);

        $(this).mouseover(function(){
            
          $(this).html(content);

        });
        $(this).mouseout(function(){
            $(this).html(con);
        });
        
    });

    $(".content_freeze").each(function(){
        var freeze = $(this).data("freeze");
        var free = $(this).data("free");
        var time = $(this).data("time");
        var reason = $(this).data("reason");
        var adminid = $(this).data("adminid");
        $(this).text(free);
        if(freeze==1){
            $(this).mouseover(function(){
                
                $(this).html(free+"<br/>操作时间:"+time+"<br/>原因:"+reason+"<br/>操作人:"+adminid);

            });
            $(this).mouseout(function(){
                $(this).text(free);
            });
        }
    });
    
    $(".content_limit").each(function(){
        var limit_type = $(this).data("type");
        var limit = $(this).data("limit");
        var time = $(this).data("time");
        var reason = $(this).data("reason");
        var adminid = $(this).data("adminid");
        if(limit_type>0){
            $(this).text(limit);
            $(this).mouseover(function(){
                
                $(this).html(limit+"<br/>操作时间:"+time+"<br/>原因:"+reason+"<br/>操作人:"+adminid);

            });
            $(this).mouseout(function(){
                $(this).text(limit);
            });
        }else{
            $(this).text("未限制");
        }
    });


   
   
    $(".regular_stu_num").on("click",function(){
        var teacherid = $(this).data("teacherid");
        if(teacherid > 0){
            var title = "学生详情";
            var html_node = $("<div id=\"div_table\"><div class=\"col-md-12\" id=\"div_grade\"><div class=\"col-md-2\">年级统计:</div></div><br><div class=\"col-md-12\" id=\"div_subject\"><div class=\"col-md-2\">科目统计:</div></div><br><br><br><table   class=\"table table-bordered \"><tr><td>id</td><td>名字</td><td>年级</td><td>科目</td><tr></table></div>");
            
            $.do_ajax('/tongji_ss/get_teacher_stu_info_new',{
                "teacherid" : teacherid
            },function(resp) {
                var userid_list   = resp.data;
               // console.log(userid_list);
                var grade_count   = resp.grade;
                var subject_count = resp.subject;
                for(var i in grade_count){
                    html_node.find("#div_grade").append("<div class=\"col-md-1\">"+i+":"+grade_count[i]+"</div>");
                }
                for(var i in subject_count){
                    html_node.find("#div_subject").append("<div class=\"col-md-1\">"+i+":"+subject_count[i]+"</div>");
                }

                /*html_node.prepend("<div class=\"col-md-12\"><div class=\"col-md-2\">年级统计:</div><div class=\"col-md-3\">小学:"+grade_count.primary+"</div><div class=\"col-md-3\">初中:"+grade_count.junior+"</div><div class=\"col-md-3\">高中:"+grade_count.senior+"</div></div><br><br><br>");*/
                
                $.each(userid_list,function(i,item){
                    var userid = item["userid"];
                    var name = item["nick"];
                    var subject = item["subject_str"];
                    var grade = item["grade_str"];
                    html_node.find("table").append("<tr><td>"+userid+"</td><td>"+name+"</td><td>"+grade+"</td><td>"+subject+"</td></tr>");
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

            dlg.getModalDialog().css("width","1024px");

        }
        
    });

   
    $(".test_lesson_num").on("click",function(){
        var teacherid = $(this).data("teacherid");
        if(teacherid > 0){
                                 

            var title = "今后三周试听课详情";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>lessonid</td><td>时间</td><td>学生</td><td>年级</td><td>科目</td><tr></table></div>");

            $.do_ajax('/tongji_ss/get_teacher_test_lesson_info_new',{
                "teacherid" : teacherid
            },function(resp) {
                var userid_list = resp.data;
                $.each(userid_list,function(i,item){
                    var lessonid = item["lessonid"];
                    var nick = item["nick"]
                    var time = item["lesson_start_str"];
                    var subject = item["subject_str"];
                    var grade = item["grade_str"];
                    html_node.find("table").append("<tr><td>"+lessonid+"</td><td>"+time+"</td><td>"+nick+"</td><td>"+grade+"</td><td>"+subject+"</td></tr>");
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

            dlg.getModalDialog().css("width","1024px");

        }
        
    });

    $(".test_lesson_num_week").on("click",function(){
        var teacherid = $(this).data("teacherid");
        if(teacherid > 0){
            var title = "本周剩余试听课详情";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>lessonid</td><td>时间</td><td>学生</td><td>年级</td><td>科目</td><tr></table></div>");

            $.do_ajax('/tongji_ss/get_teacher_test_lesson_info_week',{
                "teacherid" : teacherid
            },function(resp) {
                var userid_list = resp.data;
                $.each(userid_list,function(i,item){
                    var lessonid = item["lessonid"];
                    var nick = item["nick"]
                    var time = item["lesson_start_str"];
                    var subject = item["subject_str"];
                    var grade = item["grade_str"];
                    html_node.find("table").append("<tr><td>"+lessonid+"</td><td>"+time+"</td><td>"+nick+"</td><td>"+grade+"</td><td>"+subject+"</td></tr>");
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

            dlg.getModalDialog().css("width","1024px");

        }
        
    });

    if(adminid != 72 && adminid != 349){
        $(".id_account_teacher").hide();
    }

    $(".order_num_per").on("click",function(){
        var teacherid = $(this).data("teacherid");
        // console.log(g_subject);
        if(teacherid > 0){
            var title = "各年级段签单详情";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>年级段</td><td>试听课数</td><td>签单数</td><td>签单率</td><tr></table></div>");

            $.do_ajax('/tongji_ss/get_test_lesson_grade_info',{
                "teacherid"  : teacherid,
                "subject"    : g_subject,
                "start_time" : g_start_time,
                "end_time"   : g_end_time
            },function(resp) {
                var userid_list = resp.data;
                $.each(userid_list,function(i,item){
                    var name     = item["name"];
                    var lesson     = item["lesson"]
                    var order     = item["order"];
                    var per  = item["per"];
                    html_node.find("table").append("<tr><td>"+name+"</td><td>"+lesson+"</td><td>"+order+"</td><td>"+per+"%</td></tr>");
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

            dlg.getModalDialog().css("width","1024px");
        }

    });
    $(".all_lesson").on("click",function(){
        var teacherid = $(this).data("teacherid");
       // console.log(g_subject);
        if(teacherid > 0){
            var title = "试听课详情";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>lessonid</td><td>时间</td><td>学生</td><td>年级</td><td>科目</td><tr></table></div>");

            $.do_ajax('/tongji_ss/get_test_lesson_history_info',{
                "teacherid"  : teacherid,
                "subject"    : g_subject,
                "start_time" : g_start_time,
                "end_time"   : g_end_time
            },function(resp) {
                var userid_list = resp.data;
                $.each(userid_list,function(i,item){
                    var lessonid = item["lessonid"];
                    var nick     = item["nick"]
                    var time     = item["lesson_start_str"];
                    var subject  = item["subject_str"];
                    var grade    = item["grade_str"];
                    html_node.find("table").append("<tr><td>"+lessonid+"</td><td>"+time+"</td><td>"+nick+"</td><td>"+grade+"</td><td>"+subject+"</td></tr>");
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

            dlg.getModalDialog().css("width","1024px");
        }
    });

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
                        ,draw : SVG(tmp_div[0]).size(me.width, me.height )
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
                            console.log( "ERROR : " +  item_data.opt_type );
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
        return ret;
    };

    $(".lesson_num").on("click",function(){
        var teacherid = $(this).data("teacherid");
        var teacher_subject = $(this).data("subject");
        if(teacherid > 0){
            var title     = "试听成功详情";
            var html_node = $("<div id=\"div_table\"><table class=\"table table-bordered \"><tr><td>lessonid</td><td>时间</td><td>学生</td><td>年级</td><td>科目</td><td>试听需求</td><td width=\"100px\">视频回放</td><td>咨询师回访记录</td><td>电话回访记录</td><td>合同</td><td width=\"120px\">签约失败说明</td><tr></table></div>");

            $.do_ajax('/tongji_ss/get_test_lesson_history_success_info',{
                "teacherid"  : teacherid,
                "subject"    : g_subject,
                "start_time" : g_start_time,
                "end_time"   : g_end_time
            },function(resp) {
                var userid_list = resp.data;
                $.each(userid_list,function(i,item){
                    var lessonid = item["lessonid"];
                    var nick     = item["nick"]
                    var time     = item["lesson_start_str"];
                    var subject  = item["subject_str"];
                    var grade    = item["grade_str"];
                    var rev      = item["rev"];
                    var call_rev      = item["call_rev"];
                    var html ="<tr>"
                        +"<td>"+lessonid+"</td>"
                        +"<td>"+time+"</td>"
                        +"<td>"+nick+"</td>"
                        +"<td>"+grade+"</td>"
                        +"<td>"+subject+"</td>"
                        +"<td>期待时间:"+item["stu_request_test_lesson_time"]+"<br>"
                        +"试听内容:"+item["stu_test_lesson_level_str"]+"<br>"
                        +"试听需求:"+item["stu_request_test_lesson_demand"]+"<br>"
                        +"教材:"+item["editionid_str"]+"<br>"
                        +"学生成绩:"+item["stu_score_info"]+"<br>"
                        +"学生性格:"+item["stu_character_info"]+"<br>"
                        +"试卷:"+item["stu_test_paper_flag_str"]+"</td>"
                        +"<td>"
                        +"<a class=\"url_video\"  data-nick=\""+nick+"\" data-lessonid=\""+lessonid+"\">点击回放</a><br><br><br>"
                        +"<a class=\"url_class\" data-subject=\""+item["subject"]+"\" data-time=\""+item["lesson_start"]+"\" data-grade=\""+item["grade"]+"\" data-url=\"http://admin.yb1v1.com/player/playback.html?draw="+encodeURIComponent(item["draw_url"])+"&audio="+encodeURIComponent(item["audio_url"])+"&start="+item["real_begin_time"]+"\">推荐视频</a><br><br><br>"
                        +"<a href=\"javascript:;\" class=\"add_record\" data-lessonid=\""+lessonid+"\" data-teacherid=\""+teacherid+"\">反馈</a><br><br><br>"
                        +"<a class=\"stu_test_paper\"  data-nick=\""+nick+"\" data-lessonid=\""+lessonid+"\">试卷下载</a>"
                        +"</td>"
                        +"<td>"+rev+"</td>"
                        +"<td>"+call_rev+"</td>"
                        +"<td>"+item["have_order"]+"</td>"
                        +"<td>"+item["fail_info"]+"</td>"
                        +"</tr>";
                    html_node.find("table").append(html);
                });

                html_node.find("table").find(".url_video").each(function(){
                    $(this).on("click",function(){
                        var nick = $(this).data("nick");

                        var lessonid = $(this).data("lessonid");
                        $.ajax({
                            type     : "post",
                            url      : "/tea_manage/get_lesson_reply",
                            dataType : "json",
                            data     : {"lessonid":lessonid},
                            success  : function(result){
                                var audio_url = result.audio_url;
                                var draw_url = result.draw_url;
                                var real_begin_time = result.real_begin_time;
                                
                                //alert(audio_url);
                                var w = $.check_in_phone()?329 : 558;
                                var h = w/4*3;
                                var html_node_ha = $("<div style=\"text-align:center;\"> "
                                                     +"<div id=\"drawing_list\" style=\"width:100%\">"
                                                     +"</div><audio preload=\"none\"></audio></div>"
                                                    );
                                BootstrapDialog.show({
                                    title    : '课程回放:lessonid:'+lessonid+", 学生:" + nick,
                                    message  : html_node_ha,
                                    closable : true,
                                    onhide   : function(dialogRef){
                                    }
                                });

                                Cwhiteboard = get_new_whiteboard(html_node_ha.find("#drawing_list"));
                                Cwhiteboard.loadData(w,h,real_begin_time,draw_url,audio_url,html_node_ha);

                            }
                        });

                        
                    });
                    
                });

                html_node.find("table").find(".stu_test_paper").each(function(){
                    $(this).on("click",function(){
                        var nick = $(this).data("nick");

                        var lessonid = $(this).data("lessonid");
                        $.do_ajax( '/ajax_deal2/get_stu_test_paper', {
                            "lessonid" :lessonid
                        },function(result){
                            if(result.ret==0){
                                window.open(result.data, '_blank'); 
                            }else{
                                BootstrapDialog.alert(result.info);
                            }
 
                        });
                  

                                              
                    });
                    
                });


                html_node.find("table").find(".audio_show").each(function(){
                    $(this).on("click",function(){
                        var url = $(this).data("url");
                        var load_wav_self_flag = $(this).data("flag");
                        if (load_wav_self_flag) {
                            var file=url.split("/")[4];
                            //get mp3
                            file=file.split(".")[0]+".mp3";
                            url= "/audio/"+file;
                        }

                        var html_node_audio = $(" <div  style=\"text-align:center;\"  > <div id=\"drawing_list\" style=\"width:100%\"  > </div>  <audio preload=\"none\"  > </audio> <br>  <a href=\""+url+"\" class=\"btn btn-primary \"  target=\"_blank\"> 下载 </a>  </div> ");

                        var audio_node   = html_node_audio.find("audio" );

                        BootstrapDialog.show({
                            title    : "录音:" ,
                            message  : html_node_audio,
                            closable : true,
                            onhide   : function(dialogRef){
                            },
                            onshown: function() {
                                //加载mp3
                                audiojs.events.ready(function(){

                                    var as = audiojs.createAll({}, audio_node  );
                                    //as[0].load( opt_data.record_url );
                                    as[0].load(url);
                                    as[0].play();
                                });
                            }

                        });
                    });
                });
                html_node.find("table").find(".url_class").each(function(){
                    $(this).on("click",function(){
                        var url = $(this).data("url");
                        var grade = $(this).data("grade");
                        var lesson_start = $(this).data("time");
                        var video_subject = $(this).data("subject");
                        console.log(grade);
                        var id_subject=$("<select/>");        
                        var id_grade_part_ex=$("<select/>");        
                        var id_identity=$("<select/>");        
                        var id_create_time=$("<select><option value=\"-1\">全部</option><option value=\"1\">入职一周</option><option value=\"2\">入职一个月</option></select>");
                        var id_tea_qua=$("<select><option value=\"-1\">全部</option><option value=\"1\">已冻结</option><option value=\"2\">已限课</option><option value=\"3\">已反馈</option></select>");
                        var id_tra=$("<select><option value=\"-1\">全部</option><option value=\"1\">高于25%</option><option value=\"2\">低于10%</option><option value=\"3\">10% - 25%</option></select>");
                        var id_send_reason = $("<textarea />");
                        var id_class_content = $("<textarea />");
                        var id_teacherid = $("<input />");

                        Enum_map.append_option_list("subject", id_subject);
                        Enum_map.append_option_list("grade_part_ex", id_grade_part_ex);
                        Enum_map.append_option_list("identity", id_identity);
                        var arr=[
                            ["老师科目", id_subject],
                            ["老师年级", id_grade_part_ex],
                            ["老师类型", id_identity],
                            ["入职情况", id_create_time],
                            ["教学质量", id_tea_qua],
                            ["转化率", id_tra],
                            ["推荐理由", id_send_reason],
                            ["上课内容", id_class_content],
                            ["老师（可不选）", id_teacherid]
                        ];
                        id_subject.val(teacher_subject);
                        $.show_key_value_table("请选择推荐的老师", arr ,{
                            label    : '确认',
                            cssClass : 'btn-warning',
                            action   : function(dialog) {
                                $.do_ajax( '/ss_deal/send_video_url_to_teacher', {
                                    "subject"                : id_subject.val(),
                                    "identity"               : id_identity.val(),
                                    "teacherid"              : teacherid,
                                    "url"                    : url,
                                    "create_time"            : id_create_time.val(),
                                    "tea_qua"                : id_tea_qua.val(),
                                    "tra"                    : id_tra.val(),
                                    "grade_part_ex"          : id_grade_part_ex.val(),
                                    "send_reason"            : id_send_reason.val(),
                                    "class_content"          : id_class_content.val(),
                                    "send_teacherid"         : id_teacherid.val(),
                                    "grade"                  : grade,
                                    "lesson_start"           : lesson_start,
                                    "video_subject"          : video_subject
                                });
                            }
                        },function(){
                            $.admin_select_user(id_teacherid,"teacher");
                        });


                    });
                    
                });
                
                html_node.find("table").find(".add_record").each(function(){
                    $(this).on("click",function(){
                        var lessonid = $(this).data("lessonid");
                        var lessonid_list = "["+lessonid+"]";
                        //alert(lessonid_list);
                        var teacherid = $(this).data("teacherid");
                        var id_jysj =  $("<select class=\"class_score\" />");
                        var id_yybd =  $("<select class=\"class_score\" />");
                        var id_zyzs =  $("<select class=\"class_score\" />");
                        var id_jxjz =  $("<select class=\"class_score\" />");
                        var id_hdqk =  $("<select class=\"class_score\" />");
                        var id_bsqk =  $("<select class=\"class_score\" />");
                        var id_rjcz =  $("<select class=\"class_score\" />");
                        var id_skhj =  $("<select class=\"class_score\" />");
                        var id_khfk =  $("<select class=\"class_score\" />");
                        var id_lcgf =  $("<select class=\"class_score\" />");                  
                        var id_sshd=$("<label><input name=\"Fruit\" type=\"checkbox\" value=\"1\" />自然型 </label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"2\" />逻辑型 </label><label><input name=\"Fruit\" type=\"checkbox\" value=\"4\" />技巧型 </label><label><input name=\"Fruit\" type=\"checkbox\" value=\"5\" />情感型 </label>");


                        Enum_map.append_option_list("teacher_lecture_score",id_jysj,true,[0,1,2,3,4,5,6,7,8,9,10]);
                        Enum_map.append_option_list("teacher_lecture_score",id_yybd,true,[0,1,2,3,4,5,6,7,8,9,10]);
                        Enum_map.append_option_list("teacher_lecture_score",id_zyzs,true,[0,1,2,3,4,5,6,7,8,9,10]);
                        Enum_map.append_option_list("teacher_lecture_score",id_jxjz,true,[0,1,2,3,4,5,6,7,8,9,10]);
                        Enum_map.append_option_list("teacher_lecture_score",id_hdqk,true,[0,1,2,3,4,5,6,7,8,9,10]);
                        Enum_map.append_option_list("teacher_lecture_score",id_bsqk,true,[0,1,2,3,4,5,6,7,8,9,10]);
                        Enum_map.append_option_list("teacher_lecture_score",id_rjcz,true,[0,1,2,3,4,5,6,7,8,9,10]);
                        Enum_map.append_option_list("teacher_lecture_score",id_skhj,true,[0,1,2,3,4,5]);
                        Enum_map.append_option_list("teacher_lecture_score",id_khfk,true,[0,1,2,3,4,5,6,7,8,9,10]);
                        Enum_map.append_option_list("test_lesson_score",id_lcgf,true,[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]);
                        var id_score = $("<input readonly/>");
                        var id_no_tea_score = $("<input readonly/>");
                        var id_record = $("<textarea />");
                        var id_jkqk = $("<textarea />");

                        var arr=[
                            ["讲义设计情况评分", id_jysj],
                            ["语言表达能力评分", id_yybd],
                            ["专业知识技能评分", id_zyzs],
                            ["教学节奏把握评分", id_jxjz],
                            ["互动情况评分", id_hdqk],
                            ["板书情况评分", id_bsqk],
                            ["软件操作评分", id_rjcz],
                            ["授课环境评分", id_skhj],
                            ["课后反馈评分", id_khfk],
                            ["流程规范情况评分", id_lcgf],
                            ["总分",id_score],
                            ["非教学相关得分",id_no_tea_score],
                            ["监课情况",id_jkqk],
                            ["意见或建议",id_record],
                            ["老师标签",id_sshd]
                        ];
                        
                        
                        $.show_key_value_table("试听评价", arr,{
                            label    : '确认',
                            cssClass : 'btn-warning',
                            action   : function(dialog) {
                                var record_info = id_record.val();
                                if(record_info==""){
                                    BootstrapDialog.alert("请填写评价内容!");
                                    return ;
                                }
                                console.log(record_info.length);
                                if(record_info.length>150){
                                    BootstrapDialog.alert("评价内容不能超过150字!");
                                    return ;
                                }

                                var sshd_good=[];
                                id_sshd.find("input:checkbox[name='Fruit']:checked").each(function(i) {
                                    sshd_good.push($(this).val());
                                });
                                if(sshd_good.length==0){
                                    BootstrapDialog.alert("请选择老师标签");
                                    return false;
                                }
                               

                                $.do_ajax("/human_resource/set_teacher_record_info_new",{
                                    "teacherid"    : teacherid,
                                    "type"         : 1,
                                    "tea_process_design_score"         : id_jysj.val(),
                                    "language_performance_score"         : id_yybd.val(),
                                    "knw_point_score"         : id_zyzs.val(),
                                    "tea_rhythm_score"         : id_jxjz.val(),
                                    "tea_concentration_score"         : id_hdqk.val(),
                                    "teacher_blackboard_writing_score"         : id_bsqk.val(),
                                    "tea_operation_score"         : id_rjcz.val(),
                                    "tea_environment_score"         : id_skhj.val(),
                                    "answer_question_cre_score"         : id_khfk.val(),
                                    "class_abnormality_score"         : id_lcgf.val(),
                                    "score"         : id_score.val(),
                                    "no_tea_related_score"                       : id_no_tea_score.val(),
                                    "record_info"                        : id_record.val(),
                                    "record_monitor_class"               : id_jkqk.val(),
                                    "record_lessonid_list"               :JSON.stringify(lessonid_list),
                                    "lessonid"                           :lessonid,
                                    "sshd_good"                          :JSON.stringify(sshd_good)                                    
                               });
                            }
                        },function(){
                            id_score.attr("placeholder","满分100分");
                            id_record.attr("placeholder","字数不能超过150字");
                        });

                        //console.log(arr[0][1]);
                        arr[0][1].parent().parent().parent().parent().parent().parent().parent().find(".class_score").on("change",function(){
                            id_score.val(parseInt(id_jysj.val())+parseInt(id_yybd.val())+parseInt(id_zyzs.val())+parseInt(id_jxjz.val())+parseInt(id_hdqk.val())+parseInt(id_bsqk.val())+parseInt(id_rjcz.val())+parseInt(id_skhj.val())+parseInt(id_khfk.val())+parseInt(id_lcgf.val()));
                            id_no_tea_score.val(parseInt(id_hdqk.val())+parseInt(id_bsqk.val())+parseInt(id_rjcz.val())+parseInt(id_skhj.val())+parseInt(id_khfk.val())+parseInt(id_lcgf.val()));

                            
                        });
                        
                    });
                    

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

            dlg.getModalDialog().css("width","1024px");

        }
        
    });
                           
    $.each( $(".teacher_lesson_count_total"), function(i,item ){
        var teacherid = $(this).data("teacherid");
        if(teacherid > 0){
            $(item).admin_select_teacher_free_time_new({
                "teacherid" : teacherid
            });
        }
    });

    if(tea_right==0){
        $(".opt-teacher-freeze").hide();
        $(".opt-limit-plan-lesson").hide();
        $(".opt-set-teacher-record-new").hide();
    }
                     


	$('.opt-change').set_input_change_event(load_data);
});






