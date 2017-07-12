// SWITCH-TO:   ../../template/user/
$(function(){
    Enum_map.append_option_list("book_status",$("#id_revisit_status"));
    Enum_map.append_option_list("book_status",$(".update_user_status"),true);

    $('#id_type').val(g_args.type);
    $('#id_start_time').val(g_start_time);
    $('#id_end_time').val(g_end_time);
    $('#id_return_order_flag').val(g_args.return_order_flag);
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


	$("#id_search_will").on("click",function(){
		var phone = $("#id_phone").val();
		if(phone == ""){
			alert("请输入电话号码");
		}else{
			load_data();
		}
	});

	//点击进入个人主页
	$('.opt-user').on('click',function(){
		var userid = $(this).parent().data("userid");
		var nick   = $(this).parent().data("stu_nick");
		//wopen('/stu_manage?sid = '+userid+'&nick='+nick+"&"  );
	    wopen( '/stu_manage?sid='+ userid+"&return_url="+ encodeURIComponent(window.location.href));
	});

	function load_data(){
        var type              = $("#id_type").val();
        var start_time        = $("#id_start_time").val();
        var end_time          = $("#id_end_time").val();
        var return_order_flag = $("#id_return_order_flag").val();
        var phone             = $("#id_phone").val();
        var status            = $("#id_revisit_status").val();
		var url = "/user_will/user_will?type="+type+"&start_time="+start_time+"&end_time="+end_time
            +"&return_order_flag="+ return_order_flag+"&phone="+phone+"&status="+status;
		window.location.href=url;
	}

	$("#id_revisit_enter").on("click",function(){
		var revisit_person = $.trim($("#id_revisit_person").val());
		var operator_note = $.trim($("#id_revisit_note").val());
		var userid = 	$(".mesg_alert03").data("userid");
		$.ajax({
			type     :"post",
			url      :"/revisit/add_revisit_record",
			dataType :"json",
			data     :{"userid":userid,"revisit_person":revisit_person,"operator_note":operator_note},
			success  : function(result){
				if(result.ret !== 0){
					alert("添加回访记录失败");
				}else{
					alert("成功添加回访记录");
					$(".mesg_alert03").hide();
				}
			}
		});
		
	});
	
    $("#id_return_back").on("click",function(){
        window.location.reload();
    });

	$(".opt-show-info").on("click",function(){
		var userid = $(this).parent().data("userid");

        $("#id_tbl_info").hide();
        $("#id_detail_info").show();

		$.ajax({
			type     :"post",
			url      :"/test_info/get_test_info",
			dataType :"json",
			data     :{"userid":userid},
			success  : function(result){
                console.log(result);
				if(result.ret == 0){
					$("#id_analysis").data("userid",result.test_result.userid);
					$("#id_user_phone").text(result.test_result.phone); 
					$("#id_user_grade").text(result.test_result.grade);
					$("#id_user_region").text(result.test_result.region);
					$("#id_user_textbook").text(result.test_result.textbook);
					$("#id_analysis").text(result.test_result.analysis);
					var str = "";
					if(result.test_result.test_type == 1){
						$.each(result.test_result.test_info, function(i, item){
							str = str+"<li>"+(i+1)+". "+item+"</li>";
						});
						$("#id_lack_knowledge").html(str);
					}else if(result.test_result.test_type == 2){
                        var tests = $("#id_test_pic").find("a");
                        $.each(tests, function(i, item){
                            var url = result.test_result.test_info[i];
                            if( typeof url != "undefined"){
                                console.log(url);
                                if(-1 == url.indexOf("http") ){
                                    url = g_public + url;
                                }
                                console.log(url);
                                $(item).data("url", url);
                                $(item).data("title", "测试试卷");
                            }
                        });
                        
					}else if(result.test_result.test_type == 3){
						str = "<li class=\"score_h\"><span>试卷总分：100分&nbsp&nbsp</span><span>学生分数："+result.test_result.score+"</span><span>&nbsp&nbsp做题时长："+result.test_result.time_cost+"</span></li>";
                        var current_point = result.test_result.current_point;
                        var test_version = result.test_result.test_version; 
                        var baseDir = "/test_quiz/"+test_version+"/"+current_point+"/question/";
						$.each(result.test_result.test_info,function(i, item){
                            var tmp = item.split("|");
                            var testUrl = baseDir + "q" + tmp[0]+".png";
                            var answerUrl = baseDir + "/analysis/analy_" + tmp[0]+".png";
                            var answer = tmp[1];
                            str = str+"<li><img  style=\"width:100%;\" src=\""+testUrl+"\"/><img style=\"width:100%;\"  src=\""+answerUrl+"\"/><p class=\"stu_score\">学生答案："+ answer +"</p></li>";
						});
						$("#id_test").html(str);
					}
				}				
			}
		});
		
	});
	
	$("#id_submit_analysis").on("click",function(){
		var analysis = $("#id_analysis").val();
		var userid = $("#id_analysis").data("userid");
		$.ajax({
			type     :"post",
			url      :"/test_info/set_analysis",
			dataType :"json",
			data     :{"userid":userid,"analysis":analysis},
			success  : function(result){
                window.location.reload();
			}
		});
	});

    $(".test_image").on("click", function(){
        $.fancybox.open([
            {
                href :  $(this).data("url"),
                title : $(this).data("title") 
            }],{
                padding : 0  ,
                type : "image"
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
                            type: 'post',
                            data: {
                                'userid':userid
                            },
                            datatype: 'json',
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


    
});
