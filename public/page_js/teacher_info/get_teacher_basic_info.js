/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-get_teacher_basic_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }

    $('.opt-set').on('click', function(){
        var old_status = $(this).attr('data-status');
        if(old_status == 'full') {
            $(this).attr('data-status','nofull');
            $(this).html("设置饱和");
        } else {
            $(this).attr('data-status', 'full');
            $(this).html("设置不饱和");
        }
        $('h3[data-status]').toggleClass('hide');
        $('p[data-status]').toggleClass('hide');

    });

    $('.opt-edit').on('click', function () {
        $(this).nextAll().removeClass('hide');
        var id = $(this).attr('data-name');
        if(id == 'user-info'){
            $(this).hide();
        }
        $('#'+id+' span').addClass('hide');
        $('#'+id+' input').removeClass('hide');
        $('#'+id+' select').removeClass('hide');
    });

    $('.direct-chat-contacts').css('backgroundColor','#fff');
    $('button[data-refresh]').on('click', function(){
       history.go(0);
    });


	  $('.opt-change').set_input_change_event(load_data);
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
                    history.go(0);
                }else{
                    alert(result.info);
                }
			      }
        });

    });
});


/* HTML ...
*/
