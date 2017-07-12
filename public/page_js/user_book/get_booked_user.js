$(function(){
    Enum_map.append_option_list("book_grade",$(".book_grade_list"));
    Enum_map.append_option_list("book_status",$(".update_user_status"),true);
    Enum_map.append_option_list("book_status",$("#id_revisit_status"));
    Enum_map.append_option_list("trial_type",$("#id_trial_type"));
    Enum_map.append_option_list("class_time",$("#id_class_time"));
    
    $('#id_start_date').val(g_start_date);
    $('#id_end_date').val(g_end_date);
    $('#id_type').val(g_args.type);
    $('#id_user_grade').val(g_args.grade);
    $('#id_revisit_status').val(g_args.status);
    $("#id_book_user").val(g_args.book_user);
    $("#id_book_origin").val(g_args.book_origin);
    $("#id_trial_type").val(g_args.trial_type);
    $("#id_sys_operator_type").val(g_args.sys_operator_type);
    $("#id_class_time").val(g_args.class_time);


    
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

    
	//时间控件-over
	function load_data( ){
        var type       = $("#id_type").val();
        var start_date = $("#id_start_date").val();
        var end_date   = $("#id_end_date").val();
        var grade      = $("#id_user_grade").val();
        var class_time=  $("#id_class_time").val();
        var status     = $("#id_revisit_status").val();
        var book_user  = $("#id_book_user").val();
        var book_origin  = $("#id_book_origin").val();
        var trial_type = $("#id_trial_type").val();
        var sys_operator_type= $("#id_sys_operator_type").val();
        
	    var url= window.location.pathname+ "?type="+type+"&start_date="+start_date+
                "&end_date="+end_date+"&grade="+grade+"&status="+status+"&class_time="+class_time+'&book_user='+book_user +'&book_origin='+book_origin+"&trial_type="+trial_type+"&sys_operator_type="+sys_operator_type ;
	    window.location.href=url;
	}
    
	$(".opt-change").on("change",function(){
		load_data();
	});

    $("#id_class_time").on("change",function(){
		load_data();
	});

    $("#id_add").on("click",function(){

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


        show_key_value_table("新增电话用户", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var nick= $.trim(id_nick.val());
                var phone = $.trim(id_phone.val());
                var grade        = id_grade.val();
                var subject      = id_subject.val();
                var  status = id_status.val();
                var  consult_desc = id_consult_desc.val();
                if (phone.length != 11  ) {
                    BootstrapDialog.alert('电话不是11位的!');
                    return;
                }
                if (nick.length <2  ) {
                    BootstrapDialog.alert('姓名不对');
                    return;
                }


                do_ajax( '/user_book/add_book_info',
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
        var html_node=$('<div></div>').html(dlg_get_html_by_class('dlg-add-revisit'));
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
                        url: '/user_book/add_book_revisit',
                        type: 'POST',
                        data: {
                            'phone': phone,
                            'op_note': op_note
                        },
                        dataType: 'json',
                        success: function(result){
                            BootstrapDialog.alert(result['info']);
                        }
                    });
			        dialog.close();
		        }
	        }]
        });
    });

    $('.opt-show-revisit-record').on('click',function(){
        var html_node=$('<div></div>').html(dlg_get_html_by_class('dlg-show-revisit'));
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
                            for(i=0; i<result['revisit_list'].length; i++) {
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
            if (g_args.register_flag==1 ) {
                $opt_lesson_open.hide();
            }
        }
        
    });

    $(".opt-set_sys_operator").on("click",function(){

        var phone  = $(this).get_opt_data("phone");
        do_ajax(
            "/user_book/set_sys_operator", {
                "phone" : phone 
            }
        );
        
    });

    $('.opt-update_user_info').on('click',function(){
        //修改部分
        var html_node = $('<div></div>').html(dlg_get_html_by_class('dlg-update_user_info'));
        var phone  = $(this).get_opt_data("phone");
        var status = $(this).parent().data('status');
        var note   = $(this).parents('td').siblings('.user-note').text();
        html_node.find(".update_user_phone").val(phone);
        html_node.find(".update_user_status").val(status);
        html_node.find(".update_user_note").val(note);
        html_node.find('.show-user-phone').text($(this).get_opt_data("phone") ); 

        BootstrapDialog.show({
            
            title: '修改用户信息',
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
			                url      : "/user_book/update_user_info",
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

    $('.opt-add_book_time_next').on('click',function(){
        var html_node = $('<div></div>').html(dlg_get_html_by_class('dlg-add_book_time_next'));
        var phone     = $(this).get_opt_data("phone");
        
	    html_node.find('.update_book_time_next').datetimepicker({
		    lang:'ch',
		    timepicker:false,
		    format:'Y-m-d'
	    });

        BootstrapDialog.show({
            title: '添加下次回访时间',
            message : html_node,
            closable: true, 
            closeByBackdrop:false,
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action : function(dialog) {
                        var update_book_time_next = html_node.find(".update_book_time_next").val();
                        
                        $.ajax({
			                type     : "post",
			                url      : "/user_book/update_book_time_next",
			                dataType : "json",
			                data : {
                                "phone"          : phone,
                                "book_time_next" : update_book_time_next
                            },
			                success : function(result){
                                window.location.reload();
			                }
                        });
                        dialog.close();
                    }
                },{
                    label: '清除回访',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                        $.ajax({
                            url:  '/user_book/clear_book_time',
                            type: 'POST',
                            data: {
                                'phone':phone
                            },
                            dataType: 'json',
                            success: function(result){
                                window.location.reload();
                            }
                        });
                    }
                },{
                    label: '取消',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();
                    }
                }]
        });
    });
	//点击进入个人主页
	$('.opt-user').on('click',function(){
		var userid=$(this).get_opt_data ("userid");
        if (userid) {
	        wopen( '/stu_manage?sid='+ userid+"&return_url="+ encodeURIComponent(window.location.href));
        }else{
            alert("用户未注册");
        }
	});



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

        show_key_value_table("安排课程时间", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var class_time= id_lesson_open.val();
                //alert(courseid);
                do_ajax('/user_book/set_class_time', {
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
        var nouse_userid_map= {
            "18616626799":true,
            "18616378968":true,
            "18601927270":true,
            "18616230003":true,
            "13003123870":true,
            "18790256265":true,
            "18801955047":true,
            "13661866487":true,
            "13918157988":true,
            "15221365528":true,
            "13262737397":true,
            "13524512024":true,
            "13512124679":true,
            "13162996379":true,
            "15000572329":true,
            "13661514024":true,
            "13918687479":true,
            "18116193588":true,
            "18717866927":true,
            "13918526997":true,
            "15601830297":true,
            "15000956045":true,
            "13661763881":true,
            "13720242201":true,
            "18801731860":true,
            "18672161690":true,
            "18516222920":true
        };
        $.each($tr_list ,function(tr_i,tr_item )  {
            var row_data= [];
            var $td_list= $(tr_item ).find("td");
            var phone = $(tr_item).find(".td-opt  >div" ).data("phone");

            if ( !nouse_userid_map[phone ]   && !map[phone] ) {
                
                $.each(  $td_list, function( i, td_item)  {
                    if (i>0 && i< $td_list.length-1 ) {
                        if (
                             i==1
                            || i==2
                            || i==3
                            || i==6
                            || i==7
                            || i==8
                            || i==10
                            || i==11
                            || i==12
                           ){
                               var value= $(td_item).text() ;
                               if ( tr_i>0 && i==1 )  {
                                   value=phone;
                               }

                               row_data.push($.trim (value));
                           }
                    }
                });
                list_data.push(row_data);
                map[phone]=true;
            }

        });
        
        do_ajax ( "/common_new/upload_xls_data",{
            xls_data :  JSON.stringify(list_data )
        },function(data){
            // window.open("/common_new/download_xls",true);
            window.location.href= "/common_new/download_xls";
        });

    });



});
