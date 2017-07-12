/**
 * leo 上传
 * 需要包含jquery, jquery.md5, qiniu upload相关文件
 */
$(function(){
    // 可以根据需求进行更改
    var uptoken_url = '/upload/pub_token';
    var leo_upload = function(btn_id, containerid, domain, compelete_func, beforeupload_func){
        var uploader = Qiniu.uploader({
		    runtimes: 'html5, flash, html4',
		    browse_button: btn_id , //choose files id
		    uptoken_url: uptoken_url,
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
				    if (!beforeupload_func(file.type)) {
					    BootstrapDialog.alert('请上传PDF文件');
					    return;
                    }

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
                    compelete_func(domain, info);
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

    function FileProgress(file, targetID)
    {
	    this.fileProgressID = file.id;
	    this.file = file;
	    var fileSize = plupload.formatSize(file.size).toUpperCase();
	    this.fileProgressWrapper = $('#' + this.fileProgressID); 

	    if (!this.fileProgressWrapper.length) {
	 	    $('#process_info').find('.process_in .pro_cover').css('width', 0 + '%');

	    }

	    this.setTimer(null);
    }

    FileProgress.prototype.setTimer = function(timer) {
        this.fileProgressWrapper.FP_TIMER = timer;
    };

    FileProgress.prototype.getTimer = function(timer) {
        return this.fileProgressWrapper.FP_TIMER || null;
    };

    FileProgress.prototype.setProgress = function(percentage, speed, upload_btn) {

        var file = this.file;
        var uploaded = file.loaded;

        var size = plupload.formatSize(uploaded).toUpperCase();
        var formatSpeed = plupload.formatSize(speed).toUpperCase();

        percentage = parseInt(percentage, 10);
        if (file.status !== plupload.DONE && percentage === 100) {
            percentage = 99;
        }

        $('#'+upload_btn).parents('.row').siblings().find('.upload_process_info').css('width', percentage + '%');

    };
});
