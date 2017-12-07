/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tq-get_list.d.ts" />
function notify_cur_playpostion(  ) {

}


$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type:  $('#id_date_type').val(),
            opt_date_type:  $('#id_opt_date_type').val(),
            start_time: $('#id_start_time').val(),
            end_time:   $('#id_end_time').val(),
            phone:  $('#id_phone').val(),
            user_info         : $('#id_user_info').val(),
            is_called_phone:    $('#id_is_called_phone').val(),
            seller_student_status:  $('#id_seller_student_status').val(),
            uid:    $('#id_uid').val()
        });
    }

    Enum_map.append_option_list("boolean",$("#id_is_called_phone"));
    $("#id_user_info").val(g_args.user_info);
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

        var html_node = $(" <div  style=\"text-align:center;\"  > <div id=\"drawing_list\" style=\"width:100%\"  > </div>  <audio id=\"myaudio\" preload=\"none\"  > </audio> <br> <button id=\"half_speed\" class=\"btn btn-primary \"> 0.5倍速 </button> <button id=\"one_speed\" class=\"btn btn-primary \"  > 1倍速 </button>  <button id=\"one_half_speed\" class=\"btn btn-primary \"  >1.5倍速 </button> <a href=\""+url+"\" class=\"btn btn-primary \"  target=\"_blank\"> 下载 </a>  </div> ");

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
                    //url = "http://admin.leo1v1.com/audio/13784613859_51136893_233cf4bd-ce41-4eb1-bbad-dd8909c44f5c.mp3";
                    as[0].load(url);
                    as[0].play();
                });
            }

        });

        html_node.find('#half_speed').on("click",function(){
            myVid=document.getElementById("myaudio");
            myVid.playbackRate=0.5;
        });
        html_node.find('#one_speed').on("click",function(){
            myVid=document.getElementById("myaudio");
            myVid.playbackRate=1;
        });
        html_node.find('#one_half_speed').on("click",function(){
            myVid=document.getElementById("myaudio");
            myVid.playbackRate=1.5;
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
