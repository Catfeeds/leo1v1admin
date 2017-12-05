/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-train_lecture_lesson.d.ts" />

var Cwhiteboard=null;
var notify_cur_playpostion =null;

$(function(){
    function load_data(){
        $.reload_self_page ( {
			      date_type_config : $('#id_date_type_config').val(),
			      date_type        : $('#id_date_type').val(),
			      opt_date_type    : $('#id_opt_date_type').val(),
			      start_time       : $('#id_start_time').val(),
			      end_time         : $('#id_end_time').val(),
			      lesson_status    : $('#id_lesson_status').val(),
			      grade            : $('#id_grade').val(),
			      subject          : $('#id_subject').val(),
            identity         : $('#id_identity').val(),
			      check_status     : $('#id_check_status').val(),
			      train_teacherid  : $('#id_train_teacherid').val(),
            id_train_through_new_time:$("#id_train_through_new_time").val(),
            id_train_through_new:$("#id_train_through_new").val(),
			      have_wx          : $('#id_have_wx').val(),
			      lecture_status   : $('#id_lecture_status').val(),
			      train_email_flag : $('#id_train_email_flag').val(),
			      full_time        : $('#id_full_time').val(),
            teacherid        : $('#id_teacherid').val(),

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
        // console.log(ret);
        return ret;
    };


    Enum_map.append_option_list("lesson_status",$("#id_lesson_status"),false,[0,1,2]);
    Enum_map.append_option_list("subject",$("#id_subject"));
    Enum_map.append_option_list("grade",$("#id_grade"));
    Enum_map.append_option_list("identity",$("#id_identity"));
    Enum_map.append_option_list("boolean",$("#id_have_wx"));
    Enum_map.append_option_list("boolean",$("#id_train_email_flag"));
    Enum_map.append_option_list("boolean",$("#id_full_time"));
    Enum_map.append_option_list("check_status",$("#id_lecture_status"));
	  $('#id_lesson_status').val(g_args.lesson_status);
	  $('#id_full_time').val(g_args.full_time);
	  $('#id_grade').val(g_args.grade);
	  $('#id_subject').val(g_args.subject);
    $('#id_identity').val(g_args.identity);
	  $('#id_check_status').val(g_args.check_status);
	  $('#id_train_teacherid').val(g_args.train_teacherid);
	  $('#id_have_wx').val(g_args.have_wx);
	  $('#id_lecture_status').val(g_args.lecture_status);
	  $('#id_train_email_flag').val(g_args.train_email_flag);
    $("#id_train_through_new").val(g_args.id_train_through_new);
    $("#id_train_through_new_time").val(g_args.id_train_through_new_time);
    $.admin_select_user($("#id_train_teacherid"),"teacher",load_data);

	  $('#id_teacherid').val(g_args.teacherid);
    $.admin_select_user( $("#id_teacherid"), "teacher", load_data);


    $(".opt-edit").on("click",function(){
	      var data           = $(this).get_opt_data();
        var id_flag        = $("<select/>");
        var id_identity    = $("<select/>");
        var id_record_info = $("<textarea/>");
        var flag_html      = "<option value='0'>不通过</option>"
            +"<option value='1'>通过</option>"
            +"<option value='2'>老师未到</option>";
        Enum_map.append_option_list("identity",id_identity,true,[5,6,7,8]);
        id_flag.append(flag_html);

        var arr = [
            ["是否通过",id_flag],
            ["老师身份",id_identity],
            ["面试评价",id_record_info],
        ];

        id_record_info.val(data.record_info);
        id_identity.val(data.identity);
        id_flag.val(data.trial_train_status);

        $.show_key_value_table("面试评价",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/tea_manage/set_train_lecture_status",{
                    "teacherid"   : data.teacherid,
                    "lessonid"    : data.lessonid,
                    "phone"       : data.phone_spare,
                    "flag"        : id_flag.val(),
                    "record_info" : id_record_info.val(),
                    "grade"       : data.grade,
                    "subject"     : data.subject,
                    "nick"        : data.nick,
                    "account"     : data.account,
                    "identity"    : id_identity.val()
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                })
            }
        });
    });

    $(".opt-play").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var lessonid = opt_data.lessonid;

        $.ajax({
            type     : "post",
            url      : "/tea_manage/get_lesson_reply",
            dataType : "json",
            data     : {"lessonid":lessonid},
            success  : function(result){
                if(result.ret == 0){
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
                            title    : '面试视频回放',
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

    $(".opt-del").on("click",function(){
	    var opt_data = $(this).get_opt_data();
        console.log(opt_data.trial_train_status);
        BootstrapDialog.show({
	        title   : "取消课程",
	        message : "确定取消该课程？",
	        buttons : [{
		        label  : "返回",
		        action : function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label    : "确认",
		        cssClass : "btn-warning",
		        action   : function(dialog) {
                    $.do_ajax("/tea_manage_new/cancel_train_lesson",{
                        "lessonid"           : opt_data.lessonid,
                        "trial_train_status" : opt_data.trial_train_status
                    },function(result){
                        if(result.ret==0){
                            window.location.reload();
                        }else{
                            BootstrapDialog.alert(result.info);
                        }
                    })
		        }
	        }]
        });
    });

    $(".opt-email").on("click",function(){
	    var opt_data = $(this).get_opt_data();
        BootstrapDialog.show({
	        title   : "补发邮件",
	        message : "确定要补发邮件？",
	        buttons : [{
		        label  : "返回",
		        action : function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label    : "确认",
		        cssClass : "btn-warning",
		        action   : function(dialog) {
                    $.do_ajax("/tea_manage_new/send_train_lesson_email",{
                        "phone"      : opt_data.phone_spare,
                        "realname" : opt_data.nick,
                        "lesson_time":opt_data.lesson_time,
                        "lessonid" :opt_data.lessonid
                    },function(result){
                        if(result.ret==0){
                            window.location.reload();
                        }else{
                            BootstrapDialog.alert(result.info);
                        }
                    })
		        }
	        }]
        });
    });




    var init_noit_btn=function( id_name, title) {
        var btn=$('#'+id_name);
        btn.tooltip({
            "title":title,
            "html":true
        });
        var value =btn.text();
        if (value) {
            btn.addClass("btn-warning");
        }
    };


    var init_field_list=function() {
        $('#id_train_teacherid').val(-1);
        $('#id_lesson_status').val(-1);
        $('#id_subject').val(-1);
        $('#id_grade').val(-1);
        $('#id_check_status').val(-2);
        $('#id_lecture_status').val(-1);
        $('#id_have_wx').val(-1);
        $('#id_id_train_email_flag').val(-1);
    }


    init_noit_btn("id_have_wx_flag",    "微信绑定比例" );

    $("#id_have_wx_flag").on("click",function(){
        init_field_list();

        $('#id_have_wx').val(1);

        load_data();
    });
    init_noit_btn("id_send_email_flag",    "邮件发送比例" );

    $("#id_send_email_flag").on("click",function(){
        init_field_list();

        $('#id_train_email_flag').val(1);

        load_data();
    });

    $(".opt-edit-new").on("click",function(){
        var data           = $(this).get_opt_data();
        var id_lecture_out=$("<label><input name=\"lecture_out\" type=\"checkbox\" value=\"3\" />语速过慢/过快 </label> <label><input name=\"lecture_out\" type=\"checkbox\" value=\"4\" />语调沉闷 </label> <label><input name=\"lecture_out\" type=\"checkbox\" value=\"5\" />节奏拖沓 </label><label><input name=\"lecture_out\" type=\"checkbox\" value=\"6\" />枯燥乏味 </label> <label><input name=\"lecture_out\" type=\"checkbox\" value=\"8\" />解题错误</label><label><input name=\"lecture_out\" type=\"checkbox\" value=\"9\" />普通话发音不标准</label><label><input name=\"lecture_out\" type=\"checkbox\" value=\"10\" />英文发音不标准</label><label><input name=\"lecture_out\" type=\"checkbox\" value=\"100\" />其他</label>");
        var id_reason_all = $("<textarea/>");

        var arr = [
            ["未通过",id_lecture_out],
            ["原因/建议",id_reason_all]
        ];
        $.show_key_value_table("审核-new",arr,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                var lecture_out_list=[];
                id_lecture_out.find("input:checkbox[name='lecture_out']:checked").each(function(i) {
                    lecture_out_list.push($(this).val());
                });  
                if(lecture_out_list.length==0){
                    var id_lecture_content_design_score  =  $("<select class=\"class_score\" />");
                    var id_lecture_combined_score =  $("<select class=\"class_score\" />");
                    var id_teacher_point_explanation_score =  $("<select class=\"class_score\" />");
                    var id_teacher_dif_point_score =  $("<select class=\"class_score\" />");
                    var id_course_review_score  =  $("<select class=\"class_score\" />");
                    var id_teacher_mental_aura_score =  $("<select class=\"class_score\" />");
                    var id_teacher_class_atm_score  =  $("<select class=\"class_score\" />");
                    var id_teacher_explain_rhythm_score =  $("<select class=\"class_score\" />");
                    var id_teacher_blackboard_writing_score  =  $("<select class=\"class_score\" />");
                    var id_teacher_language_performance_score  =  $("<select class=\"class_score\" />");

                    var id_sshd=$("<label><input name=\"Fruit\" type=\"checkbox\" value=\"6\" />幽默风趣 </label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"7\" />生动活泼 </label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"8\" />循循善诱 </label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"9\" />细致耐心 </label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"10\" />考纲熟悉 </label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"11\" />善于互动</label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"12\" />没有口音</label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"13\" />经验丰富</label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"14\" />功底扎实</label> ");
                    var id_reason = $("<textarea/>");
                    var id_total_score = $("<input readonly /> ");
                    var id_res         = $("<select/>");
                    var flag_html      = "<option value='0'>不通过</option>"
                                        +"<option value='1'>通过</option>"
                                        +"<option value='2'>老师未到</option>";
                    id_res.append(flag_html);
                    var id_identity      = $("<select/>");
                    var id_work_year     = $("<input />");
                    var id_not_grade     = $("<div />");
                    var id_flag = $("<input />");
                    Enum_map.append_option_list("teacher_lecture_score",id_lecture_content_design_score,true,[0,1,2,3,4,5,6,7,8,9,10]);
                    Enum_map.append_option_list("teacher_lecture_score",id_lecture_combined_score,true,[0,1,2,3,4,5,6,7,8,9,10]);
                    Enum_map.append_option_list("teacher_lecture_score",id_teacher_point_explanation_score,true,[0,1,2,3,4,5,6,7,8,9,10]);
                    Enum_map.append_option_list("teacher_lecture_score",id_teacher_dif_point_score,true,[0,1,2,3,4,5,6,7,8,9,10]);
                    Enum_map.append_option_list("teacher_lecture_score",id_course_review_score,true,[0,1,2,3,4,5,6,7,8,9,10]);
                    Enum_map.append_option_list("teacher_lecture_score",id_teacher_mental_aura_score,true,[0,1,2,3,4,5,6,7,8,9,10]);
                    Enum_map.append_option_list("teacher_lecture_score",id_teacher_class_atm_score,true,[0,1,2,3,4,5,6,7,8,9,10]);
                    Enum_map.append_option_list("teacher_lecture_score",id_teacher_explain_rhythm_score,true,[0,1,2,3,4,5,6,7,8,9,10]);
                    Enum_map.append_option_list("teacher_lecture_score",id_teacher_blackboard_writing_score,true,[0,1,2,3,4,5,6,7,8,9,10]);
                    Enum_map.append_option_list("teacher_lecture_score",id_teacher_language_performance_score,true,[0,1,2,3,4,5,6,7,8,9,10]);
                    Enum_map.append_option_list("identity",id_identity,false,[5,6,7,8]);

                    var not_grade         = data.not_grade;
                    var grade_start       = data.grade_start;
                    var grade_end         = data.grade_end;
                    var trans_grade_start = data.trans_grade_start;
                    var trans_grade_end   = data.trans_grade_end;
                    var trans_grade       = data.trans_grade;
                    var identity          = data.teacher_type;
                    if(trans_grade>0){
                        grade_start = trans_grade_start;
                        grade_end   = trans_grade_end;
                    }

                    var id_not_grade_list = ["101","102","103","104","105","106","201","202","203","301","302","303"];
                    Enum_map.append_checkbox_list("grade",id_not_grade,"not_grade",id_not_grade_list);

                    var not_grade_arr=check_data_to_arr(not_grade,",");
                    id_identity.val(identity);

                    var arr = [
                        ["讲义内容设计", id_lecture_content_design_score],
                        ["讲练结合情况", id_lecture_combined_score],
                        ["知识点正确率", id_teacher_point_explanation_score],
                        ["重难点偏向性", id_teacher_dif_point_score],
                        ["课程回顾总结", id_course_review_score],
                        ["教师气场把控", id_teacher_mental_aura_score],
                        ["课堂氛围营造", id_teacher_class_atm_score],
                        ["教学节奏把握", id_teacher_explain_rhythm_score],
                        ["板书书写规范", id_teacher_blackboard_writing_score],
                        ["语言表达能力", id_teacher_language_performance_score],
                        ["总分",id_total_score],
                        ["结果",id_res],
                        ["原因或意见或建议",id_reason],
                        ["老师身份",id_identity],
                        ["工作年限",id_work_year],
                        ["禁止年级",id_not_grade],
                        ["老师标签",id_sshd]
                    ];
                    $.show_key_value_table("试听评价", arr,{
                        label    : '确认',
                        cssClass : 'btn-warning',
                        action   : function(dialog) {
                            var record_info = id_reason.val();
                            if(record_info==""){
                                BootstrapDialog.alert("请填写原因或意见或建议!");
                                return ;
                            }

                            var sshd_good=[];
                            id_sshd.find("input:checkbox[name='Fruit']:checked").each(function(i) {
                                sshd_good.push($(this).val());
                            });
                            var not_grade = "";
                            $("input[name='not_grade']:checked").each(function(){
                                if(not_grade==""){
                                    not_grade = $(this).val();
                                }else{
                                    not_grade += ","+$(this).val();
                                }
                            });

                            $.do_ajax("/tea_manage/set_train_lecture_status_b2",{
                                "teacherid"   : data.teacherid,
                                "lessonid"    : data.lessonid,
                                "phone"       : data.phone_spare,
                                "flag"        : id_res.val(),
                                "record_info" : id_reason.val(),
                                "grade"       : data.grade,
                                "subject"     : data.subject,
                                "nick"        : data.nick,
                                "account"     : data.account,
                                "lecture_combined_score"             : id_lecture_combined_score.val(),
                                "lecture_content_design_score"       : id_lecture_content_design_score.val(),
                                "teacher_language_performance_score" : id_teacher_language_performance_score.val(),
                                "teacher_explain_rhythm_score"       : id_teacher_explain_rhythm_score.val(),
                                "teacher_point_explanation_score"    : id_teacher_point_explanation_score.val(),
                                "course_review_score"                : id_course_review_score.val(),
                                "teacher_mental_aura_score"          : id_teacher_mental_aura_score.val(),
                                "teacher_dif_point_score"            : id_teacher_dif_point_score.val(),
                                "teacher_class_atm_score"            : id_teacher_class_atm_score.val(),
                                "teacher_blackboard_writing_score"   : id_teacher_blackboard_writing_score.val(),
                                "total_score"                        : id_total_score.val(),
                                "identity"                           : id_identity.val(),
                               // "subject"                            : data.subject,
                               // "grade"                              : data.grade,
                                "not_grade"                          : not_grade,
                                "work_year"                          : id_work_year.val(),
                               // "not_grade"                          : not_grade,
                                "sshd_good"                          : JSON.stringify(sshd_good),
                            },function(result){
                                if(result.ret==-1){
                                    BootstrapDialog.alert(result.info);
                                }else{
                                    window.location.reload();
                                }
                            });
                        }
                    },function(){
                        id_total_score.attr("placeholder","满分100分");
                        var check_not="";
                        if(not_grade_arr[0]){
                            $.each(not_grade_arr,function(k,v){
                                $("input[name='not_grade']").each(function(){
                                    check_not=$(this).val();
                                    if(check_not==v){
                                        $(this).attr("checked","true");
                                    }
                                });
                            });
                        }

                    });

                    //console.log(arr[0][1]);
                    arr[0][1].parent().parent().parent().parent().parent().parent().parent().find(".class_score").on("change",function(){
                        id_total_score.val(parseInt(id_lecture_combined_score.val())+parseInt( id_lecture_content_design_score.val())+parseInt(id_teacher_language_performance_score.val())+parseInt(id_teacher_explain_rhythm_score.val())+parseInt(id_teacher_point_explanation_score.val())+parseInt(id_course_review_score.val())+parseInt(id_teacher_dif_point_score.val())+parseInt(id_teacher_mental_aura_score.val())+parseInt(id_teacher_class_atm_score.val())+parseInt(id_teacher_blackboard_writing_score.val()));
                        if(id_total_score.val() <60){
                            id_res.val(0);
                        }else{
                            id_res.val(1);
                        }

                    });
                    arr[0][1].parent().parent().parent().parent().parent().parent().parent().parent().css("width",970);
                    arr[0][1].parent().parent().parent().parent().parent().parent().parent().parent().css("left",-200);

                }else{
                    $.do_ajax("/tea_manage/set_train_lecture_status_b1",{
                        "teacherid"   : data.teacherid,
                        "lessonid"    : data.lessonid,
                        "phone"       : data.phone_spare,
                        "flag"        : 0,
                        "record_info" : id_reason_all.val(),
                        "grade"       : data.grade,
                        "subject"     : data.subject,
                        "nick"        : data.nick,
                        "account"     : data.account,
                        "identity"    : data.identity,
                        "lecture_out_list":JSON.stringify(lecture_out_list),
                    });
                }
            }
        });
    });

    $(".opt-edit-pass").on("click",function(){
        var data           = $(this).get_opt_data();
        $.do_ajax('/ajax_deal2/get_teacher_tag_info',{
        },function(resp) {
            var list = resp.data;
            var teacher_related_labels=$("<div><div class=\"col-xs-6 col-md-3\">风格性格:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"style_character\"></div><div class=\"col-xs-6 col-md-3\">专业能力:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"professional_ability\"> </div><div>");
            var class_related_labels=$("<div><div class=\"col-xs-6 col-md-3\">课堂氛围:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"classroom_atmosphere\"></div><div class=\"col-xs-6 col-md-3\">课件要求:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"courseware_requirements\"> </div><div>");
            var teaching_related_labels=$("<div><div class=\"col-xs-6 col-md-3\">素质培养:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"diathesis_cultivation\"></div>");

            $.each(list,function(i,item){
                var str="";
                $.each(item,function(ii,item_p){
                    console.log(item_p);
                    str += "<label><input name=\""+i+"\" type=\"checkbox\" value=\""+item_p+"\" /> "+item_p+"</label>";
                });
                if(i=="风格性格"){
                    teacher_related_labels.find("#style_character").append(str);
                }else if(i=="专业能力"){
                    teacher_related_labels.find("#professional_ability").append(str);
                }else if(i=="课堂氛围"){
                    class_related_labels.find("#classroom_atmosphere").append(str);
                }else if(i=="课件要求"){
                    class_related_labels.find("#courseware_requirements").append(str);
                }else if(i=="素质培养"){
                    teaching_related_labels.find("#diathesis_cultivation").append(str);
                }
            });



            var id_lecture_content_design_score  =  $("<select class=\"class_score\" />");
            var id_lecture_combined_score =  $("<select class=\"class_score\" />");
            var id_teacher_point_explanation_score =  $("<select class=\"class_score\" />");
            var id_teacher_dif_point_score =  $("<select class=\"class_score\" />");
            var id_course_review_score  =  $("<select class=\"class_score\" />");
            var id_teacher_mental_aura_score =  $("<select class=\"class_score\" />");
            var id_teacher_class_atm_score  =  $("<select class=\"class_score\" />");
            var id_teacher_explain_rhythm_score =  $("<select class=\"class_score\" />");
            var id_teacher_blackboard_writing_score  =  $("<select class=\"class_score\" />");
            var id_teacher_language_performance_score  =  $("<select class=\"class_score\" />");

            var id_sshd=$("<label><input name=\"Fruit\" type=\"checkbox\" value=\"6\" />幽默风趣 </label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"7\" />生动活泼 </label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"8\" />循循善诱 </label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"9\" />细致耐心 </label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"10\" />考纲熟悉 </label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"11\" />善于互动</label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"12\" />没有口音</label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"13\" />经验丰富</label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"14\" />功底扎实</label> ");
            var id_reason = $("<textarea/>");
            var id_total_score = $("<input readonly /> ");
            var id_res         = $("<select/>");
            var flag_html      = "<option value='0'>不通过</option>"
                +"<option value='1'>通过</option>"
                +"<option value='2'>老师未到</option>";
            id_res.append(flag_html);
            var id_identity      = $("<select/>");
            var id_work_year     = $("<input />");
            var id_not_grade     = $("<div />");
            var id_flag = $("<input />");
            Enum_map.append_option_list("teacher_lecture_score",id_lecture_content_design_score,true,[0,1,2,3,4,5,6,7,8,9,10]);
            Enum_map.append_option_list("teacher_lecture_score",id_lecture_combined_score,true,[0,1,2,3,4,5,6,7,8,9,10]);
            Enum_map.append_option_list("teacher_lecture_score",id_teacher_point_explanation_score,true,[0,1,2,3,4,5,6,7,8,9,10]);
            Enum_map.append_option_list("teacher_lecture_score",id_teacher_dif_point_score,true,[0,1,2,3,4,5,6,7,8,9,10]);
            Enum_map.append_option_list("teacher_lecture_score",id_course_review_score,true,[0,1,2,3,4,5,6,7,8,9,10]);
            Enum_map.append_option_list("teacher_lecture_score",id_teacher_mental_aura_score,true,[0,1,2,3,4,5,6,7,8,9,10]);
            Enum_map.append_option_list("teacher_lecture_score",id_teacher_class_atm_score,true,[0,1,2,3,4,5,6,7,8,9,10]);
            Enum_map.append_option_list("teacher_lecture_score",id_teacher_explain_rhythm_score,true,[0,1,2,3,4,5,6,7,8,9,10]);
            Enum_map.append_option_list("teacher_lecture_score",id_teacher_blackboard_writing_score,true,[0,1,2,3,4,5,6,7,8,9,10]);
            Enum_map.append_option_list("teacher_lecture_score",id_teacher_language_performance_score,true,[0,1,2,3,4,5,6,7,8,9,10]);
            Enum_map.append_option_list("identity",id_identity,false,[5,6,7,8]);

            var not_grade         = data.not_grade;
            var grade_start       = data.grade_start;
            var grade_end         = data.grade_end;
            var trans_grade_start = data.trans_grade_start;
            var trans_grade_end   = data.trans_grade_end;
            var trans_grade       = data.trans_grade;
            var identity          = data.teacher_type;
            if(trans_grade>0){
                grade_start = trans_grade_start;
                grade_end   = trans_grade_end;
            }

            var id_not_grade_list = ["101","102","103","104","105","106","201","202","203","301","302","303"];
            Enum_map.append_checkbox_list("grade",id_not_grade,"not_grade",id_not_grade_list);

            var not_grade_arr=check_data_to_arr(not_grade,",");
            id_identity.val(identity);

            var arr = [
                ["讲义内容设计", id_lecture_content_design_score],
                ["讲练结合情况", id_lecture_combined_score],
                ["知识点正确率", id_teacher_point_explanation_score],
                ["重难点偏向性", id_teacher_dif_point_score],
                ["课程回顾总结", id_course_review_score],
                ["教师气场把控", id_teacher_mental_aura_score],
                ["课堂氛围营造", id_teacher_class_atm_score],
                ["教学节奏把握", id_teacher_explain_rhythm_score],
                ["板书书写规范", id_teacher_blackboard_writing_score],
                ["语言表达能力", id_teacher_language_performance_score],
                ["总分",id_total_score],
                ["结果",id_res],
                ["原因或意见或建议",id_reason],
                ["老师身份",id_identity],
                ["工作年限",id_work_year],
                ["禁止年级",id_not_grade],
                ["教师相关标签",teacher_related_labels],
                ["课堂相关标签",class_related_labels],
                ["教学相关标签",teaching_related_labels],
            ];
            $.show_key_value_table("试听评价", arr,{
                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog) {
                    var style_character=[];
                    teacher_related_labels.find("#style_character").find("input:checkbox[name='风格性格']:checked").each(function(i) {
                        style_character.push($(this).val());
                    });
                    var professional_ability=[];
                    teacher_related_labels.find("#professional_ability").find("input:checkbox[name='专业能力']:checked").each(function(i) {
                        professional_ability.push($(this).val());
                    });
                    var classroom_atmosphere=[];
                    class_related_labels.find("#classroom_atmosphere").find("input:checkbox[name='课堂氛围']:checked").each(function(i) {
                        classroom_atmosphere.push($(this).val());
                    });
                    var courseware_requirements=[];
                    class_related_labels.find("#courseware_requirements").find("input:checkbox[name='课件要求']:checked").each(function(i) {
                        courseware_requirements.push($(this).val());
                    });
                    var diathesis_cultivation=[];
                    teaching_related_labels.find("#diathesis_cultivation").find("input:checkbox[name='素质培养']:checked").each(function(i) {
                        diathesis_cultivation.push($(this).val());
                    });
                    if(courseware_requirements.length ==0 || style_character.length==0 || professional_ability.length==0 || classroom_atmosphere.length==0 || diathesis_cultivation.length==0){
                        BootstrapDialog.alert("请填写标签内容");
                        return ;

                    }

                    var record_info = id_reason.val();
                    if(record_info==""){
                        BootstrapDialog.alert("请填写原因或意见或建议!");
                        return ;
                    }

                    var not_grade = "";
                    $("input[name='not_grade']:checked").each(function(){
                        if(not_grade==""){
                            not_grade = $(this).val();
                        }else{
                            not_grade += ","+$(this).val();
                        }
                    });

                    $.do_ajax("/tea_manage/set_train_lecture_status_b2",{
                        "teacherid"   : data.teacherid,
                        "lessonid"    : data.lessonid,
                        "phone"       : data.phone_spare,
                        "flag"        : id_res.val(),
                        "record_info" : id_reason.val(),
                        "grade"       : data.grade,
                        "subject"     : data.subject,
                        "nick"        : data.nick,
                        "account"     : data.account,
                        "lecture_combined_score"             : id_lecture_combined_score.val(),
                        "lecture_content_design_score"       : id_lecture_content_design_score.val(),
                        "teacher_language_performance_score" : id_teacher_language_performance_score.val(),
                        "teacher_explain_rhythm_score"       : id_teacher_explain_rhythm_score.val(),
                        "teacher_point_explanation_score"    : id_teacher_point_explanation_score.val(),
                        "course_review_score"                : id_course_review_score.val(),
                        "teacher_mental_aura_score"          : id_teacher_mental_aura_score.val(),
                        "teacher_dif_point_score"            : id_teacher_dif_point_score.val(),
                        "teacher_class_atm_score"            : id_teacher_class_atm_score.val(),
                        "teacher_blackboard_writing_score"   : id_teacher_blackboard_writing_score.val(),
                        "total_score"                        : id_total_score.val(),
                        "identity"                           : id_identity.val(),
                        // "subject"                            : data.subject,
                        // "grade"                              : data.grade,
                        "not_grade"                          : not_grade,
                        "work_year"                          : id_work_year.val(),
                        "style_character"                  : JSON.stringify(style_character),
                        "professional_ability"             : JSON.stringify(professional_ability),
                        "classroom_atmosphere"             : JSON.stringify(classroom_atmosphere),
                        "courseware_requirements"          : JSON.stringify(courseware_requirements),
                        "diathesis_cultivation"            : JSON.stringify(diathesis_cultivation),
                        "new_tag_flag" : 1
                        // "not_grade"                          : not_grade,
                       // "sshd_good"                          : JSON.stringify(sshd_good),
                    },function(result){
                        if(result.ret==-1){
                            BootstrapDialog.alert(result.info);
                        }else{
                            window.location.reload();
                        }
                    });
                }
            },function(){
                id_total_score.attr("placeholder","满分100分");
                var check_not="";
                if(not_grade_arr[0]){
                    $.each(not_grade_arr,function(k,v){
                        $("input[name='not_grade']").each(function(){
                            check_not=$(this).val();
                            if(check_not==v){
                                $(this).attr("checked","true");
                            }
                        });
                    });
                }

            });

            //console.log(arr[0][1]);
            arr[0][1].parent().parent().parent().parent().parent().parent().parent().find(".class_score").on("change",function(){
                id_total_score.val(parseInt(id_lecture_combined_score.val())+parseInt( id_lecture_content_design_score.val())+parseInt(id_teacher_language_performance_score.val())+parseInt(id_teacher_explain_rhythm_score.val())+parseInt(id_teacher_point_explanation_score.val())+parseInt(id_course_review_score.val())+parseInt(id_teacher_dif_point_score.val())+parseInt(id_teacher_mental_aura_score.val())+parseInt(id_teacher_class_atm_score.val())+parseInt(id_teacher_blackboard_writing_score.val()));
                if(id_total_score.val() <60){
                    id_res.val(0);
                }else{
                    id_res.val(1);
                }

            });
            arr[0][1].parent().parent().parent().parent().parent().parent().parent().parent().css("width",970);
            arr[0][1].parent().parent().parent().parent().parent().parent().parent().parent().css("left",-200);

        });

    });


    $(".opt-edit-no-pass").on("click",function(){
        var data           = $(this).get_opt_data();
        var id_lecture_out=$("<label><input name=\"lecture_out\" type=\"checkbox\" value=\"3\" />语速过慢/过快 </label> <label><input name=\"lecture_out\" type=\"checkbox\" value=\"4\" />语调沉闷 </label> <label><input name=\"lecture_out\" type=\"checkbox\" value=\"5\" />节奏拖沓 </label><label><input name=\"lecture_out\" type=\"checkbox\" value=\"6\" />枯燥乏味 </label> <label><input name=\"lecture_out\" type=\"checkbox\" value=\"8\" />解题错误</label><label><input name=\"lecture_out\" type=\"checkbox\" value=\"9\" />普通话发音不标准</label><label><input name=\"lecture_out\" type=\"checkbox\" value=\"10\" />英文发音不标准</label><label><input name=\"lecture_out\" type=\"checkbox\" value=\"100\" />其他</label>");
        var id_reason_all = $("<textarea/>");

        var arr = [
            ["未通过",id_lecture_out],
            ["原因/建议",id_reason_all]
        ];
        $.show_key_value_table("审核-new",arr,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                var lecture_out_list=[];
                id_lecture_out.find("input:checkbox[name='lecture_out']:checked").each(function(i) {
                    lecture_out_list.push($(this).val());
                });  
                
                if(lecture_out_list.length==0 || id_reason_all.val()==""){
                    BootstrapDialog.alert("请填写数据");
                    return;

                }else{
                    $.do_ajax("/tea_manage/set_train_lecture_status_b1",{
                        "teacherid"   : data.teacherid,
                        "lessonid"    : data.lessonid,
                        "phone"       : data.phone_spare,
                        "flag"        : 0,
                        "record_info" : id_reason_all.val(),
                        "grade"       : data.grade,
                        "subject"     : data.subject,
                        "nick"        : data.nick,
                        "account"     : data.account,
                        "identity"    : data.identity,
                        "lecture_out_list":JSON.stringify(lecture_out_list),
                    });
                }
            }
        });
    });


    $(".opt-test").on("click",function(){
        var opt_data = $(this).get_opt_data();
        console.log(opt_data.tt_train_type);
        console.log(opt_data.tt_train_lessonid);
        console.log(opt_data.tt_id);
        console.log(opt_data.tt_add_time);
    });

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
                    var html_node = $.dlg_need_html_by_id("id_dlg_set_server");

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

    $(".opt-resume_url").on("click",function(){
        var url=$(this).get_opt_data("resume_url");
        window.open(url, '_blank');
    });

    $(".show_phone").on("click",function(){
        var val = $(this).data("phone");
        BootstrapDialog.alert({
            title: "数据",
            message:val ,
            closable: true,
            callback: function(){
                
            }
        });

    });

    if (window.location.pathname=="/tea_manage/train_lecture_lesson_zs" || window.location.pathname=="/tea_manage/train_lecture_lesson_zs/") {
        download_hide();
    }



   	$('.opt-change').set_input_change_event(load_data);
});
