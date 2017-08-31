/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-get_teacher_basic_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }
    var cur_status = $('#my_status').attr('cur-status');
    if (cur_status == 0) {
        $('[data-status=nofull]').addClass('hide');
    } else {
        $('[data-status=full]').addClass('hide');
    }

    $(".opt-upload").on("click",function(){
        do_add_or_update("add");
    });

    var do_add_or_update = function( opt_type, item ,id){
        var html_txt = $.dlg_get_html_by_class('dlg_add_certificate_info');
        html_txt=html_txt.
            replace(/\"id_upload_add\"/, "\"id_upload_add_tmp\"" ).
            replace(/\"id_container_add\"/, "\"id_container_add_tmp\"" )
        ;
        var html_node = $("<div></div>").html(html_txt);

        if (opt_type=="update") {
            pic_url=item.pic;
            pic_img="<img width=100 src=\""+pic_url+"\" />";
            html_node.find(".add_header_img").html(pic_img);
            html_node.find(".add_pic").html(pic_url);
            html_node.find(".add_wxnew_type").val(item.wxnew_type);
            html_node.find(".add_title").val(item.title);
            html_node.find(".add_des").val(item.des);
            html_node.find(".add_new_link").val(item.new_link);
        }

        var title = "";
        if (opt_type=="update"){
            title="修改证书";
        }else{
            title="上传证书";
        }
        BootstrapDialog.show({
            title           : title,
            message         : html_node,
            closable        : true,
            closeByBackdrop : false,
            onshown         : function(dialog){
                custom_qiniu_upload ("id_container_add_tmp","id_upload_add_tmp",
                                     g_args.qiniu_upload_domain_url , true,
                                     function (up, info, file){
                                         var res = $.parseJSON(info);
                                         zgz_url = g_args.qiniu_upload_domain_url + res.key;
                                         console.log(zgz_url)
                                     });
            },
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action : function(dialog) {
                        var new_link = html_node.find(".add_new_link").val();
                        if (opt_type=="update") {
                            $.ajax({
                                type     : "post",
                                url      : "/t_yxyx_wxnews_info/update_new_info",
                                dataType : "json",
                                data : {
                                    "id"        : id
                                    ,"new_link" : new_link
                                },
                                success : function(result){
                                    if(result.ret==0){
                                        window.location.reload();
                                    }else{
                                        dialog.close();
                                    }
                                }
                            });

                        } else {
                            $.ajax({
                                type     : "post",
                                url      : "/t_yxyx_wxnews_info/add_new_info",
                                dataType : "json",
                                data : {
                                    "title"     : title
                                    ,"new_link" : new_link
                                },
                                success : function(result){
                                    if(result.ret==0){
                                        window.location.reload();
                                    }else{
                                        dialog.close();
                                    }
                                }
                            });
                        }
                    }
                },{
                    label: '取消',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();
                    }
                }]
        });

    };


    $('.opt-set').on('click', function(){
        var old_status = $(this).attr('data-status');
        $.ajax({
			      type     : "post",
			      url      : "/teacher_info/edit_teacher_status",
			      dataType : "json",
			      data     : {'status': old_status},
			      success : function(result){
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
        $(this).nextAll().removeClass('hide');
        var id = $(this).attr('data-name');
        if(id == 'user-info'){
            $(this).hide();
        }
        $('#'+id+' span').addClass('hide');
        $('#'+id+' a').addClass('hide');
        $('#'+id+' input').removeClass('hide');
        $('#'+id+' select').removeClass('hide');
    });

    $('.btn-bank').on('click', function() {
        if ($(this).text() != '提交') {
            $(this).text('提交');
            $('#bank-info').removeClass('hide');
            $('#bank-info input').removeClass('hide');
            $('#bank-info select').removeClass('hide');
            $('#bank-info span').addClass('hide');
        } else {
            $.ajax({
			          type     : "post",
			          url      : "/teacher_info/edit_teacher_bank_info",
			          dataType : "json",
			          data     : $('#bank-info').serialize(),
			          success : function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        alert(result.info);
                    }
			          }
            });

        }
    });
    $('.opt-bank').on('click', function (){
        $(this).parent().remove();
        $('.div-bank').removeClass('hide');
        $('.btn-bank').click();
    });
    $('.direct-chat-contacts').css('backgroundColor','#fff');
    $('button[data-refresh]').on('click', function(){
        window.location.reload();
    });
    $('.opt-submit').on('click', function () {
        var form_id = $(this).attr('data-name');
        var sub_url = $('#'+form_id).attr('data-sub');
        $.ajax({
			      type     : "post",
			      url      : "/teacher_info/"+sub_url,
			      dataType : "json",
			      data     : $('#'+form_id).serialize(),
			      success : function(result){
                if(result.ret==0){
                    window.location.reload();
                }else{
                    alert(result.info);
                }
			      }
        });
    });
	  $('.opt-change').set_input_change_event(load_data);


});


/* HTML ...
*/
