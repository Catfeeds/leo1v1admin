$(function(){
    $("#id_teacherid").val(g_args.teacherid);
    $("#id_teacherid").admin_select_user({
        "type"   : "teacher",
        "onChange": function(){
            load_data(  $("#id_teacherid").val());
        }
    });

    $("#id_ass_name").val(g_args.ass_nick);
    $("#id_ass_phone").val(g_args.phone);
	//确认删除老师
	//tab栏
	// tab('.nav_tit .teach_mate','.teach_mate','.stu_tab12 td','.teacher_box',0);
	//弹窗
	$("#id_edit_age").datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d'
	});
	
	$("#id_choose_ym").datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m'
	});

	$(".opt-change").on("change",function(){
		load_data();
	});


    function load_data(){
        var tea_nick     = $("#id_ass_name").val();
        var phone        = $("#id_ass_phone").val();
        var teacherid    = $("#id_teacherid").val();

        reload_self_page({
            teacherid    : teacherid,
            ass_nick     : tea_nick,
            phone        : phone
        });
    }




    $('.done_s').on('click', function(){
        var html_node = $("<div></div>").append( dlg_get_html_by_class('dlg_add_teacher'));
        html_node.find("#id_add_birth").datetimepicker({
		    lang:'ch',
		    timepicker:false,
		    format:'Y-m-d'
	    });

        BootstrapDialog.show({
            title: '新增教师',
            message : html_node,
            closable: true, 
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action: function(dialog) {
		                var tea_nick     = html_node.find("#id_add_tea_name").val();
		                var gender       = html_node.find("#id_add_gender").val();
		                var birth        = html_node.find("#id_add_birth").val();
		                var work_year    = html_node.find("#id_add_work_year").val();
		                var phone        = html_node.find("#id_add_phone").val();
		                var email        = html_node.find("#id_add_email").val();
		                var teacher_type = html_node.find("#id_add_teacher_type").val();
		                
		                $.ajax({
			                type     :"post",
			                url      :"/tea_manage/add_teacher",
			                dataType :"json",
			                data     :{"tea_nick":tea_nick,"gender":gender,"birth":birth,"work_year":work_year,
                                       "phone":phone,"email":email,"teacher_type":teacher_type},
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
                    label: '取消',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();
                    }
                }]
        });

    });

    $(".done_modify").on("click", function(){
        var html_node =$("<div></div>").html(dlg_get_html_by_class('dlg_set_dynamic_passwd'));

        html_node.find(".tea_phone").text($(this).parents("td").siblings(".tea_phone").text());
        html_node.find(".tea_nick").text($(this).parents("td").siblings(".tea_nick").text());
        html_node.find(".dynamic_passwd").val("123456");
    
        BootstrapDialog.show({
            title: '设置教师动态登陆密码',
            message : html_node,
            closable: true, 
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action: function(dialog) {
                        var phone  = html_node.find(".tea_phone").text();
                        var passwd = html_node.find(".dynamic_passwd").val();

		                $.ajax({
			                type     :"post",
			                url      :"/user_manage/set_dynamic_passwd",
			                dataType :"json",
			                data     :{"phone":phone, "passwd": passwd, "role": 2 },
			                success  : function(result){
                                BootstrapDialog.alert(result['info']);
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
    });

    $(".done_info").on("click", function(){
        var teacherid    = $(this).parents("td").siblings(".tea_id").text();
        $(this).parents('td').addClass('teacher_modify');
        dp_get_tags(teacherid);
    });

    var dp_get_tags = function(teacherid) {
        $.getJSON('/tea_manage/get_tea_tags', {
            'teacherid': teacherid
        }, function(result){
            dp_set_tags(teacherid, result['tags_info']);
        });
    };

    var dp_set_tags = function(teacherid, tags_info){
        var html_node = $("<div></div>").html(dlg_get_html_by_class('dlg_set_tags'));
        /* tags start */
        var tags_str = '';
        if (tags_info['teacher_tags'].length != 0) {
            for(var i=0; i<tags_info['teacher_tags'].length; i++) {
                tags_str += '<tr><td>教师标签_'+(i+1)+'</td>'+
                    '<td class="tags add_confirmed">'+tags_info['teacher_tags'][i]+'</td><td>'+
                    '<button class="btn btn-warning fa fa-close form-control delete_tags " ></button>' +
                    '</td></tr>';
            }
        }
        html_node.find('.add_tags').parents('tr').after(tags_str);        
        /* tags end */
        /* textbook start */
        var textbook_str = '';
        if (tags_info['teacher_textbook'].length != 0) {
            for(var j=0; j<tags_info['teacher_textbook'].length; j++) {
                textbook_str += '<tr><td>教师教材_'+(j+1)+'</td>'+
                    '<td class="textbook add_confirmed">'+tags_info['teacher_textbook'][j]+'</td><td>'+
                    '<button class="btn btn-warning fa fa-close form-control delete_textbook " ></button>' +
                    '</td></tr>';
            }
        }
        html_node.find('.add_textbook').parents('tr').after(textbook_str);        
        /* textbook end */
        
        BootstrapDialog.show({
            title: '添加教师标签和教材',
	        message : function(dialog){
                html_node.find('.package_tags_set').on('click',  '.add_tags', function(){
                    $(this).parents('tr').after('<tr><td>新添教师标签</td>'+
                        '<td class="tags"><input type="text" class="form-control" /></td><td>'+
                        '<button class="btn btn-warning fa fa-check form-control add_tags_check" ></button>' +
                        '</td></tr>');
                });
                
	            html_node.find('.package_tags_set').on('click',  '.add_textbook', function(){
                    $(this).parents('tr').after('<tr><td>新添教师教材</td>'+
                        '<td class="textbook"><input type="text" class="form-control" /></td><td>'+
                        '<button class="btn btn-warning fa fa-check form-control add_textbook_check" ></button>' +
                        '</td></tr>');
                });

                html_node.find('.package_tags_set').on('click', '.add_tags_check', function(){
                    var package_tags = $(this).parents('tr').children('.tags').children('input').val();
                    $(this).parents('tr').children('.tags').empty();
                    $(this).parents('tr').children('.tags').text(package_tags);
                    $(this).parents('tr').children('.tags').addClass('add_confirmed');
                    $(this).addClass('delete_tags');
                    $(this).addClass('fa-close');
                    $(this).removeClass('add_tags_check');
                    $(this).removeClass('fa-check');
                });
                
                html_node.find('.package_tags_set').on('click', '.add_textbook_check', function(){
                    var package_textbook = $(this).parents('tr').children('.textbook').children('input').val();
                    $(this).parents('tr').children('.textbook').empty();
                    $(this).parents('tr').children('.textbook').text(package_textbook);
                    $(this).parents('tr').children('.textbook').addClass('add_confirmed');
                    $(this).addClass('delete_textbook');
                    $(this).addClass('fa-close');
                    $(this).removeClass('add_textbook_check');
                    $(this).removeClass('fa-check');
                });

                html_node.find('.package_tags_set').on('click', '.delete_tags', function(){
                    $(this).parents('tr').remove();
                });
                
                html_node.find('.package_tags_set').on('click', '.delete_textbook', function(){
                    $(this).parents('tr').remove();
                });

                return html_node;
            },
           
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action: function(dialog) {
                        var textbook = '';
                        var tags = '';
                        html_node.find('.tags','.add_confirmed').each(function(){
                            tags += $(this).text() + ',';
                        });
                        
                        html_node.find('.textbook','.add_confirmed').each(function(){
                            textbook += $(this).text() + ',';
                        });


                        $.ajax({
			                type     :"post",
			                url      :"/tea_manage/set_tea_tags",
			                dataType :"json",
			                data     :{"teacherid":teacherid, "textbook": textbook, "tags": tags },
			                success  : function(result){
                                BootstrapDialog.alert(result['info']);
                                if (result['ret'] == 0) {
                                    // shezhi
                                    $('.teacher_modify').siblings('.teacher_tags').text(result['tags']);
                                    $('.teacher_modify').siblings('.teacher_textbook').text(result['textbook']);
                                    $('.teacher_modify').removeClass('teacher_modify');
                                }
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


    
    function get_tea_detail(teacherid){
     	$.ajax({
			type     :"post",
			url      :"/human_resource/get_tea_page_info",
			dataType :"json",
			data     :{'teacherid':teacherid},
			success  : function(result){
				$("#id_teacher_name").html(result.teacher_info.tea_nick);
				$("#id_teacher_gender").html(result.teacher_info.gender);
				$("#id_teacher_age").html(result.teacher_info.age);
				$("#id_teacher_work_year").html(result.teacher_info.work_year);
				$("#id_teacher_phone").html(result.teacher_info.phone);
				$("#id_teacher_email").html(result.teacher_info.email);
				$("#id_teacher_rate_score").html(result.teacher_info.rate_score);
				$("#id_teacher_style").html(result.teacher_info.teacher_style);
				$("#id_teacher_achievement").html(result.teacher_info.achievement);
				$(".header_img").html(result.teacher_info.face);
				$("#id_pictures").html(result.teacher_info.analysis_pics);
				$("#id_hidden_teacherid").val(result.teacher_info.teacherid);
				$("#id_pre_list").val(result.teacher_info.quiz_analyse);

				$("#id_free_time").html(result.free_list);

                $("#id_teacher_gender").data('gender_num', result.teacher_info.gender_num);
			}
		});
		$('.teach_mesg').show().siblings('.teacher_list').hide();
    }

    // $("#id_add_analysis_url").on("click", function(){
    //     var url = $("#id_analysis_url").val();
    //     var teacherid = $("#id_teacher_name").data("teacherid");
    //     var pre_list = $("#id_pre_list").val();
    //     if(url == ""){
    //         alert("URL不能为空!");
    //     }
 	//     $.ajax({
	// 		type     :"post",
	// 		url      :"/human_resource/upload_pic",
	// 		dataType :"json",
	// 	    data     :{'url':url, 'pre_list': pre_list, 'teacherid': teacherid},
	// 		success  : function(result){
    //             alert(result.data);
	// 		}
	// 	});
    // });
    
	//教师详情页面
	$('.teacher_list .done_o').click(function(){
		var teacherid = $(this).parent().data("teacherid");
		$("#id_teacher_name").data("teacherid", teacherid);
        get_tea_detail(teacherid);
        $(".teacher_box").hide();
        $('.teach_mesg').show();
	});
	
	$('.teach_mesg .back').click(function(){
		$('.teach_mesg').hide().siblings('.teacher_box').show();
	});

	$('.load_editor').click(function(){
		for(i=0;i<$('.edit_b').length;i++){
			$('.put_mesag').eq(i).html($('.edit_b').eq(i).val());
		};
		$('.put_mesag').show().siblings('.edi').hide();
		$('.put_mesag02').show().html($("#tea_sexy").val()).siblings('.edi').hide();
		$('.put_mesag03').show().html($("#tea_job").val()).siblings('.edi').hide();
		$('.put_mesag04').show().html($("#tea_grade").val()).siblings('.edi').hide();
		$('.watch').show().siblings('.re_change').hide();

		var teacherid = $("#id_teacher_name").data("teacherid");
		var tea_nick = $("#id_edit_name").val();
		var gender = $("#id_edit_gender").val();
		var birth = $("#id_edit_age").val();
		var work_year = $("#id_edit_work_year").val();
		var phone = $("#id_edit_phone").val();
		var email = $("#id_edit_email").val();
		var teacher_style = $("#id_edit_style").val();
		var achievement = $("#id_edit_achievement").val();
		if(gender == 1){
			$("#id_teacher_gender").html("男");
		}else{
			$("#id_teacher_gender").html("女");
		}
		$.ajax({
			type     :"post",
			url      :"/human_resource/update_teacher_info",
			dataType :"json",
			data     :{'teacherid':teacherid,'tea_nick':tea_nick,'gender':gender,'birth':birth,'work_year':work_year,'phone':phone,'email':email,'advantage':teacher_style,'base_intro':achievement},
			success  : function(result){
				if(result['ret'] != 0){
                    alert(result['info']);
                }
			}
		});
		$(this).hide().siblings('.re_editor').show();
	});

    $('.tea_scheduleTab').on("click", ".free_time", function(){
		var isfree = $(this).data("isfree");
		if(isfree == 0){
			$(this).addClass('have');
			$(this).data("isfree",1);
		}else{
			$(this).removeClass('have');
			$(this).data("isfree",0);
		}
    });

    $('#id_set_all').on("click",  function(){
		var free_time_list = $(".free_time");
		free_time_list.addClass('have');
        free_time_list.data("isfree",1);
    });

    $('#id_clean_all').on("click",  function(){
		var free_time_list = $(".free_time");
		free_time_list.removeClass('have');
        free_time_list.data("isfree",0);
    });


	
	// $("#id_change_free_time").on("click",function(){
	// 	var teacherid = $("#id_teacher_name").data("teacherid");
	// 	var free_time_list = $(".free_time");
	// 	var str = "";
	// 	$.each(free_time_list,function(i,item){
	// 		var isfree = $(item).data("isfree");
	// 		if(isfree == 1){
	// 			str = str+"1,";
	// 		}else{
	// 			str = str+"0,";
	// 		}
	// 	});
	// 	str = str.substr(0,97);
	// 	$.ajax({
	// 		type     :"post",
	// 		url      :"/human_resource/update_teacher_free",
	// 		dataType :"json",
	// 		data     :{'teacherid':teacherid,'free_time_list':str},
	// 		success  : function(result){
	// 			//window.location.reload();
	// 			alert("更新完成");
	// 		}
	// 	});
	// 	return false;
	// });
	
	// $("#id_change_free_time").on("click",function(){
	// 	var teacherid = $("#id_teacher_name").data("teacherid");
    //     $.ajax({
	// 		type     :"post",
	// 		url      :"/human_resource/get_teacher_arranged_lesson",
	// 		dataType :"json",
	// 		data     :{'teacherid':teacherid},
	// 		success  : function(result){
    //             if(result.ret == 0){
    //                 $(".alert11").show();
    //                 if(result.num != 0){
    //                     $("#id_submit_free").hide();
    //                     $("#id_jump_to_lesson").show();
    //                     $("#id_alert_info").html("");
    //                     $("#id_alert_info").html("空闲时间与未上完的课程时间冲突。");
    //                 }else if(result.num == 0){
    //                     $("#id_alert_info").html("");
    //                     $("#id_alert_info").html("确定更新老师的空闲时间？");
    //                     $("#id_jump_to_lesson").hide();
    //                     $("#id_submit_free").show();
    //                 }
    //             }
	// 		}
	// 	});
	// });

	$("#id_change_free_time").on("click",function(){
		var teacherid = $("#id_teacher_name").data("teacherid");
		var free_time_list = $(".free_time");
		var str = "";
		$.each(free_time_list,function(i,item){
			var isfree = $(item).data("isfree");
			if(isfree == 1){
				str = str+"1,";
			}else{
				str = str+"0,";
			}
		});
		str = str.substr(0,97);
        $(".mesg_alert35").show();
        $.ajax({
			type     :"post",
			url      :"/human_resource/check_teacher_free",
			dataType :"json",
			data     :{'teacherid':teacherid, 'teacher_free': str},
			success  : function(result){
                if(result.ret == 0){
                    var str = "";
                    $.each(result.clash_lesson_list, function(i, item){
                        str += "<li><a href='javascript:;' class='lesson_url' data-userid='"+item.userid+"'data-nick='"+item.nick+"'><i>"+item.nick+"</i> 课程id:<i>"+item.courseid+"</i> 第<i>"+item.lesson_num+"</i>次课</li></a>";
                    });
                    if(str == ""){
                        str = "没有冲突课程,请提交更新后的老师空闲时间";
                        BootstrapDialog.show({
                            title: '系统提示',
                            message: str,
                            buttons: [
                                {
                                    label: '确认',
                                    cssClass: 'btn btn-warning',
                                    action: function(dialog) {
    	                                var teacherid = $("#id_teacher_name").data("teacherid");
		                                var free_time_list = $(".free_time");
		                                var str = "";
		                                $.each(free_time_list,function(i,item){
			                                var isfree = $(item).data("isfree");
			                                if(isfree == 1){
				                                str = str+"1,";
			                                }else{
				                                str = str+"0,";
			                                }
		                                });
		                                str = str.substr(0,97);
			                            $.ajax({
			                                type     :"post",
			                                url      :"/human_resource/update_teacher_free",
			                                dataType :"json",
			                                data     :{'teacherid':teacherid,'free_time_list':str},
			                                success  : function(result){
				                                //window.location.reload();
                                                window.location.reload();
			                                }
		                                });
                                        dialog.close();
                                    }
                                }, {
                                    label: '取消',
                                    cssClass: 'btn btn-default',
                                    action: function(dialog) {
                                        dialog.close();
                                    }
                                }
                            ]
                        }); 
                    }else{
                    }
                    $(".lesson_clash").find("ul").html(str);
                }
			}
		});
	});

    $("#id_modify_teacher_info").on("click", function(){

        var html_node = $("<div></div").html(dlg_get_html_by_class('dlg_modify_teacher_info'));
		html_node.find("#id_edit_age").datetimepicker({
		    lang:'ch',
		    timepicker:false,
		    format:'Y-m'
        });
        html_node.find("#id_edit_name").val($("#id_teacher_name").text());
        html_node.find("#id_edit_gender").children().filter( function(index) {
            var ret = $(this).text().localeCompare( $("#id_teacher_gender").text());
            return ret == 0;
        }).attr("selected", true);
        html_node.find("#id_edit_age").val($("#id_teacher_age").text());
        html_node.find("#id_edit_work_year").val($("#id_teacher_work_year").text());
        html_node.find("#id_edit_email").val($("#id_teacher_email").text());
        html_node.find("#id_edit_style").val($("#id_teacher_style").text());
        html_node.find("#id_edit_achievement").val($("#id_teacher_achievement").text());

        BootstrapDialog.show({
            title: '修改教师信息',
            message: html_node,
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn btn-warning',
                    action: function(dialog) {
		                var teacherid     = $("#id_teacher_name").data("teacherid");
		                var tea_nick      = html_node.find("#id_edit_name").val();
		                var gender        = html_node.find("#id_edit_gender").val();
		                var birth         = html_node.find("#id_edit_age").val();
		                var work_year     = html_node.find("#id_edit_work_year").val();
		                var phone         = html_node.find("#id_edit_phone").val();
		                var email         = html_node.find("#id_edit_email").val();
		                var teacher_style = html_node.find("#id_edit_style").val();
		                var achievement   = html_node.find("#id_edit_achievement").val();
		                $.ajax({
			                type     :"post",
			                url      :"/human_resource/update_teacher_info",
			                dataType :"json",
			                data     :{'teacherid':teacherid,'tea_nick':tea_nick,'gender':gender,'birth':birth,'work_year':work_year,'email':email,'advantage':teacher_style,'base_intro':achievement},
			                success  : function(result){
				                if(result['ret'] != 0){
                                    alert(result['info']);
                                }
			                }
		                });
                        // window.location.reload();
                        dialog.close();
                    }
                },{
                    label: '取消',
                    cssClass: 'btn btn-primary',
                    action: function(dialog) {
                        dialog.close();
                    }
                }
                
            ]
        });
    });

    // $(".lesson_url").live("click", function(){
    //     $(this).addClass("clicked");
    //     var url = "/stu_manage/lesson_plan?sid="+$(this).data("userid")+"&nick="+$(this).data("nick");
    //     window.open(url);
    // });
    
	$(".done_u").on("click",function(){
		var teacherid = $(this).parent().data("teacherid");
		$("#id_choose_ym").data("teacherid",teacherid);
		var year_month = $("#id_choose_ym").val();
		$.ajax({
			type     :"post",
			url      :"/human_resource/get_teacher_checking_attend",
			dataType :"json",
			data     :{'teacherid':teacherid,'year_month':year_month},
			success  : function(result){
                var html_node = $("<table  border=\"1\" bordercolor=\"#d5d5d5\" style=\"width:100%\"></table>").append(
                    '<thead><tr><td></td><td>无提前5分钟</td><td>迟到5分内</td>'+
                    '<td>迟5-10分内</td><td>迟10分上</td><td>提前退出</td>'+
                    '<td>旷课</td></tr></thead><tbody id="id_checking_attend"></tbody>');
                html_node.find("#id_checking_attend").html(result.checking);

                BootstrapDialog.show({
                    title: '上课统计',
                    message : html_node,
                    closable: true, 
                    buttons: [{
                        label: '返回',
                        action: function(dialog) {
                            dialog.close();
                        }
                    }]
                });
			}
		});	
	});

	$("#id_search_checking").on("click", function(){
		var teacherid = $("#id_choose_ym").data("teacherid");
		var year_month =  $("#id_choose_ym").val();
		alert(year_month);
		$.ajax({
			type     :"post",
			url      :"/human_resource/get_teacher_checking_attend",
			dataType :"json",
			data     :{'teacherid':teacherid,'year_month':year_month},
			success  : function(result){
				$("#id_checking_attend").html("");
				$("#id_checking_attend").html(result.checking);
			}
		});	
	});

	$(".done_t").on("click", function(){
		var teacherid = $(this).parent().data("teacherid");
        BootstrapDialog.show({
            title: '系统提示',
            message : '确认从教师档案中删除该老师及其相关信息',
            closable: true, 
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-danger',
                    action: function(dialog) {
		                $.ajax({
			                type     :"post",
			                url      :"/human_resource/delete_teacher",
			                dataType :"json",
			                data     :{'teacherid':teacherid,'teacher_type':0},
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

    // 设置老师空闲时间
    $(".done_allo").on('click', function(){
        
    });
    var custom_upload = function(btn_id, containerid, domain, compelete_func, extra_data){
        console.log('asdasfd');
        var uploader = Qiniu.uploader({
		    runtimes: 'html5, flash, html4',
		    browse_button: btn_id , //choose files id
		    uptoken_url: '/upload/pub_token',
		    domain: domain,
		    container: containerid,
		    drop_element: containerid,
		    max_file_size: '30mb',
		    dragdrop: true,
		    flash_swf_url: '/js/qiniu/plupload/Moxie.swf',
		    chunk_size: '4mb',
		    unique_names: false,
		    save_key: false,
		    auto_start: true,
		    init: {
			    'FilesAdded': function(up, files) {
				    plupload.each(files, function(file) {
                        var progress = new FileProgress(file, 'process_info');
                        console.log('waiting...');
                    });
			    },
			    'BeforeUpload': function(up, file) {
				    console.log('before uplaod the file');
			    },
			    'UploadProgress': function(up,file) {
				    var progress = new FileProgress(file, 'process_info');
                    progress.setProgress(file.percent + "%", up.total.bytesPerSec, btn_id);
				    console.log('upload progress');
			    },
			    'UploadComplete': function() {
                    // $("#"+btn_id).siblings('div').remove();
				    console.log('success');
			    },
			    'FileUploaded' : function(up, file, info) {
				    console.log('Things below are from FileUploaded');
                    compelete_func(domain, info, extra_data);
                    // var res = $.parseJSON(info);
                    // $(".bootstrap-dialog-body .gift_url").val(domain + res.key);
                    // $(".bootstrap-dialog-body .preview_gift_pic").attr("href", domain + res.key);
                    // set the key
			    },
			    'Error': function(up, err, errTip) {
				    console.log('Things below are from Error');
				    console.log(up);
				    console.log(err);
				    console.log(errTip);
			    },
			    'Key': function(up, file) {
                    console.log("Key start");
                    console.log(file);
                    var suffix = file.type.split('/').pop();
                    console.log(suffix);
                    console.log("Key end");
				    var key = "";
				    //generate the key
                    var time = (new Date()).valueOf();
				    return $.md5(file.name) +time+ "." + suffix;
			    }
		    }
	    });
        
    };

    var uploader = Qiniu.uploader({
		runtime: 'html5, flash, html4',
		browse_button: 'id_upload', //choose files
		uptoken_url: '/upload/pub_token',
		domain: 'http://ebtestpub.qiniudn.com/',
		container: 'id_container',
		drop_element: 'id_container',
		max_file_size: '2mb',
		dragdrop: true,
		chunk_size: '4mb',
		unique_names: false,
		save_key: false,
		auto_start: true,
        filters: {
            mime_types : [
                {title:"image", extensions: "jpg"},
                {title:"image", extensions: "jpeg"},
                {title:"image", extensions: "png"},
                {title:"image", extensions: "bmp"},
                {title:"image", extensions: "gif"},
            ]
        },
		init: {
			'FilesAdded': function(up, files) {
				console.log('files added');
				plupload.each(files, function(file) {
                    var progress = new FileProgress(file, 'process_info');
                    console.log('waiting...');
                });
			},

			'BeforeUpload': function(up, file) {
				console.log('before uplaod the file');
				var progress = new FileProgress(file, 'process_info');
			},
			
			'UploadProgress': function(up,file) {
				console.log('upload progress');
				//TODO add the processing info
				var progress = new FileProgress(file, 'process_info');
                progress.setProgress(file.percent + "%", up.total.bytesPerSec);
			},

			'UploadComplete': function() {
				console.log('success');
			},

			'FileUploaded' : function(up, file, info) {
				console.log('Things below are from FileUploaded');
				var progress = new FileProgress(file, 'process_info');           
                progress.setComplete(up, info, file);//function(up, info, file)
			},

			'Error': function(up, err, errTip) {
				console.log('Things below are from Error');
				console.log(err);
                console.log(err.code);

                switch(err.code) {
                    case -600:
                        alert("请上传2M以内图片");
                        break;
                    default:
                        alert("上传错误,请确认图片大小在2M以内以及图片格式正确");
                }
			},
			'Key': function(up, file) {
				//generate the key
                time = (new Date()).valueOf();
				return $.md5(file.name) +time;
			}
		}

	});

    function FileProgress(file, targetID)
    {
	    this.fileProgressID = file.id;
	    this.file = file;
	    var fileSize = plupload.formatSize(file.size).toUpperCase();
	    this.fileProgressWrapper = $('#' + this.fileProgressID); 
	    file_size = get_file_size(file.size);
	    this.setTimer(null);
    }

    FileProgress.prototype.setTimer = function(timer) {
        this.fileProgressWrapper.FP_TIMER = timer;
    };

    FileProgress.prototype.getTimer = function(timer) {
        return this.fileProgressWrapper.FP_TIMER || null;
    };

    FileProgress.prototype.setProgress = function(percentage, speed) {

        var file = this.file;
        var uploaded = file.loaded;
        
        var size = plupload.formatSize(uploaded).toUpperCase();
        var formatSpeed = plupload.formatSize(speed).toUpperCase();
        var file_size = get_file_size(size);

        percentage = parseInt(percentage, 10);
        if (file.status !== plupload.DONE && percentage === 100) {
            percentage = 99;
        }

    };

    FileProgress.prototype.setComplete = function(up, info, file) {
        var upload_succ = true;
	    var fileTargetID = file.id;
        var res = $.parseJSON(info);
        var url;
        if (res.url) {
    	// when this condition is true, I should know all the info.
    	// This case may not appear a lot, that's why when it appears, the client sends a message to me.
        } else {
            var domain = up.getOption('domain');
            url = domain + encodeURI(res.key);
            var link = domain + res.key;
            console.log('Aaron Else Test: ' + info);

        //add_upload_client
            var file_name = file.name;
            var urlkey    = res.key;
            var file_md5  = res.hash;
            var size      = file.size;
            var page_num  = file.page_num;
		    var teacherid = $("#id_teacher_name").data("teacherid");
            //TODO: MILLIONS 上传成功修改老师头像URL    
            $.ajax({
        	    url: '/human_resource/set_teacher_face',
        	    type: 'POST',
        	    data: {'key': urlkey,'teacherid':teacherid},
			    dataType: 'json',
			    success: function(data) {
				    if (data['ret'] == 0) {
                        alert('上传成功');
                        get_tea_detail(teacherid);
                    } else if(data['ret'] == -1) {
                        alert('上传失败，请重新上传');
                    } else {
					    console.log(data);
				    }
			    }
            });
        }
    };

    function get_file_size(file_size)
    {
	    if (file_size > 1024 && file_size < 1024 * 1024) {
		    size = (file_size / 1024).toFixed(2);
		    return size + ' KB';
	    } else if (file_size > 1024 * 1024) {
		    size = ((file_size / 1024) / 1024).toFixed(2);
		    return size + ' MB';
	    } else {
		    return file_size;
	    }
    }

    $('#id_add_analysis_url').on('click', function(){
        var teacherid = $("#id_teacher_name").data("teacherid");
        dp_get_quiz_analyse(teacherid);
    });

    var dp_get_quiz_analyse = function(teacherid){
        $.getJSON('/tea_manage/get_quiz_analyse', {
            'teacherid': teacherid
        }, function(result){
            if (result['ret'] != 0) {
                BootstrapDialog.alert(result['info']);
            } else {
                dp_set_quiz_analyse(teacherid, result['quiz_analyse']);
            }
        });
    };

    var dp_set_quiz_analyse = function(teacherid, quiz_analyse) {
        var html_node= $('<div></div>').html(dlg_get_html_by_class('dlg_modify_quiz_analyse'));
        html_node.find('.quiz_analyse').attr('src', quiz_analyse);
        html_node.find('.upload_quiz_analyse').attr('id', 'opt-upload-quiz-analyse');
        html_node.find('.upload_quiz_analyse').parent().attr('id', 'opt-upload-quiz-analyse-parent');

        BootstrapDialog.show({
	        title: "更改老师精品试题图片",
	        message : function(dialog){
                return html_node;
            },
	        buttons: [{
		        label: '返回',
		        action: function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label: '确认',
		        cssClass: 'btn-warning',
		        action: function(dialog) {
                    var quiz_analyse = html_node.find('.quiz_analyse').attr('src');
                    $.getJSON('/tea_manage/set_quiz_analyse', {
                        'teacherid': teacherid, 'quiz_analyse': quiz_analyse 
                    }, function(result){
                        BootstrapDialog.alert(result['info']);
                    });
			        dialog.close();
		        }
	        }]
        });
        var th = setTimeout(function(){
            custom_upload('opt-upload-quiz-analyse', 'opt-upload-quiz-analyse-parent',
                          g_UPLOAD_DOMAIN_URL, set_modify_quiz_analyse, teacherid);  
            clearTimeout(th);
        }, 1000);
    };

    var set_modify_quiz_analyse = function(domain, info, teacherid) {
        
        var res = $.parseJSON(info);
        alert(res.key);
        $(".bootstrap-dialog-body .quiz_analyse").attr('src', domain + res.key);
    };

    $('.done_create_meeting').on('click', function(){
		var teacherid = $(this).parent().data("teacherid");
        get_create_meeting(teacherid);
    });

    var get_create_meeting = function(teacherid) {
        $.getJSON('/tea_manage/get_create_meeting', {
            'teacherid': teacherid
        }, function(result){
            if (result['ret'] != 0) {
                BootstrapDialog.alert(result['info']);
            } else {
                var message = '';
                if (result['create_meeting'] == 0) {
                    message = '<div class="text-center"><h3>开启</h3><br>当前老师创建会议权限</div>';
                } else {
                    message = '<div class="text-center"><h3>取消</h3><br>当前老师创建会议权限</div>';
                }

                BootstrapDialog.show({
	                title: "开启会议权限",
	                message : message,
	                buttons: [{
		                label: '返回',
		                action: function(dialog) {
			                dialog.close();
		                }
	                }, {
		                label: '确认',
		                cssClass: 'btn-warning',
		                action: function(dialog) {
                            set_create_meeting(teacherid);       
			                dialog.close();
		                }
	                }]
                });
            }
        });
    };

    var set_create_meeting = function(teacherid) {
        $.getJSON('/tea_manage/set_create_meeting', {
            'teacherid': teacherid
        }, function(result){
            BootstrapDialog.alert(result['info']);
        });
    };

    $.each( $(".opt-show-lessons"), function(i,item ){
        $(item).admin_select_teacher_free_time({
            "teacherid":   $(item).get_opt_data("teacherid")
        });
    });

    $(".opt-tea-level").on("click", function(){
		var teacherid = $(this).parent().data("teacherid");
        var id_level  = $("<select/>");
        Enum_map.append_option_list( "level",id_level);
        var arr = [
            ["设置教师等级", id_level]
        ]; 
        show_key_value_table("设置等级", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var level = id_level.val();
                $.ajax({
                    url: '/human_resource/set_level',
                    type: 'POST',
                    data: {
                        teacherid : teacherid,
                        level     : level
                    },
                    dataType: 'json',
                    success:  window.location.reload()
                });

			    dialog.close();

            }
        });

	});










});
