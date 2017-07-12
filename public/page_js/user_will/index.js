$(function(){
    Enum_map.append_option_list("book_status",$("#id_revisit_status"));
    Enum_map.append_option_list("book_status",$(".update_user_status"),true);

    $('#id_type').val(g_args.type);
    $('#id_start_time').val(g_start_time);
    $('#id_end_time').val(g_end_time);
    $('#id_return_order_flag').val(g_args.return_order_flag);
    $('#id_login_order_flag').val(g_args.login_order_flag);
    $('#id_revisit_status').val(g_args.status);
    $('#id_phone').val(g_args.phone);
    
	$('#id_start_time').datetimepicker({
		lang:'ch',
		timepicker:false,
		onChangeDateTime :function(){
			load_data();
		},
		format:'Y-m-d'
	});
	
	$('#id_end_time').datetimepicker({
		lang:'ch',
		timepicker:false,
		onChangeDateTime :function(){
			load_data();
		},
		format:'Y-m-d'
	});
	
	$(".opt-query-change" ).on( "change",function(){
		load_data();
	});
	
	function load_data(){
        var type              = $("#id_type").val();
        var start_time        = $("#id_start_time").val();
        var end_time          = $("#id_end_time").val();
        var return_order_flag = $("#id_return_order_flag").val();
        var login_order_flag  = $("#id_login_order_flag").val();
        var phone             = $("#id_phone").val();
        var status            = $("#id_revisit_status").val();
		var url = "/user_will?type="+type+"&start_time="+start_time+"&end_time="+end_time
            +"&return_order_flag="+ return_order_flag+"&login_order_flag="+login_order_flag+"&phone="+phone+"&status="+status;
		window.location.href=url;
	}

    $("#id_phone").on('keypress',function(e){
        if(e.keyCode== 13){
            var phone = $("#id_phone").val();
 		    if(phone != ""){
			    load_data();
		    }else{
			    alert("请输入 姓名或 电话 ");
		    }
        }
	});
    
    $("#id_reg_user_search").on('click',function(){
        var phone = $("#id_phone").val();
		if(phone!=""){
			load_data();
		}else{
			alert("请输入 姓名或 电话 ");
		}
	});
    
    $("#id_add_user").on("click",function(){
        var id_telphone=$("<input type=\"\">");
        var id_passwd=$("<input type=\"\">");
        var id_grade=$("<select/>");
        Enum_map.append_option_list("grade", id_grade,true);
        id_grade.val(201);
        id_passwd.val("123456");
        var arr = [
            [ "电话",  id_telphone] ,
            [ "密码",  id_passwd] ,
            [ "年级",  id_grade] ,
        ];

        show_key_value_table("新增用户", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var passwd=id_passwd.val();
                if (passwd.length<4){
                    alert("密码长度要>4!");
                    return; 
                }
                
                do_ajax('/login/register',{
                    'telphone' : id_telphone.val(),
                    'passwd'   : id_passwd.val(),
                    'grade'    : id_grade.val() 
			    });
            }
        });
    });

	//点击进入个人主页
	$('.opt-user').on('click',function(){
		var userid = $(this).parent().data("userid");
		var nick   = $(this).parent().data("stu_nick");
	    wopen( '/stu_manage?sid='+ userid+"&return_url="+ encodeURIComponent(window.location.href));
	});
	
	$(".opt-note").on("click",function(){
		var userid = $(this).parent().data("userid");
		var status = $(this).parent().data("status");
		var note   = $(this).parents("td").siblings(".operator_note").text();
        
        var html_node=$('<div></div>').html(dlg_get_html_by_class('dlg-update_user_info'));
        html_node.find(".update_user_status").val(status);
        html_node.find(".update_user_note").val(note);
        
        BootstrapDialog.show({
            title: '备注信息',
            message : html_node,
            closable: false, 
            buttons: [{
                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
		            var note   = html_node.find(".update_user_note").val();
		            var status = html_node.find(".update_user_status").val();
		            $.ajax({
			            type     :"post",
			            url      :"/stu_manage/set_operator_note",
			            dataType :"json",
			            data     :{
                            "userid":userid
                            ,"status":status
                            ,"note":note
                        },
			            success  : function(result){
                            if(result['ret'] != 0){
                                alert(result['info']);
                            }else{
                                window.location.reload();
                            }
			            }
		            });
                }
            }]
        }); 
	});
    
    $('.opt-add_revisit_time').on('click',function(){
		var userid = $(this).parent().data("userid");
        var html_node = $('<div></div>').html(dlg_get_html_by_class('dlg-add_revisit_time'));
        
	    html_node.find('.update_revisit_time').datetimepicker({
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
                        var update_revisit_time= html_node.find(".update_revisit_time").val();
                        
                        $.ajax({
			                type     : "post",
			                url      : "/user_will/update_revisit_time",
			                dataType : "json",
			                data : {
                                "userid"       : userid,
                                "revisit_time" : update_revisit_time
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
                            url:  '/user_will/clear_revisit_time',
                            type: 'POST',
                            data: {
                                'userid':userid
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
});






