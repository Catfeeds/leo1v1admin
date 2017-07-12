/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student-student_list2.d.ts" />


function load_data( ){
    var status        = $("#id_revisit_status").val();
    var phone         = $("#id_phone").val();
    var origin        = $("#id_origin").val();
    var origin_ex        = $("#id_origin_ex").val();
    var subject       = $("#id_subject").val();
    var phone_location= $.trim($("#id_phone_location").val());
    var has_pad = $("#id_has_pad").val();
    $.reload_self_page({
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		ass_adminid_flag:	$('#id_ass_adminid_flag').val(),
        status        : status,
        phone         : phone,
        origin        : origin,
        origin_ex        : origin_ex,
        subject       : subject, 
        phone_location: phone_location, 
        callerid:  g_args.callerid , 
        has_pad : has_pad, 
		seller_resource_type:	$('#id_seller_resource_type').val()
    });
}


$(function(){

    Enum_map.append_option_list("book_grade",$(".book_grade_list"));
    Enum_map.append_option_list("book_status",$("#id_revisit_status"));
    Enum_map.append_option_list("subject",$("#id_subject"));
    Enum_map.append_option_list("pad_type",$("#id_has_pad"));
	Enum_map.append_option_list("boolean",$("#id_ass_adminid_flag")); 
	Enum_map.append_option_list("seller_resource_type",$("#id_seller_resource_type")); 


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


    $('#id_subject').val(g_args.subject);
    $('#id_revisit_status').val(g_args.status);
    $("#id_phone").val(g_args.phone);
    $("#id_origin").val(g_args.origin);
    $("#id_origin_ex").val(g_args.origin_ex);
    $("#id_phone_location").val(g_args.phone_location);
    $("#id_has_pad").val(g_args.has_pad);
	$('#id_ass_adminid_flag').val(g_args.ass_adminid_flag);
	$('#id_seller_resource_type').val(g_args.seller_resource_type);

    
    
	$('.opt-change').set_input_change_event(load_data);

    $("#id_add_user").on("click",function(){

        var id_phone=$("<input/>");
        var id_nick=$("<input/>");

        var id_grade=$("<select/>");
        var id_subject=$("<select/>");
        var id_status=$("<select/>");
        var id_consult_desc=$("<textarea/>");
	    //
        var arr                = [
            [ "电话",  id_phone] ,
            [ "姓名",  id_nick] ,
            [ "年级",  id_grade] ,
            [ "科目",  id_subject] ,
            [ "回访状态",   id_status] ,
            [ "用户备注",   id_consult_desc] ,
        ];
        

        Enum_map.append_option_list("book_status", id_status,true );
        Enum_map.append_option_list("subject", id_subject,true );
        Enum_map.append_option_list("grade", id_grade,true );
        id_subject.val(2);
        id_status.val(3);


        $.show_key_value_table("新增电话用户", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var nick         = $.trim(id_nick.val());
                var phone        = $.trim(id_phone.val());
                var grade        = id_grade.val();
                var subject      = id_subject.val();
                var status       = id_status.val();
                var consult_desc = id_consult_desc.val();
                if (phone.length != 11  ) {
                    BootstrapDialog.alert('电话不是11位的!');
                    return;
                }
                if (nick.length <2  ) {
                    BootstrapDialog.alert('姓名不对');
                    return;
                }


                $.do_ajax( '/seller_student/add_stu_info',
                         {
                             'phone': phone,
                             'grade': grade,
                             'subject': subject,
                             'nick': nick,
                             'status': status,
                             'consult_desc': consult_desc
                         },function(data)  {
                             if (data.ret!=0) {
                                 alert(data.info);
                             }else{
                                 alert("分配给你了.");
                                 window.location.reload();
                             }
                         }
                       );

			    dialog.close();

            }
        });
        

	    
    });
    //core dumped
    $('.opt-add-revisit-record').on('click', function(){
        var html_node=$('<div></div>').html($.dlg_get_html_by_class('dlg-add-revisit'));
        html_node.find('.show-user-phone').text($(this).get_opt_data("phone") ); 

        BootstrapDialog.show({
	        title: "添加回访记录",
	        message : html_node ,
	        buttons: [{
		        label: '返回',
		        action: function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label: '确认',
		        cssClass: 'btn-warning',
		        action: function(dialog) {
                    var phone   = html_node.find('.show-user-phone').text();
                    var op_note = html_node.find('.opt-add-record').val();
                    $.ajax({
                        url: '/seller_student/update_book_revisit',
                        type: 'POST',
                        data: {
                            'phone': phone,
                            'op_note': op_note
                        },
                        dataType: 'json',
                        success: function(result){
                            BootstrapDialog.alert(result['info']);
                            window.location.reload();
                        }
                    });
			        dialog.close();
		        }
	        }]
        });
    });
    // lala

    $('.opt-show-revisit-record').on('click',function(){
        var html_node=$('<div></div>').html($.dlg_get_html_by_class('dlg-show-revisit'));
        var phone = $(this).get_opt_data("phone");


        BootstrapDialog.show({
	        title: "查看回访记录",
	        message : function(dialog) {
                $.ajax({
                    url: '/user_book/get_book_revisit',
                    type: 'POST',
                    data: {
                        'phone': phone
                    },
                    dataType: 'json',
                    success: function(result) {
                        if (result['ret'] == 0) {
                            for(var i=0; i<result['revisit_list'].length; i++) {
                                html_node.find('table').append('<tr><td>' + result['revisit_list'][i]['revisit_time'] +
                                                               '</td><td>'+ result['revisit_list'][i]['op_note'] +
                                                               '</td></tr>');
                            }
                        }
                    }
                });
                return html_node;
            },
	        buttons: [{
		        label: '返回',
		        action: function(dialog) {
			        dialog.close();
		        }
	        }]
        });

    });
    
    $.each(  $(".opt-div") ,function(i,item){
        var $item=$(item);
        var sys_operator=$item.data("sys_operator");
        if (sys_operator == "" ) {
            //可以拍下；
            $item.find(" .show-in-no-select " ).show() ;
            $item.find(" .show-in-select " ).hide() ;
        }else {

            if (sys_operator == g_account) {
                $item.find(" .show-in-no-select " ).hide() ;
                $item.find(" .show-in-select " ).show() ;
            }else{
                $item.find(" .show-in-no-select " ).hide() ;
                $item.find(" .show-in-select " ).hide() ;
            }
        }

        var $opt_lesson_open= $item.find(".opt-lesson-open");

        if ($opt_lesson_open.css("display") != "none") {
            /*
            if (g_args.register_flag ==1 ) {
                $opt_lesson_open.hide();
            }
            */
        }
        
    });

    
    $(".opt-set_sys_operator").on("click",function(){

        var phone  = $(this).get_opt_data("phone");
        $.do_ajax(
            "/user_book/set_sys_operator", {
                "phone" : phone 
            }
        );
        
    });


    $('.opt-update_user_info').on('click',function(){
        //修改部分
        var html_node = $('<div></div>').html($.dlg_get_html_by_class('dlg-update_user_info'));
        var phone  = $(this).get_opt_data("phone");
        var status = $(this).parent().data('status');
        var note   = $(this).parents('td').siblings('.user-desc').text();
        html_node.find(".update_user_phone").val(phone);

        
        
        /*
        var next_status_list=[ 11,12,6,7,8,14,10 ];
        if( $.inArray( status , next_status_list  ) == -1 ) { //no find
            if (status==9) {
                next_status_list=[];
            }else{
                next_status_list=[0,1,2,3,4,5];
            }
        }
        next_status_list.push(status);
         */

        Enum_map.append_option_list("book_status",html_node.find(".update_user_status"),true);
        html_node.find(".update_user_status").val(status);
        html_node.find(".update_user_note").val(note);
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
                        if(update_status == 0){
                            alert('用户状态错误');
                            return false;
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
    function getPadType(pad_type)
    {
        switch(pad_type) {
        case 1:
            return 'iPad';
        case 2:
            return 'Android Pad';
        case 3:
            return 'no Pad';
        default:
            return '';
            
        }
    }

    function getTrialType(trial_type)
    {
        switch(trial_type){
        case 0:
            return '1v1';
        case 1:
            return '小班课';
        default:
            return '';
        }
    }


    //未回访
    $('body').on('change','.update_user_status',function(){
		var status=$(this).val();
        if (status == 0) 
            alert('状态无效请重新输入');
	});

     $('.opt-lesson-open').on('click', function(){
        var phone          = $(this).get_opt_data("phone");
        var id             = $(this).get_opt_data("id");
        var id_lesson_open = $("<input>");

        id_lesson_open. datetimepicker({
            format: "Y-m-d H:i",
            autoclose: true,
            todayBtn: true
        });
        
        var arr                = [
            ["电话", phone],
            [ "开课时间",  id_lesson_open] ,
        ];

        $.show_key_value_table("安排课程时间", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var class_time= id_lesson_open.val();
                //alert(courseid);
                $.do_ajax('/user_book/set_class_time', {
                    'id':id,
                    'class_time':class_time
                },function(){
                    alert('设置成功' );
                    window.location.reload();
                });
			    dialog.close();
            }
        });
    });


    
    $("#id_download").on("click",function(){

        if ($(".page-opt-show-all").length >0 ) {
            $(".page-opt-show-all").click();
            return ;
        } 
        

        var list_data=[];
        var $tr_list=$(".common-table").find("tr" );
        var map={
            
        };
        $.each($tr_list ,function(i,tr_item )  {
            var row_data= [];
            var $td_list= $(tr_item ).find("td");
            var phone = $(tr_item).find(".td-opt  >div" ).data("phone");
            if (!map[phone] ) {
                
                $.each(  $td_list, function( i, td_item)  {
                    if ( i>0 && i< $td_list.length-1 ) {
                        if (i==1
                            || i==2
                            || i==5
                            || i==6
                            || i==7
                            || i==9
                            || i==10
                            || i==11
                           ){
                               row_data.push( $(td_item).text() );
                           }
                    }
                });
                list_data.push(row_data);
                map[phone]=true;
            }

        });
        
        $.do_ajax ( "/common_new/upload_xls_data",{
            xls_data :  JSON.stringify(list_data )
        },function(data){
            // window.open("/common_new/download_xls",true);
            window.location.href= "/common_new/download_xls";
        });

    });

    $("#id_upload_form_submit").on("click",function(){
        alert("xx");
        $("#id_upload_form").submit(
            function(e){
                alert("Submitted");
            }
        );
    });

    //删除
    $(".done_t").on("click", function(){
		var id = $(this).parent().data("id");
		var phone= $(this).parent().data("phone");
        BootstrapDialog.show({
            title: '系统提示',
            message : '确认从预约管理中删除该学生及其相关信息',
            closable: true, 
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-danger',
                    action: function(dialog) {
		                $.ajax({
			                type     :"post",
			                url      :"/seller_student/del_student",
			                dataType :"json",
			                data     :{'id':id,
                                       'phone':phone
                                      },
			                success  : function(result){
                                if(result['ret'] != 0){
                                    alert(result['info']);
                                }else{
                                    window.location.reload();
                                }
			                }
		                });
                        dialog.close();
                    }
                },
                {
                    label: '返回',
                    cssClass: 'btn-primary',
                    action: function(dialog) {
                        dialog.close();
                    }
                },
                
            ]
        });
	});

   // 
    $('.opt-update-news').on('click', function(){
        var phone = $(this).parent().data("phone");
        $.ajax({
            type     :"post",
			url      :"/seller_student/get_show_student_info",
			dataType :"json",
			data     :{
                "phone" : phone
            },
            success: function(result){
                do_add_or_update("update", result.ret_info );
            }
        });


        
        var do_add_or_update=function(opt_type,item){
        var html_node=$("<div></div>").html($.dlg_get_html_by_class('dlg_edit_manage'));
        if (opt_type=="update") {
            html_node.find(".update_phone").val(item.phone);
            html_node.find(".update_grade").val(item.grade);
            html_node.find(".update_subject").val(item.subject);
            html_node.find(".update_pad").val(item.pad);
        }

        var title= "";
        var phone="";
        if (opt_type=="update"){
            phone=item.phone;
            title="编辑";
        }else{
            title="添加分数信息";
        }

        BootstrapDialog.show({
            title: title,
            message : html_node,
            closable: true, 
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action : function(dialog) {
                        var phone   = html_node.find(".update_phone").val();
                        var grade   = html_node.find(".update_grade").val();
                        var subject = html_node.find(".update_subject").val();
                        var pad     = html_node.find(".update_pad").val();
                       $.ajax({
			                type     : "post",
			                url      : "/seller_student/update_news",
			                dataType : "json",
			                data : {
                                "phone" : phone ,
                                "grade"   : grade , 
                                "subject"  : subject ,
                                "pad"  : pad
                            },
			                success       : function(result){
                                window.location.reload();
                            }
                        });
                        dialog.close();
                    }
                },
                {
                    label: '取消',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();
                    }
                }]
        });
    };




    });


    $(".opt-update-infomation").on("click",function(){
        var phone=$(this).get_opt_data("phone");
        $.do_ajax ( "/seller_student/get_user_info", {
            "phone" : phone
        },function(result){

            var data=result.data;
            var id_nick           = $("<input/>");
            var id_update_grade   = $("<select/>");
            var id_update_subject = $("<select/>");
            var id_update_pad     = $("<select/>");

            Enum_map.append_option_list("grade",id_update_grade,true);
            Enum_map.append_option_list("pad_type",id_update_pad,true);
            Enum_map.append_option_list("subject",id_update_subject,true);

            var arr               = [
                [ "修改姓名",  id_nick] ,
                [ "修改年级",  id_update_grade] ,
                [ "修改科目",  id_update_subject] ,
                [ "修改pad ",  id_update_pad] ,
            ];

            id_nick.val(data.nick);
            id_update_subject.val(data.subject);
            id_update_pad.val(data.has_pad);

            $.show_key_value_table("修改用户", arr ,{
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    var grade   = id_update_grade.val();
                    var subject = id_update_subject.val();
                    var pad     = id_update_pad.val();
                    $.ajax({
                        url: '/seller_student/update_news',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            'old_phone' : data.phone,
                            'grade'     : grade,
                            'subject'   : subject,
                            'nick'      : id_nick.val(),
                            'pad'       : pad
			            },
                        success: function(data) {
                            alert(data.info);
                            if(data.ret != -1){
                                window.location.reload();
                            }
                        }
                    });
                }
            });

        });

    });

    
    $(".opt-telphone").on("click",function(){
	    //
        var opt_data= $(this).get_opt_data();
        var phone    = ""+ opt_data.phone;
	    phone=phone.split("-")[0];
        

        try{
            window.navigate(
                "app:1234567@"+phone+"");
        } catch(e){
            
        };

        
        //同步...
        var lesson_info = JSON.stringify({
            cmd: "noti_phone",
            phone: phone 
        });
        $.ajax({
            type: "get",
            url: "http://admin.yb1v1.com:9501/pc_phone_noti_user_lesson_info",
            dataType: "text",
            data: {
                'username': g_account,
                "lesson_info": lesson_info
            }
        });
        // 
        $(this).parent().find(".opt-update-infomation-2").click();
    });


    
    $(".opt-update-infomation-2").on("click",function(){
        var opt_data= $(this).get_opt_data();
        var opt_obj=this;
        
        $.do_ajax("/user_manage/get_userid_by_phone", {
            phone: opt_data.phone 
        }, function (result) {
            var userid = result.userid;

            if (!userid) {
                var phone_ex = ("" + opt_data.phone).split("-")[0];
                $.do_ajax('/login/register', {
                    'telphone': phone_ex,
                    'passwd': 123456,
                    'grade': opt_data.grade 
                }, function () {
                    $.do_ajax("/user_manage/get_userid_by_phone", {
                        phone: opt_data.phone 
                    },function(ret){
                        if (!ret.userid) {
                            alert("error");
                        }else{

                            if(opt_data.userid  !=ret.userid ) {
                                $.do_ajax("/seller_student/set_phone_userid",{
                                    phone: opt_data.phone, 
                                    userid: ret.userid 
                                },function(){});
                            }
                            show_info(ret.userid);
                        }
                    });
                });
            }else{
                if(opt_data.userid  != userid ) {
                    $.do_ajax("/seller_student/set_phone_userid",{
                        phone: opt_data.phone, 
                        userid: userid
                    },function(){});
                }
                show_info(userid);
            }
        });

        var show_info=function(userid){
            var phone=opt_data.phone;

            $.do_ajax("/seller_student/get_user_info_fix",{
                "userid" : userid ,
                "phone" : phone
            } ,function(ret){
                var data=ret.data;
                var html_node = $.dlg_need_html_by_id( "id_dlg_post_user_info");
                if( data.status !=0 ) {
                    html_node.find("#id_stu_rev_info").removeClass("btn-primary");
                    html_node.find("#id_stu_rev_info").addClass("btn-warning");
                }else{
                    html_node.find("#id_stu_rev_info").addClass("btn-primary");
                    html_node.find("#id_stu_rev_info").removeClass("btn-warning");
                }
                html_node.find("#id_send_sms").on("click",function(){
                    $.do_ajax("/user_deal/get_admin_wx_info",{},function(resp){
                        var data=resp.data; 
                        var xing=$.trim(data.name).substr(0,1);
                        var dlg=BootstrapDialog.show({
                            title: "发送信息内容:",
                            message : "您好，我是刚刚联系您的"+xing+"老师 ，如果您还需要申请我们的试听课，请添加一下我的微信："+data.wx_id+"。我们会尽快帮您安排，理优教育服务热线："+data.phone,
                            closable: true, 
                            buttons: [{
                                label: '返回',
                                action: function(dialog) {
                                    dialog.close();
                                }
                            },{
                                label: '发送',
                                cssClass: 'btn-warning',
                                action: function(dialog) {
                                    $.do_ajax("/user_deal/send_seller_sms_msg", {
                                        "phone":opt_data.phone,
                                        "name":xing,
                                        "wx_id":data.wx_id,
                                        "seller_phone":data.phone,
                                    },function( resp){
                                        alert("发送成功");
                                    } );
                                }
                            }]
                        });

                        
                        /*
                        BootstrapDialog.show();
                        */
                        
                    });
                });

                html_node.find("#id_stu_rev_info") .on("click",function(){
                    $(opt_obj).parent().find(".opt-return-back-list").click();
                });
                var id_stu_nick          = html_node.find("#id_stu_nick");
                var id_par_nick          = html_node.find("#id_par_nick");
                var id_grade             = html_node.find("#id_stu_grade");
                var id_gender            = html_node.find("#id_stu_gender");
                var id_address           = html_node.find("#id_stu_addr");
                var id_subject           = html_node.find("#id_stu_subject");
                var id_status            = html_node.find("#id_stu_status");
                var id_user_desc         = html_node.find("#id_stu_user_desc");
                var id_revisite_info     = html_node.find("#id_stu_revisite_info");
                var id_has_pad           = html_node.find("#id_stu_has_pad");
                var id_editionid         = html_node.find("#id_stu_editionid");
                var id_school            = html_node.find("#id_stu_school");
                var id_next_revisit_time = html_node.find("#id_next_revisit_time");
                var id_st_class_time = html_node.find("#id_st_class_time");
                var id_st_demand= html_node.find("#id_st_demand");
                var id_stu_score_info = html_node.find("#id_stu_score_info");
                var id_stu_character_info = html_node.find("#id_stu_character_info");
                var id_stu_test_lesson_level = html_node.find("#id_stu_test_lesson_level");
                var id_stu_test_ipad_flag = html_node.find("#id_stu_test_ipad_flag");
                var id_stu_request_test_lesson_time_info = html_node.find("#id_stu_request_test_lesson_time_info");
                var id_stu_request_lesson_time_info = html_node.find("#id_stu_request_lesson_time_info");
                id_stu_request_test_lesson_time_info.data("v" , data. stu_request_test_lesson_time_info  );
                id_stu_request_lesson_time_info.data("v" , data.stu_request_lesson_time_info);
                id_stu_request_lesson_time_info.on("click",function(){
                    var v=$(this).data("v"); 
                    if(!v) {
                        v="[]";
                    }
                    var data_list=JSON.parse(v);

                    $(this).admin_select_dlg_edit({
                        onAdd:function( call_func ) {
                            var id_week= $("<select> "+
                                "<option value=1>周1</option> "+
                                "<option value=2>周2</option> "+
                                "<option value=3>周3</option> "+
                                "<option value=4>周4</option> "+
                                "<option value=5>周5</option> "+
                                "<option value=6>周6</option> "+
                                "<option value=0>周日</option> "+
                                "</select>");
                            var id_start_time=$("<input/>");
                            var id_end_time=$("<input/>");
	                        id_start_time.datetimepicker({
		                        datepicker:false,
		                        timepicker:true,
		                        format:'H:i',
		                        step:30,
	                            onChangeDateTime :function(){
                                    var end_time= $.strtotime("1970-01-01 "+id_start_time.val() ) + 7200;
                                    id_end_time.val(  $.DateFormat(end_time, "hh:mm"));
                                }
	                        });
	                        id_end_time.datetimepicker({
		                        datepicker:false,
		                        timepicker:true,
		                        format:'H:i',
		                        step:30
	                        });
                            var arr=[
                                ["周", id_week],
                                ["开始时间", id_start_time],
                                ["结束时间", id_end_time],
                            ];
                            $.show_key_value_table("增加", arr, {
                                label: '确认',
                                cssClass: 'btn-warning',
                                action: function (dialog) {
                                    call_func({
                                        "week" :  id_week.val() ,
                                        "start_time" : $.strtotime( "1970-01-01 "+ id_start_time.val()) ,
                                        "end_time" : $.strtotime ( "1970-01-01 "+ id_end_time.val())
                                    });
                                    dialog.close();
                                }
                            });


                            


                            /*
                            var div=$("<div/>");
                            div.admin_select_date_time_range({
                                
                                onSelect:function(start_time,end_time) {
                                    call_func({
                                        "start_time" : start_time ,
                                        "end_time" : end_time
                                    });
                                }
                            });
                            div.click();
                            */
                        },
                        sort_func : function(a,b){
                            var a_time=a["week"]*10000000+a["start_time"];
                            var b_time=b["week"]*10000000+b["start_time"];
                            if (a_time==b_time ) {
                                return 0;
                            }else{
                                if (a_time>b_time) return 1;
                                else return -1;
                            }
                        }, 'field_list' :[
                            {
                                title:"周",
                                render:function(val,item) {
                                    return Enum_map.get_desc("week", item["week"]*1  );
                                }
                            },{

                                title:"时间段",
                                //width :50,
                                render:function(val,item) {
                                    return  $.DateFormat(item.start_time, "hh:mm") +"~"+
                                        $.DateFormat(item.end_time, "hh:mm")  ; 
                                }
                            }
                        ] ,
                        data_list: data_list,
                        onChange:function( data_list, dialog)  {
                            id_stu_request_lesson_time_info.data("v" , JSON.stringify(data_list));
                        }
                    });
                }) ;

                id_stu_request_test_lesson_time_info.on("click",function(){
                    var v=$(this).data("v"); 
                    if(!v) {
                        v="[]";
                    }
                    var data_list=JSON.parse(v);

                    $(this).admin_select_dlg_edit({
                        onAdd:function( call_func ) {
                            var div=$("<div/>");
                            div.admin_select_date_time_range({
                                
                                onSelect:function(start_time,end_time) {
                                    call_func({
                                        "start_time" : start_time ,
                                        "end_time" : end_time
                                    });
                                }
                            });
                            div.click();
                        },
                        sort_func : function(a,b){
                            var a_time=a["start_time"];
                            var b_time=b["start_time"];
                            if (a_time==b_time ) {
                                return 0;
                            }else{
                                if (a_time>b_time) return 1;
                                else return -1;
                            }
                        }, 'field_list' :[
                            {
                                title:"时间段",
                                //width :50,
                                render:function(val,item) {
                                    return  $.DateFormat(item.start_time, "yyyy-MM-dd hh:mm") +"~"+
                                        $.DateFormat(item.end_time, "hh:mm")  ; 
                                }
                            }
                        ] ,
                        data_list: data_list,
                        onChange:function( data_list, dialog)  {
                            id_stu_request_test_lesson_time_info.data("v" , JSON.stringify(data_list));
                        }
                    });


                }) ;

                html_node.find("#id_stu_reset_next_revisit_time").on("click",function(){
                    id_next_revisit_time.val("");
                });
                Enum_map.append_option_list("grade", id_grade, true,[101,102,103,104,105,106,201,202,203,301,302,303]);
                Enum_map.append_option_list("pad_type", id_has_pad, true);
                Enum_map.append_option_list("subject", id_subject, true);
                Enum_map.append_option_list("boolean", id_stu_test_ipad_flag, true);
                Enum_map.append_option_list("test_lesson_level", id_stu_test_lesson_level, true);
                id_st_class_time.datetimepicker( {
		            lang:'ch',
		            timepicker:true,
                    format: "Y-m-d H:i",
	                onChangeDateTime :function(){
                    }
                });

                html_node.find("#id_stu_reset_st_class_time").on("click",function(){
                    id_st_class_time.val("");
                });
                
                /*
	              array(0,"","未回访" ),
	              array(1,"","无效资源" ),
	              array(2,"","未接通" ),
	              array(3,"","有效-意向A档" ),
	              array(4,"","有效-意向B档" ),
	              array(5,"","有效-意向C档" ),
	              array(6,"","已试听-待跟进" ),
	              array(7,"","已试听-未签A档" ),
	              array(20,"","已试听-未签B档" ),
	              array(21,"","已试听-未签C档" ),
	              array(8,"","已试听-已签" ),
	              array(9,"test_lesson_report","试听-预约" ),
	              array(10,"test_lesson_set_lesson","试听-已排课" ),
	              array(11,"","试听-时间待定" ), //,有预约意向，但时间没有确定
	              array(12,"","试听-时间确定" ), // 
	              array(13,"","试听-无法排课" ),
	              array(14,"","试听-驳回" ),
	              array(15,"","试听-课程取消" ),

                */
                var now=(new Date()).getTime()/1000;

                var status=data.status*1;
                var show_status_list=[];
                if ( $.inArray( status,[0,1,2,3,4,5,11,9])!=-1  ) {
                    show_status_list=[ 1,2,3,4,5,11];
                }else if ( $.inArray(status,[6,7,8,20,21] ) !=-1 ) {
                    show_status_list=[ 6,7,20,21,8];
                }else if ( $.inArray(status,[10] ) !=-1  ) {
                    if ( now  > opt_data.lesson_end  ) {
                        show_status_list=[6,7,20,21,8];
                    }else{
                        show_status_list=[ 12];
                    }
                }else if ( $.inArray(status,[14,15] ) !=-1 ){
                    show_status_list=[ 1,2,3,4,5,11,9];
                }else if ( $.inArray(status,[12] ) !=-1 ){
                    if ( now  > opt_data.lesson_end  ) {
                        show_status_list=[6,7,20,21,8];
                    }
                }
                show_status_list.push(status);

                Enum_map.append_option_list("book_status", id_status,true , show_status_list );
                Enum_map.append_option_list("gender", id_gender, true);
                Enum_map.append_option_list("region_version", id_editionid, true);

                console.log(userid);
                id_stu_nick.val(data.stu_nick);
                id_par_nick.val(data.par_nick);
                id_grade.val(data.grade);
                id_gender.val(data.gender);
                id_address.val(data.address);
                id_subject.val(data.subject);
                id_status.val(data.status);
                id_user_desc.val(data.user_desc);
                id_revisite_info.val(data.revisite_info);
                id_has_pad.val(data.has_pad);
                id_school.val(data.school);
                id_editionid.val(data.editionid);
                id_next_revisit_time.val(data.next_revisit_time);

                id_st_class_time.val(data.st_class_time);
                id_st_demand.val(data.st_demand);
                id_stu_score_info.val(data.stu_score_info);
                id_stu_test_lesson_level.val(data.stu_test_lesson_level);
                id_stu_test_ipad_flag.val(data.stu_test_ipad_flag);
                id_stu_character_info.val(data.stu_character_info);

                id_next_revisit_time.datetimepicker( {
		            lang:'ch',
		            timepicker:true,
                    format: "Y-m-d H:i",
	                onChangeDateTime :function(){
                    }
                });

                var dlg=BootstrapDialog.show({
                    title: '用户信息['+opt_data.phone+':'+opt_data.phone_location+']'+"-渠道:["+data.origin+"]",
                    size: "size-wide", 
                    message : html_node, 
                    closable: false, 
                    buttons: [{
                        label: '返回',
                        action: function(dialog) {
                            dialog.close();
                        }
                    },{
                        label: '提交',
                        cssClass: 'btn-warning',
                        action: function(dialog) {
                            $.do_ajax("/seller_student/save_user_info",{
                                phone         : phone,
                                userid        : userid,
                                stu_nick      : id_stu_nick.val(),
                                par_nick      : id_par_nick.val(),
                                grade         : id_grade.val(),
                                gender        : id_gender.val(),
                                address       : id_address.val(),
                                subject       : id_subject.val(),
                                status        : id_status.val(),
                                user_desc     : id_user_desc.val(),
                                revisite_info : id_revisite_info.val(),
                                next_revisit_time : id_next_revisit_time.val(),
                                editionid : id_editionid.val(),
                                school: id_school.val(),
                                st_class_time:id_st_class_time.val(),
                                st_demand:id_st_demand.val(),
                                stu_score_info:id_stu_score_info.val(),
                                stu_test_lesson_level:id_stu_test_lesson_level.val(),
                                stu_test_ipad_flag:id_stu_test_ipad_flag.val(),
                                stu_character_info:id_stu_character_info.val(),
                                stu_request_test_lesson_time_info:id_stu_request_test_lesson_time_info.data("v"),
                                stu_request_lesson_time_info:id_stu_request_lesson_time_info.data("v"),
                                has_pad       : id_has_pad.val()
                            });

                        }
                    }]
                });

                dlg.getModalDialog().css("width","98%");


                var close_btn=$('<div class="bootstrap-dialog-close-button" style="display: block;"><button class="close">×</button></div>');
                dlg.getModalDialog().find(".bootstrap-dialog-header").append( close_btn);
                close_btn.on("click",function(){
                    dlg.close();
                } );
                
            });
        }

        
    });




	$('.opt-change').set_input_change_event(load_data);
    

    //$.需再次回访数 

    if($("#id_student_table > tbody > tr" ).length==1 && g_args.callerid) {

        $(".opt-update-infomation-2" ).click();
    } 

    
    var init_noit_btn=function( id_name, title) {
        var btn=$('#'+id_name);
        btn.tooltip({
            "title":title,
            "html":true
        });
        var value =btn.data("value");
        btn.text(value);
        if (value >0 ) {
            btn.addClass("btn-warning");
        }
    };

    init_noit_btn("id_next_revisit",    "需再次回访数" );
    init_noit_btn("id_lesson_today",    "今天上课须通知数" );
    init_noit_btn("id_lesson_tomorrow", "明天上课须通知数" );
    init_noit_btn("id_return_back_count", "被驳回未处理的个数" );
    init_noit_btn("id_require_count", "已预约未排数" );
    $("#id_require_count").on("click",function(){
        $('#id_revisit_status').val(9);
        load_data();
    });

    $("#id_next_revisit").on("click",function(){
        var now=new Date();
        var t=now.getTime()/1000;
        $('#id_date_type').val(1); 
        $('#id_opt_date_type').val(0); //时段
        $('#id_start_time').val($.DateFormat(t-864000, "yyyy-MM-dd" ));
        $('#id_end_time').val( $.DateFormat(t, "yyyy-MM-dd" ));

        $('#id_subject').val(-1);
        $('#id_revisit_status').val(-1);
        $("#id_phone").val("");
        $("#id_origin").val("");
        $("#id_origin_ex").val("");
        $("#id_phone_location").val("");
        $("#id_has_pad").val(-1);
        load_data();
    });

    $("#id_return_back_count").on("click",function(){
        var now=new Date();
        var t=now.getTime()/1000;
        $('#id_date_type').val(3); 
        $('#id_opt_date_type').val(0); //时段
        $('#id_start_time').val($.DateFormat(t-86400*14, "yyyy-MM-dd" ));
        $('#id_end_time').val( $.DateFormat(t, "yyyy-MM-dd" ));

        $('#id_subject').val(-1);
        $('#id_revisit_status').val(14);
        $("#id_phone").val("");
        $("#id_origin").val("");
        $("#id_origin_ex").val("");
        $("#id_phone_location").val("");
        $("#id_has_pad").val(-1);
        load_data();
    });


    $("#id_lesson_tomorrow ,#id_lesson_today").on("click",function(){
        var now=new Date();
        var t=now.getTime()/1000;
        $('#id_opt_date_type').val(1);  //时段
        $('#id_date_type').val(5);  //上课时间
        if ($(this).attr("id")=="id_lesson_today") {
            $('#id_start_time').val($.DateFormat(t, "yyyy-MM-dd" ));
            $('#id_end_time').val( $.DateFormat(t, "yyyy-MM-dd" ));
        }else{
            $('#id_start_time').val($.DateFormat( t+86400, "yyyy-MM-dd" ));
            $('#id_end_time').val( $.DateFormat(t+86400, "yyyy-MM-dd" ));
        }

        $('#id_subject').val(-1);
        $('#id_revisit_status').val(-4);
        $("#id_phone").val("");
        $("#id_origin").val("");
        $("#id_origin_ex").val("");
        $("#id_phone_location").val("");
        $("#id_has_pad").val(-1);

        load_data();
    });

    
    $(".opt-notify-lesson").on("click",function(){
        var opt_data=$(this).get_opt_data();
        if (opt_data.notify_lesson_flag ==0) {
            alert("上课前两天内，才可设置");
            return;
        }
        var set_flag=1;
        var title="要设置［"+opt_data.nick+"］:";
        if (opt_data.notify_lesson_flag==2)  {
            set_flag=0;
            title+="未通知";

        }else{
            title+="已通知";
            set_flag=1;
        }
        
        BootstrapDialog.confirm(title,function(val){
            if (val) {
                $.do_ajax("/user_deal/seller_student_lesson_set_notify_flag",{
                    phone: opt_data.phone,
                    notify_flag: set_flag
                });
            }
        });
        //alert(opt_data.notify_lesson_flag);
	    
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

    $(".opt-get_stu_performance").on("click",function(){
        var lessonid = $(this).get_opt_data("st_arrange_lessonid");
        console.log(lessonid);
        get_stu_performance_for_seller(lessonid);
    });

    var get_stu_performance_for_seller =function(lessonid){
        var html_node = $.dlg_need_html_by_id( "id_dlg_set_user_info");

        var id_stu_lesson_content     = html_node.find("#id_stu_lesson_content");
        var id_stu_lesson_status      = html_node.find("#id_stu_lesson_status");
        var id_stu_study_status       = html_node.find("#id_stu_study_status");
        var id_stu_advantages         = html_node.find("#id_stu_advantages");
        var id_stu_disadvantages      = html_node.find("#id_stu_disadvantages");
        var id_stu_lesson_plan        = html_node.find("#id_stu_lesson_plan");
        var id_stu_teaching_direction = html_node.find("#id_stu_teaching_direction");
        var id_stu_textbook_info      = html_node.find("#id_stu_textbook_info");
        var id_stu_teaching_aim       = html_node.find("#id_stu_teaching_aim");
        var id_stu_lesson_count       = html_node.find("#id_stu_lesson_count");
        var id_stu_advice             = html_node.find("#id_stu_advice");

        $.do_ajax("/seller_student/get_stu_performance_for_seller",{
            lessonid : lessonid
        },function(result){
            var data=result.data;
            id_stu_lesson_content.val(data.stu_lesson_content);
            id_stu_lesson_status.val(data.stu_lesson_status);
            id_stu_study_status.val(data.stu_study_status);
            id_stu_advantages.val(data.stu_advantages);
            id_stu_disadvantages.val(data.stu_disadvantages);
            id_stu_lesson_plan.val(data.stu_lesson_plan);
            id_stu_teaching_direction.val(data.stu_teaching_direction);
            id_stu_textbook_info.val(data.stu_textbook_info);
            id_stu_teaching_aim.val(data.stu_teaching_aim);
            id_stu_lesson_count.val(data.stu_lesson_count);
            id_stu_advice.val(data.stu_advice);

            var dlg=BootstrapDialog.show({
                title: "试听课堂反馈",
                size: "size-wide", 
                message : html_node, 
                closable: true, 
                buttons: [{
                    label: '返回',
                    action: function(dialog) {
                        dialog.close();
                    }
                }]
            });
            dlg.getModalDialog().css("width","98%");
        });
    };
    $(".opt-copy").on("click",function(){
        var opt_data=$(this).get_opt_data();
        
        var id_phone        = $("<input/>");
        var id_nick         = $("<input/>");
        var id_origin       = $("<input/>");
        var id_grade        = $("<select/>");
        var id_subject      = $("<select/>");
        var id_status       = $("<select/>");
        var id_consult_desc = $("<textarea/>");

        
        var arr                = [
            [ "电话",  (""+opt_data.phone).split("-")[0] ] ,
            [ "姓名",  opt_data.nick ] ,
            [ "渠道",  opt_data.origin ] ,
            [ "年级",  id_grade] ,
            [ "科目",  id_subject] ,
            [ "回访状态",   id_status] ,
            [ "用户备注",   id_consult_desc] ,
        ];

        Enum_map.append_option_list("book_status", id_status,true );
        Enum_map.append_option_list("subject", id_subject,true );
        Enum_map.append_option_list("grade", id_grade,true );

        id_phone.val((""+opt_data.phone).split("-")[0]);
        id_nick.val(opt_data.nick);
        id_origin.val(opt_data.origin);
        id_grade.val(opt_data.grade);
        id_subject.val(opt_data.subject);
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
                var status       = id_status.val();
                var consult_desc = id_consult_desc.val();
                if (phone.length < 11  ) {
                    BootstrapDialog.alert('电话不是11位的!');
                    return;
                }
                if (nick.length <2  ) {
                    BootstrapDialog.alert('姓名不对');
                    return;
                }


                $.do_ajax( '/seller_student/add_stu_info',
                         {
                             "set_self_flag":1,
                             'phone': phone,
                             'grade': grade,
                             'subject': subject,
                             'nick': nick,
                             'status': status,
                             'origin': origin,
                             'consult_desc': consult_desc
                         },function(data)  {
                             if (data.ret!=0) {
                                 alert(data.info);
                             }else{
                                 window.location.reload();
                             }
                         }
                       );

			    dialog.close();

            }
        });
    });


    var init_noit_btn=function( id_name, title) {
        var btn=$('#'+id_name);
        btn.tooltip({
            "title":title,
            "html":true
        });
        var value =btn.data("value");
        btn.text(value);
        if (value >0 ) {
            btn.addClass("btn-warning");
        }
    };

    init_noit_btn("id_next_revisit",    "需再次回访数" );
    init_noit_btn("id_lesson_today",    "今天上课须通知数" );
    init_noit_btn("id_lesson_tomorrow", "明天上课须通知数" );
    init_noit_btn("id_return_back_count", "被驳回未处理的个数" );
    init_noit_btn("id_require_count", "已预约未排数" );

    $("#id_require_count").on("click",function(){
        $('#id_revisit_status').val(9);
        load_data();
    });

    $("#id_next_revisit").on("click",function(){
        var now=new Date();
        var t=now.getTime()/1000;
        $('#id_date_type').val(1); 
        $('#id_opt_date_type').val(0); //时段
        $('#id_start_time').val($.DateFormat(t-864000, "yyyy-MM-dd" ));
        $('#id_end_time').val( $.DateFormat(t, "yyyy-MM-dd" ));

        $('#id_subject').val(-1);
        $('#id_revisit_status').val(-1);
        $("#id_phone").val("");
        $("#id_origin").val("");
        $("#id_origin_ex").val("");
        $("#id_phone_location").val("");
        $("#id_has_pad").val(-1);
        load_data();
    });

    $("#id_return_back_count").on("click",function(){
        var now=new Date();
        var t=now.getTime()/1000;
        $('#id_date_type').val(3); 
        $('#id_opt_date_type').val(0); //时段
        $('#id_start_time').val($.DateFormat(t-86400*14, "yyyy-MM-dd" ));
        $('#id_end_time').val( $.DateFormat(t, "yyyy-MM-dd" ));

        $('#id_subject').val(-1);
        $('#id_revisit_status').val(14);
        $("#id_phone").val("");
        $("#id_origin").val("");
        $("#id_origin_ex").val("");
        $("#id_phone_location").val("");
        $("#id_has_pad").val(-1);
        load_data();
    });


    $("#id_lesson_tomorrow ,#id_lesson_today").on("click",function(){
        var now=new Date();
        var t=now.getTime()/1000;
        $('#id_opt_date_type').val(1);  //时段
        $('#id_date_type').val(5);  //上课时间
        if ($(this).attr("id")=="id_lesson_today") {
            $('#id_start_time').val($.DateFormat(t, "yyyy-MM-dd" ));
            $('#id_end_time').val( $.DateFormat(t, "yyyy-MM-dd" ));
        }else{
            $('#id_start_time').val($.DateFormat( t+86400, "yyyy-MM-dd" ));
            $('#id_end_time').val( $.DateFormat(t+86400, "yyyy-MM-dd" ));
        }

        $('#id_subject').val(-1);
        $('#id_revisit_status').val(-4);
        $("#id_phone").val("");
        $("#id_origin").val("");
        $("#id_origin_ex").val("");
        $("#id_phone_location").val("");
        $("#id_has_pad").val(-1);

        load_data();
    });


});


