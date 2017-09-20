/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-present_manage_new.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            del_flag : $('#id_del_flag').val(),
        })
    }

    Enum_map.append_option_list("gift_del_flag", $("#id_del_flag"));
    $('#id_del_flag').val(g_args.del_flag);

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
        var gift_name = $(this).get_opt_data().gift_name;
        var giftid = $(this).parent().data("giftid");
        var del_name = '<span style="color:red">'+gift_name+'</span>';
        BootstrapDialog.show({
            title: "下架礼品",
            message : "确认是否下架礼品" + del_name ,
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

    $(".opt-gift-desc").on("click", function(){
        var html_node = $("<div></div>").html($.dlg_get_html_by_class('dlg_modify_gift_desc'));
        var gift_desc_str = $(this).get_opt_data().gift_desc_str;
        for (var i=0;i<gift_desc_str.length;i++){
            html_node.find(".show_pic").append(
                '<div style="float:left;"><a title="点击选中图片" class="change_image" href="javascript:;">'+
                    '<p style="width:100px;height:100px;overflow:hidden;"><img src='+
                    gift_desc_str[i] +' style="width:100%;"></p></a></div>');
        }
        html_node.find(".add_pic").attr('id', 'opt-add-pic');
        html_node.find(".add_pic").parent().attr('id', 'opt-add-pic-parent');
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
            }, buttons: [{
                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    var gift_desc_new = '';
                    html_node.find(".show_pic").children().each(function(){
                        gift_desc_new += $(this).children('a').children('p').children('img').attr('src') + ',';
                    });

                    gift_desc_new = remove_last_comma(gift_desc_new);
                    $.ajax({
                        url: '/authority/modify_gift_desc',
                        type: 'POST',
                        data: {'giftid': giftid, 'gift_desc': gift_desc_new},
                        dataType: 'json',
                        success: function(data) {
                            BootstrapDialog.alert(data['info']);
                            window.location.reload();
                        }
                    });
                    dialog.close();
                }
            }]
        });

        var ta = setTimeout(function(){
            do_ajax( "/common/get_bucket_info",{
                is_public: 1
            },function(ret){
                var domain_name=ret.domain;
                custom_upload_file('opt-add-pic', 1,function (up, info, file) {
                    var res = $.parseJSON(info);
                    $(".bootstrap-dialog-body .show_pic").append(
                        '<div style="float:left;"><a title="点击选中图片" class="change_image" href="javascript:;">'+
                            '<p style="width:100px;height:100px;overflow:hidden;"><img src='+
                            domain_name +'/'+ res.key +' style="width:100%;"></p></a></div>');
                }, null,[ "png", "jpg"]);
                clearTimeout(ta);
            });
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

    function FileProgress(file, targetID) {
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

    function check_type(file_type) {
        // return file_type == 'image/png' ? true : false;
        console.log("check_type" . file_type);
        return true;
    }

    function set_modify_gift_pic(domain, info) {
        var res = $.parseJSON(info);
        $(".bootstrap-dialog-body .gift_url").val(domain + res.key);
        $(".bootstrap-dialog-body .preview_gift_pic").attr("href", domain + res.key);
    }

    function set_modify_gift_desc(domain, info) {
        var res = $.parseJSON(info);
        $(".bootstrap-dialog-body .show_pic").append('<div style="float:left;"><a title="点击选中图片" class="change_image" href="javascript:;">'+
                                                     '<p style="width:100px;height:100px;overflow:hidden;"><img src='+
                                                     domain + res.key +' style="width:100%;"></p></a></div>');
    }

    $('.opt-change').set_input_change_event(load_data);

    $('#opt-add-gift').on('click', function () {
        add_or_update_info(1);
    });
    $('.opt-gift-modify').on('click', function() {
        var opt_data = $(this).get_opt_data();
        add_or_update_info(0,opt_data);
    });

    var pic_url;
    var ratio = parseInt($('#ratio').text() );
    var gen_upload_item = function(btn_id,pic_url){
        var id_item = $(
            "<div class=\"row\"> "+
                "<div class=\"col-md-12\" id=\"img\"></div>"+
                "<div class=\" col-md-2\">" +
                "<button class=\"btn btn-primary\" id=\""+btn_id+"\">上传</button>"+
                "</div></div>"
        );

        id_item["onshown_init"]=function () {
            if (pic_url) {
                var pic_thumb = '<img src="'+pic_url+'" width="200">';
                $("#img").append(pic_thumb);
            }

            custom_upload_file(
                btn_id,true,function(up, file, info) {
                    var res = $.parseJSON(file);
                    if(res.key != ""){
                        pic_url  = pub_domain+res.key;
                        gift_url = pic_url;
                        var pic_thumb = '<img src="'+pic_url+'" width="200">';
                        $("#img").empty();
                        $("#img").append(pic_thumb);
                    }
                }, [], ["bmp","jpg","png","gif"],function(){}
            );

        }
        return id_item;
    };

    var add_or_update_info = function(flag, opt_data){
        var select_type = '<select class="gift_type" >'
            +' <option value="0">系统礼包</option> '
            +'<option value="1">实物</option>'
            +' <option value="2">虚拟物品（手机）</option> '
            +'<option value="3">虚拟物品（qq）</option> </select> ';

        if(flag == 0) {
            pic_url = opt_data.gift_pic;
            gift_url = pic_url;
        } else {
            pic_url = '';
            gift_url = '';
        }
        var id_gift_url=gen_upload_item("upload_pic",pic_url);
        var id_gift_name   = $("<input/>");
        var id_gift_type   = $(select_type);
        var id_cost_price  = $("<input id=\"price\" type=\"number\" min=\"0\"/>");
        var id_sale        = $("<input id=\"sale\" type=\"number\" min=\"1\" min=\"1\" value=\"100\"/>");
        var id_shop_link   = $("<input/>");
        var id_gift_praise = $("<span id=\"praise\"/>");
        var id_del_flag    = $("<select/>");
        var id_gift_intro  = $("<textarea/>");
        Enum_map.append_option_list("gift_del_flag", id_del_flag,true);
        if (flag == 1) {
            var modal_title = '添加礼品';
            var giftid = 0;
        } else {
            var modal_title = '修改礼品信息';
            var giftid = opt_data.giftid;
            id_gift_name.val(opt_data.gift_name);
            id_gift_type.val(opt_data.gift_type);
            id_cost_price.val(opt_data.cost_price);
            id_gift_praise.text(opt_data.current_praise);
            id_shop_link.val(opt_data.shop_link);
            id_del_flag.val(opt_data.del_flag);
            id_sale.val(opt_data.sale);
            id_gift_intro.val(opt_data.gift_intro);
        }


        var arr= [
            ["礼品名称：", id_gift_name],
            ["礼品类型：", id_gift_type],
            ["封面图片：", id_gift_url],
            ["商品原价：", id_cost_price],
            ["商品优惠：", id_sale],
            ["所需赞数：", id_gift_praise],
            ["商品状态：", id_del_flag],
            ["购买链接：", id_shop_link],
            ["礼品简介：", id_gift_intro],
        ];

        $.show_key_value_table(modal_title, arr,{

            label    : '确认',
            cssClass : 'btn-info',
            action   : function() {
                var praise = $('#praise').text();
                $.ajax({
                    type     : "post",
                    url      : "/authority/add_or_update_gift",
                    dataType : "json",
                    data : {
                        'giftid'     : giftid,
                        'gift_name'  : id_gift_name.val(),
                        'gift_type'  : id_gift_type.val(),
                        'pic_url'    : gift_url,
                        'cost_price' : id_cost_price.val(),
                        'praise'     : praise,
                        'shop_link'  : id_shop_link.val(),
                        'del_flag'   : id_del_flag.val(),
                        'sale'       : id_sale.val(),
                        'gift_intro' : id_gift_intro.val(),
                    } ,
                    success : function(result){
                        if(result.ret==0){
                            window.location.reload();
                        }else{
                            alert(result.info);
                        }
                    }
                });
            }
        },function(){
            id_gift_url["onshown_init"]();
            $('#price, #sale').keyup(function(){
                var new_praise = parseInt($("#price").val()) * ratio * parseInt($('#sale').val()) /10000;
                if (new_praise) {
                    $('#praise').text( new_praise );
                }
            });
        }, false,600);

    };

});
