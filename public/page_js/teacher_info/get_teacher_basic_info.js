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
