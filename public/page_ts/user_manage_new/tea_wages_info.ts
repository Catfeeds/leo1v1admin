/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-tea_wages_info.d.ts" />

$(function(){
    var notify_cur_playpostion =null;
    function load_data(){
        $.reload_self_page ( {
			      date_type_config : $('#id_date_type_config').val(),
			      date_type        : $('#id_date_type').val(),
			      opt_date_type    : $('#id_opt_date_type').val(),
			      start_time       : $('#id_start_time').val(),
			      end_time         : $('#id_end_time').val(),
			      teacherid        : $('#id_teacherid').val(),
			      studentid        : $('#id_studentid').val(),
			      show_type        : $('#id_show_type').val(),
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

	  $('#id_teacherid').val(g_args.teacherid);
	  $('#id_studentid').val(g_args.studentid);
	  $('#id_show_type').val(g_args.show_type);
	  $('.opt-change').set_input_change_event(load_data);
    $('#id_teacherid').val(g_args.teacherid);
    $('#id_studentid').val(g_args.studentid);

    $(".opt-goto-lesson").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/tea_manage/lesson_list?lessonid=" + opt_data.lessonid);
    });

    var link_css = {
        color  : "#3c8dbc",
        cursor : "pointer"
    };

    $(".l-1 .key1").css(link_css);
    $(".l-2 .key2").css(link_css);
    $(".l-1 .key1").on("click",function(){
        var $this      = $(this);
        var class_name = $this.data("class_name");
        if ($this.data("show") ==true) {
            $(".key2."+class_name ).parent().hide();
        }else{
            var $opt_item=$(".key2."+class_name ).parent(".l-2");
            $opt_item.show();
        }
        $this.parent().show();
        $this.data("show", !$this.data("show") );
    });

    $(".l-2 .key2").on("click",function(){
        var $this      = $(this);
        var class_name = $this.data("class_name");
        if ($this.data("show") ==true) {
            $(".key3."+class_name ).parent().hide();
        }else{
            var $opt_item=$(".key3."+class_name ).parent(".l-3");
            $opt_item.show();
        }
        $this.parent().show();
        $this.data("show", !$this.data("show") );
    });

    $(".l-3 .key3").on("click",function(){
        var $this      = $(this);
        var class_name = $this.data("class_name");
        if ($this.data("show") ==true) {
            $(".key4."+class_name ).parent().hide();
        }else{
            var $opt_item=$(".key4."+class_name ).parent(".l-4");
            $opt_item.show();
        }
        $this.parent().show();
        $this.data("show", !$this.data("show") );
    });

	//时间控件
	$('#id_start_time').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
	    onChangeDateTime :function(){
		    load_data();
        }
	});
    
	$('#id_end_time').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
		onChangeDateTime :function(){
		    load_data();
        }
	});
    
	$(".opt-change").on("change",function(){
		load_data();
	});

    $.admin_select_user( $("#id_teacherid"), "teacher",  load_data, true) ;
    $.admin_select_user( $("#id_studentid"), "student",  load_data, false) ;

    $("#id_reset_already_lesson_count").on("click",function(){
        $.do_ajax("/user_deal/reset_already_lesson_count",{
            "teacherid"  : $("#id_teacherid").val(),
            "start_time" : $("#id_start_time").val(),
            "end_time"   : $("#id_end_time").val()
        });
    });

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
            }, "play_next_back" : function (cur_play_time) {
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
            },"play_next" :  function( cur_play_time){
                var me = this;
                //前进处理
                var front_flag = me.play_next_front(cur_play_time);
                if (!front_flag){
                    me.play_next_back(cur_play_time);
                }
            },"show_page" : function( pageid ){
                console.log("PAGEID : "+pageid);
                var me              = this;
                if ( me.play_pageid != pageid ){
                    var div_id = "drawing_"+ pageid ;
                    me.obj_drawing_list.find("div").hide();
                    me.obj_drawing_list.find("#"+div_id ).show();
                    me.play_pageid = pageid;
                }
            },"loadData" : function(  w, h, lession_start_time , xml_file, mp3_file,html_node ){
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


    $(".opt-play").on("click", function(){
        var opt_data=$(this).get_opt_data();
        var lessonid = opt_data.lessonid; 
        $.ajax({
			type     : "post",
			url      : "/tea_manage/get_lesson_reply",
			dataType : "json",
			data     : {"lessonid":lessonid},
			success  : function(result){
				if(result.ret == 0 ){
                    //加载数据
                    
                    var w=$.check_in_phone()?329 : 558;
                    var h=w/4*3;


                    var html_node = $(" <div  style=\"text-align:center;\"  > <div id=\"drawing_list\" style=\"width:100%\"  > </div>  <audio preload=\"none\"  > </audio> </div> ");
                    BootstrapDialog.show({
                        title    : '课程回放:lessonid:'+opt_data.lessonid+", 学生:" + opt_data.stu_nick ,
                        message  : html_node,
                        closable : true, 
                        onhide   : function(dialogRef){
                        }
                    }); 

                    Cwhiteboard=get_new_whiteboard(html_node.find("#drawing_list"));

                    //audio_url "http://7tszue.com2.z0.glb.qiniucdn.com/l_217y4y1yaudio.mp3?e = 1435135882&token=yPmhHAZNeHlKndKBLvhwV3fw4pzNBVvGNU5ne6Px:PMZEngdMVO4tilZ5OC0P5nTC0fw="
                    //draw_url "http://7tszue.com2.z0.glb.qiniucdn.com/l_217y4y1ydraw.xml?e = 1435135882&token=yPmhHAZNeHlKndKBLvhwV3fw4pzNBVvGNU5ne6Px:ZzhOtcnnVkVKd2dZjWYV8OHSqio="
                    //real_begin_time "1434765333"
                    Cwhiteboard.loadData(w , h, result.real_begin_time, result.draw_url, result.audio_url, html_node );
                    /*
                     Cwhiteboard.loadData(w , h, 1434765333,
                     "http                                                         : //7tszue.com2.z0.glb.qiniucdn.com/l_217y4y1ydraw.xml?e=1435135882&token=yPmhHAZNeHlKndKBLvhwV3fw4pzNBVvGNU5ne6Px:ZzhOtcnnVkVKd2dZjWYV8OHSqio=" ,
                     "http://7tszue.com2.z0.glb.qiniucdn.com/l_217y4y1yaudio.mp3?e = 1435135882&token=yPmhHAZNeHlKndKBLvhwV3fw4pzNBVvGNU5ne6Px:PMZEngdMVO4tilZ5OC0P5nTC0fw=",
                     html_node
                     );
                     */
				}else{
                    BootstrapDialog.alert(result.info);
                }
			}
		});
    });        

    notify_cur_playpostion = function (cur_play_time){
        Cwhiteboard.play_next( cur_play_time );
    };
    
    $(".opt-div").each(function() {
        var $this=$(this) ;
        if (!$this.data("lessonid")) {
            $(this).hide();
        }
    });

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

    $(".opt-add_reward").on("click",function(){
	    var data            = $(this).get_opt_data();
        var id_reward_type  = $("<select/>");
        var id_reward_money = $("<input/>");

        Enum_map.append_option_list("reward_type",id_reward_type,true,[2,3]);
        var arr = [
            ["奖励类型",id_reward_type],
            ["奖励金额",id_reward_money],
        ];
        $.show_key_value_table("添加奖励",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/teacher_money/add_teacher_reward",{
                    "money_info" : data.lessonid,
                    "type"       : id_reward_type.val(),
                    "teacherid"  : teacherid,
                    "money"      : id_reward_money.val()*100
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                });
            }
        });
    });


    $(".opt-reset_lesson").on("click",function(){
	      var data = $(this).get_opt_data();

        BootstrapDialog.show({
	          title   : "重置本节课的老师工资类型和等级",
	          message : "确认重置本节课的老师工资？",
	          buttons : [{
		            label  : "返回",
		            action : function(dialog) {
			              dialog.close();
		            }
	          }, {
		            label    : "确认",
		            cssClass : "btn-warning",
		            action   : function(dialog) {
                    $.do_ajax("/teacher_money/reset_lesson_reward",{
                        "lessonid" : data.lessonid,
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

});
