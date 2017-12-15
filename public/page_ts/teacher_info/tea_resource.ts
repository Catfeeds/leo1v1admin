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


    $('.opt-add-dir').on('click', function(){
        var dir_name = $("<input/>");
        var arr=[
            ["文件夹名称", dir_name  ]
        ];
        $.show_key_value_table("新建文件夹", arr, {
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                do_ajax("/teacher_info/tea_edit_dir", {
                    "name"   : dir_name.val(),
                    "type"   : 'add',
                    "dir_id" : cur_dir_id
                },function(ret){
                    if(ret.ret == 0){
                        BootstrapDialog.alert("操作成功！");
                        setTimeout(function(){
                            window.location.reload();
                        },1000);
                    } else {
                        BootstrapDialog.alert(ret.info);
                    }
                });
            }
        } );

    });
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
