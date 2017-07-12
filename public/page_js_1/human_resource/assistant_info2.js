$(function(){
  	$("#id_add_birth").datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d'
	});

	$("#id_edit_birth").datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d'
	});

    Enum_map.append_option_list( "star_level", $("#id_rate_score"));
    Enum_map.append_option_list( "assistant_type", $("#id_is_part_time"));
    $("#id_rate_score").val(g_args.score);
    $("#id_is_part_time").val(g_args.is_part_time);
    $("#id_ass_name").val(g_args.ass_nick);
    $("#id_ass_phone").val(g_args.phone);

	$(".opt-change").on("change",function(){
		load_data();
	});


    function load_data(){
        var score        = $("#id_rate_score").val();
        var tea_nick     = $("#id_ass_name").val();
        var phone        = $("#id_ass_phone").val();
        var is_part_time = $("#id_is_part_time").val();

        reload_self_page({
            is_part_time : is_part_time,
            ass_nick     : tea_nick,
            phone        : phone,
            score        : score
        });
    }

	$(".hr_change").on("change", function(){
		var is_part_time =  $("#id_is_part_time").val();
		var score = $("#id_rate_score").val();
		load_data();
	});

	$("#id_add_ass").on("click",function(){
		var ass_nick       = $("#id_add_ass_name").val();
		var gender         = $("#id_add_gender").val();
		var birth          = $("#id_add_birth").val();
		var work_year      = $("#id_add_work_year").val();
		var phone          = $("#id_add_phone").val();
		var email          = $("#id_add_email").val();
		var assistant_type = $("#id_add_assistant_type").val();
		var school         = $("#id_add_school").val();
		
		$.ajax({
			type     :"post",
			url      :"/ass_manage/add_assistant",
			dataType :"json",
			data     :{"ass_nick":ass_nick,"gender":gender,"birth":birth,"work_year":work_year,"phone":phone,"email":email,"assistant_type":assistant_type,'school':school},
			success  : function(result){
                if(result['ret'] != 0){
				    alert(result['info']);
                }else{
                    window.location.reload();
                }
			}
		});
		$(".mesg_alert23").hide();
	});

	$(".done_t").on("click", function(){
		var assistantid = $(this).parent().data("assistantid");
        BootstrapDialog.show({
            title: '系统提示',
            message : '确认从助教档案中删除该助教及其相关信息',
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
			                data     :{'teacherid':assistantid,'teacher_type':1},
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
	
	$("#id_delete_ass").on("click", function(){
		var assistantid= $(this).data("assistantid");
		$.ajax({
			type     :"post",
			url      :"/human_resource/delete_teacher",
			dataType :"json",
			data     :{'teacherid':assistantid,'teacher_type':1},
			success  : function(result){
                if(result['ret'] != 0){
                    alert(result['info']);
                }else{
                    window.location.reload();
                }
			}
		});
	});

    function get_ass_detail(assistantid){
 		$.ajax({
			type     :"post",
			url      :"/human_resource/ass_detail_info",
			dataType :"json",
			data     :{'assistantid':assistantid},
			success  : function(result){
				var info = result.info;
				$("#id_detail_name").html(info.ass_nick);
				$("#id_ass_gender").html(info.gender);
				$("#id_ass_gender").data('gender_num', info.gender_num);
				$("#id_ass_birth").html(info.birth);
				$("#id_ass_work_year").html(info.work_year);
				$("#id_detail_phone").html(info.phone);
				$("#id_ass_email").html(info.email);
				$("#id_ass_school").html(info.school);
				$("#id_ass_type").html(info.is_part_time);
				$("#id_ass_score").html(info.rate_score);
				$("#id_ass_style").html(info.ass_style);
				$("#id_ass_prize").html(info.prize);
				$("#id_ass_achievement").html(info.achievement);
				$("#id_ass_base_intro").html(info.base_intro);
				$(".header_img").html(info.face);
                
			}
		});
		$('.teach_mesg').show().siblings('.teacher_list').hide();
    }
    
	$(".done_o").on("click", function(){
		var assistantid = $(this).parent().data("assistantid");
		$("#id_save_info").data("assistantid",assistantid);
        get_ass_detail(assistantid);
	});

	$("#id_back_to_main").on("click",function(){
		$('.teacher_list').show().siblings('.teach_mesg').hide();
	});

	$("#id_save_info").on("click",function(){
		var assistantid = $("#id_save_info").data("assistantid");
		var ass_nick = $("#id_edit_name").val();
		var work_year = $("#id_edit_work_year").val();
		var birth = $("#id_edit_birth").val();
		var phone = $("#id_edit_phone").val();
		var email = $("#id_edit_email").val();
		var school = $("#id_edit_school").val();
		var assistant_type = $("#tea_job").val();
		var ass_style = $("#id_edit_style").val();
		var achievement = $("#id_edit_achievement").val();
		var base_intro = $("#id_edit_base_intro").val();
		var prize = $("#id_edit_prize").val();
		var gender = $("#tea_sexy").val();
		$.ajax({
			type     :"post",
			url      :"/human_resource/update_assistant_info",
			dataType :"json",
			data     :{'assistantid':assistantid,'ass_nick':ass_nick,'gender':gender,'work_year':work_year,'birth':birth,'phone':phone,'email':email,'school':school,'assistant_type':assistant_type,'ass_style':ass_style,'achievement':achievement,'base_intro':base_intro,'prize':prize},
			success  : function(result){
                if(result['ret'] != 0){
                    alert(result['info']);
                }else{
                    window.location.reload();
                }
			}
		});
		if(gender == 1){
			$("#id_ass_gender").html('男');
		}else{
			$("#id_ass_gender").html('女');
		}

		if(assistant_type == 1){
			$("#id_ass_type").html('兼职');
		}else{
			$("#id_ass_type").html('全职');
		}
	});

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
		    var assistantid = $("#id_save_info").data("assistantid");
            //TODO: MILLIONS 上传成功修改老师头像URL    
            $.ajax({
        	    url: '/human_resource/set_assistant_face',
        	    type: 'POST',
        	    data: {'key': urlkey,'assistantid':assistantid},
			    dataType: 'json',
			    success: function(data) {
				    if (data['ret'] == 0) {
                        alert('上传成功');
                        get_ass_detail(assistantid);
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

    function cphone(str) {
        var re = /^1\d{10}$/;
        if (re.test(str)) {
        } else {
            alert("请正确填写手机号码");
            return;
        }
    }
    function ce_name(str){
        var re = /^([A-Za-z]+\s?)*[A-Za-z]$/;
        if(re.test(str)){
        }else{
            alert("请正确填写英文名");
            return;
        }
    }

    function cemail(str){
        var re = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/;
        if(re.test(str)){
        }else{
            alert("请正确填写邮箱");
            return;
        }
    }
    //新增助教
    $(".done_s").on("click", function(){
        var id_name   = $("<input> </input>") ;
        var id_e_name = $("<input> </input>") ;
        var id_birth  = $("<input> </input>") ;
        var id_years  = $("<input> </input>") ;
        var id_school = $("<input> </input>") ;
        var id_phone  = $("<input> </input>") ;
        var id_email  = $("<input> </input>") ;
        var id_sex    = obj_copy_node("#id_sex") ;
        var id_job    = obj_copy_node("#id_job") ;
        var arr = [
            [ "姓名",  id_name ] ,
            [ "英文名",  id_e_name ] ,
            [ "性别",  id_sex ] ,
            [ "生日",  id_birth ] ,
            [ "工龄",  id_years] ,
            [ "学校",  id_school ] ,
            [ "手机",  id_phone ] ,
            [ "邮箱",  id_email ] ,
            [ "兼/职",  id_job ] ,
        ];
        id_birth.datetimepicker({
		    lang:'ch',
		    timepicker:false,
		    format:'Y-m-d'
	    });
        show_key_value_table("新增助教", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var ass_nick       = $.trim(id_name.val());
                var e_name         = $.trim(id_e_name.val());
		        var gender         = id_sex.val();
		        var birth          = id_birth.val(); 
		        var work_year      = id_years.val();
		        var phone          = id_phone.val();
		        var email          = id_email.val(); 
		        var assistant_type = id_job.val();
		        var school         = id_school.val();

                ce_name(e_name);
                cphone(phone);
                cemail(email);

                
                if(ass_nick == "" ||e_name == "" ||  typeof gender == "undefined" || typeof assistant_type == "undefined"
                   ||  birth == "" ||  phone == "" || email == "" || !/^([A-Za-z]+\s?)*[A-Za-z]$/.test(e_name) || !/^1\d{10}$/.test(phone)
                   || !/^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/.test(email) )
                  
                {
                    console.log(ass_nick);
                    console.log(e_name);
                    console.log(gender);
                    console.log(assistant_type);
                    console.log(birth);
                    console.log(phone);
                    console.log(email);
                    alert("请以正确格式输入全部信息");
                    return;
                }
                
                $.ajax({
                    url: '/ass_manage/add_assistant',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        "ass_nick"       : ass_nick,
                        "e_name"         : e_name,
                        "gender"         : gender,
                        "birth"          : birth,
                        "work_year"      : work_year,
                        "phone"          : phone,
                        "email"          : email,
                        "assistant_type" : assistant_type,
                        "school"         : school
			        },
                    success: function(data){
                        if(data.ret != -1){
                            window.location.reload();
                        }
                    }
                });
                dialog.close();
            }
        });
    });
    $("#id_modify").on("click", function(){
        var html_node = $("<div></div>").html(dlg_get_html_by_class('dlg_modify_assistant'));
        html_node.find("#id_edit_birth").datetimepicker({
		    lang:'ch',
		    timepicker:false,
		    format:'Y-m-d'
        });
        html_node.find("#id_edit_name").val($("#id_detail_name").text());
        html_node.find("#id_edit_work_year").val($("#id_ass_work_year").text());
        html_node.find("#id_edit_birth").val($("#id_ass_birth").text());
        html_node.find("#id_edit_email").val($("#id_ass_email").text());
        html_node.find("#id_edit_school").val($("#id_ass_school").text());
        html_node.find("#tea_job").children().filter(function(){
            return $(this).text().localeCompare($("#id_ass_type").text());
        }).attr("selected", true);
        html_node.find("#id_edit_style").val($("#id_ass_style").text());
        html_node.find("#id_edit_achievement").val($("#id_ass_achievement").text());
        html_node.find("#id_edit_base_intro").val($("#id_ass_base_intro").text());
        html_node.find("#id_edit_prize").val($("#id_ass_prize").text());
        html_node.find("#tea_sexy").children().filter(function(){
            return $(this).text().localeCompare( $("#id_ass_gender").text());
        }).attr("selected", true);

        
        BootstrapDialog.show({
            title: '更改助教信息',
            message : html_node,
            closable: true, 
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-danger',
                    action: function(dialog) {
		                var assistantid    = $("#id_save_info").data("assistantid");
		                var ass_nick       = html_node.find("#id_edit_name").val();
		                var work_year      = html_node.find("#id_edit_work_year").val();
		                var birth          = html_node.find("#id_edit_birth").val();
		                var email          = html_node.find("#id_edit_email").val();
		                var school         = html_node.find("#id_edit_school").val();
		                var assistant_type = html_node.find("#tea_job").val();
		                var ass_style      = html_node.find("#id_edit_style").val();
		                var achievement    = html_node.find("#id_edit_achievement").val();
		                var base_intro     = html_node.find("#id_edit_base_intro").val();
		                var prize          = html_node.find("#id_edit_prize").val();
		                var gender         = html_node.find("#tea_sexy").val();
		                $.ajax({
			                type     :"post",
			                url      :"/human_resource/update_assistant_info",
			                dataType :"json",
			                data     :{'assistantid':assistantid,'ass_nick':ass_nick,'gender':gender,'work_year':work_year,'birth':birth,'email':email,'school':school,'assistant_type':assistant_type,'ass_style':ass_style,'achievement':achievement,'base_intro':base_intro,'prize':prize},
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

    //修改
    $(".opt-update-news").on("click",function(){
        var assistantid = $(this).get_opt_data("assistantid");
        do_ajax ( "/human_resource/get_assistant_info2", {
            "assistantid" : assistantid
        },function(result){
            var data=result.ret_info;
            //console.log(data);
            var id_assistantid = $("<input display:none > </input>") ;
            var id_name        = $("<input> </input>") ;
            var id_e_name      = $("<input> </input>") ;
            var id_birth       = $("<input> </input>") ;
            var id_years       = $("<input> </input>") ;
            var id_school      = $("<input> </input>") ;
            var id_phone       = $("<input> </input>") ;
            var id_email       = $("<input> </input>") ;
            var id_sex         = obj_copy_node("#id_sex") ;
            var id_job         = obj_copy_node("#id_job") ;
            var arr            = [
                [ "姓名",  id_name ] ,
                [ "英文名",  id_e_name ] ,
                [ "性别",  id_sex ] ,
                [ "生日",  id_birth ] ,
                [ "工龄",  id_years] ,
                [ "学校",  id_school ] ,
                [ "手机",  id_phone ] ,
                [ "邮箱",  id_email ] ,
                [ "兼/职",  id_job ] ,
            ];
            id_birth.datetimepicker({
		        lang:'ch',
		        timepicker:false,
		        format:'Y-m-d'
	        });

            id_assistantid.val(data.assistantid);
            id_name.val(data.nick);
            id_e_name.val(data.e_name);
            id_birth.val(data.birth);
            id_years.val(data.work_year);   
            id_school.val(data.school); 
            id_phone.val(data.phone); 
            id_email.val(data.email); 
            id_sex.val(data.gender); 
            id_job.val(data.assistant_type);  
        
            show_key_value_table("更改助教信息", arr ,{
                label: '确认',
                cssClass: 'btn-warning',
                action : function(dialog) {
                    var assistantid=id_assistantid.val(); 
                    var name   = id_name.val();
                    var e_name = id_e_name.val();
                    var birth  = id_birth.val();
                    var years  = id_years.val();   
                    var school = id_school.val(); 
                    var phone  = id_phone.val(); 
                    var email  = id_email.val(); 
                    var sex    = id_sex.val(); 
                    var job    = id_job.val();  


                    $.ajax({
                        url: '/human_resource/update_assistant_info2',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            'assistantid' : assistantid,
                            'name'        : name,
                            'e_name'      : e_name,
                            'birth'       : birth,
                            'years'       : years,
                            'school'      : school,
                            'phone'       : phone,
                            'email'       : email,
                            'sex'         : sex,
                            'job'         : job
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
   
    
    $(".opt-update-passwd").on("click",function(){
	    //
        var assistantid = $(this).get_opt_data("assistantid");

        show_input ("修改密码" , 1428457, function(val){
            do_ajax("/user_deal/user_set_passwd",{
                "userid": assistantid,
                "passwd":val 
            });
        });
    });

	
});
 
