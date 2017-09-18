/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-get_teacher_basic_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    };

    $('.opt-change').set_input_change_event(load_data);
    $('.direct-chat-contacts').css('backgroundColor','#fff');

    $("[data-val]").each(function() {
        var opt_field = $(this).attr('data-val');
        custom_upload_file(
            opt_field,0,function(up, file, info) {
                var res = $.parseJSON(file);
                if( res.key!='' ){
                    var get_pdf_url=res.key;
                    $.ajax({
                        type     : "post",
                        url      : "/teacher_info/update_teacher_pdf_info",
                        dataType : "json",
                        data : {
                            "opt_field": opt_field,
                            "get_pdf_url": get_pdf_url,
                        },
                        success : function(result){
                            if(result.ret==0){
                                alert("上传成功！");
                                window.location.reload();
                            }else{
                                alert("上传失败！");
                            }
                        }
                    });
                }
            }, [], ["pdf","zip"],function(){}
        );
    });

    $('.opt-show').on('click', function (){
        var pdf_url = $(this).attr('data-pdf');
        $.custom_show_pdf(pdf_url,"/teacher_info/get_pdf_download_url");
    })

    var cur_status = $('#my_status').attr('cur-status');
    if (cur_status == 0) {
        $('[data-status=nofull]').addClass('hide');
    } else {
        $('[data-status=full]').addClass('hide');
    }

    $('.opt-set').on('click', function(){
        var old_status = $(this).attr('data-status');
        $.ajax({
            type     : "post",
            url      : "/teacher_info/edit_teacher_status",
            dataType : "json",
            data     : {'status': old_status},
            success  : function(result){
                if(result.ret==0){
                    $('b[data-status]').toggleClass('hide');
                    $('p[data-status]').toggleClass('hide');
                    $('button[data-status]').toggleClass('hide');
                }else{
                    alert(result.info);
                }
            }
        });
    });

    $('.opt-edit').on('click', function () {
        var title_type = $(this).attr('data-name');
        edit_info(title_type);

    });
    var edit_info = function(title_type){

        var bank_select = '<select name="bank_type" class="form-control">'
            +' <option>中国建设银行</option>'
            +' <option>中国工商银行</option>'
            +' <option>中国农业银行</option>'
            +' <option>交通银行</option>'
            +' <option>招商银行</option>'
            +' <option>中国银行</option> </select>';

        var id_nick          = $("<span style=\"line-height:33px;\"/>");
        var id_gender        = $('<select class="form-control"/>');
        var id_work_year     = $("<input/>");
        var id_address       = $("<input/>");
        var id_bank_account  = $("<input/>");
        var id_bank_address  = $("<input/>");
        var id_bank_city     = $("<input/>");
        var id_bank_phone    = $("<input/>");
        var id_bank_province = $("<input/>");
        var id_bank_type     = $(bank_select);
        var id_bankcard      = $("<input/>");
        var id_birth         = $("<input placeholder=\"格式如19910101\"/>");
        var id_dialect_notes = $("<input/>");
        var id_education     = $('<select class="form-control"/>');
        var id_hobby         = $("<input/>");
        var id_idcard        = $("<input/>");
        var id_major         = $("<input/>");
        var id_school        = $("<input/>");
        var id_speciality    = $("<input/>");

        var tea_name = $('#teacher-name').text();
        id_nick.text(tea_name);
        // id_nick.val(tea_name);
        id_work_year.val(able_edit.work_year);
        id_address.val(able_edit.address);
        id_bank_account.val(able_edit.bank_account);
        id_bank_address.val(able_edit.bank_address);
        id_bank_city.val(able_edit.bank_city);
        id_bank_phone.val(able_edit.bank_phone);
        id_bank_province.val(able_edit.bank_province);
        id_bank_type.val(able_edit.bank_type);
        id_bankcard.val(able_edit.bankcard);
        id_birth.val(able_edit.birth);
        id_dialect_notes.val(able_edit.dialect_notes);
        id_hobby.val(able_edit.hobby);
        id_idcard.val(able_edit.idcard);
        id_major.val(able_edit.major);
        id_school.val(able_edit.school);
        id_speciality.val(able_edit.speciality);

        Enum_map.append_option_list("gender",id_gender,true);
        id_gender.val(able_edit.gender);

        Enum_map.append_option_list("education",id_education,true);
        id_education.val(able_edit.education);

        if (title_type == 'user-info') {
            $modal_title = '可编辑信息';
            var arr= [
                ["merge","个人资料"],
                ["姓名：", id_nick],
                ["性别：", id_gender],
                ["生日：", id_birth],
                ["merge","教学信息"],
                ["教龄：",     id_work_year],
                ["方言备注：", id_dialect_notes],
                ["所在地：",   id_address],
                ["merge",  "教育背景"],
                ["毕业院校：", id_school],
                ["最高学历：", id_education],
                ["专业：",     id_major],
                ["兴趣爱好：", id_hobby],
                ["个人特长：", id_speciality],
            ];
        } else {
            $modal_title = '银行卡信息';
            var arr= [
                ["持卡人：",     id_bank_account],
                ["身份证号：",   id_idcard],
                ["银行卡类型：", id_bank_type],
                ["支行名称：",   id_bank_address],
                ["开户省：",     id_bank_province],
                ["开户市：",     id_bank_city],
                ["卡号：",       id_bankcard],
                ["预留手机号：", id_bank_phone],
            ];

        }
        $.tea_show_key_value_table($modal_title, arr,{
            label    : '确认',
            cssClass : 'btn-info col-xs-2 margin-lr-20',
            action   : function() {
                if (title_type == 'user-info') {
                    $.ajax({
                        type     : "post",
                        url      : "/teacher_info/edit_teacher_info",
                        dataType : "json",
                        data : {
                            'gender'        : id_gender.val(),
                            'birth'         : id_birth.val(),
                            'work_year'     : id_work_year.val(),
                            'dialect_notes' : id_dialect_notes.val(),
                            'address'       : id_address.val(),
                            'school'        : id_school.val(),
                            'education'     : id_education.val(),
                            'major'         : id_major.val(),
                            'hobby'         : id_hobby.val(),
                            'speciality'    : id_speciality.val(),
                        } ,
                        success : function(result){
                            if(result.ret==0){
                                // alert("修改成功！");
                                window.location.reload();
                            }else{
                                alert(result.info);
                            }
                        }
                    });
                } else {
                    $.ajax({
                        type     : "post",
                        url      : "/teacher_info/edit_teacher_bank_info",
                        dataType : "json",
                        data : {
                            'bankcard'      : id_bankcard.val(),
                            'bank_phone'    : id_bank_phone.val(),
                            'bank_account'  : id_bank_account.val(),
                            'idcard'        : id_idcard.val(),
                            'bank_type'     : id_bank_type.val(),
                            'bank_address'  : id_bank_address.val(),
                            'bank_city'     : id_bank_city.val(),
                            'bank_province' : id_bank_province.val(),
                        } ,
                        success : function(result){
                            if(result.ret==0){
                                // alert("修改成功！");
                                window.location.reload();
                            }else{
                                alert(result.info);
                            }
                        }
                    });

                }
            }
        },'',false,600,'padding-right:80px;');

    };

    //处理头像

    $("#face").on('click', function(){
        var options = {
            thumbBox: '.thumbBox',
            spinner: '.spinner',
            imgSrc: '/img/user.jpg'
        }
        var cropper = $('.imageBox').cropbox(options);
        $('#upload-file').on('change', function(){
            var reader = new FileReader();
            reader.onload = function(e) {
                options.imgSrc = e.target.result;
                cropper = $('.imageBox').cropbox(options);
            }
            reader.readAsDataURL(this.files[0]);
            this.files = [];
        });
        var picStr;
        $('#btnCrop').on('click', function(){
            picStr = cropper.getDataURL();
            $('.cropped').html('');
            $('.cropped').append('<img src="'+picStr+'" align="absmiddle" style="width:64px;margin-top:4px;border-radius:64px;box-shadow:0px 0px 12px #7E7E7E;" ><p>64px*64px</p>');
            $('.cropped').append('<img src="'+picStr+'" align="absmiddle" style="width:128px;margin-top:4px;border-radius:128px;box-shadow:0px 0px 12px #7E7E7E;"><p>128px*128px</p>');
            $('.cropped').append('<img src="'+picStr+'" align="absmiddle" style="width:180px;margin-top:4px;border-radius:180px;box-shadow:0px 0px 12px #7E7E7E;"><p>180px*180px</p>');
        })
        $('#btnZoomIn').on('click', function(){
            cropper.zoomIn();
        })
        $('#btnZoomOut').on('click', function(){
            cropper.zoomOut();
        })
        var pic_token;
        $.ajax({
            type    : "post",
            url     : "/teacher_info/get_pub_upload_token",
            success : function(result){
                var ret = JSON.parse(result);
                pic_token = ret.upload_token;
            }
        });

        $('.opt-submit').on('click', function() {
            if (picStr) {
                upload_base64(picStr, pic_token);
            } else {
                alert("请先剪切图片！");
            }
        });

    });

    //头像上传

    function upload_base64(picStr, pic_token){
        picStr   = picStr.substring(22);
        var url  = "http://up-z0.qiniu.com/putb64/"+picSize(picStr);
        var xhr  = new XMLHttpRequest();
        xhr.onreadystatechange = function()
        {
            if ( xhr.readyState == 4 ){
                var keyText = xhr.responseText;
                keyText = JSON.parse(keyText);
                $.ajax({
                    type    : "post",
                    url     : "/teacher_info/edit_teacher_face",
                    dataType: "json",
                    data    : {'face': keyText.key},
                    success : function(result){
                        if( result.ret == 0 ){
                            window.location.reload();
                        }else{
                            alert(result.info);
                        }
                    }
                });
            }
        }

        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/octet-stream");
        xhr.setRequestHeader("Authorization", "UpToken "+pic_token);
        xhr.send(picStr);

    }

    function picSize(str) {
        var fileSize;
        if(str.indexOf('=')>0)
        {
            var indexOf = str.indexOf('=');
            str = str.substring(0,indexOf);
        }
        fileSize = parseInt( str.length-(str.length/8)*2 );
        return fileSize;
    }


        //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    })

});
