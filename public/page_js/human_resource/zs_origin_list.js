/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-zs_origin_list.d.ts" />

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
        'date_type'      : g_args.date_type,
        'opt_date_type'  : g_args.opt_date_type,
        'start_time'     : g_args.start_time,
        'end_time'       : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

    $.each($(".l-2"),function(){       
        $(this).hide();        
    });



    var link_css=        {
        color: "#3c8dbc",
        cursor:"pointer"
    };

    $(".l-1 .teacher_ref_type").css(link_css);
   
    $(".l-1 .teacher_ref_type").on("click",function(){
        var $this=$(this);
        var class_name= $this.data("class_name");
        if ($this.data("show") ==true) {
            $(".teacher_ref_type."+class_name ).parent().hide();
        }else{
            var $opt_item=$(".teacher_ref_type."+class_name ).parent(".l-2");
            $opt_item.show();
        }
        $this.parent().show();
        $this.data("show", !$this.data("show") );

    });

   

	$('.opt-change').set_input_change_event(load_data);
});
