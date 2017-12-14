/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/table_manage-query.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            db_name:	$('#id_db_name').val(),
            "sql" : $('#id_sql').val()
        });
    }


    $('#id_db_name').val(g_args.db_name);
    $('#id_sql').val(g_args.sql);

    $("#id_query").on("click",function(){
        $.do_ajax_t( "/table_manage/check_query",{
            "db_name" :$('#id_db_name').val(),
            "sql" : $('#id_sql').val()
        },function(resp){
            if (resp.ret!=0 ) {
                alert(resp.info );
            }else{
                load_data();
            }
        });
    });


    $('.opt-change').set_input_change_event(load_data);


    $("#id_nice").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.do_ajax("/table_manage/get_nick_sql",{
            "sql": $("#id_sql").val()
        },function(resp){
            $("#id_nice_sql").text(resp.format_sql );
            $('pre code').each(function(i, block) {
                hljs.highlightBlock(block);
            });

        });

    });
    if(g_account_role==12 || g_account=='tom'){
        download_show();
    }
});
