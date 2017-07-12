/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/upload_tmk-post_list.d.ts" />



$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type_config:	$('#id_date_type_config').val(),
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val()
        });
    }


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


    $('.opt-change').set_input_change_event(load_data);

    $("#id_add_upload").on("click",function(){
        var $desc=$("<input/>");
        var arr=[
            ["批次说明",$desc ],
        ];
        $.show_key_value_table("增加批次", arr,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/upload_tmk/add_upload_post",{
                    "desc" :$desc.val(),
                } );
            }
        });

    });


    $(".opt-del" ).on("click",function(){
        var opt_data=$(this).get_opt_data();
        BootstrapDialog.confirm("要删除"+ opt_data.upload_time + "的批次?", function(val){
            if (val) {
                $.do_ajax("/upload_tmk/del_upload_post",{
                    "postid" :opt_data.postid
                } );
            }
        });

    });


    $(".opt-list").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/upload_tmk/post_student_list?postid="+opt_data.postid,false );
    });

});
