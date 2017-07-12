/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student-test_lesson_list.d.ts" />

var notify_cur_playpostion =null;

function load_data( ){
    var from_type= $("#id_from_type").val();
    var status            = $("#id_revisit_status").val();
    var phone             = $.trim($("#id_phone").val());
    var origin            = $.trim( $("#id_origin").val());
    var st_application_nick = $("#id_st_application_nick").val();
    var subject           = $("#id_subject").val();
    var grade             = $("#id_grade").val();
    var page_count = $("#id_page_count").val();
    

    $.reload_self_page( {
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),

		test_lesson_cancel_flag:	$('#id_test_lesson_cancel_flag').val(),
		ass_adminid_flag:	$('#id_ass_adminid_flag').val(),
		require_user_type:	$('#id_require_user_type').val(),
		userid:	$('#id_userid').val(),
		teacherid:	$('#id_teacherid').val(),

		confirm_flag:	$('#id_confirm_flag').val(),
        page_count: page_count,
        phone             : phone, 
        from_type : from_type, 
        st_application_nick : st_application_nick, 
        origin            : origin, 
        grade             : grade, 
        subject           : subject, 
        origin_ex: $("#id_origin_ex").val(),
        status            : status 
    });

}

function isNumber( s ){
    var regu = "^[0-9]+$";
    var re = new RegExp(regu);
    if (s.search(re) != -1) {
        return true;
    } else {
        return false;
    }
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

	$('.opt-change').set_input_change_event(load_data);

    Enum_map.append_option_list("book_grade",$(".book_grade_list"));
    Enum_map.append_option_list("book_status",$("#id_revisit_status"),true, [6,7,8,9,10,12,13,14,15,20,21]);
    Enum_map.append_option_list("book_status",$(".update_user_status"),true, [1,6,7,8,9,10,13,14,15] );
    Enum_map.append_option_list("test_lesson_cancel_flag",$(".update_cancel_status"),true );
    Enum_map.append_option_list("grade",$("#id_grade"));
    Enum_map.append_option_list("subject",$("#id_subject"));
    Enum_map.append_option_list("test_listen_from_type",$("#id_from_type"));
    Enum_map.append_option_list( "confirm_flag", $("#id_confirm_flag"));
	Enum_map.append_option_list("boolean",$("#id_ass_adminid_flag")); 
	Enum_map.append_option_list("test_lesson_cancel_flag",$("#id_test_lesson_cancel_flag")); 
    
    $('#id_grade').val(g_args.grade);
    $('#id_subject').val(g_args.subject);
    $('#id_revisit_status').val(g_args.status);
    $("#id_phone").val(g_args.phone);
    $("#id_origin").val(g_args.origin);
    $("#id_st_application_nick").val(g_args.st_application_nick);
    $("#id_from_type").val(g_args.from_type);
    $("#id_origin_ex").val( g_args.origin_ex ); 
    $("#id_userid").val(g_args.userid);
	$('#id_require_user_type').val(g_args.require_user_type);
    $("#id_teacherid").val(g_args.teacherid);
    $.admin_select_user($("#id_userid"),"student",load_data);
    $.admin_select_user($("#id_teacherid"),"teacher",load_data);
	$('#id_ass_adminid_flag').val(g_args.ass_adminid_flag);

	$('#id_test_lesson_cancel_flag').val(g_args.test_lesson_cancel_flag);

	$('#id_confirm_flag').val(g_args.confirm_flag);
    

    // lala


    $('.opt-update_user_info').on('click',function(){
        //修改部分
        var html_node = $('<div></div>').html($.dlg_get_html_by_class('dlg-update_user_info'));
        var phone  = $(this).get_opt_data("phone");
        var status = $(this).parent().data('status');
        var note   = $(this).parents('td').siblings('.user-desc').text();
        var opt_data = $(this).get_opt_data();

        html_node.find(".update_user_phone").val(phone);
        html_node.find(".update_user_status").val(status);
        if (status=15) {
            html_node.find(".update_user_status").attr("readonly","readonly");
        }
        
        html_node.find(".update_user_note").val(note);
        html_node.find(".update_cancel_status").val(opt_data.cancel_flag);
        html_node.find(".update_cancel_reason").val(opt_data.cancel_reason);

        html_node.find('.show-user-phone').text($(this).get_opt_data("phone") ); 
        

        BootstrapDialog.show({
            
            title: '回访信息',
            message : html_node,
            closable: true, 
            closeByBackdrop:false,
            buttons: [{
                label: '确认',
                cssClass: 'btn-primary',
                action : function(dialog) {
                    var update_phone  = html_node.find(".update_user_phone").val();
                    var update_status = html_node.find(".update_user_status").val();
                    var update_note   = html_node.find(".update_user_note").val();
                    var phone         = html_node.find('.show-user-phone').text();
                    var op_note       = html_node.find('.update_user_record').val();
                    var cancel_flag = html_node.find('.update_cancel_status').val();
                    var cancel_reason = html_node.find('.update_cancel_reason').val();
                    if(update_status == 0){
                        alert('用户状态错误');
                        return false;
                    }
                    if (  update_status ==15) {
                        if((cancel_flag==0) ) {
                            alert('要设置取消标志');
                            return false;
                        }
                    }else{
                        
                        if(!(cancel_flag==0) ) {
                            alert('不能设置取消标志');
                            return false;
                        }

                    }

                    //alert(op_note); 
                    $.ajax({
			            type     : "post",
			            url      : "/seller_student/update_user_info",
			            dataType : "json",
			            data : {
                            "phone"   : update_phone 
                            ,"status" : update_status 
                            ,"note"   : update_note
                            ,"cancel_flag"   : cancel_flag
                            ,"cancel_reason"   : cancel_reason
                            ,'op_note': op_note
                        },
			            success : function(result){
                            BootstrapDialog.alert(result['info']);
                            window.location.reload();
			            }
                    });
                    

                    dialog.close();
                    return true;
                }
            }, {
                label: '取消',
                cssClass: 'btn',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
    });


    

    
    $("#id_add_user").on("click",function(){
	    // 处理
        var $phone=$("<input/>");
        var arr                = [
            ["电话", $phone],
        ];
        
        $.show_key_value_table("新增申请", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {

                var phone=$phone.val();
                if(phone.length!=11) {
                    alert("电话要11位") ;
                    return;
                }
                $.do_ajax('/seller_student/add_test_lesson_user', {
                    'phone':phone
                },function(){
                    alert('设置成功' );
                    window.location.reload();
                });
			    dialog.close();
            }
        });


    });


    $(".opt-set-test-lesson-info").on("click", function () {
        var phone             = $(this).get_opt_data("phone");
        var origin            = $(this).get_opt_data("origin");
        var grade             = $(this).get_opt_data("grade");
        var admin_revisiterid = $(this).get_opt_data("admin_revisiterid");
        var opt_data= $(this).get_opt_data();
        if ($.trim(opt_data.nick ) == "" ) {
            alert("要设置用户姓名");
            return;
        }
        if ( $.inArray(opt_data.grade,[101,102,103,104,105,106,201,202,203,301,302,303]   ) == -1  ) {
            alert("要设置用户的年级");
            return;
        }


        if (  $.inArray( opt_data.status,  [ 1,2,3,4,5,11,9,14,15] )==-1 ) {
            alert("当前状态:"+ opt_data.status_str+"：不能预约");
            return ;
        }
        
        if (!admin_revisiterid) { //
            alert("请设置销售!");
            return;
        }

        var admin_select_user = $(this).get_opt_data("origin");
        $.do_ajax("/seller_student/get_user_info", {
            "phone": phone
        }, function (result) {
            var data = result.data;
            var $st_class_time = $("<input/>");
            var $st_from_school = $("<input/>");
            var $st_demand = $("<textarea/>");
            $st_demand.css({
                //  width :"90%", 
                height: "80px"
            });

            $st_class_time.datetimepicker({
                lang: 'ch',
                timepicker: true,
                format: 'Y-m-d H:i'
            });

            var arr = [
                ["电话", phone],
                ["期待试听时间", $st_class_time],
                ["在读学校", $st_from_school],
                ["试听需求", $st_demand]
            ];

            var phone_ex = ("" + phone).split("-")[0];

            if (data.st_class_time > 0) {
                $st_class_time.val($.DateFormat(data.st_class_time, "yyyy-MM-dd hh:mm"));
            }
            $st_from_school.val(data.st_from_school);
            $st_demand.val(data.st_demand);

            $.show_key_value_table("设置试听信息", arr, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function (dialog) {
                    if (
                        data.status == 6 ||
                        data.status == 7 ||
                        data.status == 8
                    ) {
                        alert("已试听中，不可重置");
                        return;
                    }

                    $.do_ajax("/user_manage/get_userid_by_phone", {
                        phone: phone
                    }, function (result) {
                        var userid = result.userid;
                        if (!userid) {
                            $.do_ajax('/login/register', {
                                'telphone': phone_ex,
                                'passwd': 123456,
                                'grade': grade
                            }, function () {

                            });
                        }

                        //设置
                        $.do_ajax('/seller_student/register_appstore', {
                            'telphone': phone_ex,
                            'origin': origin,
                            'seller': admin_revisiterid
                        }, function () {

                        });

                        $.do_ajax('/seller_student/set_test_lesson_info', {
                            'phone': phone,
                            'st_class_time': $st_class_time.val(),
                            'st_from_school': $st_from_school.val(),
                            'st_demand': $st_demand.val()
                        }, function () {
                            alert('设置成功');
                            window.location.reload();
                        });
                        dialog.close();
                    });
                }
            });
        });
    });

    
    
    $(".opt-set-lesson").on("click",function(){
        var opt_data     = $(this).get_opt_data();
        var phone        = $(this).get_opt_data("phone");
        var stu_nick     = $(this).get_opt_data("nick");
        var parent_nick  = $(this).get_opt_data("parent_nick");
        var parent_phone = $(this).get_opt_data("parent_phone");
        var address      = $(this).get_opt_data("address");
        var subject      = $(this).get_opt_data("subject");
        if(!stu_nick){
            stu_nick= "";
        };
        if(!parent_nick || parent_nick =="xx"){
            parent_nick = "未设置";
        };
        if(!parent_phone){
            parent_phone = phone;
        };
        parent_phone = (""+parent_phone).split("-")[0];
        if(!address || address =="address" ){
            address = "未设置";
        };
        if(!subject){
            subject = 1;
        };
        
        var check_test_lesson_order=function(userid) {
            $.do_ajax("/user_deal/check_test_lesson" ,{
                userid   :   userid
            },function(result){
                var courseid    = result.courseid;
                if( !result.courseid){
                    $.ajax({
			            type     :"post",
			            url      :"/user_manage/add_contract",
			            dataType :"json",
			            data     :{
                            'userid'            : userid
                            ,'stu_nick'         : stu_nick 
                            ,'grade'            : opt_data.grade
                            ,'subject'          : subject
                            ,'parent_nick'      : parent_nick
                            ,'parent_phone'     : parent_phone
                            ,'address'          : address
                            ,'lesson_total'     : ""
                            ,'need_receipt'     : ""
                            ,'title'            : ""
                            ,'requirement'      : ""
                            ,'contract_type'    : 2
                            ,"presented_reason" : ""
                            ,"should_refund"    : ""
                            ,"config_courseid"  : ""
                            ,"taobao_orderid"   : ""
                        },success : function(result){
				            if(result.ret != 0){
					            alert(result.info);
				            }else{
                                $.do_ajax("/user_deal/check_test_lesson" ,{
                                    userid   :   userid
                                },function(result){
                                    var courseid = result.courseid;
                                    window.open('/stu_manage/lesson_plan_edit/?sid='+userid+"&courseid="+courseid+ "&return_url="+encodeURIComponent(window.location.href)); 
				                });
			                }
				        }
		            });
                }else{ 
                    window.open('/stu_manage/lesson_plan_edit/?sid='+userid+"&courseid="+courseid+ "&return_url="+encodeURIComponent(window.location.href));
                }
            });
        };

        $.do_ajax("/user_manage/get_userid_by_phone" ,{
            phone: $(this).get_opt_data("phone")
        },function(result){
            var userid=result.userid;
            if (userid) {
                if (opt_data.st_userid != userid) {
                    $.do_ajax(  "/seller_student/set_phone_userid",{
                        "phone"  : opt_data.phone,
                        "userid" : userid
                    },function(){
                    });
                }
                check_test_lesson_order(userid);
            }else{
                alert('用户未注册');
            };   
        });
    });


    $(".opt-binding-lesson").on('click',function(){
        var phone =  $(this).get_opt_data("phone");
        var opt_data =   $(this).get_opt_data();
         var select_lesson=function(userid) {
             $(this).admin_select_dlg_ajax({
                "opt_type" :  "select", // or "list"
                select_no_select_value  :   0, // 没有选择是，设置的值 
                select_no_select_title  :   '未设置', // "未设置"
                select_primary_field : "lessonid",
                select_display       : "",
                "url"          : "/user_deal/get_lesson_list",
                //其他参数
                "args_ex" : {
                    userid :  userid ,
                    lesson_type:  2 
                },
                //字段列表
                'field_list' :[
                    {
                        title:"lessonid",
                        width :50,
                        field_name:"lessonid"
                    },{
                        title:"类型",
                        //width :50,
                        render:function(val,item) {
                            return item.lesson_type_str;
                        }
                    },{
                        title:"课程时间",
                        //width :50,
                        render:function(val,item) {
                            return item.lesson_time;
                        }
                    },{
                        title:"老师",
                        field_name:"teacher_nick"
                    }
                ] ,
                //查询列表
                filter_list:[
                    [
                        {
                            size_class: "col-md-4" ,
                            title :"性别",
                            type  : "select" ,
                            'arg_name' :  "gender"  ,
                            select_option_list: [ {
                                value : -1 ,
                                text :  "全部" 
                            },{
                                value :  1 ,
                                text :  "男" 
                            },{
                                value :  2 ,
                                text :  "女" 
                                
                            }]
                        },{
                            size_class: "col-md-8" ,
                            title :"姓名/电话",
                            'arg_name' :  "nick_phone"  ,
                            type  : "input" 
                        }

                    ] 
                ],
                
                "auto_close"       : true,
                //选择
                "onChange" : function(val) {
                    $.do_ajax( "/seller_student/set_test_lesson_st_arrange_lessonid",{
                        "st_arrange_lessonid" :  val ,
                        "phone" :  phone
                    });
                },
                //加载数据后，其它的设置
                "onLoadData"       : null
            });
         };
        $.do_ajax("/user_manage/get_userid_by_phone" ,{
            phone: $(this).get_opt_data("phone")
        },function(result){
            var userid=result.userid;
            if (userid) {
                if (opt_data.st_userid != userid) {
                    $.do_ajax(  "/seller_student/set_phone_userid",{
                        "phone" :opt_data.phone,
                        "userid" :userid
                    },function(){
                    });
                }

                select_lesson(userid);
            };   
            
        });

        
    });
        

    $(".opt-assign-teacher").on("click",function(){
        var st_userid = $(this).get_opt_data("st_userid");
        var id        = $(this).get_opt_data("id");
        $.wopen("/seller_student/test_lesson_assign_teacher?seller_student_id="+id,true);
    
    });

    $(".opt-get_stu_performance").on("click",function(){
        var lessonid = $(this).get_opt_data("st_arrange_lessonid");
        console.log(lessonid);
        get_stu_performance_for_seller(lessonid);
    });

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
                        title    : '课程回放:lessonid:'+opt_data.lessonid+", 学生:" + opt_data.nick ,
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


    $(".opt-confirm").on("click",function(){
        var opt_data= $(this).get_opt_data();
        var lessonid        = opt_data.lessonid; 
        var $confirm_flag   = $("<select> </select>");
        var $confirm_reason = $("<textarea/> ");
        Enum_map.append_option_list( "confirm_flag", $confirm_flag,true);
        $confirm_flag.val( opt_data.confirm_flag  );
        $confirm_reason.val( opt_data.confirm_reason );

        
        var arr=[
            ["上课完成", $confirm_flag ] ,
            ["无效原因", $confirm_reason ] 
        ];
        $confirm_flag.on("change",function(){
            /*
            var val=$confirm_flag.val();
            if (val==1) {
                $confirm_reason.parent().parent().hide();
            }else{
                $confirm_reason.parent().parent().show();
            }
            */
        });
        
        $.show_key_value_table("确认课时", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                if (opt_data.status ==15 ) { //取消
                    var confirm_flag=$confirm_flag.val();
                    if (confirm_flag ==0 ||  confirm_flag==1 ) {
                        alert("课程已取消,不能设置为 :未设置或有效");
                        return;
                    }
                }
                $.do_ajax("/user_deal/lesson_set_confirm", {
                    "lessonid":lessonid,
                    "confirm_flag":$confirm_flag.val(),
                    "confirm_reason":$confirm_reason.val()
                });
            }
        } ,function(){
		});

	    
    });

    $(".opt-copy").on("click",function(){
        var opt_data=$(this).get_opt_data();
        
        var id_phone        = $("<input/>");
        var id_nick         = $("<input/>");
        var id_origin       = $("<input/>");
        var id_grade        = $("<select/>");
        var id_subject      = $("<select/>");
        //var id_status       = $("<select/>");
        var id_consult_desc = $("<textarea/>");
        var id_st_demand=  $("<textarea/>");

        
        var arr                = [
            [ "电话",  (""+opt_data.phone).split("-")[0] ] ,
            [ "姓名",  opt_data.nick ] ,
            [ "渠道",  opt_data.origin ] ,
            [ "年级",  id_grade] ,
            [ "科目",  id_subject] ,
            //[ "回访状态",   id_status] ,
            [ "用户备注",   id_consult_desc] ,
            [ "试听需求",   id_st_demand] ,
        ];

        //Enum_map.append_option_list("book_status", id_status,true );
        Enum_map.append_option_list("subject", id_subject,true );
        Enum_map.append_option_list("grade", id_grade,true );

        id_phone.val((""+opt_data.phone).split("-")[0]);
        id_nick.val(opt_data.nick);
        id_origin.val(opt_data.origin);
        id_grade.val(opt_data.grade);
        id_subject.val(opt_data.subject);
        id_st_demand.val(opt_data.st_demand);
        id_phone.attr("readonly","readonly");
        id_origin.attr("readonly","readonly");


        $.show_key_value_table("新增电话用户", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var nick         = $.trim(id_nick.val());
                var phone        = $.trim(id_phone.val());
                var origin       = $.trim(id_origin.val());
                var grade        = id_grade.val();
                var subject      = id_subject.val();
                //var status       = id_status.val();
                var consult_desc = id_consult_desc.val();
                if (phone.length < 11  ) {
                    BootstrapDialog.alert('电话不是11位的!');
                    return;
                }
                if (nick.length <2  ) {
                    BootstrapDialog.alert('姓名少于两个字!');
                    return;
                }


                $.do_ajax( '/seller_student/add_stu_info',
                         {
                             "set_self_flag":0,
                             'phone': phone,
                             'grade': grade,
                             'subject': subject,
                             'nick': nick,
                             'status': 9,
                             'origin': origin,
                             'consult_desc': consult_desc,
                             'st_demand': id_st_demand.val(),
                             'st_application_nick': opt_data.st_application_nick,
                             'ass_adminid': opt_data.ass_adminid,
                             'admin_revisiterid'  : opt_data.admin_revisiterid,
                             'userid'  :  opt_data.st_userid 
                         });

			    dialog.close();

            }
        });
    });

    if (window.location.pathname=="/seller_student/test_lesson_list_ass") {
        $("#id_st_application_nick").parent().parent().hide();
    }

    $(".opt-set-lesson-new").on("click",function(){
        var opt_data      = $(this).get_opt_data();
        if(opt_data.st_arrange_lessonid > 0 ){
            alert('已有排课,请确认!');
            return;
        }
        var id_teacherid  = $("<input/>");
        var id_start_time = $("<input  /> ");
        var id_end_time   = $("<input  /> ");
        var id_grade      = $("<select /> ");
        var id_subject    = $("<select/> ");
        id_teacherid.val(opt_data.teacherid);
        Enum_map.append_option_list("subject", id_subject,true );
        Enum_map.append_option_list("grade", id_grade,true );
        id_grade.val(opt_data.grade);
        id_subject.val(opt_data.subject);


        id_start_time.datetimepicker({
		    lang:'ch',
		    datepicker:true,
		    timepicker:true,
		    format:'Y-m-d H:i',
		    step:30,
	        onChangeDateTime :function(){
                var end_time= parseInt(strtotime(id_start_time.val() )) + 2400;
                id_end_time.val(  $.DateFormat(end_time, "hh:mm"));
            }

	    });        
	    id_end_time.datetimepicker({
		    lang:'ch',
		    datepicker:false,
		    timepicker:true,
		    format:'H:i',
		    step:30
	    });
        var arr=[
            ["老师",  id_teacherid ]  ,
            ["开始时间",  id_start_time ]  ,
            ["结束时间",  id_end_time ]  ,
            ["年级",  id_grade ]  ,
            ["科目",  id_subject ]  ,
        ];

        $.show_key_value_table("请排课", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/user_deal/course_set_new",{
                    'userid'       : opt_data.st_userid, 
                    'phone'        : opt_data.phone, 
                    'teacherid'    : id_teacherid.val(), 
                    'lesson_start' : id_start_time.val(),
                    'lesson_end'   : id_end_time.val(),
                    'grade'        : id_grade.val(),
                    'subject'      : id_subject.val()
                });
            }
        },function(){
            $.admin_select_user(id_teacherid,"teacher");
        });
    });

    $(".opt-div").each(function(){
        var status = $(this).data("status");
        if(status!=9 && status!=14 && status!=15){
            $(this).find(".opt-set-teacher").hide();
        }
    });

    $(".opt-set-teacher").on("click",function(){
        var phone = $(this).get_opt_data("phone");
        var nick  = $(this).get_opt_data("nick");
        $.do_ajax("/seller_student/get_teacher_for_seller_student",{
            "phone" : phone
        },function(result){
            if(result.ret!=0){
                BootstrapDialog.alert(result.info);
                return;
            }

            var stu_info = result.stu_info;
            var now      = Date.parse(new Date())/1000;
            if(stu_info['stu_class_time']<now){
                BootstrapDialog.alert("学生期待试听的日期小于当前时间,请检查!");
                return ;
            }

            if(!result.data.length){
                BootstrapDialog.alert("没有老师匹配该时间!");
                return ;
            }

            $(this).admin_select_dlg({
                header_list     : ["teacherid","姓名","匹配状态","电话"],
                data_list       : result.data,
                multi_selection : false,
                onChange        : function(teacherid,dlg){
                    var id_stu_time_info = $("<div/>");
                    var id_lesson_start  = $("<input/>");
                    var id_lesson_end    = $("<input/>");

                    var arr=[
                        ['学生信息',nick+"/"+phone],
                        ['学生上课日期',id_stu_time_info],
                        ['课堂开始时间',id_lesson_start],
                        ['课堂结束时间',id_lesson_end]
                    ];

                    id_stu_time_info.html(stu_info['stu_time']);
                    id_lesson_start.val(stu_info['start_time']);
	                id_lesson_start.datetimepicker({
		                lang:'ch',
		                datepicker:false,
		                timepicker:true,
		                format:'H:i',
		                step:30
	                });
                    id_lesson_end.val(stu_info['end_time']);
	                id_lesson_end.datetimepicker({
		                lang:'ch',
		                datepicker:false,
		                timepicker:true,
		                format:'H:i',
		                step:30
	                });


                    $.show_key_value_table("设置排课时间", arr ,{
                        label: '确认',
                        cssClass: 'btn-warning',
                        action: function(dialog) {
                            $.do_ajax('/seller_student/add_test_lesson', {
                                'phone'        : phone,
                                'grade'        : stu_info['grade'],
                                'subject'      : stu_info['subject'],
                                'stu_time'     : stu_info['stu_time'],
                                'lesson_start' : id_lesson_start.val(),
                                'lesson_end'   : id_lesson_end.val(),
                                'teacherid'    : teacherid,
                            },function(result){
                                console.log(id_lesson_start.val());
                                console.log(id_lesson_end.val());
                                if(result.ret!=0){
                                    BootstrapDialog.alert(result.info);
                                    return ;
                                }else{
                                    window.location.reload();
                                }
                            });
                        }
                    });
                }
            });
        });
    });
    
    $(".opt-user-info").on("click",function(){
        var opt_data=$(this).get_opt_data();
	    
        var id_left_info=$("<div />");
        var id_right_info=$("<div />");
        
        var arr=[
            [id_left_info, id_right_info],
        ];
        var left_info = '地址:'+opt_data.phone_location+'<br>'+'姓名:'+opt_data.nick+'<br>'+'年级:'+opt_data.grade_str+'<br>'+'科目:'+opt_data.subject_str+'<br>'+'学校:'+opt_data.st_from_school+'<br>'+'教材:'+opt_data.editionid_str+'<br>'+'试卷:'+opt_data.st_test_paper_str+'<br>'+'成绩情况:'+opt_data.stu_score_info+'<br>'+'性格信息:'+opt_data.stu_character_info;
        var right_info = '期待时间:'+opt_data.st_class_time+'<br>'+'期待时间(其它):'+opt_data.stu_request_test_lesson_time_info_str+'<br>'+'正式上课:'+opt_data.stu_request_lesson_time_info_str+'<br>'+'试听内容:'+opt_data.stu_test_lesson_level_str+'<br>'+'试听需求:'+opt_data.st_demand;
        id_left_info.html(left_info);
        id_right_info.html(right_info);
        id_left_info.css("text-align","left").css("width","250px");
        id_right_info.css("width","350px");
        $.show_key_value_table("试听信息", arr ,"");
        id_left_info.parent().parent().parent().parent().find("thead tr").children("td").eq(0).css("text-align","left").text("基本信息");
        id_left_info.parent().parent().parent().parent().find("thead tr").children("td").eq(1).text("试听信息");
        id_left_info.parent().parent().parent().parent().parent().parent().parent().parent().css("width","700px");

    });

});
