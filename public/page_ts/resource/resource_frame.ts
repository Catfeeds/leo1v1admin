/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/resource-resource_frame.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {

    });
}
$(function(){

    // $(".common-table").tbody_scroll_table(500);
    $(".common-table").table_group_level_more_init(8);

    $('.l-3 .key4').each(function(i){
        var r_type = $(this).parent().data('resource_type');
        if(r_type < 6 || r_type == 9){
            $(this).css({
                color: "#3c8dbc",
                cursor:"pointer"
            });
            $(this).on('click',function(){
                var info_str = $(this).prev().data('class_name');
                add_region_version(info_str);
            });
        }
    });

    var add_region_version = function(info_str){
        var id_book = $("<select />");
        Enum_map.append_option_list("region_version",id_book,true);
        var arr= [
            ["添加教材版本：", id_book],
        ];

        $.show_key_value_table('添加教材版本', arr,{
            label    : '确认',
            cssClass : 'btn-info',
            action   : function() {

                if(id_book.val() > 0){
                    $.ajax({
                        type     : "post",
                        url      : "/resource/add_region_version",
                        dataType : "json",
                        data : {
                            'info_str' : info_str,
                            'region'   : id_book.val(),
                        } ,
                        success : function(result){
                            if(result.ret == 0){
                                window.location.reload();
                            } else {
                                alert(result.info);
                            }
                        }
                    });
                } else {
                    alert('请选择教材版本!');
                }

            }
        },function(){},600);

    }

	  $('.opt-change').set_input_change_event(load_data);
});



