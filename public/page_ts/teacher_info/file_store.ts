/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-file_store.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            dir: g_args.dir,
        });

    }

    $(".opt-download ").each(function(){
        var opt_data=$(this).get_opt_data();
        var $file_name=$(this).closest("tr").find(".file_name");
        if  (opt_data.is_dir  ) {
            $file_name.html("<a href=\"/teacher_info/file_store?dir="+ opt_data.abs_path +"\" > "+ opt_data.file_name+" </a> ");
            $(this).hide();
            $(this).parent().find(".opt-edit").hide();
            $(this).parent().find(".opt-del").hide();
            if (opt_data.no_share_flag ) {
                $(this).parent().find(".opt-share").hide();
            }
        }else{

        }
    });



    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var $file_name= $("<input/>");
        $file_name.val( opt_data.file_name);
            //opt_data.file_name;
        var arr=[
            ["文件名", $file_name  ]
        ];
        $.show_key_value_table("修改", arr, {
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/teacher_info/file_store_rename", {
                    "old_path" : opt_data.abs_path,
                    "new_name" : $file_name.val()
                } );
            }
        } );
    });

    function do_share(path) {
        $.do_ajax("/teacher_info/get_share_link",{
            "share_path" : path,
        },function(resp){
            $.wopen("/teacher_info/file_share?sign=" +encodeURIComponent(resp.sign)   );
        });

    }

    $(".opt-share").on("click",function(){
        var opt_data=$(this).get_opt_data();
        do_share( opt_data.abs_path );
    });


    $("#id_share_cur").on("click",function(){
        do_share( g_args.dir);
    });


    $(".opt-download").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.do_ajax("/teacher_info/get_download_url",{
            "file_path" : opt_data.abs_path
        },function(resp){
            $.wopen(resp.url);

        });
    });


    $(".opt-download").on("click",function(){
        var opt_data=$(this).get_opt_data();
    });

    $("#id_add_dir").on("click",function(){
        var $dir_name=$("<input/>");
        var $arr=[
            ["名称", $dir_name ]
        ];
        $.show_key_value_table("新建文件夹",$arr, {
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/teacher_info/file_store_add_dir",{
                    "dir" :  g_args.dir,
                    "dir_name" : $dir_name.val()
                } );

            }
        });
    });

    var custom_upload = function(btn_id, containerid, domain, compelete_func){

        $.do_ajax("/teacher_info/get_upload_token",{
            "dir" : g_args.dir
        },function(resp){
            var upload_token=resp.upload_token;
            var pre_dir= resp.pre_dir;
            var uploader = Qiniu.uploader({

                runtimes: 'html5, flash, html4',
                browse_button: btn_id , //choose files id
                //uptoken_url: uptoken_url,
                uptoken:  upload_token ,
                domain: "http://file-store.leo1v1.com",
                container: containerid,
                drop_element: containerid,
                max_file_size: '100mb',
                dragdrop: true,
                flash_swf_url: '/js/qiniu/plupload/Moxie.swf',
                chunk_size: '4mb',
                unique_names: false,
                save_key: false,
                auto_start: true,
                init: {
                    'FilesAdded': function(up, files) {

                        BootstrapDialog.show({
                            title: '上传进度',
                            message: $('<div class="progress progress-sm active">' +
                                       '<div id="id_upload_process_info" class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">'+
                                       '<span class="sr-only">0% Complete</span>  </div> </div>'),
                        });

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
                        /*
                          console.log("Key start");
                          console.log(file);
                          var suffix = file.type.split('/').pop();
                          console.log(suffix);
                          console.log("Key end");
                        */
                        //var key = "";
                        //generate the key
                        //       var time = (new Date()).valueOf();
                        var key= pre_dir+file.name;
                        return key;
                    }
                }
            });


        } );

    };

    function FileProgress(file, targetID)
    {
      this.fileProgressID = file.id;
      this.file = file;
      var fileSize = plupload.formatSize(file.size).toUpperCase();
      this.fileProgressWrapper = $('#' + this.fileProgressID);

      if (!this.fileProgressWrapper.length) {
         this.fileProgressWrapper.find('.process_in .pro_cover').css('width', 0 + '%');

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

        $('#id_upload_process_info').css('width', percentage + '%');

    };


    custom_upload('id_add_file', 'id_add_dir_parent',"" ,function(){
        BootstrapDialog.alert("上传成功！");
        setTimeout(function(){
            load_data();
        },1000);
    });


    $(".opt-del").on("click",function(){
        var opt_data=$(this).get_opt_data();
        BootstrapDialog.confirm("要删除"+opt_data.file_name , function(val){
            if (val) {
                $.do_ajax("/teacher_info/file_store_del_file",{
                    "path" :  opt_data.abs_path ,
                } );
            }
        });
    });



});
