/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-tea_resource.d.ts" />

function load_data(){
	  if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		    order_by_str : g_args.order_by_str,
		    cur_dir:	$('#id_cur_dir').val()
		});
}
$(function(){


	  $('#id_cur_dir').val(g_args.cur_dir);

    $("#id_select_all").on("click",function(){
        $(".opt-select-item").iCheck("check");
    });

    $("#id_select_other").on("click",function(){
        $(".opt-select-item").each(function(){
            var $item=$(this);
            if ($item.iCheckValue() ) {
                $item.iCheck("uncheck");
            }else{
                $item.iCheck("check");
            }
        } );
    });

	  $('.opt-change').set_input_change_event(load_data);
});
