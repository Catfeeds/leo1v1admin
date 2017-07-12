/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/taobao_manage-taobao_type.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			type:	$('#id_type').val()
        });
    }

	$('#id_type').val(g_args.type);
	$('.opt-change').set_input_change_event(load_data);

    $('.opt-change_type').on("click",function(){
        var cid         = $(this).get_opt_data("cid");
        var type_val    = $(this).get_opt_data("type");
        var change_type = 0;
        if(type_val==0){
            change_type=1;
        }

        $.do_ajax("/taobao_manage/update_taobao_type",{
            "cid"  : cid, 
            "type" : change_type
        },function(result){
            if(result.ret==0){
                load_data();
            }else{
                BootstrapDialog.alert(result.info);
            }
        });
    });

});
