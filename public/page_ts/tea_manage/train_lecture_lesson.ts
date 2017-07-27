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
			check_status     : $('#id_check_status').val(),
			train_teacherid  : $('#id_train_teacherid').val(),
			have_wx:	$('#id_have_wx').val(),
			lecture_status:	$('#id_lecture_status').val(),
			train_email_flag:	$('#id_train_email_flag').val()
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
    Enum_map.append_option_list("boolean",$("#id_have_wx"));
    Enum_map.append_option_list("boolean",$("#id_train_email_flag"));
    Enum_map.append_option_list("check_status",$("#id_lecture_status"));
	$('#id_lesson_status').val(g_args.lesson_status);
	$('#id_grade').val(g_args.grade);
	$('#id_subject').val(g_args.subject);
	$('#id_check_status').val(g_args.check_status);
	$('#id_train_teacherid').val(g_args.train_teacherid);
	$('#id_have_wx').val(g_args.have_wx);
	$('#id_lecture_status').val(g_args.lecture_status);
	$('#id_train_email_flag').val(g_args.train_email_flag);
    $.admin_select_user($("#id_train_teacherid"),"teacher",load_data);

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
                        "lessonid"      : opt_data.lessonid,
                        "lesson_status" : opt_data.lesson_status
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


  
   	$('.opt-change').set_input_change_event(load_data);
});

