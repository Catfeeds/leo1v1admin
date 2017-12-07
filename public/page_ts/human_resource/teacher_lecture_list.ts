/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-teacher_lecture_list.d.ts" />
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
			      grade            : $('#id_grade').val(),
			      trans_grade      : $('#id_trans_grade').val(),
			      subject          : $('#id_subject').val(),
            identity         : $('#id_identity').val(),
			      status           : $('#id_status').val(),
			      phone            : $('#id_phone').val(),
			      teacherid        : $('#id_teacherid').val(),
			      is_test_flag     : $('#id_is_test_flag').val(),
			      full_time        : $('#id_full_time').val(),
			      have_wx:	$('#id_have_wx').val(),
                  id_train_through_new_time:$("#id_train_through_new_time").val(),
                  id_train_through_new:$("#id_train_through_new").val(),
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

    Enum_map.append_option_list("grade",$("#id_grade"));
    Enum_map.append_option_list("subject",$("#id_subject"));
    Enum_map.append_option_list("identity",$("#id_identity"));
    Enum_map.append_option_list("check_status",$("#id_status"));
    Enum_map.append_option_list("is_test",$("#id_is_test_flag"));
    Enum_map.append_option_list("boolean",$("#id_trans_grade"));
    Enum_map.append_option_list("boolean",$("#id_have_wx"));
    Enum_map.append_option_list("boolean",$("#id_full_time"));

	$('#id_grade').val(g_args.grade);
	$('#id_trans_grade').val(g_args.trans_grade);
	  $('#id_subject').val(g_args.subject);
    $('#id_identity').val(g_args.identity);
	$('#id_status').val(g_args.status);
	$('#id_phone').val(g_args.phone);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_is_test_flag').val(g_args.is_test_flag);
	$('#id_have_wx').val(g_args.have_wx);
	$('#id_full_time').val(g_args.full_time);
    $.admin_select_user( $("#id_teacherid"), "teacher", load_data);
    $("#id_train_through_new").val(g_args.id_train_through_new);
    $("#id_train_through_new_time").val(g_args.id_train_through_new_time);

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

 

    $(".opt-play").on("click", function(){
        var opt_data  = $(this).get_opt_data();
        var account   = opt_data.account;
        var draw_url  = opt_data.draw;
        var audio_url = opt_data.audio;
        var start     = opt_data.real_begin_time;
        var phone    = $(this).get_opt_data("phone");
        var subject  = $(this).get_opt_data("subject");
        var grade   = $(this).get_opt_data("grade");
        $.do_ajax("/tea_manage_new/get_re_submit_num",{
            "phone"   : phone,
            "subject" : subject,
            "grade"   : grade
        },function(result){
            var num = result.num;
            console.log(num);
            if(num>4 && account=="" && opt_data.phone != 13079618620){
                BootstrapDialog.alert("该老师重审次数超过1次,不能再审核,请联系技术人员处理!!");
                return;
            }
        });

        $.do_ajax("/human_resource/get_week_confirm_num",{
            "adminid" : g_adminid
        },function(result){
            var num = result.data;
            if(num >=20){
                alert("您本周面试名额已用完!如有疑问,请联系Erick!");
                return ;
            }else{
                if(audio_url.substring(0,4)!="http"){
                    BootstrapDialog.alert("错误!"+audio_url);
                    return false;
                }

                if(acc==account  || account_role=="10" || account_role=="11"  || account_role=="12" || account_role=="8" ){
                    if($.check_in_phone()){
                        var w = $.check_in_phone()?329:558;
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
                        Cwhiteboard.loadData(w,h,start,draw_url,audio_url,html_node);
                    }else{
                        window.open("http://admin.leo1v1.com/player/playback.html?draw="+draw_url
                                    +"&audio="+audio_url
                                    +"&start="+start,"_blank");
                        window.location.reload();
                    }
                }else if(account != "" && (acc=="wander" || acc=="nick" || acc=="jack" || acc=="ted")){
                    if($.check_in_phone()){
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
                        Cwhiteboard.loadData(w,h,start,draw_url,audio_url,html_node);
                    }else{
                        window.open("http://admin.leo1v1.com/player/playback.html?draw="+draw_url
                                    +"&audio="+audio_url
                                    +"&start="+start,"_blank");
                        window.location.reload(); 
                    }
                }else if(account==""){
                    $.do_ajax("/human_resource/set_teacher_lecture_account",{
                        "id"  : opt_data.id,
                        "acc" : acc,
                    },function(result){
                        if(result.ret==-1){
                            BootstrapDialog.alert(result.info);
                        }else{
                            if($.check_in_phone()){
                                var w = $.check_in_phone()?329 : 558;
                                var h = w/4*3;
                                var html_node = $(
                                    "<div style=\"text-align:center;\"> "
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
                                Cwhiteboard.loadData(w,h,start,draw_url,audio_url,html_node);
                            }else{
                                window.open("http://admin.leo1v1.com/player/playback.html?draw="+draw_url
                                            +"&audio="+audio_url
                                            +"&start="+start,"_blank");
                                window.location.reload(); 
                            }
                        }
                    });
                }
            }
        });
    });
    
    $(".opt-confirm-score_new").on("click",function(){
        var id_sshd=$(" <label><input name=\"Fruit\" type=\"checkbox\" value=\"2\" />鼓励学生发言 </label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"3\" />提问形式多样化 </label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"4\" />关注学生反馈 </label><label><input name=\"Fruit\" type=\"checkbox\" value=\"5\" />擅长举一反三 </label><label><input name=\"Fruit\" type=\"checkbox\" value=\"6\" />善于引导学生</label> ");
        var id_sshd2=$("<label><input name=\"dog\" type=\"checkbox\" value=\"7\" />空话套话过多 </label> <label><input name=\"dog\" type=\"checkbox\" value=\"8\" />Yes/No问题过多 </label> <label><input name=\"dog\" type=\"checkbox\" value=\"9\" />提问但不关注学生回答 </label> <label><input name=\"dog\" type=\"checkbox\" value=\"10\" />缺少课前暖场 </label><label><input name=\"dog\" type=\"checkbox\" value=\"11\" />自顾讲解,缺少沟通 </label> ");

        var id_ktfw=$("<label><input name=\"ktfw\" type=\"checkbox\" value=\"1\" />语调轻快 </label> <label><input name=\"ktfw\" type=\"checkbox\" value=\"2\" />激昂热情 </label> <label><input name=\"ktfw\" type=\"checkbox\" value=\"3\" />情感渲染力强 </label> <label><input name=\"ktfw\" type=\"checkbox\" value=\"4\" />课程设计紧凑 </label><label><input name=\"ktfw\" type=\"checkbox\" value=\"5\" />轻松愉快</label><label><input name=\"ktfw\" type=\"checkbox\" value=\"6\" />学术氛围浓厚</label> ");
        var id_ktfw2=$("<label><input name=\"kt\" type=\"checkbox\" value=\"7\" />语速过慢,节奏拖沓 </label> <label><input name=\"kt\" type=\"checkbox\" value=\"8\" />语调沉闷,缺少生气 </label> <label><input name=\"kt\" type=\"checkbox\" value=\"9\" />语速过快,匆忙急促 </label>  ");
        var id_skgf=$("<label><input name=\"skgf\" type=\"checkbox\" value=\"1\" />软件操作熟练 </label> <label><input name=\"skgf\" type=\"checkbox\" value=\"2\" />讲义规范 </label> <label><input name=\"skgf\" type=\"checkbox\" value=\"3\" />截图合理 </label> <label><input name=\"skgf\" type=\"checkbox\" value=\"4\" />板书规范 </label> ");
        var id_skgf2=$("<label><input name=\"sk\" type=\"checkbox\" value=\"5\" />软件操作不熟练 </label> <label><input name=\"sk\" type=\"checkbox\" value=\"6\" />讲义不规范 </label> <label><input name=\"sk\" type=\"checkbox\" value=\"7\" />截图不合理 </label><label><input name=\"sk\" type=\"checkbox\" value=\"8\" />板书不规范 </label>  ");
        var id_jsfg=$("<label><input name=\"jsfg\" type=\"checkbox\" value=\"1\" />平易近人 </label> <label><input name=\"jsfg\" type=\"checkbox\" value=\"2\" />生动活泼</label> <label><input name=\"jsfg\" type=\"checkbox\" value=\"3\" />幽默风趣 </label> <label><input name=\"jsfg\" type=\"checkbox\" value=\"4\" />严谨认真 </label> ");
        var id_jsfg2=$("<label><input name=\"js\" type=\"checkbox\" value=\"5\" />咄咄逼人</label> <label><input name=\"js\" type=\"checkbox\" value=\"6\" />沉闷乏味 </label> <label><input name=\"js\" type=\"checkbox\" value=\"7\" />缺乏课堂主导性 </label><label><input name=\"js\" type=\"checkbox\" value=\"8\" />散漫随性 </label>  ");


        var arr = [
            ["标签-师生互动(好)",id_sshd],
            ["标签-师生互动(不好)",id_sshd2],
            ["标签-课堂氛围(好)",id_ktfw],
            ["标签-课堂氛围(不好)",id_ktfw2],
            ["标签-授课规范(好)",id_skgf],
            ["标签-授课规范(不好)",id_skgf2],
            ["标签-教师风格(好)",id_jsfg],
            ["标签-教师风格(不好)",id_jsfg2]
        ];
        
        
        $.show_key_value_table("设置标签",arr,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                var sshd_good=[];
                id_sshd.find("input:checkbox[name='Fruit']:checked").each(function(i) {
                    sshd_good.push($(this).val());
                });
                var sshd_bad=[];
                id_sshd2.find("input:checkbox[name='dog']:checked").each(function(i) {
                    sshd_bad.push($(this).val());
                });
                var ktfw_good=[];
                id_ktfw.find("input:checkbox[name='ktfw']:checked").each(function(i) {
                    ktfw_good.push($(this).val());
                });
                var ktfw_bad=[];
                id_ktfw2.find("input:checkbox[name='kt']:checked").each(function(i) {
                    ktfw_bad.push($(this).val());
                });
                var skgf_good=[];
                id_skgf.find("input:checkbox[name='skgf']:checked").each(function(i) {
                    skgf_good.push($(this).val());
                });
                var skgf_bad=[];
                id_skgf2.find("input:checkbox[name='sk']:checked").each(function(i) {
                    skgf_bad.push($(this).val());
                });
                var jsfg_good=[];
                id_jsfg.find("input:checkbox[name='jsfg']:checked").each(function(i) {
                    jsfg_good.push($(this).val());
                });
                var jsfg_bad=[];
                id_jsfg2.find("input:checkbox[name='js']:checked").each(function(i) {
                    jsfg_bad.push($(this).val());
                });


                alert(jsfg_bad.length);
                
            }
        },function(){
            

        });

    });

    $(".opt-confirm-score").on("click",function(){
        var id        = $(this).get_opt_data("id");
        var teacher_accuracy_score         = $(this).get_opt_data("teacher_accuracy_score");
        var retrial_info        = $(this).get_opt_data("retrial_info");
        var status_str        = $(this).get_opt_data("status_str");
        
        $.do_ajax('/ss_deal/get_teacher_confirm_score',{
            "id" : id
        },function(resp) {
            var title = "审核评分详情";
            var list = resp.data;
            if(teacher_accuracy_score>0){
                var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>评分大类</td><td>评分细分</td><td>得分</td><tr></table></div>");

                if(list.self_introduction_by_eng ==1){
                    var self_flag="有";
                }else{
                    var self_flag="无";
                }
                var html_score=
                    "<tr>"
                    +"<td>教师气场</td>"
                    +"<td>"+list.teacher_mental_aura+"</td>"
                    +"<td>"+list.teacher_mental_aura_score+"</td>"
                    +"</tr>"
                    +"<tr>"
                    +"<td>教师经验</td>"
                    +"<td>"+list.teacher_exp+"</td>"
                    +"<td>"+list.teacher_exp_score+"</td>"
                    +"</tr>"
                    +"<tr>"
                    +"<td>课堂氛围</td>"
                    +"<td>"+list.teacher_class_atm+"</td>"
                    +"<td>"+list.teacher_class_atm_score+"</td>"
                    +"</tr>"
                    +"<tr>"
                    +"<td>知识点讲解</td>"
                    +"<td>"+list.teacher_point_explanation+"</td>"
                    +"<td>"+list.teacher_point_explanation_score+"</td>"
                    +"</tr>"
                    +"<tr>"
                    +"<td>讲题方法思路</td>"
                    +"<td>"+list.teacher_point_explanation+"</td>"
                    +"<td>"+list.teacher_point_explanation_score+"</td>"
                    +"</tr>"
                    +"<tr>"
                    +"<td>讲题方法思路/英语发音,语音读音错误描述</td>"
                    +"<td>"+list.teacher_method+"</td>"
                    +"<td>"+list.teacher_method_score+"</td>"
                    +"</tr>"
                    +"<tr>"
                    +"<td>知识点与练习比例</td>"
                    +"<td>"+list.teacher_knw_point+"</td>"
                    +"<td>"+list.teacher_knw_point_score+"</td>"
                    +"</tr>"
                    +"<tr>"
                    +"<td>重难点把握</td>"
                    +"<td>"+list.teacher_dif_point+"</td>"
                    +"<td>"+list.teacher_dif_point_score+"</td>"
                    +"</tr>"
                    +"<tr>"
                    +"<td>板书</td>"
                    +"<td>"+list.teacher_blackboard_writing+"</td>"
                    +"<td>"+list.teacher_blackboard_writing_score+"</td>"
                    +"</tr>"
                    +"<tr>"
                    +"<td>讲解节奏</td>"
                    +"<td>"+list.teacher_explain_rhythm+"</td>"
                    +"<td>"+list.teacher_explain_rhythm_score+"</td>"
                    +"</tr>"
                    +"<tr>"
                    +"<td>语言表达和组织能力</td>"
                    +"<td>"+list.teacher_language_performance+"</td>"
                    +"<td>"+list.teacher_language_performance_score+"</td>"
                    +"</tr>"
                    +"<tr>"
                    +"<td>教师端操作</td>"
                    +"<td>"+list.teacher_operation+"</td>"
                    +"<td>"+list.teacher_operation_score+"</td>"
                    +"</tr>"
                    +"<tr>"
                    +"<td>周边环境</td>"
                    +"<td>"+list.teacher_environment+"</td>"
                    +"<td>"+list.teacher_environment_score+"</td>"
                    +"</tr>"
                    +"<tr>"
                    +"<td>英语自我介绍</td>"
                    +"<td colspan=\"2\">"+self_flag+"</td>"
                    +"</tr>"
                    +"<tr>"
                    +"<td>总分</td>"
                    +"<td colspan=\"2\">"+list.teacher_lecture_score+"</td>"
                    +"</tr>"
                    +"<tr>"
                    +"<td>准确率</td>"
                    +"<td colspan=\"2\">"+list.teacher_accuracy_score+"%</td>"
                    +"</tr>";
                
            }else{
                if(retrial_info && list.teacher_lecture_score==0){
                    var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>结果</td><td>原因</td><tr></table></div>");
                    var html_score=
                        "<tr>"
                        +"<td>"+status_str+"</td>"
                        +"<td>"+list.reason+"</td>"
                        +"</tr>";
                    
                }else{
                    var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>评分项</td><td>得分</td><tr></table></div>");
                    var html_score=
                        "<tr>"
                        +"<td>讲义内容设计</td>"
                        +"<td>"+list.lecture_content_design_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>讲练结合情况</td>"
                        +"<td>"+list.lecture_combined_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>知识点正确率</td>"
                        +"<td>"+list.teacher_point_explanation_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>重难点偏向性</td>"
                        +"<td>"+list.teacher_dif_point_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>课程回顾总结</td>"
                        +"<td>"+list.course_review_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>教师气场把控</td>"
                        +"<td>"+list.teacher_mental_aura_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>课堂氛围营造</td>"
                        +"<td>"+list.teacher_class_atm_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>教学节奏把握</td>"
                        +"<td>"+list.teacher_explain_rhythm_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>板书书写规范</td>"
                        +"<td>"+list.teacher_blackboard_writing_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>语言表达能力</td>"
                        +"<td>"+list.teacher_language_performance_score+"</td>"
                        +"</tr>"
                        +"<tr>"
                        +"<td>总分</td>"
                        +"<td>"+list.teacher_lecture_score+"</td>"
                        +"</tr>"
                        +"<tr>";
                }
            }

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
    });

    $(".opt-edit-new").on("click",function(){
        var data     = $(this).get_opt_data();
        var id       = $(this).get_opt_data("id");
        var status   = $(this).get_opt_data("status");
        var reason   = $(this).get_opt_data("reason");
        var tea_nick = $(this).get_opt_data("nick");
        var phone    = $(this).get_opt_data("phone");
        var identity = $(this).get_opt_data("identity");
        var subject  = $(this).get_opt_data("subject");
        var grade   = $(this).get_opt_data("grade");
        $.do_ajax("/tea_manage_new/get_re_submit_num",{
            "phone"   : phone,
            "subject" : subject,
            "grade"   : grade
        },function(result){
            var num = result.num;
            console.log(num);
            if(num>4  && data.phone != 13079618620){
                BootstrapDialog.alert("该老师没有审核机会了!!");
                return;
            }else{
                var id_re_submit=$("<label><input name=\"re_submit\" type=\"checkbox\" value=\"1\" />授课环境不佳</label> <label><input name=\"re_submit\" type=\"checkbox\" value=\"2\" />授课内容错误 </label><label><input name=\"re_submit\" type=\"checkbox\" value=\"7\" />无自我介绍(英语科目) </label><label><input name=\"re_submit\" type=\"checkbox\" value=\"100\" />其他</label> ");
                var id_lecture_out=$("<label><input name=\"lecture_out\" type=\"checkbox\" value=\"3\" />语速过慢/过快 </label> <label><input name=\"lecture_out\" type=\"checkbox\" value=\"4\" />语调沉闷 </label> <label><input name=\"lecture_out\" type=\"checkbox\" value=\"5\" />节奏拖沓 </label><label><input name=\"lecture_out\" type=\"checkbox\" value=\"6\" />枯燥乏味 </label> <label><input name=\"lecture_out\" type=\"checkbox\" value=\"8\" />解题错误</label><label><input name=\"lecture_out\" type=\"checkbox\" value=\"9\" />普通话发音不标准</label><label><input name=\"lecture_out\" type=\"checkbox\" value=\"10\" />英文发音不标准</label><label><input name=\"lecture_out\" type=\"checkbox\" value=\"100\" />其他</label>");
                var id_reason_all = $("<textarea/>");

                var arr = [
                    ["可重审",id_re_submit],
                    ["未通过",id_lecture_out],
                    ["原因/建议",id_reason_all]
                ];

                $.show_key_value_table("重审淘汰判断",arr,{
                    label    : '确认',
                    cssClass : 'btn-warning',
                    action   : function(dialog) {
                        var re_submit_list=[];
                        id_re_submit.find("input:checkbox[name='re_submit']:checked").each(function(i) {
                            re_submit_list.push($(this).val());
                        });
                        var lecture_out_list=[];
                        id_lecture_out.find("input:checkbox[name='lecture_out']:checked").each(function(i) {
                            lecture_out_list.push($(this).val());
                        });  
                        if(re_submit_list.length==0&& lecture_out_list.length==0){
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
                            var id_self_introduction = $("<select/>");

                            var id_total_score = $("<input readonly /> ");
                            var id_res = $("<input readonly/> <span style=\"font-size:11px\">");
                            var id_identity      = $("<select/>");
                            var id_work_year     = $("<input />");
                            var id_not_grade     = $("<div />");
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

                            if(identity==11){
                                identity=2;
                            }
                            var not_grade         = data.not_grade;
                            var grade_start       = data.grade_start;
                            var grade_end         = data.grade_end;
                            var trans_grade_start = data.trans_grade_start;
                            var trans_grade_end   = data.trans_grade_end;
                            var trans_grade       = data.trans_grade;
                            if(trans_grade>0){
                                grade_start = trans_grade_start;
                                grade_end   = trans_grade_end;
                            }

                            var id_not_grade_list = ["101","102","103","104","105","106","201","202","203","301","302","303"];
                            Enum_map.append_checkbox_list("grade",id_not_grade,"not_grade",id_not_grade_list);

                            var not_grade_arr=check_data_to_arr(not_grade,",");
                            id_reason.val(reason);
                            id_identity.val(identity);

                            var arr=[
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
                                ["老师类型",id_identity],
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


                                    $.do_ajax("/human_resource/set_teacher_lecture_status_new",{
                                        "id"                                 : id,
                                        "appointment_id"                     : data.appointment_id,
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
                                        "reason"                             : id_reason.val(),
                                        "identity"                           : id_identity.val(),
                                        "subject"                            : subject,
                                        "grade"                              : data.grade,
                                        "work_year"                          : id_work_year.val(),
                                        "not_grade"                          : not_grade,
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
                                if(id_total_score.val() <55){
                                    id_res.val("不通过");
                                }else if(id_total_score.val() <65){
                                    id_res.val("重审");
                                }else{
                                    id_res.val("通过");
                                }
                            });
                            arr[0][1].parent().parent().parent().parent().parent().parent().parent().parent().css("width",970);
                            arr[0][1].parent().parent().parent().parent().parent().parent().parent().parent().css("left",-200);
                        }else{
                            $.do_ajax("/tea_manage_new/set_re_submit_and_lecture_out_info",{
                                "id" : id,
                                "re_submit_list": JSON.stringify(re_submit_list),
                                "lecture_out_list":JSON.stringify(lecture_out_list),
                                "reason" : id_reason_all.val()
                            });
                        }
                    }
                });
            }
        });
    });



                              
    $(".opt-edit-pass").on("click",function(){
        var data     = $(this).get_opt_data();
        var id       = $(this).get_opt_data("id");
        var status   = $(this).get_opt_data("status");
        var reason   = $(this).get_opt_data("reason");
        var tea_nick = $(this).get_opt_data("nick");
        var phone    = $(this).get_opt_data("phone");
        var identity = $(this).get_opt_data("identity");
        var subject  = $(this).get_opt_data("subject");
        var grade   = $(this).get_opt_data("grade");
        $.do_ajax("/tea_manage_new/get_re_submit_num",{
            "phone"   : phone,
            "subject" : subject,
            "grade"   : grade
        },function(result){
            var num = result.num;
            console.log(num);
            if(num>4  && data.phone != 13079618620){
                BootstrapDialog.alert("该老师没有审核机会了!!");
                return;
            }else{
                var list = result.data;
                var teacher_related_labels=$("<div><div class=\"col-xs-6 col-md-3\">风格性格:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"style_character\"></div><div class=\"col-xs-6 col-md-3\">专业能力:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"professional_ability\"> </div><div>");
                var class_related_labels=$("<div><div class=\"col-xs-6 col-md-3\">课堂气氛:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"classroom_atmosphere\"></div><div class=\"col-xs-6 col-md-3\">课件要求:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"courseware_requirements\"> </div><div>");
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
                    }else if(i=="课堂气氛"){
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
                var id_self_introduction = $("<select/>");

                var id_total_score = $("<input readonly /> ");
                var id_res = $("<input readonly/> <span style=\"font-size:11px\">");
                var id_identity      = $("<select/>");
                var id_work_year     = $("<input />");
                var id_not_grade     = $("<div />");
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

                if(identity==11){
                    identity=2;
                }
                var not_grade         = data.not_grade;
                var grade_start       = data.grade_start;
                var grade_end         = data.grade_end;
                var trans_grade_start = data.trans_grade_start;
                var trans_grade_end   = data.trans_grade_end;
                var trans_grade       = data.trans_grade;
                if(trans_grade>0){
                    grade_start = trans_grade_start;
                    grade_end   = trans_grade_end;
                }

                var id_not_grade_list = ["101","102","103","104","105","106","201","202","203","301","302","303"];
                Enum_map.append_checkbox_list("grade",id_not_grade,"not_grade",id_not_grade_list);

                var not_grade_arr=check_data_to_arr(not_grade,",");
                id_reason.val(reason);
                id_identity.val(identity);

                var arr=[
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
                    ["老师类型",id_identity],
                    ["工作年限",id_work_year],
                    ["禁止年级",id_not_grade],
                    ["教师相关标签",teacher_related_labels],
                    ["课堂相关标签",class_related_labels],
                    ["教学相关标签",teaching_related_labels]
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


                        $.do_ajax("/human_resource/set_teacher_lecture_status_new",{
                            "id"                                 : id,
                            "appointment_id"                     : data.appointment_id,
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
                            "reason"                             : id_reason.val(),
                            "identity"                           : id_identity.val(),
                            "subject"                            : subject,
                            "grade"                              : data.grade,
                            "work_year"                          : id_work_year.val(),
                            "not_grade"                          : not_grade,
                           // "sshd_good"                          : JSON.stringify(sshd_good),
                            "style_character"                  : JSON.stringify(style_character),
                            "professional_ability"             : JSON.stringify(professional_ability),
                            "classroom_atmosphere"             : JSON.stringify(classroom_atmosphere),
                            "courseware_requirements"          : JSON.stringify(courseware_requirements),
                            "diathesis_cultivation"            : JSON.stringify(diathesis_cultivation),
                            "new_tag_flag" : 1
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
                    if(id_total_score.val() <55){
                        id_res.val("不通过");
                    }else if(id_total_score.val() <65){
                        id_res.val("重审");
                    }else{
                        id_res.val("通过");
                    }
                });
                arr[0][1].parent().parent().parent().parent().parent().parent().parent().parent().css("width",970);
                arr[0][1].parent().parent().parent().parent().parent().parent().parent().parent().css("left",-200);
                
            }
        });
    });


        $(".opt-edit-no-pass").on("click",function(){
        var data     = $(this).get_opt_data();
        var id       = $(this).get_opt_data("id");
        var status   = $(this).get_opt_data("status");
        var reason   = $(this).get_opt_data("reason");
        var tea_nick = $(this).get_opt_data("nick");
        var phone    = $(this).get_opt_data("phone");
        var identity = $(this).get_opt_data("identity");
        var subject  = $(this).get_opt_data("subject");
        var grade   = $(this).get_opt_data("grade");
        $.do_ajax("/tea_manage_new/get_re_submit_num",{
            "phone"   : phone,
            "subject" : subject,
            "grade"   : grade
        },function(result){
            var num = result.num;
            console.log(num);
            if(num>4  && data.phone != 13079618620){
                BootstrapDialog.alert("该老师没有审核机会了!!");
                return;
            }else{
                var id_re_submit=$("<label><input name=\"re_submit\" type=\"checkbox\" value=\"1\" />授课环境不佳</label> <label><input name=\"re_submit\" type=\"checkbox\" value=\"2\" />授课内容错误 </label><label><input name=\"re_submit\" type=\"checkbox\" value=\"7\" />无自我介绍(英语科目) </label><label><input name=\"re_submit\" type=\"checkbox\" value=\"100\" />其他</label> ");
                var id_lecture_out=$("<label><input name=\"lecture_out\" type=\"checkbox\" value=\"3\" />语速过慢/过快 </label> <label><input name=\"lecture_out\" type=\"checkbox\" value=\"4\" />语调沉闷 </label> <label><input name=\"lecture_out\" type=\"checkbox\" value=\"5\" />节奏拖沓 </label><label><input name=\"lecture_out\" type=\"checkbox\" value=\"6\" />枯燥乏味 </label> <label><input name=\"lecture_out\" type=\"checkbox\" value=\"8\" />解题错误</label><label><input name=\"lecture_out\" type=\"checkbox\" value=\"9\" />普通话发音不标准</label><label><input name=\"lecture_out\" type=\"checkbox\" value=\"10\" />英文发音不标准</label><label><input name=\"lecture_out\" type=\"checkbox\" value=\"100\" />其他</label>");
                var id_reason_all = $("<textarea/>");

                var arr = [
                    ["可重审",id_re_submit],
                    ["未通过",id_lecture_out],
                    ["原因/建议",id_reason_all]
                ];

                $.show_key_value_table("重审淘汰判断",arr,{
                    label    : '确认',
                    cssClass : 'btn-warning',
                    action   : function(dialog) {
                        var re_submit_list=[];
                        id_re_submit.find("input:checkbox[name='re_submit']:checked").each(function(i) {
                            re_submit_list.push($(this).val());
                        });
                        var lecture_out_list=[];
                        id_lecture_out.find("input:checkbox[name='lecture_out']:checked").each(function(i) {
                            lecture_out_list.push($(this).val());
                        });

                        if(id_reason_all.val()=="" || (re_submit_list.length==0 && lecture_out_list.length==0)){
                            BootstrapDialog.alert("请填写完整");
                            return;
                        }
                       
                        $.do_ajax("/tea_manage_new/set_re_submit_and_lecture_out_info",{
                            "id" : id,
                            "re_submit_list": JSON.stringify(re_submit_list),
                            "lecture_out_list":JSON.stringify(lecture_out_list),
                            "reason" : id_reason_all.val()
                        });
                    }
                });
            }
        });
    });




    var get_not_grade_list = function(grade_start,grade_end){
        var grade_range   = ["101,102,103","104,105,106","201,202","203","301,302","303"];
        var all_grade_str = "";
        var arr           = new Array();
        if(grade_start>0 && grade_end>0){
            for(var i=grade_start-1;i<=(grade_end-1);i++){
                if(all_grade_str==""){
                    all_grade_str=grade_range[i];
                }else{
                    all_grade_str+=(","+grade_range[i]);
                }
            }
            arr = all_grade_str.split(",");
        }
        return arr;
    }

    $(".opt-add_teacher").on("click",function(){
        var phone    = $(this).get_opt_data("phone");
        var id       = $(this).get_opt_data("id");
        var tea_nick = $(this).get_opt_data("nick");
        var identity = $(this).get_opt_data("identity");
        console.log(id);


        BootstrapDialog.show({
            title   : "添加老师",
            message : "是否确认添加此老师？",
            buttons : [{
                label: '确认',
                cssClass: 'btn btn-warning',
                action: function(dialog)  {
                    $.do_ajax("/human_resource/get_teacher_simple_info",{
                        "phone" : phone,                  
                        "id"    : id,                  
                    },function(result){
                        if(result.ret==0){
                            var res = result.data;
                            res.record_info = "老师试讲，添加老师处添加";
                            add_teacher(tea_nick,phone,identity,res);
                        }else{
                            BootstrapDialog.alert(result.info);   
                        }
                    });
                }
            },{
                label: '取消',
                cssClass: 'btn btn-default',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
    });

    var add_teacher=function(tea_nick,phone,identity,data,work_year,dialect_note,interview_score,sshd_good,sshd_bad,ktfw_good,ktfw_bad,skgf_good,skgf_bad,jsfg_good,jsfg_bad){
        BootstrapDialog.alert("试讲通过,正在为老师添加账号,请稍后!");
        
        var email              = "";
        var school             = "";
        var textbook           = "";
        var grade_part_ex      = 0;
        var grade              = 0;
        var grade_start        = 0;
        var grade_end          = 0;
        var subject            = 0;
        var not_grade          = "";
        var teacher_money_type = 4;
        var record_info        = "";
        var bankcard           = "";
        var bank_address       = "";
        var bank_account       = "";
        var create_time        = "";
        email        = data.email;
        school       = data.school;
        textbook     = data.textbook;
        subject      = data.subject;
        grade        = data.grade;
        grade_start  = data.grade_start;
        grade_end    = data.grade_end;
        not_grade    = data.not_grade;
        bankcard     = data.bankcard;
        bank_address = data.bank_address;
        bank_account = data.bank_account;
        create_time  = data.confirm_time;
        if(data.record_info != undefined){
            record_info   = data.record_info;
        }
        if(data.teacher_money_type>0){
            teacher_money_type = data.teacher_money_type;
        }
        if(data.grade >= 100 && data.grade < 200){
            grade_part_ex = 1;
        }else if(data.grade >= 200 && data.grade < 300){
            grade_part_ex = 2;
        }else if(data.grade >= 300){
            grade_part_ex = 3;
        }

        $.do_ajax("/tea_manage/add_teacher",{
            "tea_nick"              : tea_nick,
            "phone"                 : phone,
            "identity"              : identity,
            "gender"                : 0,
            "level"                 : 1,
            "create_time"           : create_time,
            "subject"               : subject,
            "grade_part_ex"         : grade_part_ex,
            "grade"                 : grade,
            "teacher_type"          : 0,
            "teacher_money_type"    : teacher_money_type,
            "trial_lecture_is_pass" : 1,
            "email"                 : email,
            "school"                : school,
            "textbook"              : textbook,
            "resume_url"            : data.resume_url,
            "work_year"             : work_year,
            "dialect_note"          : dialect_note,
            "interview_score"       : interview_score,
            "sshd_good"             : JSON.stringify(sshd_good),
            "sshd_bad"              : JSON.stringify(sshd_bad),
            "ktfw_good"             : JSON.stringify(ktfw_good),
            "ktfw_bad"              : JSON.stringify(ktfw_bad),
            "skgf_good"             : JSON.stringify(skgf_good),
            "skgf_bad"              : JSON.stringify(skgf_bad),
            "jsfg_good"             : JSON.stringify(jsfg_good),
            "jsfg_bad"              : JSON.stringify(jsfg_bad),
            "record_info"           : record_info,
            "grade_start"           : grade_start,
            "grade_end"             : grade_end,
            "not_grade"             : not_grade,
            "bankcard"              : bankcard,
            "bank_address"          : bank_address,
            "bank_account"          : bank_account,
        },function(ret_info){
            if(ret_info.ret==0){
                var tea_url = "<a href=\"/human_resource/index_new?address="
                    +ret_info.teacherid+"\" target=\"_blank\">请完善基本信息</a>";
                BootstrapDialog.alert("老师账号已添加!["+tea_url+"]!");
                sleep(6000);
                window.location.reload();
            }else{
                window.location.reload();
                BootstrapDialog.alert(ret_info.info);
            }
        });
    }

    $(".opt-get_identity_image").on("click",function(){
        var url=$(this).get_opt_data("identity_image");
        window.open(url, '_blank');
    });
    
    $(".opt-resume_url").on("click",function(){
        var url=$(this).get_opt_data("resume_url");
        window.open(url, '_blank');
    });

    $("#id_phone").on("keypress",function(e){
        if(e.keyCode == 13){
            var phone = $("#id_phone").val();
            load_data();
        }
    });

    $(".opt-update_lecture_status").on("click",function(){
        var id=$(this).get_opt_data("id");
        var id_status=$("<select/>");
        Enum_map.append_option_list("check_status",id_status,true);

        var arr = [
            ["审核状态",id_status]
        ];

        $.show_key_value_table("更改状态",arr,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/human_resource/update_lecture_status",{
                    "id"     : id,
                    "status" : id_status.val()
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

    $(".opt-video_error").on("click",function(){
        var opt_data = $(this).get_opt_data();

        BootstrapDialog.show({
	        title   : "发送确认",
	        message : "确定发送视频出错通知么？",
	        buttons : [{
		        label  : "返回",
		        action : function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label    : "确认",
		        cssClass : "btn-warning",
		        action   : function(dialog) {
                    $.do_ajax("/human_resource/send_sms_by_video_error",{
                        "id"                    : opt_data.id,
                        "phone"                 : opt_data.phone,
                        "teacher_re_submit_num" : opt_data.teacher_re_submit_num,
                        "nick"                  : opt_data.nick,
                    },function(result){
                        if(result.ret==0){
                            window.location.reload();
                        }else{
                            BootstrapDialog.alert(result.info);
                        }
                    });
		        }
	        }]
        });
    });

    $(".opt-set_test").on("click",function(){
        var id = $(this).get_opt_data("id");
        $.do_ajax("/human_resource/set_teacher_lecture_is_test",{
            "id":id
        },function(result){
            BootstrapDialog.alert(result.info);
        });
    });

    $(".opt-reset").on("click",function(){
        var data = $(this).get_opt_data();
        BootstrapDialog.show({
	        title   : "清空负责人",
	        message : "确认清空负责人么？",
	        buttons : [{
		        label  : "返回",
		        action : function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label    : "确认",
		        cssClass : "btn-warning",
		        action   : function(dialog) {
                    $.do_ajax("/human_resource/reset_lecture_account",{
                        "id":data.id
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

    $("#id_reset_lecture_grade").on("click",function(){
	    $.do_ajax("/user_manage_new/reset_lecture_grade",{
        },function(result){
            if(result.ret==0){
                window.location.reload();
            }else{
                BootstrapDialog.alert(result.info);
            }
        })

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

    if (window.location.pathname=="/human_resource/teacher_lecture_list_zs" || window.location.pathname=="/human_resource/teacher_lecture_list_zs/") {
        download_hide();
    }



	$('.opt-change').set_input_change_event(load_data);
});

