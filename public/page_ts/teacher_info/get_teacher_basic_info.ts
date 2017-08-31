/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-get_teacher_basic_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }




    var gen_upload_item = function(btn_id ,status, file_name_fix, get_url_fun, set_url_fun, bucket_info, noti_origin_file_func, back_flag){
        var id_item = $(
            "<div class=\"row\"> "+
                "<div class=\" col-md-2\">" +
                "<button class=\"btn btn-primary  upload \" id=\""+btn_id+"\">上传</button>"+
                "</div>"+
                "<div class=\" col-md-2\">" +
                "<button class=\"btn btn-primary show\">查看 </button>"+
                "</div>"+
                "</div>"
        );
        id_item.find(".show").on("click",function(){
            $.custom_show_pdf(get_url_fun(),"/teacher_info/get_pdf_download_url");
        });

        // upload_status_show(id_item,status);
        id_item["onshown_init"]=function () {
            if ( back_flag ) {

                $.self_upload_process(btn_id,"/common/upload_qiniu",[] ,["pdf","zip"],{
                    "file_name_fix":file_name_fix
                }, function( ret,ctminfo){
                    set_url_fun(ret.file_name);
                    upload_status_show(id_item,1);
                });

            }else{
                try {
                    $.custom_upload_file_process(
                        btn_id, 0,
                        function(up, info, file, lesson_info) {
                            var res = $.parseJSON(info);
                            if(res.key!=''){
                                set_url_fun(res.key);
                                upload_status_show(id_item,1);
                            }
                        }, [], ["pdf","zip"], bucket_info, noti_origin_file_func);
                }catch(e){
                    $.self_upload_process(btn_id,
                                          "/common/upload_qiniu",[] ,
                                          ["pdf","zip"],
                                          {
                                              "file_name_fix":file_name_fix
                                          }, function( ret,ctminfo){
                                              set_url_fun(ret.file_name);
                                              upload_status_show(id_item,1);
                                          });
                }
            }
        }
        //console.log(id_item);
        return id_item;
    };

    var upload_info = function( opt_data, back_flag){
        var stu_status  = opt_data.stu_status;
        var btn_student_upload_id = "id_student_upload";
        $.do_ajax("/common/get_bucket_info",{
            is_public : 0
        },function(ret){
            var id_student = gen_upload_item(
                btn_student_upload_id,stu_status,
                "l_stu_"+opt_data.lessonid,
                function(){return stu_cw_url; },
                function(url) {stu_cw_url=url;}, ret ,function(file_name) {
                },back_flag
            );

            var arr= [
                ["----","证件信息"],
                ["上传证书", id_student],
            ];

            $.show_key_value_table("课堂信息", arr ,{
                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog) {
                    if ($(".false").length>0) {
                        BootstrapDialog.alert("请完善信息");
                        return;
                    }

                    $.do_ajax("/teacher_info/update_teacher_student_pdf",{
                        "stu_cw_url"         : stu_cw_url,
                    });
                }
            },function(){
                id_student["onshown_init"]();
            },false,900);
        });
    };


    $(".opt-upload").on("click", function( ){
        var opt_data = $(this).get_opt_data();
        upload_info(opt_data,false);
    });

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
