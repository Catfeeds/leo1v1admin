/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-get_teacher_basic_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    };

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
        var class_content = $(this).attr('data-name');
        var id_model   = $(this).attr('data-target');
        var html_test = $.dlg_get_html_by_class(class_content);
        var html_code = $("<form></form>").html(html_test);
        if (class_content == 'user-info') {
            $(id_model+' .modal-title').text('基本信息');
        } else {
            $(id_model+' .modal-title').text('银行卡信息');
        }
        html_code.find('input,select,span,a').toggleClass('hide');
        $(id_model+' .modal-body').empty().append(html_code);
    });

    $('.direct-chat-contacts').css('backgroundColor','#fff');
    $('.opt-submit').on('click', function () {
        var sub_content =  $('#modal-default .modal-body>form').serialize();
        var sub_url     =  $('#modal-default .modal-body>form').children().first().attr('data-sub');
        console.log(sub_url)
        console.log(sub_content)
        $.ajax({
            type     : "post",
            url      : "/teacher_info/"+sub_url,
            dataType : "json",
            data     : sub_content,
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

    $(".tag").on("click", function () {
        if (!confirm("确定退出编辑吗？")){
            return false;
        }
    });

    //处理图片
});
