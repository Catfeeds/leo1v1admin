/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/table_manage-table_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
        db_name:	$('#id_db_name').val(),
    });
}
$(function(){

    $('#id_db_name').val(g_args.db_name);


    $('.opt-change').set_input_change_event(load_data);


    $(".opt-table-info").on("click",function(){
        var opt_data=$(this).get_opt_data();
	      $.wopen( "/table_manage/index?db_name="+g_args.db_name +"&table_name=" + opt_data.table_name );
    });

    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();
	      $.wopen( "/table_manage/edit_table_data?db_name="+g_args.db_name +"&table_name=" + opt_data.table_name );

    });

});
