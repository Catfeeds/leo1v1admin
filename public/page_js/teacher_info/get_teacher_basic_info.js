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

    })
    $('.direct-chat-contacts').css('backgroundColor','#fff');



	  $('.opt-change').set_input_change_event(load_data);
});


/* HTML ...
*/
