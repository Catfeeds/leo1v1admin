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
    Enum_map.append_option_list("book_grade",$(".book_grade_list"));
    Enum_map.append_option_list("book_status",$("#id_revisit_status"),true, [6,7,8,9,10,12,13,14]);
    Enum_map.append_option_list("book_status",$(".update_user_status"),true, [1,6,7,8,9,10,13,14] );
    Enum_map.append_option_list("grade",$("#id_grade"));
    Enum_map.append_option_list("subject",$("#id_subject"));
    Enum_map.append_option_list("test_listen_from_type",$("#id_from_type"));
    
    $('#id_start_date').val(g_args.start_date);
    $('#id_end_date').val(g_args.end_date);
    $('#id_grade').val(g_args.grade);
    $('#id_subject').val(g_args.subject);
    $('#id_revisit_status').val(g_args.status);
    $("#id_phone").val(g_args.phone);
    $("#id_origin").val(g_args.origin);
    $("#id_st_application_nick").val(g_args.st_application_nick);
    $("#id_opt_date_type").val(g_args.opt_date_type);
    $("#id_from_type").val(g_args.from_type);

    set_input_enter_event($("#id_phone") ,load_data);

    
	//时间控件
	$('#id_start_date').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
	    onChangeDateTime :function(){
		    load_data();
        }
	});
    
	$('#id_end_date').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
		onChangeDateTime :function(){
		    load_data();
        }
	});

    

	function load_data( ){
        var from_type= $("#id_from_type").val();
        var start_date        = $("#id_start_date").val();
        var end_date          = $("#id_end_date").val();
        var status            = $("#id_revisit_status").val();
        var phone             = $.trim($("#id_phone").val());
        var origin            = $.trim( $("#id_origin").val());
        var st_application_nick = $("#id_st_application_nick").val();
        var subject           = $("#id_subject").val();
        var grade             = $("#id_grade").val();
        var opt_date_type = $("#id_opt_date_type").val();
        var page_count = $("#id_page_count").val();
        
        reload_self_page( {
            page_count: page_count,
            start_date        : start_date,
            end_date          : end_date, 
            phone             : phone, 
            from_type : from_type, 
            st_application_nick : st_application_nick, 
            origin            : origin, 
            grade             : grade, 
            subject           : subject, 
            opt_date_type  : opt_date_type,
            status            : status 
        });
	}
    
	$(".opt-change").on("change",function(){
		load_data();
	});

    $("#id_class_time").on("change",function(){
		load_data();
	});


    // lala


  $('.opt-update_user_info').on('click',function(){
        //修改部分
        var html_node = $('<div></div>').html(dlg_get_html_by_class('dlg-update_user_info'));
        var phone  = $(this).get_opt_data("phone");
        var status = $(this).parent().data('status');
        var note   = $(this).parents('td').siblings('.user-desc').text();
        html_node.find(".update_user_phone").val(phone);
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


    

    
    $("#id_add_user").on("click",function(){
	    // 处理
        var $phone=$("<input/>");
        var arr                = [
            ["电话", $phone],
        ];
        
        show_key_value_table("新增申请", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {

                var phone=$phone.val();
                if(phone.length!=11) {
                    alert("电话要11位") ;
                    return;
                }
                do_ajax('/seller_student/add_test_lesson_user', {
                    'phone':phone
                },function(){
                    alert('设置成功' );
                    window.location.reload();
                });
			    dialog.close();
            }
        });


    });


    
    
    $(".opt-set-lesson").on("click",function(){
        var phone        = $(this).get_opt_data("phone");
        var stu_nick     = $(this).get_opt_data("nick");
        var parent_nick  = $(this).get_opt_data("parent_nick");
        var parent_phone = $(this).get_opt_data("parent_phone");
        var address      = $(this).get_opt_data("address");
        var subject      = $(this).get_opt_data("subject");
        if(!stu_nick){
            stu_nick= "xxxxx";
        };

        if(!parent_nick){
            parent_nick = "xxx";
        };
        if(!parent_phone){
            parent_phone =  phone;
        };
        parent_phone =  (""+parent_phone).split("-")[0];
        if(!address){
            address = "默认地址";
        };
        if(!subject){
            subject = 1;
        };
        
        var check_test_lesson_order=function( userid ) {
                do_ajax("/user_deal/check_test_lesson" ,{
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
                            ,'grade'            : ""
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
                        },
			                success  : function(result){
				            if(result.ret != 0){
					            alert(result.info);
				            }else{

                                window.open('/stu_manage/lesson_plan_edit/?sid='+userid+"&courseid="+courseid+ "&return_url="+encodeURIComponent(window.location.href));
				            }
			            }
		                });
                        
                    }else{ 
                        window.open('/stu_manage/lesson_plan_edit/?sid='+userid+"&courseid="+courseid+ "&return_url="+encodeURIComponent(window.location.href));
                    }
                    
                });
        };

        do_ajax("/user_manage/get_userid_by_phone" ,{
            phone: $(this).get_opt_data("phone")
        },function(result){
            var userid=result.userid;
            if (userid) {
                check_test_lesson_order(userid);
            }else{
                alert('用户未注册');
            };   
            
        });
        
    });


    $(".opt-bro-lesson").on('click',function(){
        var phone =  $(this).get_opt_data("phone");
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
                    userid :  userid 
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
                    do_ajax( "/seller_student/set_test_lesson_st_arrange_lessonid",{
                        "st_arrange_lessonid" :  val ,
                        "phone" :  phone
                    });
                },
                //加载数据后，其它的设置
                "onLoadData"       : null
            });
         };
        do_ajax("/user_manage/get_userid_by_phone" ,{
            phone: $(this).get_opt_data("phone")
        },function(result){
            var userid=result.userid;
            if (userid) {
                select_lesson(userid);
            };   
            
        });

        
    });
        






});











    


