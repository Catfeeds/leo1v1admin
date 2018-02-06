/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-lesson_list.d.ts" />
var Cwhiteboard=null;
var notify_cur_playpostion =null;
    function load_data( ){
        $.reload_self_page({
		        order_by_str : g_args.order_by_str,
            date_type     :	$('#id_date_type').val(),
            opt_date_type :	$('#id_opt_date_type').val(),
            start_time    :	$('#id_start_time').val(),
            end_time      :	$('#id_end_time').val(),

            lesson_status: $("#id_lesson_status").val(),
            lesson_type  : $("#id_lesson_type").val(),
            confirm_flag : $("#id_confirm_flag").val(),
            subject      : $("#id_subject").val(),
            grade        : $("#id_grade").val(),
            studentid    : $("#id_studentid").val(),
            teacherid    : $("#id_teacherid").val(),
            lessonid     : $("#id_lessonid").val(),

            assistantid       : $("#id_assistantid").val(),
            test_seller_id    : $("#id_test_seller_id").val(),
            is_with_test_user : $("#id_is_with_test_user").val(),
            has_performance   : $("#id_has_performance").val(),
            lesson_count      : $("#id_lesson_count").val(),
            lesson_del_flag:	$('#id_lesson_del_flag').val(),

            origin : $("#id_origin").val(),

            has_video_flag            :	$('#id_has_video_flag').val(),
            lesson_cancel_reason_type :	$('#id_lesson_cancel_reason_type').val(),
            lesson_user_online_status :	$('#id_lesson_user_online_status').val(),
            fulltime_teacher_type:	$('#id_fulltime_teacher_type').val()
        });
    }

