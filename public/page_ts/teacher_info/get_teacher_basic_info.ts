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
                                BootstrapDialog.alert("上传成功！");
                                setTimeout(function(){
                                    window.location.reload();
                                },2000);

                            }else{
                                BootstrapDialog.alert("上传失败！");
                            }
                        }
                    });
                }
            }, [], ["pdf","doc","doxc"],function(){}
        );
    });

    $('.opt-show').on('click', function (){
        var file_url = $(this).attr('data-pdf');
        // $.custom_show_pdf(pdf_url,"/teacher_info/get_pdf_download_url");

        $.ajax({
            //url      : "/tea_manage/get_pdf_download_url",
            //url      : "/common_new/get_qiniu_download",
            url      : "/teacher_info/get_pdf_download_url",
            type     : 'GET',
            dataType : 'json',
            data     : {'file_url': file_url},
            success : function(ret) {
                if (ret.ret != 0) {
                    BootstrapDialog.alert(ret.info);
                } else {
                    window.open(ret.file_ex, '_blank');
                }
            }
        });
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
                    BootstrapDialog.alert(result.info);
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
        var id_work_year     = $("<input type='number' min='1'/>");
        var id_address       = $("<input/>");
        var id_bank_account  = $("<input/>");
        var id_bank_address  = $("<input/>");
        var id_bank_city     = $("<input/>");
        var id_bank_phone    = $("<input type='phone' />");
        var id_bank_province = $("<input/>");
        var id_bank_type     = $(bank_select);
        var id_bankcard      = $("<input type='number' />");
        // var id_birth         = $("<input placeholder=\"格式如19910101\"/>");
        var id_birth         =$("<input/> ");
        var id_dialect_notes = $("<input/>");
        var id_education     = $('<select class="form-control"/>');
        //var id_hobby         = $("<input/>");
        //var id_major         = $("<input/>");
        //var id_speciality    = $("<input/>");
        var id_qq_info       = $("<input/>");
        var id_wx_name       = $("<input/>");
        var id_is_prove      = $('<select class="form-control"/>');
        var id_idcard        = $("<input/>");
        var id_school        = $("<input/>");
        var id_teacher_textbook        = $("<input >");
        var id_teaching_achievement   = $("<textarea  placeholder='您可以输入：1、您获得的奖励荣誉；2、您的教学经验；3、您的教育特色与教学理念；4、您的优秀学生案例' />");

        var tea_name = $('#teacher-name').text();

        //时间插件
        id_birth.datetimepicker({
            lang       : 'ch',
            datepicker : true,
            timepicker : false,
            format     : 'Y-m-d',
        });


        id_nick.text(tea_name);
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
        id_qq_info.val(able_edit.qq_info);
        id_wx_name.val(able_edit.wx_name);
        id_is_prove.val(able_edit.is_prove);
        id_teaching_achievement.val(able_edit.teaching_achievement);
        id_idcard.val(able_edit.idcard);
        
        id_school.val(able_edit.school);
        //id_speciality.val(able_edit.speciality);
        //id_major.val(able_edit.major);
        //id_hobby.val(able_edit.hobby);

        id_teacher_textbook.val(able_edit.teacher_textbook_str);
        Enum_map.append_option_list("gender",id_gender,true);
        id_gender.val(able_edit.gender);

        Enum_map.append_option_list("education",id_education,true);
        Enum_map.append_option_list("boolean",id_is_prove,true);
        id_education.val(able_edit.education);
        id_teaching_achievement.val(able_edit.teaching_achievement);
        var required = '<span style="color:ff3451;">* </span>';
        if (title_type == 'user-info') {
            var modal_title = '可编辑信息';
            var arr= [
                ["merge","个人资料"],
                ["姓名：", id_nick],
                [required+"性别：", id_gender],
                [required+"生日：", id_birth],
                ["merge","教学信息"],
                [required+"教龄：",     id_work_year],
                [required+"教材版本:", id_teacher_textbook],
                ["方言备注：", id_dialect_notes],
                [required+"所在地：",   id_address],
                ["merge",  "教育背景"],
                [required+"毕业院校：", id_school],
                [required+"最高学历：", id_education],
                //["专业：",     id_major],
                //["兴趣爱好：", id_hobby],
                //["个人特长：", id_speciality],
                ["QQ", id_qq_info],
                ["微信",id_wx_name],
                [required+"有无教师资格证",id_is_prove],
                ["merge",  "教学成果"],
                ["merge", id_teaching_achievement],
            ];
        } else {
            var modal_title = '银行卡信息';
            var arr= [
                [required+"持卡人：",     id_bank_account],
                [required+"身份证号：",   id_idcard],
                [required+"银行卡类型：", id_bank_type],
                [required+"支行名称：",   id_bank_address],
                [required+"开户省：",     id_bank_province],
                [required+"开户市：",     id_bank_city],
                [required+"卡号：",       id_bankcard],
                [required+"预留手机号：", id_bank_phone],
            ];
        }

        id_teacher_textbook.on("click",function(){
            var textbook = able_edit.teacher_textbook;
            console.log(textbook+"111");
            $.do_ajax("/user_deal/get_teacher_textbook",{
                "textbook" : textbook
            },function(response){
                var data_list   = [];
                var select_list = [];
                $.each( response.data,function(){
                    data_list.push([this["num"], this["textbook"]  ]);
                    if (this["has_textbook"]) {
                        select_list.push (this["num"]) ;
                    }
                });
                console.log(data_list);
                var screen_height=window.screen.availHeight-300;        
                $(this).admin_select_dlg({
                    header_list     : [ "id","教材版本" ],
                    data_list       : data_list,
                    multi_selection : true,
                    select_list     : select_list,
                    div_style       : {"height":screen_height,"overflow":"auto"},
                    onChange        : function( select_list,dlg) {
                        $.do_ajax("/user_deal/get_teacher_textbook_str", {
                            "teacher_textbook"               : JSON.stringify(select_list),
                        },function(respdata){
                            console.log(respdata);
                            id_teacher_textbook.val(respdata.textbook);
                            //id_teacher_textbook.data("textbook_str",respdata.textbook_value);
                            able_edit.teacher_textbook = respdata.textbook_value;
                            dlg.close();
                        });
                    }
                },function(){
                    console.log(header_list);
                });
            });
        });
        $.tea_show_key_value_table(modal_title, arr,{
            label    : '确认',
            cssClass : 'btn-info col-xs-2 margin-lr-20',
            action   : function() {
                if (title_type == 'user-info') {
                    if( !id_gender.val() ) {
                        BootstrapDialog.alert('请设置性别！');
                    } else if ( !id_birth.val() ) {
                        BootstrapDialog.alert('请设置出生日期！');
                    } else if ( !id_work_year.val() ) {
                        BootstrapDialog.alert('教龄不能为空!');
                    } else if ( !id_address.val() ) {
                        BootstrapDialog.alert('所在地不能为空!');
                    } else if ( !id_school.val() ) {
                        BootstrapDialog.alert('毕业院校不能为空!');
                    } else if ( !id_education.val() ) {
                        BootstrapDialog.alert('最高学历不能为空');
                    } else if ( !able_edit.teacher_textbook ) {
                        BootstrapDialog.alert('教材版本不能为空');
                    } else {
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
                                //'major'         : id_major.val(),
                                //'hobby'         : id_hobby.val(),
                                //'speciality'    : id_speciality.val(),
                                "qq_info"       : id_qq_info.val(),
                                "wx_name"       : id_wx_name.val(),
                                "is_prove"      : id_is_prove.val(),
                                "teaching_achievement"   : id_teaching_achievement.val(),
                                'teacher_textbook' : able_edit.teacher_textbook,
                            } ,
                            success : function(result){
                                if(result.ret==0){
                                    BootstrapDialog.alert("修改成功！");
                                    setTimeout(function(){
                                        window.location.reload();
                                    },2000);
                                }else{
                                    BootstrapDialog.alert(result.info);
                                }
                            }
                        });
                    }
                } else {
                    if (id_bank_account.val() == '') {
                        BootstrapDialog.alert('持卡人不能为空!');
                    } else if ( !id_idcard.val() ) {
                        BootstrapDialog.alert('身份证号不能为空！');
                    } else if ( !id_bank_type.val() ) {
                        BootstrapDialog.alert('请选择银行卡类型!');
                    } else if ( !id_bank_address.val() ) {
                        BootstrapDialog.alert('支行名称不能为空!');
                    } else if ( !id_bank_province.val() ) {
                        BootstrapDialog.alert('开户省不能为空!');
                    } else if ( !id_bank_city.val() ) {
                        BootstrapDialog.alert('开户市区不能为空!');
                    } else if ( !id_bankcard.val() ) {
                        BootstrapDialog.alert('银行卡号不能为空!');
                    } else if ( !id_bank_phone.val() ) {
                        BootstrapDialog.alert('预留手机号不能为空!');
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
                                    BootstrapDialog.alert("修改成功！");
                                    setTimeout(function(){
                                        window.location.reload();
                                    },2000);
                                }else{
                                    BootstrapDialog.alert(result.info);
                                }
                            }
                        });
                    }
                }
            }
        },'',false,600,'padding-right:60px;');

    };

    //处理头像

    $("#face").on('click', function(){
        var options = {
            thumbBox: '.thumbBox',
            spinner: '.spinner',
            imgSrc: '/img/no_img.jpeg'
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
        do_ajax("/teacher_info/get_pub_upload_token",{}, function(ret){
            pic_token = ret.upload_token;
        });
        console.log(pic_token)

        $('.opt-submit').on('click', function() {
            if (picStr) {
                console.log(pic_token)
                upload_base64(picStr, pic_token);
            } else {
                BootstrapDialog.alert("请先剪切图片！");
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
                            BootstrapDialog.alert("修改成功！");
                            setTimeout(function(){
                                window.location.reload();
                            },2000);
                        }else{
                            BootstrapDialog.alert(result.info);
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
