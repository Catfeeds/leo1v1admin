/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test_roy-test_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		    userid:	$('#id_userid').val()
    });
}
$(function(){


	  $('#id_userid').val(g_args.userid);


	  $('.opt-change').set_input_change_event(load_data);
    
    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();
        alert(opt_data.grade);
	      
    });

});