$(function(){

    $(".opt-set-server").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var select_id_list=[];

        $(".opt-select-item").each(function(){
            var $item=$(this) ;
            if($item.iCheckValue()) {
                select_id_list.push( $item.data("id") ) ;
            }
        } ) ;

        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" :  "select", // or "list"
            "url"          : "/user_deal/get_xmpp_server_list_js",
            select_primary_field   : "server_name",
            select_display         : "server_name",
            select_no_select_value : "",
            select_no_select_title : "[全部]",

            //其他参数
            "args_ex" : {
            },
            //字段列表
            'field_list' :[
                {
                    title:"ip",
                    render:function(val,item) {return item.ip;}
                },{
                    title:"权重",
                    render:function(val,item) {return item.weights ;}
                },{
                    title:"名称",
                    render:function(val,item) {return item.server_name;}
                },{

                    title:"说明",
                    render:function(val,item) {return item.server_desc;}
                }
            ] ,
            filter_list: [],

            "auto_close"       : true,
            //选择
            "onChange"         : function(v) {
            $.do_ajax( '/ajax_deal2/set_lesson_current_server',{
                    "lessonid" : opt_data.lessonid ,
                    "current_server" :  v,
                });
            },
            //加载数据后，其它的设置
            "onLoadData"       : null,

        });
    });

    // $(".opt-set-server ").on("click",function(){
    //     var opt_data=$(this).get_opt_data();
    //     var $server=$ ("<select >  <option value=\"h_01\">杭州</option> <option value=\"q_01\">青岛</option>   <option value=\"b_01\">北京</option> "
    //                    + "<option value=\"a_01\" >青岛_27</option> "
    //                    +" </select>");
    //     var arr=[
    //         ["服务器", $server]
    //     ];
    //     $server.val(opt_data.current_server);
    //     $.show_key_value_table("选择服务器", arr, {
    //         label: '确认',
    //         cssClass: 'btn-warning',
    //         action: function(dialog) {
    //             $.do_ajax( '/ajax_deal2/set_lesson_current_server',{
    //                 "courseid" : opt_data.courseid,
    //                 "current_server" :  $server.val()
    //             });
    //         }
    //     });

    // });


    Enum_map.append_option_list( "lesson_status", $('#id_lesson_status'));
    Enum_map.append_option_list( "boolean", $('#id_lesson_del_flag'));
    Enum_map.append_option_list( "fulltime_teacher_type", $('#id_fulltime_teacher_type'),false,[1,2]);

    // $(".opt-change-price").on("click",function(){
    //     var lessonid     = $(this).parent().data("lessonid");
    //     var tea_money    = $(this).parent().data("teacher_price");

    //     var price = $("<input/> ");
    //     var arr = [
    //         ["修改老师金额(元)", price],
    //     ];
    //     price.val(tea_money);
    //     $.show_key_value_table("修改老师金额", arr ,{
    //         label: '确认',
    //         cssClass: 'btn-warning',
    //         action: function(dialog) {
    //             $.ajax({
    //                 url: '/tea_manage/update_tea_money',
    //                 type: 'POST',
    //                 dataType: 'json',
    //                 data : {
    //                     'lessonid'  : lessonid,
    //                     'tea_money' : (price.val())*100
    //                 },
    //                 success: function(data) {
    //                     if(data.ret==0){
    //                         window.location.reload();
    //                     }else{
    //                         BootstrapDialog.alert(data.info);
    //                     }
    //                 }
    //             });
    //         }
    //     });

    // });

    Enum_map.append_option_list("set_boolean",$("#id_lesson_user_online_status"));


    $('#id_lesson_user_online_status').val(g_args.lesson_user_online_status);
    $('#id_fulltime_teacher_type').val(g_args.fulltime_teacher_type);
    $('#id_origin').val(g_args.origin);


    $("#id_teacherid").val(g_args.teacherid);
    $.admin_select_user( $("#id_teacherid"),"teacher", load_data);

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

        $.show_key_value_table("修改", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/user_deal/lesson_set_grade", {
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

    //Enum_map.append_option_list( "contract_type_ex", $("#id_lesson_type"));
    Enum_map.append_option_list( "test_user", $("#id_is_with_test_user"));
    Enum_map.append_option_list( "subject", $("#id_subject"));

    Enum_map.append_option_list( "contract_type", $("#id_lesson_type") );
    Enum_map.append_option_list("boolean",$("#id_has_video_flag"));
    Enum_map.append_option_list( "confirm_flag", $("#id_confirm_flag"));
    Enum_map.append_option_list( "lesson_cancel_reason_type", $("#id_lesson_cancel_reason_type"));

    $('#id_is_with_test_user').val(g_args.is_with_test_user);
    $("#id_lesson_type").val(g_args.lesson_type);
    $("#id_confirm_flag").val(g_args.confirm_flag);
    $("#id_subject").val(g_args.subject);

  $('#id_grade').admin_set_select_field({
    "enum_type"    : "grade",
    "field_name" : "grade",
    "select_value" : g_args.grade,
    "multi_select_flag"     : true,
    "onChange"     : load_data,
    "th_input_id"  : "th_grade",
    "only_show_in_th_input"     : false,
    "btn_id_config"     : {},
  });

    //04-21
    $.enum_multi_select ( $("#id_confirm_flag"),"confirm_flag", function( ){
        load_data();
    });


    $("#id_test_seller_id").val(g_args.test_seller_id);
    $("#id_has_performance").val(g_args.has_performance);
    $("#id_lesson_cancel_reason_type").val(g_args.lesson_cancel_reason_type);
    $('#id_has_video_flag').val(g_args.has_video_flag);

    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        "th_input_id" : "th_date_range",
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });


    $.admin_select_user(
        $('#id_test_seller_id'),
        "admin", load_data ,false, {
            "main_type": 2, //分配用户
            select_btn_config: [{
                "label": "所有非销售",
                "value":  -3
            },{
                "label": "所有销售",
                "value":  -2
            }]
        }
    );

    //时间控件-over

    $('.opt-change').set_input_change_event(load_data);
    $("#id_lesson_count").val(g_args.lesson_count );
    if (g_args.lessonid != -1 ) {
        $("#id_lessonid").val(g_args.lessonid );
    }

    $(".opt-edit-lesson-upload-time").on("click",function(){
        var opt_data= $(this).get_opt_data();
        var lessonid = opt_data.lessonid;


        var id_lesson_upload_time = $("<input />");
        var id_record_audio_server1 = $("<input />");
        var id_record_audio_server2 = $("<input />");
        var id_gen_video_grade      = $("<input />");
        id_lesson_upload_time.val( opt_data.lesson_upload_time);
        id_record_audio_server1.val( opt_data.record_audio_server1);
        id_record_audio_server2.val( opt_data.record_audio_server2);
        id_gen_video_grade.val( opt_data.gen_video_grade);

        var arr = [
            [ "生成录像时间",  id_lesson_upload_time] ,
            [ "录音服务器1",  id_record_audio_server1] ,
            [ "录音服务器2",  id_record_audio_server2] ,
            [ "设置视频生成优先级",  id_gen_video_grade] ,
        ];

        $.show_key_value_table("修改", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/user_deal/set_lesson_record_info", {
                    "lessonid":lessonid,
                    "lesson_upload_time":id_lesson_upload_time.val(),
                    "record_audio_server1":id_record_audio_server1.val(),
                    "record_audio_server2":id_record_audio_server2.val(),
                    "gen_video_grade":id_gen_video_grade.val()
                });
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
                    console.log("http://admin.leo1v1.com/player/playback.html?draw="+encodeURIComponent(result.draw_url)
                                    +"&audio="+encodeURIComponent(result.audio_url)
                                    +"&start="+result.real_begin_time);
                    if ( false && !$.check_in_phone() ) {

                        /*
                        $.wopen ("http://admin.leo1v1.com/player/playback.html?draw="+encodeURIComponent(result.draw_url)
                                    +"&audio="+encodeURIComponent(result.audio_url)
                                    +"&start="+result.real_begin_time );
                        */

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

    $(".opt-upload").on("click",function(){
        $(this).addClass('current_opt_lesson_record');
        var html_node   = $('<div></div>').html($.dlg_get_html_by_class('dlg_upload'));
        var lesson_info = new Object();

        var opt_data=$(this).get_opt_data();
        console.log(opt_data);


        lesson_info["lessonid"]= $(this).parent().data("lessonid");
        lesson_info["lesson_status"]= $(this).parents('td').siblings('.lesson_status').find('.status').val();
        lesson_info["work_status"]= $(this).parents('td').siblings('.homework_url').find('.status').val();

        html_node.find(".opt-teacher-url").attr('id', 'optid-teacher-url'+lesson_info["lessonid"]);
        html_node.find(".opt-teacher-url").parent().attr('id', 'optid-teacher-url-parent'+lesson_info["lessonid"]);
        html_node.find(".opt-student-url").attr('id', 'optid-student-url'+lesson_info["lessonid"]);
        html_node.find(".opt-student-url").parent().attr('id', 'optid-student-url-parent'+lesson_info["lessonid"]);
        html_node.find(".opt-homework-url").attr('id', 'optid-homework-url'+lesson_info["lessonid"]);
        html_node.find(".opt-homework-url").parent().attr('id', 'optid-homework-url-parent'+lesson_info["lessonid"]);
        // add lesson quiz
        html_node.find(".opt-quiz-url").attr('id', 'optid-quiz-url'+lesson_info["lessonid"]);
        html_node.find(".opt-quiz-url").parent().attr('id', 'optid-quiz-url-parent'+lesson_info["lessonid"]);

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
            onshown : function(dialog){
                $.custom_upload_file( 'optid-teacher-url'+lesson_info["lessonid"] ,
                                      false ,setCompleteTeacher, lesson_info,
                                      ["pdf","zip"], setProgress );

                $.custom_upload_file( 'optid-student-url'+lesson_info["lessonid"] ,
                                      false ,setCompleteStudent, lesson_info,
                                      ["pdf","zip"], setProgress );

                $.custom_upload_file( 'optid-quiz-url'+lesson_info["lessonid"] ,
                                      false ,setCompleteQuiz, lesson_info,
                                      ["pdf","zip"], setProgress );

                $.custom_upload_file( 'optid-homework-url'+lesson_info["lessonid"] ,
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
                    var url = "/tea_manage/get_homework_list?lessonid="+lessonid+"&grade="+grade+"&subject="+subject;
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
        var lessonid  = $(this).parent().data("lessonid");

        var html_node = $('<div></div>').html($.dlg_get_html_by_class('dlg_download'));

        console.log(html_node);
        var lesson_info           = new Object();
        lesson_info["lesson_status"] = $(this).parents('td').siblings('.lesson_status').find('.status').val();
        lesson_info["work_status"]   = $(this).parents('td').siblings('.homework_url').find('.status').val();
        lesson_info["tea_cw_url"]    = $(this).parents('td').siblings('.tea_cw_url').find('.file_url').val();
        lesson_info["stu_cw_url"]    = $(this).parents('td').siblings('.stu_cw_url').find('.file_url').val();
        lesson_info["homework_url"]  = $(this).parents('td').siblings('.homework_url').find('.file_url').val();
        lesson_info["lesson_quiz"]   = $(this).parents('td').siblings('.lesson_quiz_url').find('.file_url').val();

        html_node.find(".lesson_time").text($(this).parents('td').siblings('.lesson_time').text());
        html_node.find(".tea_nick").text($(this).parents('td').siblings('.tea_nick').text());
        html_node.find(".stu_nick").text($(this).parents('td').siblings('.stu_nick').text());

        BootstrapDialog.show({
            title   : "下载本次课的课件或作业",
            message : function(dialog) {
                html_node.find(".opt-teacher-url").on('click', function(){
                    if (!lesson_info["tea_cw_url"]) {
                        BootstrapDialog.alert("老师版课件未上传");
                        return;
                    }
                    custom_download(lesson_info["tea_cw_url"]);
                });

                html_node.find(".opt-student-url").on('click', function(){
                    if (!lesson_info["stu_cw_url"]) {
                        BootstrapDialog.alert("学生版课件未上传");
                        return;
                    }
                    custom_download(lesson_info["stu_cw_url"]);
                });

                html_node.find(".opt-homework-url").on('click', function(){
                    if (!lesson_info["homework_url"]) {
                        BootstrapDialog.alert("课后作业未上传");
                        return;
                    }
                    custom_download(lesson_info["homework_url"]);
                });

                html_node.find(".opt-quiz-url").on('click', function(){
                    if (!lesson_info["lesson_quiz"]) {
                        BootstrapDialog.alert("课堂测验未上传");
                        return;
                    }
                    custom_download(lesson_info["lesson_quiz"]);
                });

                return html_node;
            },
            buttons : [
            //     {
            //     label  : '查看老师版课件',
            //     cssClass : 'btn-warning',
            //     action : function(dialog) {

            //         window.open('./tea_imgs_show/?lessonid='+lessonid, '_blank');
            //        //                        window.open('/pdf_viewer?file='+ret.file, '_blank');


            //         dialog.close();
            //     }
            // },
                {
                label  : '返回',
                action : function(dialog) {
                    dialog.close();
                }
            }]
        });

    });






    $('.opt-score-star').on('click', function(){
        var lessonid  = $(this).parent().data("lessonid");
        var html_node = $('<div></div>').html($.dlg_get_html_by_class('dlg_score_star'));
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
                url  : '/lesson_manage/set_tea_cw_url',
                type : 'POST',
                data : {
                    'tea_cw_url' : res.key,
                    'lessonid'   : lesson_info.lessonid
                },
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

    $(".opt-small-class-or-open" ).on( "click",function( ){
        var lesson_type= $(this).get_opt_data("lesson_type");
        var lessonid = $(this).get_opt_data("lessonid");
        var courseid = $(this).get_opt_data("courseid");
        var stu_id   = $(this).get_opt_data("stu_id");
        if (lesson_type == 3001){
            $.wopen( "/small_class/index?courseid="+courseid );
        } else if (lesson_type >= 1000 && lesson_type<2000 ){
            $.wopen( "/tea_manage/open_class?lessonid="+lessonid);
        } else if (lesson_type >= 0 && lesson_type<1000 ){
            $.wopen( "/stu_manage/lesson_plan/?sid="+stu_id);
        }else{
            $(this).hide();
        }
    });

    $(".opt-out-link").on("click",function(){
        var lessonid = $(this).get_opt_data("lessonid");
        $.do_ajax( "/common/encode_text",{
            "text" : lessonid
        }, function(ret){

            //
            BootstrapDialog.alert(
                $( "<dir><font color=red> 问题报告群链接: </font> http://admin.leo1v1.com/supervisor/lesson_all_info?lessonid="+lessonid +" <br/>  <br/> 分享观看链接 : http://"+ window.location.hostname + "/tea_manage/show_lesson_video?lessonid=" + ret.text + "</div>" )  );
        });
    });


    $(".opt-qr-pad-at-time").on("click",function(){
        var lessonid= $(this).get_opt_data("lessonid");
        var url=$(this).data("type");
        var title = $(this).attr("title");
        //得到
        $.do_ajax("/tea_manage/get_lesson_xmpp_audio",{
            "lessonid" :lessonid
        },function(result){

            var data=result.data;

            var args="title=lessonid:"+lessonid+"&beginTime="+data.lesson_start+"&endTime="+data.lesson_end+"&roomId="+data.roomid+"&xmpp="+data.xmpp+"&webrtc="+data.webrtc+"&ownerId="+data.teacherid+"&type="+data.type+"&audioService="+data.audioService ;
            var args_64 = $.base64.encode(args);

            var text = encodeURIComponent(url+args_64);
            var dlg = BootstrapDialog.show({
                title: title,
                message :"<div style = \"text-align:center\"><img width=\"350px\" src=\"/common/get_qr?text="+text+"\"></img>" ,
                closable : true
            });
            //dlg.getModalDialog().css("width","800px");

        });

    });

    $(".opt-qr-pad-at-time-new").on("click",function(){
        var lessonid= $(this).get_opt_data("lessonid");
        var title = $(this).attr("title");
        //得到
        $.do_ajax("/tea_manage/get_tea_pad_lesson_qr",{
            "lessonid"  :lessonid,
            "type_flag" : 1,
        },function(result){

            console.log(result.data);
            var img_src = result.data;
            var dlg = BootstrapDialog.show({
                title: title,
                message :"<div style = \"text-align:center\"><img width=\"350px\" src=\""+img_src+"\"></img>" ,
                closable : true
            });

          });
    });

    $(".opt-qr-pad").on("click",function(){
        var lessonid= $(this).get_opt_data("lessonid");
        var url = $(this).data("type");
        var title=$(this).attr("title");
        //得到
        $.do_ajax("/tea_manage/get_lesson_xmpp_audio",{
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

    $(".opt-qr-pad-new").on("click",function(){
        var lessonid= $(this).get_opt_data("lessonid");
        var title = $(this).attr("title");
        //得到
        $.do_ajax("/tea_manage/get_tea_pad_lesson_qr",{
            "lessonid"  : lessonid,
            "type_flag" : 2,
        },function(result){

            console.log(result.data);
            var img_src = result.data;
            var dlg = BootstrapDialog.show({
                title: title,
                message :"<div style = \"text-align:center\"><img width=\"350px\" src=\""+img_src+"\"></img>" ,
                closable : true
            });

          });
    });


    // $(".opt-add-error").on("click", function(){
    //     var lessonid     = $(this).parent().data("lessonid");
    //     var courseid     = $(this).parent().data("courseid");
    //     var lesson_type  = $(this).parent().data("lesson_type");
    //     var lesson_start = $(this).parent().data("lesson_start");
    //     var lesson_end   = $(this).parent().data("lesson_end");
    //     var teacherid    = $(this).parents("td").siblings(".tea_nick").data("teacherid");
    //     var tea_nick     = $(this).parents("td").siblings(".tea_nick").text();
    //     var stu_id       = $(this).parents("td").siblings(".stu_nick").data("stu_id");
    //     var stu_nick     = $(this).parents("td").siblings(".stu_nick").text();
    //     $.do_ajax("/lesson_manage/add_error_lessonid",{
    //         "lessonid"     : lessonid,
    //         "courseid"     : courseid,
    //         "lesson_type"  : lesson_type,
    //         "lesson_start" : lesson_start,
    //         "lesson_end"   : lesson_end,
    //         "teacherid"    : teacherid,
    //         "tea_nick"     : tea_nick,
    //         "stu_id"       : stu_id,
    //         "stu_nick"     : stu_nick
    //     },function(result){
    //         if(result.ret<0){
    //             alert(result.info);
    //         }else{
    //             var url              = "/lesson_manage/error_info?lessonid="+lessonid;
    //             //window.location.href = url;
    //             window.open(url,"_blank");
    //         }
    //     });
    // });

    $("#id_studentid").val(g_args.studentid);
    $("#id_seller_adminid").val(g_args.seller_adminid);
    $('#id_lesson_del_flag').val(g_args.lesson_del_flag);


    $.admin_select_user( $("#id_studentid"), "student", load_data);

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
                $.do_ajax("/user_deal/lesson_reset_cw_info",{
                    "lessonid": lessonid
                });
            }
        });
    });

    $(".opt-confirm-test").on("click",function(){
        var opt_data = $(this).get_opt_data();

        var $fail_greater_4_hour_flag = $("<select> <option value=0>否</option> <option value=1>是</option>  </select>") ;
        var $success_flag = $("<select><option value=0>未设置</option><option value=1>成功</option><option value=2>失败</option></select>") ;
        var $test_lesson_fail_flag = $("<select></select>") ;
        var $fail_reason = $("<textarea></textarea>") ;
        Enum_map.append_option_list("test_lesson_fail_flag", $test_lesson_fail_flag, true );
        $success_flag.val(opt_data.success_flag );
        $fail_reason.val(opt_data.fail_reason);
        $test_lesson_fail_flag.val(opt_data.test_lesson_fail_flag);
        $fail_greater_4_hour_flag .val(opt_data.fail_greater_4_hour_flag);

        var arr=[
            ["确认时间限制","课程所在的次月"
             +font_color("6号0点")+"之后无法修改课程的课时数; 如:一节2018年1月任何时间的课程,在2018年2月6日0点之后无法修改"],
            ["学生", opt_data.stu_nick],
            ["老师", opt_data.tea_nick],
            ["上课时间", opt_data.lesson_time],
            ["是否成功",  $success_flag ],
            ["是否离上课4个小时以前(不付老师工资)", $fail_greater_4_hour_flag],
            ["失败类型", $test_lesson_fail_flag],
            ["失败原因", $fail_reason],
        ];

        var update_show_status = function(){
            var show_flag = $success_flag.val()==2 ;
            $fail_greater_4_hour_flag.key_value_table_show( show_flag);
            $test_lesson_fail_flag.key_value_table_show( show_flag);
            $fail_reason.key_value_table_show( show_flag);
            $test_lesson_fail_flag.html("");
            if ($fail_greater_4_hour_flag.val() ==1 ) { //不付老师工资
                Enum_map.append_option_list("test_lesson_fail_flag",$test_lesson_fail_flag,true,
                                            [100,106,107,108,109,110,111,112,113]);
            }else{
                Enum_map.append_option_list("test_lesson_fail_flag", $test_lesson_fail_flag,true,
                                            [1,2,109,110,111,112,113]);
            }
        };

        $success_flag.on("change",update_show_status);
        $fail_greater_4_hour_flag.on("change",update_show_status);

        $.show_key_value_table("课程确认", arr, {
            label    : '提交',
            cssClass : 'btn-danger',
            action   : function(dialog) {
                $.do_ajax("/ss_deal/confirm_test_lesson_ass", {
                    "lessonid"                 : opt_data.lessonid ,
                    "success_flag"             : $success_flag.val(),
                    "fail_reason"              : $fail_reason.val(),
                    "test_lesson_fail_flag"    : $test_lesson_fail_flag.val(),
                    "fail_greater_4_hour_flag" : $fail_greater_4_hour_flag.val(),
                },function(result){
                    if(result.ret==0){
                        load_data();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                });
            }
        },function(){
            update_show_status();
        });
    });

    $(".opt-confirm").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var lessonid = $(this).get_opt_data("lessonid");

        var $confirm_flag                              = $("<select> </select>");
        var $lesson_cancel_reason_type                 = $("<select> </select>");
        var $lesson_cancel_time_type                   = $("<select> </select>");
        var $lesson_cancel_reason_next_lesson_time     = $("<input/>");
        var $lesson_cancel_reason_next_lesson_end_time = $("<input/>");
        var $lesson_count                              = $("<input/>");
        var $confirm_reason                            = $("<textarea/> ");

        Enum_map.append_option_list( "confirm_flag", $confirm_flag,true,[0,1,2,3]);
        Enum_map.append_option_list( "lesson_cancel_reason_type", $lesson_cancel_reason_type,true);
        Enum_map.append_option_list( "lesson_cancel_time_type", $lesson_cancel_time_type,true);

        var arr = [
            ["确认时间限制","课程所在的次月"
             +font_color("6号0点")+"之后无法修改课程的课时数; 如:一节2018年1月任何时间的课程,在2018年2月6日0点之后无法修改"],
            ["上课完成",$confirm_flag] ,
            ["无效类型",$lesson_cancel_reason_type] ,
            ["课堂确认情况",$lesson_cancel_time_type] ,
            ["调课-上课时间",$lesson_cancel_reason_next_lesson_time],
            ["调课-下课时间",$lesson_cancel_reason_next_lesson_end_time],
            ["课时数",$lesson_count],
            ["无效说明",$confirm_reason]
        ];

        $confirm_flag.val( opt_data.confirm_flag )  ;
        $confirm_reason.val( opt_data.confirm_reason )  ;
        $lesson_cancel_reason_next_lesson_time.val( opt_data.lesson_cancel_reason_next_lesson_time )  ;
        $lesson_count.val( opt_data.lesson_count/100 )  ;
        $lesson_cancel_time_type.val( opt_data.lesson_cancel_time_type )  ;
        $lesson_cancel_reason_type.val( opt_data.lesson_cancel_reason_type )  ;

        var show_field=function (jobj,show_flag) {
            if ( show_flag ) {
                jobj.parent().parent().show();
            }else{
                jobj.parent().parent().hide();
            }
        };

        var reset_ui = function(){
            var val=$confirm_flag.val();
            if (val==1 || val==0) {
                show_field( $confirm_reason ,false );
                show_field( $lesson_cancel_reason_type,false );
                show_field( $lesson_cancel_time_type,false );
                show_field( $lesson_cancel_reason_next_lesson_time,false );
                show_field( $lesson_cancel_reason_next_lesson_end_time ,false);
                show_field( $lesson_count ,false);
            }else{
                show_field( $confirm_reason ,true);
                show_field( $lesson_cancel_reason_type,true);
                show_field( $lesson_cancel_time_type,true);
                var reason_type= $lesson_cancel_reason_type.val();
                if ( reason_type >0  && reason_type <10 ) {
                    show_field( $lesson_cancel_reason_next_lesson_time,true);
                    show_field( $lesson_cancel_reason_next_lesson_end_time,true);
                    show_field( $lesson_count,true);
                }else{
                    show_field( $lesson_cancel_reason_next_lesson_time ,false);
                    show_field( $lesson_cancel_reason_next_lesson_end_time ,false);
                    show_field( $lesson_count ,false);
                }
            }
        };

        $confirm_flag.on("change",function(){
            reset_ui();
        });
        $lesson_cancel_reason_type.on("change",function(){
            reset_ui();
        });

        $.show_key_value_table("确认课时", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/user_deal/lesson_set_confirm", {
                    "lessonid"                                  : lessonid,
                    "confirm_flag"                              : $confirm_flag.val(),
                    "confirm_reason"                            : $confirm_reason.val(),
                    "lesson_cancel_reason_next_lesson_time"     : $lesson_cancel_reason_next_lesson_time.val(),
                    "lesson_cancel_reason_next_lesson_end_time" : $lesson_cancel_reason_next_lesson_end_time.val(),
                    "lesson_cancel_reason_type"                 : $lesson_cancel_reason_type.val(),
                    "lesson_cancel_time_type"                   : $lesson_cancel_time_type.val(),
                    "lesson_count"                              : $lesson_count.val(),
                    "courseid"                                  : opt_data.courseid,
                    "lesson_type"                               : opt_data.lesson_type,
                    "subject"                                   : opt_data.subject,
                    "grade"                                     : opt_data.grade,
                    "teacherid"                                 : opt_data.teacherid,
                    "userid"                                    : opt_data.stu_id,
                    "phone"                                     : opt_data.stu_phone
                },function(result){
                    if(result.ret==0){
                        load_data();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                });
            }
        },function(){
            reset_ui();
            $lesson_cancel_reason_next_lesson_time.datetimepicker({
                datepicker : true,
                timepicker : true,
                format     : 'Y-m-d H:i',
                step       : 30,
                onChangeDateTime : function(){
                    var end_time =
                        $.strtotime($lesson_cancel_reason_next_lesson_time.val()+':00') + opt_data.lesson_diff;
                    $lesson_cancel_reason_next_lesson_end_time.val($.DateFormat(end_time,"hh:mm"));
                }
            });
            $lesson_cancel_reason_next_lesson_end_time.datetimepicker({
                datepicker : false,
                timepicker : true,
                format     : 'H:i',
                step       : 30
            });
        });
    });

    $("tr").each(function(){
        var status = $(this).find(".opt").data("performance_status");
        var html_node = "<a href=\"javascript:;\" class=\"btn fa opt-get_stu_performance\" title=\"老师评价\">课堂反馈</a>";
        if(status==1){
            $(this).find(".opt-confirm").before(html_node);
        }
    });

    $(".opt-get_stu_performance").on("click",function(){
        var opt_data       = $(this).get_opt_data();
        var lessonid       = opt_data.lessonid;
        var lesson_type    = opt_data.lesson_type;
        var tea_has_update = opt_data["tea_has_update"];
        if(lesson_type==2 && tea_has_update==1){
            get_stu_performance_for_seller(lessonid);
        }else{
            get_stu_performance(lessonid);
        }
    });

    var get_stu_performance = function(lessonid){
        var $total_judgement    = $("<select></select>");
        var $homework_situation = $("<input/>");
        var $content_grasp      = $("<input/>");
        var $lesson_interact    = $("<input/>");
        var $point_note_list    = $("<textarea/>");
        var $point_note_list2   = $("<textarea/>");
        var $stu_comment        = $("<textarea/>");
        var point_name          = '';
        var point_name2         = '';
        var point_stu_desc      = '';
        var point_stu_desc2     = '';

        Enum_map.append_option_list( "performance", $total_judgement,true);
        $.do_ajax("/tea_manage/get_stu_performance",{
            "lessonid" : lessonid
        },function(result){
            $total_judgement.val(result.total_judgement);
            $homework_situation.val(result.homework_situation);
            $content_grasp.val(result.content_grasp);
            $lesson_interact.val(result.lesson_interact);
            $stu_comment.val(result.stu_comment);
            if(result.point_name){
                point_name      = result.point_name[0];
                point_stu_desc  = result.point_stu_desc[0];
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
                ["总体评价", $stu_comment] ,
                [point_name, $point_note_list] ,
                [point_name2, $point_note_list2]
            ];

            //console.log(point_name2);

            $.show_key_value_table("课堂反馈", arr ,{
                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog) {
                    $.do_ajax("/tea_manage/set_stu_performance", {
                        "lessonid"           : lessonid,
                        "total_judgement"    : $total_judgement.val(),
                        "homework_situation" : $homework_situation.val(),
                        "content_grasp"      : $content_grasp.val(),
                        "lesson_interact"    : $lesson_interact.val(),
                        "point_name"         : point_name,
                        "point_stu_desc"     : $point_note_list.val(),
                        "point_name2"        : point_name2,
                        "point_stu_desc2"    : $point_note_list2.val(),
                        "stu_comment"        : $stu_comment.val()
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
    };

    var get_stu_performance_for_seller = function(lessonid){
        $.do_ajax('/seller_student/get_stu_performance_for_seller',{
            "lessonid":lessonid
        },function(result){
            var $stu_lesson_content     = $("<div></div>");
            var $stu_lesson_status      = $("<div></div>");
            var $stu_study_status       = $("<div></div>");
            var $stu_advantages         = $("<div></div>");
            var $stu_disadvantages      = $("<div></div>");
            var $stu_lesson_plan        = $("<div></div>");
            var $stu_teaching_direction = $("<div></div>");
            var $stu_advice             = $("<div></div>");

            var arr = [
                ["试听情况", $stu_lesson_content],
                ["学习态度", $stu_lesson_status],
                ["学习基础情况", $stu_study_status],
                ["学生优点", $stu_advantages],
                ["学生有待提高", $stu_disadvantages],
                ["培训计划", $stu_lesson_plan],
                ["教学方向", $stu_teaching_direction],
                ["意见,建议", $stu_advice],
            ];

            $stu_lesson_content.html(result.data.stu_lesson_content);
            $stu_lesson_status.html(result.data.stu_lesson_status);
            $stu_study_status.html(result.data.stu_study_status);
            $stu_advantages.html(result.data.stu_advantages);
            $stu_disadvantages.html(result.data.stu_disadvantages);
            $stu_lesson_plan.html(result.data.stu_lesson_plan);
            $stu_teaching_direction.html(result.data.stu_teaching_direction);
            $stu_advice.html(result.data.stu_advice);

            $.show_key_value_table("试听评价", arr);
        });
    };

    $(".opt-set_lesson_info").on("click",function(){
        var lessonid         = $(this).get_opt_data("lessonid");
        var id_lesson_name   = $("<input/>");
        var id_lesson_intro  = $("<input/>");
        var id_lesson_intro2 = $("<input/>");
        var arr              = [
            [ "课程名(必填)",  id_lesson_name] ,
            [ "知识点1(必填)",  id_lesson_intro] ,
            [ "知识点2(可不填)",  id_lesson_intro2] ,
        ];

        $.do_ajax("/tea_manage/get_lesson_name_and_intro",{
            "lessonid":lessonid
        },function(result){
            var lesson_info=result.data;
            if(result.data!=null){
                id_lesson_name.val(lesson_info.lesson_name);
                var lesson_point=lesson_info.lesson_intro.split("|");
                id_lesson_intro.val(lesson_point[0]);

                if(typeof(lesson_point[1])!="undefined"){
                    id_lesson_intro2.val(lesson_point[1]);
                }
            }
        });

        $.show_key_value_table("新增课程", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var lesson_name   = id_lesson_name.val();
                var lesson_intro  = id_lesson_intro.val();
                var lesson_intro2 = id_lesson_intro2.val();

                if(lesson_name == "" || lesson_intro=="")
                {
                    alert("请输入全部必填信息");
                    return;
                }

                $.ajax({
                    url: '/tea_manage/set_lesson_info',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        'lessonid'      : lessonid,
                        'lesson_name'   : lesson_name,
                        'lesson_intro'  : lesson_intro,
                        'lesson_intro2' : lesson_intro2
                    },
                    success: function(data){
                        window.location.reload();
                    }
                });
            }
        });
    });

    $(".opt-show_teacher_comment").on("click",function(){
        var opt_data            = $(this).get_opt_data();
        var id_teacher_comment  = $("<div/>");
        var id_teacher_effect   = $("<div/>");
        var id_teacher_quality  = $("<div/>");
        var id_teacher_interact = $("<div/>");
        var id_stu_stability    = $("<div/>");

        id_teacher_comment.val(opt_data.teacher_comment);
        id_teacher_effect.val(opt_data.teacher_effect);
        id_teacher_quality.val(opt_data.teacher_quality);
        id_teacher_interact.val(opt_data.teacher_interact);
        id_stu_stability.val(opt_data.stu_stability);

        var arr=[
            ["学生评语", id_teacher_comment],
            ["上课质量", id_teacher_effect],
            ["课件质量", id_teacher_quality],
            ["课堂互动", id_teacher_interact],
            ["学生端系统稳定性", id_stu_stability],
        ];
        $.show_key_value_table("查看老师评价", arr );
    });

    if (window.location.pathname =="/tea_manage/lesson_list_seller" ) {
        $("#id_test_seller_id").parent().parent().hide();
        $("#id_lesson_type").parent().parent().hide();
        if(window["self_groupid"]!= 0 && window["is_group_leader_flag"]== 0){
            $("#id_teacherid").parent().parent().hide();
        }
        $(".opt-confirm").hide();
    }

    if (window.location.pathname =="/tea_manage/lesson_list_seller" ||  window.location.pathname =="/tea_manage/lesson_list_seller/" || window.location.pathname =="/tea_manage/lesson_list_ass" || window.location.pathname =="/tea_manage/lesson_list_ass/") {
        $(".opt-seller-ass-record").show();
        $(".opt-seller-ass-record-new").show();
    }

    if (window.location.pathname =="/tea_manage/lesson_list_fulltime" ||  window.location.pathname =="/tea_manage/lesson_list_fulltime/") {
         $("#id_fulltime_teacher_type").parent().parent().hide();
    }


    $(".opt-user-video-info").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var lessonid = opt_data.lessonid;
        $.do_ajax('/tea_manage/get_user_video_info',{
            "lessonid" : lessonid
        },function(resp) {
            console.log(resp.data[0]);
            var all_num = resp.data[0]["all_num"];
            var all_user = resp.data[0]["all_user"];
            var id_all_num=$("<div/>");
            var id_all_user=$("<div/>");

            id_all_num.text(all_num);
            id_all_user.text(all_user);

            var arr=[
                ["视频回放总次数", id_all_num],
                ["视频回放总人数", id_all_user]
            ];
            $.show_key_value_table("视频回放统计", arr );

        });


    });

    $(".opt-enable_video").on("click",function(){
        var data            = $(this).get_opt_data();
        var id_enable_video = $("<select>");
        var arr             = [
            ["开启视频",id_enable_video],
        ];
        var enable_video = 0;
        Enum_map.append_option_list("boolean",id_enable_video,true);
        id_enable_video.val(data.enable_video);
        $.show_key_value_table("更改上课视频权限",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                enable_video = id_enable_video.val();
                $.do_ajax("/tea_manage/change_enable_video",{
                    "lessonid"     : data.lessonid,
                    "enable_video" : enable_video,
                },function(result){
                    BootstrapDialog.alert(result.info);
                    dialog.close();
                    load_data();
                });
            }
        });

    });

    $(".opt-send_email").on("click",function(){
        var lessonid  = $(this).get_opt_data("lessonid");
        var stu_email = $(this).get_opt_data("stu_email");
        var id_email  = $("<input />");
        var arr = [
            ["邮箱地址",id_email],
        ];
        id_email.val(stu_email);
        $.show_key_value_table("发送邮件",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/tea_manage/send_email_with_lessonid",{
                    "lessonid"  : lessonid,
                    "stu_email" : id_email.val(),
                },function(result){
                    BootstrapDialog.alert(result.info);
                    if(result.ret==0){
                        dialog.close();
                    }
                });
            }
        });
    });


    $(".opt-require_set_confirm_flag_4").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.flow_dlg_show("申请 学生课时不扣，付老师工资 ",function(){
            if($.inArray( opt_data.lesson_type*1 , [0,1, 3] ) != -1 ) {
                var $input=$("<input style=\"width:180px\"  placeholder=\"驳回理由\"/>");
                $.show_input( "要申请 学生课时不扣，付老师工资 :"+
                              opt_data.stu_nick +"-"+ opt_data.tea_nick +"-" +opt_data.lesson_time,
                              "",function(val){
                                  $.do_ajax("/user_deal/lesson_require_set_confirm_flag_4",{
                                      "lessonid": opt_data.lessonid ,
                                      'reason'  :val
                                  });
                              }, $input  );
            }else{
                alert("不可申请");
            }
        } ,3001, opt_data.lessonid );
    });

    $(".opt-require_lesson_success").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var flow_type=2003;
        var  from_key_int= opt_data.lessonid;
        $.flow_dlg_show("申请 课程成功检查",function(){
            var $input=$("<input style=\"width:180px\"  placeholder=\"理由\"/>");
            $.show_input( "要申请  课程成功检查 :"+
                          opt_data.stu_nick +"-"+ opt_data.tea_nick +"-" +opt_data.lesson_time,
                          "",function(val){
                              $.do_ajax("/user_deal/flow_add_flow",{
                                  "from_key_int" : from_key_int ,
                                  'reason'       : val,
                                  'flow_type'    : flow_type
                              });
                          }, $input  );

        }, flow_type , from_key_int );
    });





    $(".opt-log-list").on("click", function () {
        var lessonid     = $(this).parent().data("lessonid");
        var teacherid    = $(this).parent().data("teacherid");
        var stu_id       = $(this).parent().data("stu_id");
        var lesson_start = $(this).parent().data("lesson_start");
        var lesson_end   = $(this).parent().data("lesson_end");
        var lesson_type  = $(this).get_opt_data("lesson_type");
        var html_node    = $.obj_copy_node("#id_lesson_log");

        $.do_ajax("/lesson_manage/get_lesson_user_list_for_login_log", {
            "lessonid": lessonid
        }, function (ret) {
            var html_str = "";
            $.each(ret.list,function () {
                var userid = this[0];
                var name = this[1];
                html_str += "<option value=\"" + userid + "\">" + name + "</option>";
            });
            html_node.find(".opt-userid").html(html_str);

        });


        html_node.find(".form-control").on("change", function () {
            var userid = html_node.find(".opt-userid").val();
            var server_type = html_node.find(".opt-server-type").val();
            load_data_ex(lessonid, userid, server_type);
        });

        BootstrapDialog.show({
            title: "进出列表",
            message: html_node,
            closable: true
        });

        var load_data_ex = function (lessonid, userid, server_type) {
            $.ajax({
                type: "post",
                url: "/supervisor/lesson_get_log",
                dataType: "json",
                data: {
                    'lessonid': lessonid,
                    "userid": userid,
                    "server_type": server_type,
                    "teacher_id": teacherid,
                    "stu_id": stu_id,
                    "lesson_start": lesson_start,
                    "lesson_end": lesson_end
                },
                success: function (result) {
                    if (result['ret'] == 0) {
                        var data = result['data'];

                        var html_str = "";
                        $.each(data, function (i, item) {
                            var cls = "warning";
                            if (item.opt_type == "login") {
                                cls = "success";
                            }
                            if (item.opt_type == "register") {
                                cls = "warning";
                            }

                            if (item.opt_type == "logout") {
                                cls = "danger";
                            }



                            var rule_str = "";
                            if (item.userid == stu_id) {
                                rule_str = "学生";
                            } else if (item.userid == teacherid) {
                                rule_str = "老师";
                            }

                            html_str += "<tr class=\"" + cls + "\" > <td>" + item.opt_time + "<td>" + rule_str + "<td>" + item.userid + "<td>" + item.server_type + "<td>" + item.opt_type + "<td>" + item.server_ip + "</tr>";
                        });

                        html_node.find(".data-body").html(html_str);

                    }
                }
            });

        };

        load_data_ex(lessonid, -1, -1);
    });

    $(".opt-show_stu_request").on("click",function(){
        var data = $(this).get_opt_data();
        $.do_ajax("/tea_manage/get_stu_request",{
            "lessonid":data.lessonid
        },function(result){
            var info="";
            if(result.ret==0){
                info = result.data;
            }else{
                info = result.info;
            }
            BootstrapDialog.show({
              title   : "试听需求",
              message : info,
              buttons : [{
                label  : "返回",
                action : function(dialog) {
                  dialog.close();
                }
              }],
                onshown:function(){
                    $(".download_paper").on("click",function(){
                        var paper=$(this).data("paper");
                        $.custom_show_pdf(paper);
                    });
                }
            });
        })
    });


    $(".opt-seller-ass-record").on("click",function(){
        var data = $(this).get_opt_data();
        var lesson_type = data.lesson_type;
        var lessonid = data.lessonid;
        // alert(lesson_type);
        $.do_ajax('/tea_manage_new/get_seller_and_ass_lesson_info', {
            'lessonid': lessonid
        }, function(result){
            var list = result.data;
            var id_realname = $("<input readonly/>");
            var id_nick = $("<input readonly/>");
            var id_subject = $("<input readonly/>");
            var id_grade = $("<input readonly/>");

            var id_confirm_flag = $("<input readonly/>");//04-21

            var id_stu_score_info = $("<input />");
            var id_stu_character_info = $("<input />");
            var id_stu_request_test_lesson_demand    = $("<textarea />");
            var id_textbook = $("<input />");
            var id_record_info  = $("<textarea />");
            var id_is_change_teacher   = $("<select />");
            var id_tea_time = $("<input />");
            var id_record_info_url = $("<div><input class=\"record_info_url\" id=\"record_info_url\" type=\"text\" readonly ><span ><a class=\"upload_gift_pic\" id=\"id_upload_record_info\" href=\"javascript:;\">上传</a></span></div>");
            Enum_map.append_option_list( "set_boolean", id_is_change_teacher,true);
            id_realname.val(list.realname);
            id_nick.val(list.nick);
            id_subject.val(list.subject_str);
            id_grade.val(list.grade_str);

            id_confirm_flag.val(list.grade_str);////04-21

            id_textbook.val(list.textbook);
            id_is_change_teacher.val(0);
            if(lesson_type==2){
                id_stu_request_test_lesson_demand.val(list.stu_request_test_lesson_demand);
                var record_type=2;
            }else{
                id_stu_score_info.val(list.stu_score_info);
                id_stu_character_info.val(list.stu_character_info);
                var record_type=1;
            }

            var arr = [
                [ "老师",  id_realname] ,
                [ "学生",  id_nick] ,
                [ "科目",  id_subject] ,
                [ "年级",  id_grade] ,
                ["教材版本",  id_textbook ],
                ["学生成绩",  id_stu_score_info ],
                ["学生性格",  id_stu_character_info ],
                ["试听需求",  id_stu_request_test_lesson_demand ],
                ["试听后是否更换过老师",  id_is_change_teacher ],
                ["老师给学生的上课时长(天)",  id_tea_time ],
                ["问题反馈",  id_record_info ],
                ["问题反馈(图片)",  id_record_info_url ],
            ];

            $.show_key_value_table("教学质量反馈", arr ,{
                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog) {
                    $.do_ajax("/user_deal/add_seller_ass_record_info", {
                        "userid":list.userid,
                        "teacherid":list.teacherid,
                        "subject":list.subject,
                        "grade":list.grade,
                        "textbook":id_textbook.val(),
                        "stu_score_info": id_stu_score_info.val(),
                        "stu_character_info": id_stu_character_info.val(),
                        "stu_request_test_lesson_demand": id_stu_request_test_lesson_demand.val(),
                        "record_info": id_record_info.val(),
                        "record_info_url": id_record_info_url.find("#record_info_url").val(),
                        "tea_time": id_tea_time.val(),
                        "lessonid": lessonid,
                        "is_change_teacher": id_is_change_teacher.val(),
                        "type"  :record_type
                    },function(response){
                        var ret = response.ret;
                        var account = response.account;
                        if(ret==0){
                            BootstrapDialog.alert("您的教学质量反馈已提交,"+account+"老师正在紧急处理!",
                                                  function(){
                                                      window.location.reload();
                            });
                        }else{
                            BootstrapDialog.alert(response.info);
                        }
                    });
                }
            },function(){
                $.custom_upload_file('id_upload_record_info',true,function (up, info, file) {
                    var res = $.parseJSON(info);
                    $("#record_info_url").val(res.key);
                }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);
                if(lesson_type==2){
                    id_is_change_teacher.parent().parent().hide();
                    id_tea_time.parent().parent().hide();
                    id_stu_character_info.parent().parent().hide();
                    id_stu_score_info.parent().parent().hide();
                }else{
                    id_stu_request_test_lesson_demand.parent().parent().hide();
                }

            });

        });

    });

    $(".opt-seller-ass-record-new").on("click",function(){
        var data = $(this).get_opt_data();
        var lesson_type = data.lesson_type;
        var lessonid = data.lessonid;
        // alert(lesson_type);
        $.do_ajax('/tea_manage_new/get_seller_and_ass_lesson_info', {
            'lessonid': lessonid
        }, function(result){
            var list = result.data;
            var id_realname = $("<input readonly/>");
            var id_nick = $("<input readonly/>");
            var id_subject = $("<input readonly/>");
            var id_grade = $("<input readonly/>");

            var id_confirm_flag = $("<input readonly/>");//04-21

            var id_stu_score_info = $("<input />");
            var id_stu_character_info = $("<input />");
            var id_stu_request_test_lesson_demand    = $("<textarea />");
            var id_textbook = $("<input />");
            var id_record_info  = $("<textarea />");
            var id_is_change_teacher   = $("<select />");
            var id_tea_time = $("<input />");
            var id_record_info_url = $("<div><input class=\"record_info_url\" id=\"record_info_url\" type=\"text\" readonly ><span ><a class=\"upload_gift_pic\" id=\"id_upload_record_info\" href=\"javascript:;\">上传</a></span></div>");

            var tag = result.tag;
            var teacher_related_labels=$("<div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>风格性格:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"style_character\"></div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>专业能力:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"professional_ability\"> </div><div>");
            var class_related_labels=$("<div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>课堂气氛:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"classroom_atmosphere\"></div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>课件要求:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"courseware_requirements\"> </div><div>");
            var teaching_related_labels=$("<div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>素质培养:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"diathesis_cultivation\"></div>");

            // var teacher_related_labels=$("<div><div class=\"col-xs-6 col-md-3\">风格性格:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"style_character\"></div><div class=\"col-xs-6 col-md-3\">专业能力:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"professional_ability\"> </div><div>");
            // var class_related_labels=$("<div><div class=\"col-xs-6 col-md-3\">课堂气氛:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"classroom_atmosphere\"></div><div class=\"col-xs-6 col-md-3\">课件要求:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"courseware_requirements\"> </div><div>");
            // var teaching_related_labels=$("<div><div class=\"col-xs-6 col-md-3\">素质培养:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"diathesis_cultivation\"></div>");

            $.each(tag,function(i,item){
                var str="";
                $.each(item,function(ii,item_p){
                    console.log(item_p);
                    str += "<label style=\"margin-left:6px\"><input name=\""+i+"\" type=\"checkbox\" value=\""+item_p+"\" /> "+item_p+"</label>";
                });
                if(i=="风格性格"){
                    teacher_related_labels.find("#style_character").append(str);
                }else if(i=="专业能力"){
                    teacher_related_labels.find("#professional_ability").append(str);
                }else if(i=="课堂气氛"){
                    class_related_labels.find("#classroom_atmosphere").append(str);
                }else if(i=="课件要求"){
                    class_related_labels.find("#courseware_requirements").append(str);
                }else if(i=="素质培养"){
                    teaching_related_labels.find("#diathesis_cultivation").append(str);
                }
            });

            Enum_map.append_option_list( "set_boolean", id_is_change_teacher,true);
            id_realname.val(list.realname);
            id_nick.val(list.nick);
            id_subject.val(list.subject_str);
            id_grade.val(list.grade_str);

            id_confirm_flag.val(list.grade_str);////04-21

            id_textbook.val(list.textbook);
            id_is_change_teacher.val(0);
            if(lesson_type==2){
                id_stu_request_test_lesson_demand.val(list.stu_request_test_lesson_demand);
                var record_type=2;
            }else{
                id_stu_score_info.val(list.stu_score_info);
                id_stu_character_info.val(list.stu_character_info);
                var record_type=1;
            }

            var arr = [
                [ "老师",  id_realname] ,
                [ "学生",  id_nick] ,
                [ "科目",  id_subject] ,
                [ "年级",  id_grade] ,
                ["<font style=\"color:red\">*</font>&nbsp教材版本",  id_textbook ],
                ["<font style=\"color:red\">*</font>&nbsp学生成绩",  id_stu_score_info ],
                ["<font style=\"color:red\">*</font>&nbsp学生性格",  id_stu_character_info ],
                ["<font style=\"color:red\">*</font>&nbsp试听需求",  id_stu_request_test_lesson_demand ],
                ["<font style=\"color:red\">*</font>&nbsp试听后是否更换过老师",  id_is_change_teacher ],
                ["<font style=\"color:red\">*</font>&nbsp老师给学生的上课时长(天)",  id_tea_time ],
                ["<font style=\"color:red\">*</font>&nbsp问题反馈",  id_record_info ],
                ["<font style=\"color:red\">*</font>&nbsp问题反馈(图片)",  id_record_info_url ],
                ["<font style=\"color:red\">*</font>&nbsp教师相关标签",teacher_related_labels],
                ["<font style=\"color:red\">*</font>&nbsp课堂相关标签",class_related_labels],
                ["<font style=\"color:red\">*</font>&nbsp教学相关标签",teaching_related_labels],
            ];

            $.show_key_value_table("教学质量反馈", arr ,{
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
                    class_related_labels.find("#classroom_atmosphere").find("input:checkbox[name='课堂气氛']:checked").each(function(i) {
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

                    $.do_ajax("/user_deal/add_seller_ass_record_info", {
                        "userid":list.userid,
                        "teacherid":list.teacherid,
                        "subject":list.subject,
                        "grade":list.grade,
                        "textbook":id_textbook.val(),
                        "stu_score_info": id_stu_score_info.val(),
                        "stu_character_info": id_stu_character_info.val(),
                        "stu_request_test_lesson_demand": id_stu_request_test_lesson_demand.val(),
                        "record_info": id_record_info.val(),
                        "record_info_url": id_record_info_url.find("#record_info_url").val(),
                        "tea_time": id_tea_time.val(),
                        "lessonid": lessonid,
                        "is_change_teacher": id_is_change_teacher.val(),
                        "type"  :record_type,
                        "style_character"                  : JSON.stringify(style_character),
                        "professional_ability"             : JSON.stringify(professional_ability),
                        "classroom_atmosphere"             : JSON.stringify(classroom_atmosphere),
                        "courseware_requirements"          : JSON.stringify(courseware_requirements),
                        "diathesis_cultivation"            : JSON.stringify(diathesis_cultivation)
                    },function(response){
                        var ret = response.ret;
                        var account = response.account;
                        if(ret==0){
                            BootstrapDialog.alert("您的教学质量反馈已提交,"+account+"老师正在紧急处理!",function(){
                                if(result){
                                    window.location.reload();
                                }
                            });
                        }else{
                            BootstrapDialog.alert(response.info);
                        }
                    });
                }
            },function(){
                $.custom_upload_file('id_upload_record_info',true,function (up, info, file) {
                    var res = $.parseJSON(info);
                    $("#record_info_url").val(res.key);
                }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);
                if(lesson_type==2){
                    id_is_change_teacher.parent().parent().hide();
                    id_tea_time.parent().parent().hide();
                    id_stu_character_info.parent().parent().hide();
                    id_stu_score_info.parent().parent().hide();
                }else{
                    id_stu_request_test_lesson_demand.parent().parent().hide();
                }

            });

        });

    });


    if ($.get_action_str()=="lesson_list_seller") {
        $(".opt-add-error").hide();
        $(".opt-change-price").hide();
        $(".opt-score-star").hide();
        $(".opt-small-class-or-open").hide();
        $(".opt-edit-lesson-upload-time ").hide();
        $(".opt-set_lesson_info").hide();
        $(".opt-user-video-info").hide();
        $(".opt-require_set_confirm_flag_4").hide();
        $(".opt-confirm").hide();
    }

    if ($.get_action_str()=="lesson_list_ass") {
        $(".opt-require_lesson_success").hide();
    }


    //点击课程管理汇总页面
    $('.opt-manage-all').on('click',function(){
        var opt_data=$(this).get_opt_data();
        var js_pot = JSON.stringify(opt_data);
        window.open('/supervisor/lesson_all_info?lessonid='+opt_data.lessonid);
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
                    "teacherid"  : data.teacherid,
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


    $('.opt-modify-lesson-time').on("click",function(){
        var opt_data        = $(this).get_opt_data();
        var id_modify_time_start  = $('<input/>');
        var id_modify_time_end    = $('<input/>');
        $.do_ajax("/ss_deal/get_lesson_time",{
            'lessonid' : opt_data.lessonid
        },function(result){
            var data = result.data;

            id_modify_time_start.datetimepicker({
                lang:'ch',
                timepicker:true,
                format:'Y-m-d H:i'
            });

            id_modify_time_end.datetimepicker({
                lang:'ch',
                timepicker:true,
                format:'Y-m-d H:i'
            });

            id_modify_time_start.val(data.lesson_start);
            id_modify_time_end.val(data.lesson_end);

            var arr=[
                [ "课程开始时间", id_modify_time_start],
                [ "课程结束时间", id_modify_time_end],
            ];

            $.show_key_value_table("调换上课时间", arr,{
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    $.do_ajax("/ss_deal/set_lesson_time",{
                        'lessonid' :  opt_data.lessonid,
                        'lesson_start' : id_modify_time_start.val(),
                        'lesson_end'   : id_modify_time_end.val()
                    },function(result){
                        load_data();
                    });
                }
            },function(){
            });
        });
    });

    $(".opt-first-lesson-record").on("click",function(){
        var opt_data = $(this).get_opt_data();
        if(opt_data.lesson_type>=1000){
            alert("该课程不能反馈!");
            return;
        }
        var lesson_style=8;
        if(opt_data.lesson_type==2){
            lesson_style=7;
        }
        $.do_ajax("/teacher_level/check_is_have_record",{
            "lessonid"     :opt_data.lessonid
        },function(result){
            var id = result.id;
            if(id>0){
                $.do_ajax('/ss_deal/get_train_lesson_record_info',{
                    "id" : id,
                    "lessonid":opt_data.lessonid
                },function(resp) {
                    var title = "反馈详情";
                    var list = resp.data;
                    console.log(list);
                    var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>评分项</td><td>得分</td><tr></table></div>");
                    var html_score=
                        "<tr>"
                        +"<td>讲义设计情况评分</td>"
                        +"<td>"+list.tea_process_design_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>语言表达能力评分</td>"
                        +"<td>"+list.language_performance_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>专业知识技能评分</td>"
                        +"<td>"+list.knw_point_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>教学节奏把握评分</td>"
                        +"<td>"+list.tea_rhythm_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>互动情况评分</td>"
                        +"<td>"+list.tea_concentration_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>板书情况评分</td>"
                        +"<td>"+list.teacher_blackboard_writing_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>软件操作评分</td>"
                        +"<td>"+list.tea_operation_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>授课环境评分</td>"
                        +"<td>"+list.tea_environment_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>课后反馈评分</td>"
                        +"<td>"+list.answer_question_cre_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>流程规范情况评分</td>"
                        +"<td>"+list.class_abnormality_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>总分</td>"
                        +"<td>"+list.record_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>非教学相关得分</td>"
                        +"<td>"+list.no_tea_related_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>监课情况</td>"
                        +"<td>"+list.record_monitor_class+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>意见或建议</td>"
                        +"<td>"+list.record_info+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>老师标签</td>"
                        +"<td>"+list.label+"</td>"
                        +"</tr>"



                    html_node.find("table").append(html_score);
                    var dlg=BootstrapDialog.show({
                        title    : title,
                        message  : html_node,
                        closable : true,
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
                });

            }else{

                var lessonid = opt_data.lessonid;
                var teacherid = opt_data.teacherid;
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
                var id_lesson_invalid_flag =  $("<select ><option value=\"1\">有效课程</option><option value=\"2\">无效课程</option></select>");

                var id_sshd=$("<label><input name=\"Fruit\" type=\"checkbox\" value=\"6\" />幽默风趣 </label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"7\" />生动活泼 </label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"8\" />循循善诱 </label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"9\" />细致耐心 </label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"10\" />考纲熟悉 </label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"11\" />善于互动</label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"12\" />没有口音</label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"13\" />经验丰富</label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"14\" />功底扎实</label> ");

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
                    ["课程有效性", id_lesson_invalid_flag],
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
                    ["标签",id_sshd],
                ];
                id_lesson_invalid_flag.on("change",function(){
                    if($(this).val() ==2){
                        id_jysj.parent().parent().hide();
                        id_yybd.parent().parent().hide();
                        id_zyzs.parent().parent().hide();
                        id_jxjz.parent().parent().hide();
                        id_hdqk.parent().parent().hide();
                        id_bsqk.parent().parent().hide();
                        id_skhj.parent().parent().hide();
                        id_khfk.parent().parent().hide();
                        id_lcgf.parent().parent().hide();
                        id_score.parent().parent().hide();
                        id_no_tea_score.parent().parent().hide();
                        id_jkqk.parent().parent().hide();
                        id_sshd.parent().parent().hide();
                        id_rjcz.parent().parent().hide();

                    }else{
                        id_jysj.parent().parent().show();
                        id_yybd.parent().parent().show();
                        id_zyzs.parent().parent().show();
                        id_jxjz.parent().parent().show();
                        id_hdqk.parent().parent().show();
                        id_bsqk.parent().parent().show();
                        id_skhj.parent().parent().show();
                        id_khfk.parent().parent().show();
                        id_lcgf.parent().parent().show();
                        id_score.parent().parent().show();
                        id_no_tea_score.parent().parent().show();
                        id_jkqk.parent().parent().show();
                        id_sshd.parent().parent().show();
                        id_rjcz.parent().parent().show();

                    }

                });


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

                        $.do_ajax("/teacher_level/set_teacher_record_info",{
                            "teacherid"    : teacherid,
                            "lesson_invalid_flag":id_lesson_invalid_flag.val(),
                            "userid"    : opt_data.stu_id,
                            // "id"    : opt_data.id,
                            "type"         : 1,
                            "lesson_style" : lesson_style,
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
                            "sshd_good"                          :JSON.stringify(sshd_good),
                            "lessonid"                           :lessonid,
                            "lesson_list"                        :JSON.stringify(lessonid),
                            "train_type"                         :'',
                            "subject"                            :'',
                        });
                    }
                },function(){
                    id_score.attr("placeholder","满分100分");
                    id_record.attr("placeholder","字数不能超过150字");
                });

                //console.log(arr[0][1]);
                $(arr[0][1]).parent().parent().parent().parent().parent().parent().parent().find(".class_score").on("change",function(){
                    id_score.val(parseInt(id_jysj.val())+parseInt(id_yybd.val())+parseInt(id_zyzs.val())+parseInt(id_jxjz.val())+parseInt(id_hdqk.val())+parseInt(id_bsqk.val())+parseInt(id_rjcz.val())+parseInt(id_skhj.val())+parseInt(id_khfk.val())+parseInt(id_lcgf.val()));
                    id_no_tea_score.val(parseInt(id_hdqk.val())+parseInt(id_bsqk.val())+parseInt(id_rjcz.val())+parseInt(id_skhj.val())+parseInt(id_khfk.val())+parseInt(id_lcgf.val()));


                });
            }

        });


    });

    $(".opt-first-lesson-record-new").on("click",function(){
        var opt_data = $(this).get_opt_data();
        if(opt_data.lesson_type>=1000){
            alert("该课程不能反馈!");
            return;
        }
        var lesson_style=8;
        if(opt_data.lesson_type==2){
            lesson_style=7;
        }
        $.do_ajax("/teacher_level/check_is_have_record",{
            "lessonid"     :opt_data.lessonid
        },function(result){
            var id = result.id;
            if(id>0){
                $.do_ajax('/ss_deal/get_train_lesson_record_info',{
                    "id" : id,
                    "lessonid":opt_data.lessonid
                },function(resp) {
                    var title = "反馈详情";
                    var list = resp.data;
                    console.log(list);
                    var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>评分项</td><td>得分</td><tr></table></div>");
                    var html_score=
                        "<tr>"
                        +"<td>讲义设计情况评分</td>"
                        +"<td>"+list.tea_process_design_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>语言表达能力评分</td>"
                        +"<td>"+list.language_performance_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>专业知识技能评分</td>"
                        +"<td>"+list.knw_point_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>教学节奏把握评分</td>"
                        +"<td>"+list.tea_rhythm_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>互动情况评分</td>"
                        +"<td>"+list.tea_concentration_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>板书情况评分</td>"
                        +"<td>"+list.teacher_blackboard_writing_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>软件操作评分</td>"
                        +"<td>"+list.tea_operation_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>授课环境评分</td>"
                        +"<td>"+list.tea_environment_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>课后反馈评分</td>"
                        +"<td>"+list.answer_question_cre_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>流程规范情况评分</td>"
                        +"<td>"+list.class_abnormality_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>总分</td>"
                        +"<td>"+list.record_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>非教学相关得分</td>"
                        +"<td>"+list.no_tea_related_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>监课情况</td>"
                        +"<td>"+list.record_monitor_class+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>意见或建议</td>"
                        +"<td>"+list.record_info+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>老师标签</td>"
                        +"<td>"+list.label+"</td>"
                        +"</tr>"



                    html_node.find("table").append(html_score);
                    var dlg=BootstrapDialog.show({
                        title    : title,
                        message  : html_node,
                        closable : true,
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
                });

            }else{

                var tag = result.tag;
                // var teacher_related_labels=$("<div><div class=\"col-xs-6 col-md-3\">风格性格:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"style_character\"></div><div class=\"col-xs-6 col-md-3\">专业能力:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"professional_ability\"> </div><div>");
                // var class_related_labels=$("<div><div class=\"col-xs-6 col-md-3\">课堂气氛:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"classroom_atmosphere\"></div><div class=\"col-xs-6 col-md-3\">课件要求:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"courseware_requirements\"> </div><div>");
                // var teaching_related_labels=$("<div><div class=\"col-xs-6 col-md-3\">素质培养:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"diathesis_cultivation\"></div>");
                var teacher_related_labels=$("<div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>风格性格:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"style_character\"></div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>专业能力:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"professional_ability\"> </div><div>");
                var class_related_labels=$("<div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>课堂气氛:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"classroom_atmosphere\"></div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>课件要求:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"courseware_requirements\"> </div><div>");
                var teaching_related_labels=$("<div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>素质培养:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"diathesis_cultivation\"></div>");


                $.each(tag,function(i,item){
                    var str="";
                    $.each(item,function(ii,item_p){
                        console.log(item_p);
                        str += "<label style=\"margin-left:6px\"><input name=\""+i+"\" type=\"checkbox\" value=\""+item_p+"\" /> "+item_p+"</label>";
                    });
                    if(i=="风格性格"){
                        teacher_related_labels.find("#style_character").append(str);
                    }else if(i=="专业能力"){
                        teacher_related_labels.find("#professional_ability").append(str);
                    }else if(i=="课堂气氛"){
                        class_related_labels.find("#classroom_atmosphere").append(str);
                    }else if(i=="课件要求"){
                        class_related_labels.find("#courseware_requirements").append(str);
                    }else if(i=="素质培养"){
                        teaching_related_labels.find("#diathesis_cultivation").append(str);
                    }
                });


                var lessonid = opt_data.lessonid;
                var teacherid = opt_data.teacherid;
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
                var id_lesson_invalid_flag =  $("<select ><option value=\"1\">有效课程</option><option value=\"2\">无效课程</option></select>");



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
                    ["课程有效性", id_lesson_invalid_flag],
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
                    ["<font style=\"color:red\">*</font>&nbsp意见或建议",id_record],
                    ["<font style=\"color:red\">*</font>&nbsp教师相关标签",teacher_related_labels],
                    ["<font style=\"color:red\">*</font>&nbsp课堂相关标签",class_related_labels],
                    ["<font style=\"color:red\">*</font>&nbsp教学相关标签",teaching_related_labels],

                   // ["标签",id_sshd],
                ];
                id_lesson_invalid_flag.on("change",function(){
                    if($(this).val() ==2){
                        id_jysj.parent().parent().hide();
                        id_yybd.parent().parent().hide();
                        id_zyzs.parent().parent().hide();
                        id_jxjz.parent().parent().hide();
                        id_hdqk.parent().parent().hide();
                        id_bsqk.parent().parent().hide();
                        id_skhj.parent().parent().hide();
                        id_khfk.parent().parent().hide();
                        id_lcgf.parent().parent().hide();
                        id_score.parent().parent().hide();
                        id_no_tea_score.parent().parent().hide();
                        id_jkqk.parent().parent().hide();
                        teacher_related_labels.parent().parent().hide();
                        class_related_labels.parent().parent().hide();
                        teaching_related_labels.parent().parent().hide();
                      //  id_sshd.parent().parent().hide();
                        id_rjcz.parent().parent().hide();

                    }else{
                        id_jysj.parent().parent().show();
                        id_yybd.parent().parent().show();
                        id_zyzs.parent().parent().show();
                        id_jxjz.parent().parent().show();
                        id_hdqk.parent().parent().show();
                        id_bsqk.parent().parent().show();
                        id_skhj.parent().parent().show();
                        id_khfk.parent().parent().show();
                        id_lcgf.parent().parent().show();
                        id_score.parent().parent().show();
                        id_no_tea_score.parent().parent().show();
                        id_jkqk.parent().parent().show();
                        teacher_related_labels.parent().parent().show();
                        class_related_labels.parent().parent().show();
                        teaching_related_labels.parent().parent().show();
                       // id_sshd.parent().parent().show();
                        id_rjcz.parent().parent().show();

                    }

                });


                $.show_key_value_table("试听评价", arr,{
                    label    : '确认',
                    cssClass : 'btn-warning',
                    action   : function(dialog) {
                        var record_info = id_record.val();
                        if(record_info==""){
                            BootstrapDialog.alert("请填写意见或建议内容!");
                            return ;
                        }
                        console.log(record_info.length);
                        if(record_info.length>150){
                            BootstrapDialog.alert("评价内容不能超过150字!");
                            return ;
                        }

                        var style_character=[];
                        teacher_related_labels.find("#style_character").find("input:checkbox[name='风格性格']:checked").each(function(i) {
                            style_character.push($(this).val());
                        });
                        var professional_ability=[];
                        teacher_related_labels.find("#professional_ability").find("input:checkbox[name='专业能力']:checked").each(function(i) {
                            professional_ability.push($(this).val());
                        });
                        var classroom_atmosphere=[];
                        class_related_labels.find("#classroom_atmosphere").find("input:checkbox[name='课堂气氛']:checked").each(function(i) {
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
                        if((courseware_requirements.length ==0 || style_character.length==0 || professional_ability.length==0 || classroom_atmosphere.length==0 || diathesis_cultivation.length==0) && id_lesson_invalid_flag.val() ==1){
                            BootstrapDialog.alert("请填写标签内容");
                            return ;

                        }




                        $.do_ajax("/teacher_level/set_teacher_record_info",{
                            "teacherid"    : teacherid,
                            "lesson_invalid_flag":id_lesson_invalid_flag.val(),
                            "userid"    : opt_data.stu_id,
                            // "id"    : opt_data.id,
                            "type"         : 1,
                            "lesson_style" : lesson_style,
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
                          //  "sshd_good"                          :JSON.stringify(sshd_good),
                            "lessonid"                           :lessonid,
                            "lesson_list"                        :JSON.stringify(lessonid),
                            "train_type"                         :'',
                            "subject"                            :'',
                            "style_character"                  : JSON.stringify(style_character),
                            "professional_ability"             : JSON.stringify(professional_ability),
                            "classroom_atmosphere"             : JSON.stringify(classroom_atmosphere),
                            "courseware_requirements"          : JSON.stringify(courseware_requirements),
                            "diathesis_cultivation"            : JSON.stringify(diathesis_cultivation),
                            "new_tag_flag" : 1
                        });
                    }
                },function(){
                    id_score.attr("placeholder","满分100分");
                    id_record.attr("placeholder","字数不能超过150字");
                });

                //console.log(arr[0][1]);
                $(arr[0][1]).parent().parent().parent().parent().parent().parent().parent().find(".class_score").on("change",function(){
                    id_score.val(parseInt(id_jysj.val())+parseInt(id_yybd.val())+parseInt(id_zyzs.val())+parseInt(id_jxjz.val())+parseInt(id_hdqk.val())+parseInt(id_bsqk.val())+parseInt(id_rjcz.val())+parseInt(id_skhj.val())+parseInt(id_khfk.val())+parseInt(id_lcgf.val()));
                    id_no_tea_score.val(parseInt(id_hdqk.val())+parseInt(id_bsqk.val())+parseInt(id_rjcz.val())+parseInt(id_skhj.val())+parseInt(id_khfk.val())+parseInt(id_lcgf.val()));


                });
            }

        });


    });



   // download_hide();
    $(".opt-download").show();
    $(".opt-teacher-url").show();
    $(".opt-student-url").show();
    $(".opt-homework-url").show();
    $(".opt-quiz-url").show();
});
