$(function () {
    var uploader = Qiniu.uploader({
        runtime: 'html5, flash, html4',
        browse_button: 'upload_file', //choose files
        uptoken_url: '/upload/token',
        domain: 'http://step-upon.qiniudn.com/',
        container: 'file_container',
        drop_element: 'file_container',
        max_file_size: '5mb',
        flash_swf_url: 'http://res.taomee.com/js/qiniu/plupload/Moxie.swf',
        dragdrop: true,
        chunk_size: '5mb',
        filters: {
            mime_types : [
                {title:"PDF files", extensions: "pdf"}
            ]
        },

        unique_names: false,
        save_key: false,

        auto_start: true,

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
                if (!check_type(file.type)) {
                    alert('you should upload a pdf file');
                    return;
                }
                var progress = new FileProgress(file, 'process_info');
                var uploading_file_item = gen_uploading_item(file);
                $('#process_info').after(uploading_file_item);

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
                $('#show_err_msg').hide();
            },

            'Error': function(up, err, errTip) {
                console.log('Things below are from Error');
                console.log(err);
                console.log(err.code);

                switch(err.code) {
                case -600:
                    show_err_tip("请上传5MB以内的文件");
                    break;
                case -601:
                    show_err_tip("请上传PDF文件");
                    break;
                default:
                    show_err_tip(errTip);


                }

            },

            'Key': function(up, file) {
                var key = "";
                //generate the key

                var userid = $('#teacherid').val();
                key = 't_' + userid + '/' + $.md5(file.name) + '.pdf';
                return key;
            }
        }

    })


    $('.my_file .select').live('click', function(){

        $(this).parent().parent().addClass('ready_to_delete');

    })

    $('#confirm_del').on('click', function(){
        var upload_time = $('.ready_to_delete').children('input').val();
        $.ajax({
            url: '/upload/delete_file',
            type: 'POST',
            data: {'upload_time':upload_time},
            dataType: 'json',
            success: function(data) {
                if (data['ret'] == 0) {
                    $('.ready_to_delete').remove();
                } else {
                    $('.ready_to_delete').removeClass('ready_to_delete');
                }
                console.log(data);
            }
        })
    })

    $('#cancel_del, #close_layer').on('click', function(){
        $('.ready_to_delete').removeClass('ready_to_delete');
    })
})

function FileProgress(file, targetID)
{
    this.fileProgressID = file.id;
    this.file = file;
    var fileSize = plupload.formatSize(file.size).toUpperCase();
    this.fileProgressWrapper = $('#' + this.fileProgressID);
    file_size = get_file_size(file.size);

    if (!this.fileProgressWrapper.length) {
        str = '<li id="'+this.fileProgressID+'" class="up_ing">' + '<span class="icon"></span>' +
            '<span class="type"><i class="pdf"></i></span>' + '<span class="name">' +
            '<b class="cont">'+file.name+'</b></span>' + '<span class="size">'+
            file_size +'</span>'+ '<span class="date"></span>' + '</li>';

        $('#'+targetID).after(str);
        $('#process_info').find('.process_in .pro_cover').css('width', 0 + '%');
        $('#uploaded_size').text(0);

        $('#uploading_file_size').text(file_size);
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

    $('#process_info').find('.process_in .pro_cover').css('width', percentage + '%');

    $('#uploaded_size').text(file_size);

};

FileProgress.prototype.setComplete = function(up, info, file) {

    var upload_succ = true;
    var fileTargetID = file.id;
    var res = $.parseJSON(info);
    var url;
    //NOTE: why is there such a url, what a fuck!
    if (res.url) {
        // when this condition is true, I should know all the info.
        // This case may not appear a lot, that's why when it appears, the client sends a message to me.
        url = res.url;
        $.ajax({
            url: '/feedback/add_feedback',
            type: 'POST',
            data: {'contact': '18621696950', 'message': info, 'type': 36},
            dataType: 'json',
            success: function (data){
                console.log(data);
            }
        })

    } else {
        var domain = up.getOption('domain');
        url = domain + encodeURI(res.key);
        var link = domain + res.key;
        str = "<div><strong>Link:</strong><a href=" + url + " target='_blank' > " + link + "</a></div>" +
            "<div class=hash><strong>Hash:</strong>" + res.hash + "</div>";
        console.log('Aaron Else Test: ' + info);

        //add_upload_client
        file_name = file.name;
        urlkey    = res.key;
        file_md5  = res.hash;
        size      = file.size;
        page_num  = file.page_num;

        $.ajax({
            url: '/upload/add_upload_client',
            type: 'POST',
            data: {'file_name': file_name, 'urlkey': urlkey, 'file_type': file.type,
                   'file_md5': file_md5, 'size': size, 'page_num': page_num},
            dataType: 'json',
            success: function(data) {
                if (data['ret'] == 0) {
                    $('#'+fileTargetID).append('<input class="upload_time" type="hidden" value="'+
                                               data['upload_time']+'" />');
                } else if(data['ret'] == 6401) {
                    show_err_tip("文件已存在");
                    $('#'+file.id).remove();
                } else {

                    console.log(data);
                }

            }
        })
    }


    if (upload_succ == true) {

        $('#'+fileTargetID).removeClass('up_ing');
        $('#'+fileTargetID).addClass('up_yes');
        $('#'+fileTargetID).children('.icon').append('<i class="gou"></i>');
        $('#'+fileTargetID).children('.date').text(get_time());
        $('#'+fileTargetID).find('.name .cont').after('<i class="select" style="opacity: 0;"></i>');

    } else {

        $('#'+fileTargetID).removeClass('up_ing');
        $('#'+fileTargetID).addClass('up_no');
        $('#'+fileTargetID).children('.icon').append('<i class="cha"></i>');
        $('#'+fileTargetID).children('.date').text(get_time());
        $('#'+fileTargetID).find('.name .cont').after('<i class="faile">上传失败</i>'+
                                                      '<i class="up_ag" style="opacity: 0;"></i>'+
                                                      '<i class="select" style="opacity: 0;"></i>');
    }


};


function check_type(file_type)
{
    return file_type == 'application/pdf' ? true : false;
}

function gen_uploading_item(file)
{
    // <!--正在上传样式-->
    //                <li class="up_ing">
    //                 <span class="icon">
    //                    </span>
    //                    <span class="type">
    //                     <i class="pdf"></i>
    //                    </span>
    //                    <span class="name">
    //                     <b class="cont">如何利用微博引爆视频播放如何利用微博引爆视频播</b>
    //                    </span>
    //                    <span class="size">26.34MB</span>
    //                    <span class="date"></span>
    //                </li>
    var file_size = get_file_size(file_size);

    str = '<li class="up_ing">' + '<span class="icon"></span>' +
        '<span class="type"><i class="pdf"></i></span>' + '<span class="name">' +
        '<b class="cont">'+file.name+'</b></span>' + '<span class="size">'+
        file_size +'</span>'+ '<span class="date"></span>' + '</li>';
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
function show_err_tip(err_tip)
{
    $('#show_err_msg').show();
    $('#show_err_msg').text(err_tip);
}
