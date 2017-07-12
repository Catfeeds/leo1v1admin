;(function($) {
    var b64  = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
        a256 = '',
        r64  = [256],
        r256 = [256],
        i    = 0;
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
        encode : function(strUni) {
            // use regular expressions & String.replace callback function for better efficiency
            // than procedural approaches
            var strUtf = strUni.replace(/[\u0080-\u07ff]/g, // U+0080 - U+07FF => 2 bytes 110yyyyy, 10zzzzzz
                                        function(c) {
                                            var cc = c.charCodeAt(0);
                                            return String.fromCharCode(0xc0 | cc >> 6, 0x80 | cc & 0x3f);
                                        })
                    .replace(/[\u0800-\uffff]/g, // U+0800 - U+FFFF = > 3 bytes 1110xxxx, 10yyyyyy, 10zzzzzz
                             function(c) {
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
        decode : function(strUtf) {
            // note    : decode 3-byte chars first as decoded 2-byte strings could appear to be 3-byte char!
            var strUni = strUtf.replace(/[\u00e0-\u00ef][\u0080-\u00bf][\u0080-\u00bf]/g, // 3-byte chars
                                        function(c) { // (note parentheses for precence)
                                            var cc = ((c.charCodeAt(0) & 0x0f) << 12) | ((c.charCodeAt(1) & 0x3f) << 6) | (c.charCodeAt(2) & 0x3f);
                                            return String.fromCharCode(cc);
                                        })
                    .replace(/[\u00c0-\u00df][\u0080-\u00bf]/g, // 2-byte chars
                             function(c) { // (note parentheses for precence)
                                 var cc = (c.charCodeAt(0) & 0x1f) << 6 | c.charCodeAt(1) & 0x3f;
                                 return String.fromCharCode(cc);
                             });
            return strUni;
        }
    };
    while(i < 256) {
        var c    = String.fromCharCode(i);
        a256    += c;
        r256[i]  = i;
        r64[i]   = b64.indexOf(c);
        ++i;
    }
    function code(s, discard, alpha, beta, w1, w2) {
        s                = String(s);
        var buffer       = 0,
            i            = 0,
            length       = s.length,
            result       = '',
            bitsInBuffer = 0;
        while(i < length) {
            var c = s.charCodeAt(i);
            c             = c < 256 ? alpha[c] : -1;
            buffer        = (buffer << w1) + c;
            bitsInBuffer += w1;
            while(bitsInBuffer >= w2) {
                bitsInBuffer   -= w2;
                var tmp         = buffer >> bitsInBuffer;
                result         += beta.charAt(tmp);
                buffer         ^= tmp << bitsInBuffer;
            }
            ++i;
        }
        if(!discard && bitsInBuffer > 0) result += beta.charAt(buffer << (w2 - bitsInBuffer));
        return result;
    }
    var Plugin = $.base64 = function(dir, input, encode) {
        return input ? Plugin[dir](input, encode) : dir ? null : this;
    };
    Plugin.btoa                                   = Plugin.encode = function(plain, utf8encode) {
        plain = Plugin.raw === false || Plugin.utf8encode || utf8encode ? UTF8.encode(plain) : plain;
        plain = code(plain, false, r256, b64, 8, 6);
        return plain + '===='.slice((plain.length % 4) || 4);
    };
    Plugin.atob = Plugin.decode = function(coded, utf8decode) {
        coded = String(coded).split('=');
        var i = coded.length;
        do {--i;
            coded[i] = code(coded[i], true, r64, a256, 6, 8);
           } while (i > 0);
        coded        = coded.join('');
        return Plugin.raw === false || Plugin.utf8decode || utf8decode ? UTF8.decode(coded) : coded;
    };
}(jQuery));


$(function(){
    $(".opt-change-price").on("click",function(){
        var lessonid     = $(this).parent().data("lessonid");
        var tea_money    = $(this).parent().data("teacher_price");

        var price = $("<input/> ");
        var arr = [
            ["修改老师金额(元)", price],
        ];
        price.val(tea_money);
        show_key_value_table("修改老师金额", arr ,{
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    $.ajax({
                        url: '/tea_manage/update_tea_money',
                        type: 'POST',
                        dataType: 'json',
                        data : {
                            'lessonid'  : lessonid,
                            'tea_money' : (price.val())*100
			            },
                        success: function(data) {
                            if(data.ret==0){
                                window.location.reload();
                            }else{
                                BootstrapDialog.alert(data.info);
                            }
                        }
                    });
                }
            });

    });


    $("#id_teacherid").val(g_args.teacherid);
    $("#id_teacherid").admin_select_user({
        "type"   : "teacher",
        "onChange": function(){
            load_data();
        }
    });
    $("#id_assistantid").val(g_args.assistantid);
    $("#id_assistantid").admin_select_user({
        "type"   : "assistant",
        "onChange": function(){
            load_data();
        }
    });

    $(".opt-grade") .on("click",function(){
        var grade=$(this).get_opt_data("grade");
        var lessonid=$(this).get_opt_data("lessonid");
        var id_grade=$("<select/>");

        Enum_map.append_option_list( "grade", id_grade);
        id_grade.val(grade);
        var arr = [
            [ "年级",  id_grade] 
        ];

        show_key_value_table("修改", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                do_ajax("/user_deal/lesson_set_grade", {
                    "lessonid":lessonid,
                    "grade":id_grade.val()
                });

            }
        });

    });



    //audiojs 时间回调, 每秒3-4次
    //$(".tea_cw_url[data-v = 0], .stu_cw_url[data-v=0],.homework_url[data-v=0]" ) .parent().addClass("danger");
    //=======================================================
    notify_cur_playpostion = function (cur_play_time){
        Cwhiteboard.play_next( cur_play_time );
    };
    
    get_new_whiteboard = function (obj_drawing_list){
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
                        item_data.pageid = Math.floor(item.attr("y")/768)+1;
                        if (me.max_pageid <  item_data.pageid ){
                            me.max_pageid = item_data.pageid ;
                        }

                        item_data.timestamp = item.attr("timestamp")-me.start_time;
                        var opt_item        = item.children(":first");
                        item_data.opt_type  = $(opt_item)[0].tagName;

                        var stroke_info = opt_item.attr("stroke");
                        if( typeof stroke_info != "undefined" && stroke_info.indexOf("#") == -1){
                            stroke_info = "#" + stroke_info;
                        }
                        var opt_args={};
                        switch( item_data.opt_type  ) {

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

                        item_data.opt_args = opt_args;
                        
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

    //Enum_map.append_option_list( "contract_type_ex", $("#id_lesson_type"));
    Enum_map.append_option_list( "test_user", $("#id_is_with_test_user"));
    Enum_map.append_option_list( "subject", $("#id_subject"));
    Enum_map.append_option_list( "contract_type", $("#id_lesson_type"));
    Enum_map.append_option_list( "confirm_flag", $("#id_confirm_flag"));

    $('#id_start_date').val(g_start_date);
    $('#id_end_date').val(g_end_date);
    $('#id_is_with_test_user').val(g_args.is_with_test_user);
	$("#id_lesson_type").val(g_args.lesson_type);
	$("#id_confirm_flag").val(g_args.confirm_flag);
	$("#id_subject").val(g_args.subject);
	$("#id_test_seller_id").val(g_args.test_seller_id);
	$("#id_origin").val(g_args.origin);

    set_input_enter_event ( $("#id_origin"),load_data);
	//TODO
	//时间控件
	$('#id_start_date').datetimepicker({
		lang:'ch',
		timepicker : false,
		format     : 'Y-m-d',
	    onChangeDateTime :function(){
		    load_data();
        }
	});
	$('#id_end_date').datetimepicker({
		lang : 'ch',
		timepicker:false,
		format           : 'Y-m-d',
		onChangeDateTime : function(){
		    load_data();
        }
	});
	//时间控件-over
	function load_data( ){
        reload_self_page({
            start_date        : $("#id_start_date").val(),
            end_date          : $("#id_end_date").val(),
            lesson_type       : $("#id_lesson_type").val(),
            confirm_flag      : $("#id_confirm_flag").val(),
            subject           : $("#id_subject").val(),
            studentid         : $("#id_studentid").val(),
            teacherid         : $("#id_teacherid").val(),
            seller_adminid    : $("#id_seller_adminid").val(),
            assistantid       : $("#id_assistantid").val(),
            test_seller_id    : $("#id_test_seller_id").val(),
            origin            : $("#id_origin").val(),
            is_with_test_user : $("#id_is_with_test_user").val()

        });
	}

	$(".opt-change").on("change",function(){
		load_data();
	});	
    
    $(".opt-edit-lesson-upload-time").on("click",function(){
        var lessonid = $(this).get_opt_data("lessonid");
        
        var id_lesson_upload_time = $("<input />");
        id_lesson_upload_time.val( $(this).get_opt_data("lesson_upload_time"));
	    
        var arr = [
            [ "生成录像时间",  id_lesson_upload_time] 
        ];

        show_key_value_table("修改", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                do_ajax("/tea_manage/set_lesson_upload_time", {
                    "lessonid":lessonid,
                    "lesson_upload_time":id_lesson_upload_time.val()
                });

            }
        });
    });

    $(".opt-play").on("click", function(){
        var lessonid = $(this).parent().data('lessonid');
        $.ajax({
			type     : "post",
			url      : "/tea_manage/get_lesson_reply",
			dataType : "json",
			data     : {"lessonid":lessonid},
			success  : function(result){
				if(result.ret == 0 ){
                    //加载数据
                    
                    var w=check_in_phone()?329 : 558;
                    var h=w/4*3;


                    var html_node = $(" <div  style=\"text-align:center;\"  > <div id=\"drawing_list\" style=\"width:100%\"  > </div>  <audio preload=\"none\"  > </audio> </div> ");
                    BootstrapDialog.show({
                        title    : '课程回放',
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

    $(".opt-upload").on("click", function(){
        $(this).addClass('current_opt_lesson_record');
        var html_node   = $('<div></div>').html(dlg_get_html_by_class('dlg_upload'));
        var lesson_info = new Object();
        
        lesson_info.lessonid = $(this).parent().data("lessonid");
        lesson_info.lesson_status = $(this).parents('td').siblings('.lesson_status').find('.status').val();
        lesson_info.work_status   = $(this).parents('td').siblings('.homework_url').find('.status').val();
        
        html_node.find(".opt-teacher-url").attr('id', 'optid-teacher-url'+lesson_info.lessonid);
        html_node.find(".opt-teacher-url").parent().attr('id', 'optid-teacher-url-parent'+lesson_info.lessonid);
        html_node.find(".opt-student-url").attr('id', 'optid-student-url'+lesson_info.lessonid);
        html_node.find(".opt-student-url").parent().attr('id', 'optid-student-url-parent'+lesson_info.lessonid);
        html_node.find(".opt-homework-url").attr('id', 'optid-homework-url'+lesson_info.lessonid);
        html_node.find(".opt-homework-url").parent().attr('id', 'optid-homework-url-parent'+lesson_info.lessonid);
        // add lesson quiz
        html_node.find(".opt-quiz-url").attr('id', 'optid-quiz-url'+lesson_info.lessonid);
        html_node.find(".opt-quiz-url").parent().attr('id', 'optid-quiz-url-parent'+lesson_info.lessonid);

        html_node.find(".lesson_time").text($(this).parents('td').siblings('.lesson_time').text());
        html_node.find(".tea_nick").text($(this).parents('td').siblings('.tea_nick').text());
        html_node.find(".stu_nick").text($(this).parents('td').siblings('.stu_nick').text());

        var lessonid = $(this).parent().data('lessonid');
        var grade    = $(this).parent().data('grade');
        var subject  = $(this).parent().data('subject');
        
        BootstrapDialog.show({
	        title   : "上传本次课的课件或作业",
	        message : html_node,
            onhide  : function(dialogRef){
                $('.current_opt_lesson_record').removeClass('current_opt_lesson_record');
            },
            onshown : function(  dialog )  {
                //var $item=$("#optid-teacher-url"+lesson_info.lessonid);
                //绑定事件

                custom_upload_file( 'optid-teacher-url'+lesson_info.lessonid ,
                                    false ,setCompleteTeacher, lesson_info,
                                    ["pdf","zip"], setProgress );

                custom_upload_file( 'optid-student-url'+lesson_info.lessonid ,
                                    false ,setCompleteStudent, lesson_info,
                                    ["pdf","zip"], setProgress );

                custom_upload_file( 'optid-quiz-url'+lesson_info.lessonid ,
                                    false ,setCompleteQuiz, lesson_info,
                                    ["pdf","zip"], setProgress );

                custom_upload_file( 'optid-homework-url'+lesson_info.lessonid ,
                                    false ,setCompleteHomework, lesson_info,
                                    ["pdf","zip"], setProgress );
            },buttons : [{
		        label : '返回',
		        action: function(dialog) {
                    $('.current_opt_lesson_record').removeClass('current_opt_lesson_record');
			        dialog.close();
		        }
	        }, {
		        label    : '添加课堂作业',
		        cssClass : 'btn-warning',
		        action   : function(dialog) {
                    if(grade<200){
                        grade = 100;
                    }else if(grade<300){
                        grade = 200;
                    }else{
                        grade=300;
                    }
                    var url              = "/tea_manage/get_homework_list?lessonid="+lessonid+"&grade="+grade+"&subject="+subject;
                    window.location.href = url;
			        dialog.close();
		        }
	        }, {
		        label : '完成',
		        cssClass : 'btn-warning',
		        action: function(dialog) {
                    $('.current_opt_lesson_record').removeClass('current_opt_lesson_record');
                    window.location.reload();
			        dialog.close();
		        }
	        }]
        });
    });

    $('.opt-download').on('click', function(){
        var html_node = $('<div></div>').html(dlg_get_html_by_class('dlg_download'));

        var lesson_info           = new Object();
        lesson_info.lesson_status = $(this).parents('td').siblings('.lesson_status').find('.status').val();
        lesson_info.work_status   = $(this).parents('td').siblings('.homework_url').find('.status').val();
        lesson_info.tea_cw_url    = $(this).parents('td').siblings('.tea_cw_url').find('.file_url').val();
        lesson_info.stu_cw_url    = $(this).parents('td').siblings('.stu_cw_url').find('.file_url').val();
        lesson_info.homework_url  = $(this).parents('td').siblings('.homework_url').find('.file_url').val();
        lesson_info.lesson_quiz   = $(this).parents('td').siblings('.lesson_quiz_url').find('.file_url').val();

        html_node.find(".lesson_time").text($(this).parents('td').siblings('.lesson_time').text());
        html_node.find(".tea_nick").text($(this).parents('td').siblings('.tea_nick').text());
        html_node.find(".stu_nick").text($(this).parents('td').siblings('.stu_nick').text());

        BootstrapDialog.show({
	        title   : "下载本次课的课件或作业",
	        message : function(dialog) {
                html_node.find(".opt-teacher-url").on('click', function(){
                    if (!lesson_info.tea_cw_url) {
                        BootstrapDialog.alert("老师版课件未上传");
                        return;
                    }
                    custom_download(lesson_info.tea_cw_url);
                });
                
                html_node.find(".opt-student-url").on('click', function(){
                    if (!lesson_info.stu_cw_url) {
                        BootstrapDialog.alert("学生版课件未上传");
                        return;
                    }
                    custom_download(lesson_info.stu_cw_url);
                });

                html_node.find(".opt-homework-url").on('click', function(){
                    if (!lesson_info.homework_url) {
                        BootstrapDialog.alert("课后作业未上传");
                        return;
                    }
                    custom_download(lesson_info.homework_url);
                });
                
                html_node.find(".opt-quiz-url").on('click', function(){
                    if (!lesson_info.lesson_quiz) {
                        BootstrapDialog.alert("课堂测验未上传");
                        return;
                    }
                    custom_download(lesson_info.lesson_quiz);
                });
                
                return html_node;
            },
	        buttons : [{
		        label  : '返回',
		        action : function(dialog) {
			        dialog.close();
		        }
	        }]
        });

    });

    $('.opt-score-star').on('click', function(){
        var lessonid  = $(this).parent().data("lessonid");
        var html_node = $('<div></div>').html(dlg_get_html_by_class('dlg_score_star'));
        html_node.find(".effect").text($(this).parents('td').siblings('.teacher_effect').text());
        html_node.find(".quality").text($(this).parents('td').siblings('.teacher_quality').text());
        html_node.find(".interact").text($(this).parents('td').siblings('.teacher_interact').text());

        BootstrapDialog.show({
	        title   : "更改本次课学生对老师的评分",
	        message : html_node ,
	        buttons : [{
		        label  : '返回',
		        action : function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label    : '确认',
		        cssClass : 'btn-warning',
		        action   : function(dialog) {
			        //get data from dlg
			        var new_effect   = html_node.find(".new_effect").val();
			        var new_quality  = html_node.find(".new_quality").val();
			        var new_interact = html_node.find(".new_interact").val();

                    $.ajax({
                        url  : '/tea_manage/reset_lesson_comment',
                        type : 'POST',
                        data : {
                            'lessonid' : lessonid, 'new_effect': new_effect, 'new_quality': new_quality, 'new_interact': new_interact
                        },
                        dataType: 'json',
                        success        : function(result){
                            BootstrapDialog.alert(result['info']);
                        }
                    });
                    
			        dialog.close();
		        }
	        }]
        });

    });

    var custom_download = function(file_url) {
        $.ajax({
            url: '/tea_manage/get_pdf_download_url',
            type     : 'GET',
            dataType : 'json',
            data     : {'file_url': file_url},
            success  : function(ret) {
                if (ret.ret != 0) {
                    BootstrapDialog.alert(ret.info);
                } else {
                    BootstrapDialog.alert( ret.file_ex );
                    if (file_url.match(/.pdf$/ ) ) {
                        window.open('/pdf_viewer/?file='+ret.file, '_blank');
                    }else{
                    }
                }
            }
        });
    };
    
    
    var setCompleteHomework = function(up, info, file, lesson_info) {

        var res = $.parseJSON(info);
        var url;
        if (res.url) {
        } else {
            $.ajax({
        	    url      : '/homework_manage/set_homework_url',
        	    type     : 'POST',
        	    data     : {'homework_url': res.key, 'lessonid':lesson_info.lessonid},
			    dataType : 'json',
			    success  : function(data) {
				    if (data['ret'] == 0) {

				    } else {
                        BootstrapDialog.alert("上传失败");
				    }
			    }
            }); 
        }

    };
    
    var setCompleteQuiz = function(up, info, file, lesson_info) {
        var res = $.parseJSON(info);
        var url;
        if (res.url) {
        } else {
            $.ajax({
        	    url     : '/lesson_manage/set_lesson_quiz',
        	    type    : 'POST',
        	    data    : {'lesson_quiz': res.key, 'lessonid':lesson_info.lessonid},
			    dataType: 'json',
			    success : function(data) {
				    if (data['ret'] == 0) {
                        BootstrapDialog.alert("上传成功");
                        $('.current_opt_lesson_record').parents('td').siblings('.lesson_quiz_url').children('span').text('已传');
				    } else {
                        BootstrapDialog.alert("上传失败");
				    }
			    }
            }); 
        }
    };

    var setCompleteStudent = function(up, info, file, lesson_info) {
        var res = $.parseJSON(info);
        var url;
        if (res.url) {
        } else {
            $.ajax({
        	    url      : '/lesson_manage/set_stu_cw_url',
        	    type     : 'POST',
        	    data     : {'stu_cw_url': res.key, 'lessonid':lesson_info.lessonid},
			    dataType : 'json',
			    success: function(data) {
				    if (data['ret'] == 0) {
                        BootstrapDialog.alert("上传成功");
                        $('.current_opt_lesson_record').parents('td').siblings('.stu_cw_url').children('span').text('已传');
				    } else {
                        BootstrapDialog.alert("上传失败");
				    }
			    }
            }); 
        }
    };

    var setCompleteTeacher = function(up, info, file, lesson_info) {
        var res = $.parseJSON(info);
        var url;
        if (res.url) {
        } else {
            $.ajax({
        	    url: '/lesson_manage/set_tea_cw_url',
        	    type                : 'POST',
        	    data: {'tea_cw_url' : res.key, 'lessonid':lesson_info.lessonid},
			    dataType: 'json',
			    success             : function(data) {
				    if (data['ret'] == 0) {
                        BootstrapDialog.alert("上传成功");
                        $('.current_opt_lesson_record').parents('td').siblings('.tea_cw_url').children('span').text('已传');
				    } else {
                        BootstrapDialog.alert(data.info );
				    }
			    }
            }); 
        }

    };

    function check_type(file_type)
    {
	    return file_type == 'application/pdf' ? true : false;
    }

    function check_work_status(work_status)
    {
        return work_status < 2 ? true : false;
    }

    function check_lesson_status(lesson_status)
    {
        return true;
    }


    function setProgress ( percentage ) {
        $('.upload_process_info').css('width', percentage + '%');
    }


    $(".opt-small-class-or-open" ).each(function( ){
        var lesson_type= $(this).get_opt_data("lesson_type");
        var lessonid = $(this).get_opt_data("lessonid");
        var courseid = $(this).get_opt_data("courseid");
        var stu_id   = $(this).get_opt_data("stu_id");
        if (lesson_type == 3001){
            $(this).attr("attr", "/small_class/index?courseid="+courseid );
        } else if (lesson_type >= 1000 & lesson_type<2000 ){
            $(this).attr("attr", "/tea_manage/open_class?lessonid="+lessonid);
        } else if (lesson_type >= 0 & lesson_type<1000 ){
            $(this).attr("attr", "/stu_manage/lesson_plan/?sid = "+stu_id);
        }else{
            $(this).hide();
        }
    });

    
    $(".opt-out-link").on("click",function(){
	    var lessonid = $(this).get_opt_data("lessonid");
        do_ajax( "/common/encode_text",{
            "text" : lessonid 
        }, function(ret){
            BootstrapDialog.alert("对外链接 : http://"+ window.location.hostname + "/tea_manage/show_lesson_video?lessonid=" + ret.text  );
        });
    });


    $(".opt-qr-pad-at-time").on("click",function(){
        var lessonid= $(this).get_opt_data("lessonid");
        var url=$(this).data("type");
        var title = $(this).attr("title");
        //得到 
        do_ajax("/tea_manage/get_lesson_xmpp_audio",{
            "lessonid" :lessonid
        },function(result){

            var data=result.data;

            var args="title=lessonid:"+lessonid+"&beginTime="+data.lesson_start+"&endTime="+data.lesson_end+"&roomId="+data.roomid+"&xmpp="+data.xmpp+"&webrtc="+data.webrtc+"&ownerId="+data.teacherid+"&type="+data.type+"&audioService="+data.audioService ;

            var args_64 = $.base64.encode(args);
            
            console.log(args);
            
            var text = encodeURIComponent(url+args_64);
            
            var dlg = BootstrapDialog.show({
                title: title, 
                message :"<div style = \"text-align:center\"><img width=\"350px\" src=\"/common/get_qr?text="+text+"\"></img>" ,
                closable             : true 
            }); 
            //dlg.getModalDialog().css("width","800px");

        });

    });
    $(".opt-qr-pad").on("click",function(){

        var lessonid= $(this).get_opt_data("lessonid");
        var url = $(this).data("type");
        var title=$(this).attr("title");
        //得到 
        do_ajax("/tea_manage/get_lesson_xmpp_audio",{
            "lessonid" :lessonid
        },function(result){
            var data = result.data;
            var args="title=lessonid : "+lessonid+"&beginTime="+data.real_begin_time+"&endTime="+data.real_end_time+"&drawUrl="+data.draw+"&audioUrl="+data.audio;
            var args_64 = $.base64.encode(args);
            var text = encodeURIComponent(url+args_64);
            var dlg = BootstrapDialog.show({
                title: title, 
                message  : "<div style=\"text-align:center\"><img width=\"300\" src=\"/common/get_qr?text="+text+"\"></img><br/>" +  url+args_64+"<br/> "+args +"</div>",
                closable : true 
            }); 
        });
    });

    $(".for_input").on ("keypress", function( e){
		if (e.keyCode == 13){
		    var id_lesson = $("#id_lesson").val();
	    	if( id_lesson == ""){
		    	alert("请输入课程ID");
		    }else{
		    	var url = "/tea_manage/lesson_list?lessonid="+id_lesson;
		    	window.location.href = url;
		    }
		}
	});


	$("#id_search_lesson").on("click", function(){
		var id_lesson = $("#id_lesson").val();
		if( id_lesson == ""){
			alert("请输入课程ID");
		}else{
			var url = "/tea_manage/lesson_list?lessonid="+id_lesson;
			window.location.href = url;
		}
	});
    
	$(".opt-add-error").on("click", function(){
        var lessonid     = $(this).parent().data("lessonid");
        var courseid     = $(this).parent().data("courseid");
        var lesson_type  = $(this).parent().data("lesson_type");
        var lesson_start = $(this).parent().data("lesson_start");
        var lesson_end   = $(this).parent().data("lesson_end");
        var teacherid    = $(this).parents("td").siblings(".tea_nick").data("teacherid");
        var tea_nick     = $(this).parents("td").siblings(".tea_nick").text();
        var stu_id       = $(this).parents("td").siblings(".stu_nick").data("stu_id");
        var stu_nick     = $(this).parents("td").siblings(".stu_nick").text();
        do_ajax( "/lesson_manage/add_error_lessonid",{
            "lessonid"    : lessonid,
            "courseid"     : courseid,
            "lesson_type" : lesson_type,
            "lesson_start" : lesson_start,
            "lesson_end"  : lesson_end,
            "teacherid"   : teacherid,
            "tea_nick"     : tea_nick,
            "stu_id"      : stu_id,
            "stu_nick"     : stu_nick 
        }, function(result){
            if(result.ret<0){
                alert(result.info);
            }else{
  			    var url              = "/lesson_manage/error_info?lessonid="+lessonid;
			    window.location.href = url;
            }
        });
    });

    $("#id_studentid").val(g_args.studentid);
    $("#id_seller_adminid").val(g_args.seller_adminid);
    
    $("#id_studentid").admin_select_user({
        "type"     : "student",
        "onChange" : function(){
            load_data();
        }
    });

    admin_select_user($("#id_seller_adminid"), "admin",function(){
        load_data();
    });
    
    $.each($("tr"),function(){
        var tea = $(this).children('.tea_cw_url').data('v');
        var stu = $(this).children('.stu_cw_url').data('v');
        var hom = $(this).children('.homework_url').data('v');
        if(tea == 1){
            $(this).children('.tea_cw_url').css('color','black');
        }else{
            $(this).children('.tea_cw_url').css('color','red');
        }
        if(stu == 1){
            $(this).children('.stu_cw_url').css('color','black');
        }else{
            $(this).children('.stu_cw_url').css('color','red');
        }
        if(hom == 0){
            $(this).children('.homework_url').css('color','red');
        }else{
            $(this).children('.homework_url').css('color','black');
        }
    });
    

    $(".opt-reset-cw").on("click",function(){
        var lessonid = $(this).get_opt_data("lessonid");
        BootstrapDialog.confirm("要重置课件信息 lessonid="+lessonid, function(result){
            if(result) {
                do_ajax("/user_deal/lesson_reset_cw_info",{
                    "lessonid": lessonid
                });
            }
        });
	    
    });

    $(".opt-confirm").on("click",function(){
        var lessonid        = $(this).get_opt_data("lessonid");
        var lesson_count    = $(this).get_opt_data("lesson_count");
        var $confirm_flag   = $("<select> </select>");
        Enum_map.append_option_list( "confirm_flag", $confirm_flag,true);

        var $confirm_reason = $("<textarea/> ");
        var $lesson_count   = $("<input/> ");
		$lesson_count.val(lesson_count/100 );
        
        var arr=[
            ["上课完成", $confirm_flag ] ,
            ["无效原因", $confirm_reason ] 
        ];
        $confirm_flag.on("change",function(){
            var val=$confirm_flag.val();
            if (val==1) {
                $lesson_count.parent().parent().show();
                $confirm_reason.parent().parent().hide();
            }else{
                $lesson_count.parent().parent().hide();
                $confirm_reason.parent().parent().show();
            }
        });
        
        show_key_value_table("确认课时", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                do_ajax("/user_deal/lesson_set_comfirm", {
                    "lessonid":lessonid,
                    "confirm_flag":$confirm_flag.val(),
                    "confirm_reason":$confirm_reason.val(),
                    "lesson_count":$lesson_count.val()*100
                });
            }
        } ,function(){
            $confirm_reason.parent().parent().hide();
		});

	    
    });
   
    $("tr").each(function(){
        var status = $(this).find(".opt").data("performance_status");
        var html_node = "<a href=\"javascript:;\" class=\"btn fa opt-get_stu_performance\" >课堂反馈</a>";
        if(status==1){
            $(this).find(".opt-confirm").before(html_node);
        }
    });

    $(".opt-get_stu_performance").on("click",function(){
        var lessonid            = $(this).parent().data("lessonid");
        var $total_judgement    = $("<select> </select> ");
        var $homework_situation = $("<input/> ");
        var $content_grasp      = $("<input/> ");
        var $lesson_interact    = $("<input/> ");
        var $point_note_list    = $("<textarea/> ");
        var $point_note_list2   = $("<textarea/> ");
        var point_name          = '';
        var point_name2         = '';
        var point_stu_desc      = '';
        var point_stu_desc2     = '';
        
        Enum_map.append_option_list( "performance", $total_judgement,true);
        do_ajax("/tea_manage/get_stu_performance",{
            "lessonid":lessonid
        },function(result){
            $total_judgement.val(result.total_judgement);
            $homework_situation.val(result.homework_situation);
            $content_grasp.val(result.content_grasp);
            $lesson_interact.val(result.lesson_interact);
            if(result.point_note_list!=''){
                point_name      = result.point_name[0];
                point_stu_desc  = result.point_stu_desc[0];
                //console.log(result.point_name[1]);
                if(result.point_name[1]){
                    point_name2     = result.point_name[1];
                    point_stu_desc2 = result.point_stu_desc[1];
                }
            }

            $point_note_list.val(point_stu_desc);
            $point_note_list2.val(point_stu_desc2);
            
            var arr=[
                ["课堂评价", $total_judgement] ,
                ["作业情况", $homework_situation] ,
                ["内容掌握情况", $content_grasp] ,
                ["课堂互动情况", $lesson_interact] ,
                [point_name, $point_note_list] ,
                [point_name2, $point_note_list2] 
            ];
            
            //console.log(point_name2);
            
            show_key_value_table("课堂反馈", arr ,{
                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog) {
                    do_ajax("/tea_manage/set_stu_performance", {
                        "lessonid"           : lessonid,
                        "total_judgement"    : $total_judgement.val(),
                        "homework_situation" : $homework_situation.val(),
                        "content_grasp"      : $content_grasp.val(),
                        "lesson_interact"    : $lesson_interact.val(),
                        "point_name"         : point_name,
                        "point_stu_desc"     : $point_note_list.val(),
                        "point_name2"        : point_name2,
                        "point_stu_desc2"    : $point_note_list2.val()
                    },function(result){
                        window.location.reload();
                    });
                }
            },function(){
                if(point_name==''){
                    $point_note_list.parent().parent().hide();
                }
                if(point_name2==''){
                    $point_note_list2.parent().parent().hide();
                }
            });
        });
    });





});
