$(function(){
    $('.fancybox-effects-a').fancybox({
		helpers: {
			title : {
				type : 'outside'
			},
			overlay : {
				speedOut : 0
			}
		}
	});	

    $(".opt-gift-delete").on("click", function(){
        var gift_name = $(this).parents("td").siblings(".gift_name").text();
        var giftid = $(this).parent().data("giftid");
        BootstrapDialog.show({
	        title: "删除礼品",
	        message : "确认是否删除礼品" + gift_name,
	        buttons: [{
		        label: '返回',
		        action: function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label: '确认',
		        cssClass: 'btn-warning',
		        action: function(dialog) {
                    
                    $.ajax({
			            type     :"post",
			            url      :"/authority/del_gift",
			            dataType :"json",
			            data     :{"giftid": giftid},
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
	        }]
        });
    });

    $(".opt-gift-modify").on("click", function(){
        var html_node = $("<div></div>").html(dlg_get_html_by_class('dlg_modify_gift'));

        var giftid = $(this).parent().data('giftid');
        var pre_gift_name = $(this).parents("td").siblings(".gift_type").text();

        html_node.find(".gift_type").children().filter( function(index) {
            var ret = $(this).text().localeCompare( pre_gift_name );
            return ret == 0;
        }).attr("selected", true);
        html_node.find(".gift_name").val($(this).parents("td").siblings(".gift_name").text());
        html_node.find(".gift_intro").val($(this).parents("td").siblings(".gift_intro").text());
        html_node.find(".gift_praise").val($(this).parents("td").siblings(".gift_praise").text());

        html_node.find(".gift_url").val($(this).parents("td").siblings(".gift_url").children("a").attr("href"));
        html_node.find(".preview_gift_pic").attr("href", $(this).parents("td").siblings(".gift_url").children("a").attr("href"));

        html_node.find(".upload_gift_pic").attr('id', 'opt-upload-gift-pic');
        html_node.find(".upload_gift_pic").parent().attr('id', 'opt-upload-gift-pic-parent');
        
        BootstrapDialog.show({
	        title: "修改商品信息",
	        message : function(dialog) {

                html_node.find(".preview_gift_pic").fancybox({
		            helpers: {
			            title : {
				            type : 'outside'
			            },
			            overlay : {
				            speedOut : 0
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
	        }, {
		        label: '确认',
		        cssClass: 'btn-warning',
		        action: function(dialog) {
                    var gift_type      = html_node.find(".gift_type").val();
                    var gift_name      = html_node.find(".gift_name").val();
                    var gift_intro     = html_node.find(".gift_intro").val();
                    var current_praise = html_node.find(".gift_praise").val();
                    var gift_pic       = html_node.find(".gift_url").val();

	                $.ajax({
			            type     :"post",
			            url      :"/authority/modify_gift_info",
			            dataType :"json",
			            data     :{"giftid": giftid, "gift_type":gift_type,"gift_name":gift_name,
                                   "gift_intro":gift_intro,"praise":current_praise,"gift_pic":gift_pic},
			            success  : function(result){
				            if(result.ret != 0){
                                BootstrapDialog.alert(result['info']);
				            }else{
                                window.location.reload();
				            }
			            }
		            });
			        dialog.close();
		        }
	        }]
        });

        var th = setTimeout(function(){
            custom_upload('opt-upload-gift-pic', 'opt-upload-gift-pic-parent', g_UPLOAD_DOMAIN_URL, set_modify_gift_pic);  
            clearTimeout(th);
        }, 1000);

    });

    $("#opt-add-gift").on("click", function(){
        
        var html_node = $("<div></div>").html(dlg_get_html_by_class('dlg_add_gift'));

        BootstrapDialog.show({
	        title: "",
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
                    
                    var gift_type      = html_node.find(".gift_type").val();
                    var gift_name      = html_node.find(".gift_name").val();
                    var gift_intro     = html_node.find(".gift_intro").val();
                    var current_praise = html_node.find(".gift_praise").val();
                    var gift_pic       = html_node.find(".gift_url").val();

	                $.ajax({
			            type     :"post",
			            url      :"/authority/add_gift",
			            dataType :"json",
			            data     :{"gift_type":gift_type,"gift_name":gift_name,"gift_intro":gift_intro,"praise":current_praise,"gift_pic":gift_pic},
			            success  : function(result){
				            if(result.ret != 0){
                                BootstrapDialog.alert(result['info']);
				            }else{
                                window.location.reload();
				            }
			            }
		            });
                    
			        dialog.close();
		        }
	        }]
        });
    });

    $(".opt-gift-desc").on("click", function(){

        var html_node=$("<div></div>").html(dlg_get_html_by_class('dlg_modify_gift_desc'));
        $(this).parents("td").children(".gift_desc").children().each(function(index){
            html_node.find(".show_pic").append('<div style="float:left;"><a title="点击选中图片" class="change_image" href="javascript:;">'+
                                               '<p style="width:100px;height:100px;overflow:hidden;"><img src='+
                                               $(this).val() +' style="width:100%;"></p></a></div>');
        });

        html_node.find(".add_pic").attr('id', 'opt-add-pic');
        html_node.find(".add_pic").parent().attr('id', 'opt-add-pic-parent');
        $(this).parents("td").children(".gift_desc").addClass("current_modify_gift");
        var giftid = $(this).parent().data('giftid');

        BootstrapDialog.show({
	        title: "修改描述图片",
	        message : function(dialog){
                html_node.find('.show_pic').on('click', '.change_image', function(){
                    if ($(this).children().children().css('opacity') == 0.5) {
                        $(this).children().children().css('opacity', '1');
                        $(this).parent().removeClass('ready_to_remove');
                    } else {
                        $(this).children().children().css('opacity', '0.5');
                        $(this).parent().addClass('ready_to_remove');
                    }
                });

                html_node.find('.opt_del_pic').on("click", function(){
                    $(this).parents('.row').siblings('.show_pic').children('.ready_to_remove').remove();
                });

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
                    var gift_desc_new = '';
                    $(".current_modify_gift").html('');
                    html_node.find(".show_pic").children().each(function(){
                        gift_desc_new += $(this).children('a').children('p').children('img').attr('src') + ',';
                        $(".current_modify_gift").append('<input type="hidden" value="'+
                                               $(this).children('a').children('p').children('img').attr('src') +'">');
                    });


                    gift_desc_new = remove_last_comma(gift_desc_new);
                    // alert(gift_desc_new );
                    // alert(giftid);
                    // return ;
                    $.ajax({
                        url: '/authority/modify_gift_desc',
                        type: 'POST',
                        data: {'giftid': giftid, 'gift_desc': gift_desc_new},
                        dataType: 'json',
                        success: function(data) {
                            BootstrapDialog.alert(data['info']);
                        }
                    });
			        dialog.close();
		        }
	        }]
        });

        var ta = setTimeout(function(){
            custom_upload('opt-add-pic', 'opt-add-pic-parent', g_UPLOAD_DOMAIN_URL, set_modify_gift_desc);
            clearTimeout(ta);
        }, 1000);

    });


    var custom_upload = function(btn_id, containerid, domain, compelete_func){
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
				    if (!check_type(file.type)) {
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

    function check_type(file_type)
    {
	    // return file_type == 'image/png' ? true : false;
        console.log("check_type" . file_type);
        return true;
    }

    function set_modify_gift_pic(domain, info)
    {
        var res = $.parseJSON(info);
        $(".bootstrap-dialog-body .gift_url").val(domain + res.key);
        $(".bootstrap-dialog-body .preview_gift_pic").attr("href", domain + res.key);
    }

    function set_modify_gift_desc(domain, info)
    {
        var res = $.parseJSON(info);
        $(".bootstrap-dialog-body .show_pic").append('<div style="float:left;"><a title="点击选中图片" class="change_image" href="javascript:;">'+
                                                     '<p style="width:100px;height:100px;overflow:hidden;"><img src='+
                                                     domain + res.key +' style="width:100%;"></p></a></div>');
    }

});
