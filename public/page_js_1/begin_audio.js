$(function(){

    $(".done_t").on("click",function(){
        var teacherid = $(this).parent().data('teacherid');
        var assistantid = $(this).parent().data('assistantid');
        var upload_time = $(this).parent().data('upload_time');
        $.ajax({
        	url: '/authority/delete_begin_audio',
        	type: 'POST',
        	data: {'upload_time': upload_time,'teacherid':teacherid, 'assistantid':assistantid},
			dataType: 'json',
			success: function(data) {
				if (data['ret'] == 0) {
                    window.location.reload();
                } else {
                    alert('删除失败, 请重新尝试');
				}
			}
        });
    });
    
    $(".blue_btn").on("click",function(){
        $(".mesg_alert33").hide();
        window.location.reload();
    });
    
    var uploader = Qiniu.uploader({
		runtime: 'html5, flash, html4',
		browse_button: 'id_upload_button', //choose files
		uptoken_url: '/upload/pub_token',
		domain: g_domain,
		container: 'id_upload_div',
		drop_element: 'id_upload_div',
		max_file_size: '100mb',
		dragdrop: true,
		chunk_size: '4mb',
		unique_names: false,
		save_key: false,
		auto_start: true,
        filters: {
            mime_types : [
                {title:"audio", extensions: "mp3"},
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
                $("#process_info").show();
               
                    
				var progress = new FileProgress(file, 'process_info');
			},
			
			'UploadProgress': function(up,file) {

				console.log('upload progress');
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
                        alert("请上传100M以内音频");
                        break;
                    default:
                        alert("上传错误,请确认音频大小在100M以内以及格式正确");
                }
			},
			'Key': function(up, file) {
				//generate the key
                time = (new Date()).valueOf();
				return $.md5(file.name) +time+".mp3";
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
	    if (!this.fileProgressWrapper.length) {
	 	    $('#process_info').children("p").css('width', 0 + '%');
	    }
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
        $("#process_info").children("p").css('width', percentage + '%');
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
            console.log('Millions Test: ' + link);
            $("#process_info").hide();

        //add_upload_client
            var file_name = file.name;
            var urlkey    = res.key;
            var file_md5  = res.hash;
            var size      = file.size;
            var page_num  = file.page_num;
            var teacherid = $("#id_tea_id").val();
            var assistantid = $("#id_ass_id").val();
            if(teacherid == -1 || assistantid == -1){
                alert("请选择老师以及助教！");
                return;
            }
            // TODO :修改url以及上传时间等
            $.ajax({
        	    url: '/authority/add_begin_audio',
        	    type: 'POST',
        	    data: {'audio_url': urlkey,'teacherid':teacherid, 'assistantid':assistantid},
			    dataType: 'json',
			    success: function(data) {
				    if (data['ret'] == 0) {
                        alert('上传成功');
                    } else {
                        alert('上传失败，请重新上传');
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

});
