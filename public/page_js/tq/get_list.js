/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tq-get_list.d.ts" />
function notify_cur_playpostion(  ) {

}


$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val(),
            phone:	$('#id_phone').val(),
            is_called_phone:	$('#id_is_called_phone').val(),
            seller_student_status:	$('#id_seller_student_status').val(),
            uid:	$('#id_uid').val()
        });
    }

    Enum_map.append_option_list("boolean",$("#id_is_called_phone"));

    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });


    $(".opt-audio").each(function(){
        var opt_data=$(this).get_opt_data();
        if (!opt_data.is_called_phone) {
            $(this).hide();
        }
    });

    $(".opt-audio").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var   url = opt_data.record_url;
        if (opt_data.load_wav_self_flag) {
            var file=opt_data.record_url.split("/")[4];
            //get mp3
            file=file.split(".")[0]+".mp3";
            url= "/audio/"+file;
        }

        var html_node = $(" <div  style=\"text-align:center;\"  > <div id=\"drawing_list\" style=\"width:100%\"  > </div>  <audio preload=\"none\"  > </audio> <br>  <a href=\""+url+"\" class=\"btn btn-primary \"  target=\"_blank\"> 下载 </a>  </div> ");

        var audio_node   = html_node.find("audio" );

        BootstrapDialog.show({
            title    : "录音:"+opt_data.phone ,
            message  : html_node,
            closable : true,
            onhide   : function(dialogRef){
            },
            onshown: function() {
                //加载mp3
                audiojs.events.ready(function(){

                    var as = audiojs.createAll({}, audio_node  );
                    //as[0].load( opt_data.record_url );
                    as[0].load(url);
                    as[0].play();
                });
            }

        });


    });

    

    $('#id_phone').val(g_args.phone);
    $('#id_is_called_phone').val(g_args.is_called_phone);
    $('#id_uid').val(g_args.uid);
    $.admin_select_user( $("#id_uid"), "admin",  load_data );
    $('#id_seller_student_status').val(g_args.seller_student_status);
    $.enum_multi_select( $('#id_seller_student_status'), 'seller_student_status', function(){load_data();} )


    $('.opt-change').set_input_change_event(load_data);
});
