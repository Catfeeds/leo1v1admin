$(function(){

    function add_upload_id(){
        var opt_up = $(".done_ff");
        var type = g_cw_type;
        $.each(opt_up, function(i, item){
            var This = $(item);
            var id = 'id_'+type+'_'+This.parent().data('itemid');
            This.attr('id', id);
            var par_id = id+'_par';
            This.parent().attr('id', par_id);
            var uploader = Qiniu.uploader({
		        runtimes: 'html5, flash, html4',
		        browse_button: id , //choose files id
		        //uptoken_url: '/upload/private_upload_token',
		        uptoken_url: '/upload/private_token',
		        domain: g_qiniu_domain,
		        container: par_id,
		        drop_element: par_id,
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
                            var tmp = item.button;
                            console.log('waiting...');
                        });
			        },
			        'BeforeUpload': function(up, file) {
				        console.log('before uplaod the file');
                        
				        if (!check_type(file.type)) {
					        alert('请上传PDF文件');
					        return;
                        }
			        },
			        'UploadProgress': function(up,file) {
				        console.log('upload progress');
			        },
			        'UploadComplete': function() {
				        console.log('success');
			        },
			        'FileUploaded' : function(up, file, info) {
				        console.log('Things below are from FileUploaded');
                        setComplete(up, info, file, id);
			        },
			        'Error': function(up, err, errTip) {
				        console.log('Things below are from Error');
				        console.log(up);
				        console.log(err);
				        console.log(errTip);
			        },
			        'Key': function(up, file) {
				        var key = "";
				        //generate the key
                        var time = (new Date()).valueOf();
				        return $.md5(file.name) +time+'.pdf';
			        }
		        }
	        });
        });
    }
    add_upload_id();

	//时间控件
	$('#datetimepicker15').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
        onChangeDateTime :function(){
	        var stu_nick = $("#id_stu_list").val();
		    var start_time = $("#datetimepicker15").val();
		    var end_time = $("#datetimepicker16").val();
		    var courseware_status = $("#id_cw_status").val();
		    var courseware_type = $("#id_cw_type").val();
		    var teacherid = $("#id_cw_type").data("teacherid");
		    load_data(stu_nick, start_time, end_time, courseware_status, courseware_type, teacherid);
		}
	});
	$('#datetimepicker16').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
        onChangeDateTime :function(){
	        var stu_nick = $("#id_stu_list").val();
		    var start_time = $("#datetimepicker15").val();
		    var end_time = $("#datetimepicker16").val();
		    var courseware_status = $("#id_cw_status").val();
		    var courseware_type = $("#id_cw_type").val();
		    var teacherid = $("#id_cw_type").data("teacherid");
		    load_data(stu_nick, start_time, end_time, courseware_status, courseware_type, teacherid);
		}
	});
	//时间控件-over
	
	$(".done_ss").on('click',function(){
		var file_url = $(this).parent().data("url");
		if(file_url != ""){
			$.ajax({
				type     :"post",
				url      :"/upload/get_download_url/",
				dataType :"json",
				data     :{"file_url":file_url},
				success  : function(result){
					if(result.ret == 0){
						window.open(result.download_url); 
					}
				}
			});
		}
	});

	
	function load_data( $stu_nick, $start_time, $end_time, $courseware_status, $courseware_type, $teacherid){
		var url="/tea_manage/courseware_detail?stu_nick="+$stu_nick+"&start_time="+$start_time+"&end_time="+$end_time+"&courseware_status="+$courseware_status+"&courseware_type="+$courseware_type+"&teacherid="+$teacherid;
		window.location.href=url;
	}

	$(".cw_change").on("change",function(){
		var stu_nick = $("#id_stu_list").val();
		var start_time = $("#datetimepicker15").val();
		var end_time = $("#datetimepicker16").val();
		var courseware_status = $("#id_cw_status").val();
		var courseware_type = $("#id_cw_type").val();
		var teacherid = $("#id_cw_type").data("teacherid");
		load_data(stu_nick, start_time, end_time, courseware_status, courseware_type, teacherid);
	});
	
	$(".will_search").on("click",function(){
		var stu_nick = $("#id_stu_list").val();
		var start_time = $("#datetimepicker15").val();
		var end_time = $("#datetimepicker16").val();
		var courseware_status = $("#id_cw_status").val();
		var courseware_type = $("#id_cw_type").val();
		var teacherid = $("#id_cw_type").data("teacherid");
		load_data(stu_nick, start_time, end_time, courseware_status, courseware_type, teacherid);
	});

    setComplete = function(up, info, file, id) {
        
        var upload_succ = true;
	    var fileTargetID = file.id;
        var res = $.parseJSON(info);
        var url;
        if (res.url) {
    	   
        } else {
            var domain = up.getOption('domain');
            url = domain + encodeURI(res.key);
            var link = domain + res.key;
            console.log('MILLIONS else Test: ' + info);
            //add_upload_client
            var urlkey    = res.key;
            $.ajax({
        	    url: '/tea_manage/upload_cw',
        	    type: 'POST',
        	    data: {'urlkey': urlkey, 'id':id},
			    dataType: 'json',
			    success: function(data) {
				    if (data['ret'] == 0) {
                        alert("上传成功");
				    } else {
                        alert("上传失败");
				    }
			    }
            }); 
        }
    };


    function check_type(file_type)
    {
	    return file_type == 'application/pdf' ? true : false;
    }


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

    function get_time()
    {
	    var myDate = new Date();

	    var year   = myDate.getFullYear();
	    var month  = myDate.getMonth();
	    var day    = myDate.getDate();
	    var hour   = myDate.getHours();
	    var mimute = myDate.getMinutes();

	    return year + '-' + month + '-' + day +
		    ' ' + hour + ':' + mimute;
    }


});
