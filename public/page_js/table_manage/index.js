$(function(){
    

    
    $("#id_edit").on("click",function(){
	    wopen( "/table_manage/edit_table_data?db_name="+g_args.db_name +"&table_name=" + g_args.table_name  );

    });

    $('#id_db_name').val(g_args.db_name);
    $('#id_table_name').val(g_args.table_name);

    $("#id_db_name").on("change",function (){
        reload_self_page({
            "db_name" : $("#id_db_name").val()
        });
    });
    $("#id_table_name").on("change",function (){
        reload_self_page({
            "db_name" : $("#id_db_name").val(),
            "table_name" : $("#id_table_name").val()
        });
    });


    
    $("#id_change_table_comment").on("click",function(){
        var db_name    = $("#id_db_name").val();
        var table_name = $("#id_table_name").val();
        var id_desc    = $("<input/>");


        show_key_value_table("修改表备注", [["备注", id_desc ]],{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                do_ajax("/table_manage/change_table_comment", {
                    "db_name" : db_name,
                    "table_name" : table_name,
                    "comment":  id_desc.val()
                });
            }
        });


    });
    do_get_env(function( env) {
        if (env != "local") {
            $(".opt-field-comment").hide();
        }
    });

    
    $(".opt-field-comment").on("click",function(){
	    //
        var db_name    = $("#id_db_name").val();
        var table_name = $("#id_table_name").val();
        var field= $(this).get_opt_data("field") ;
        var id_desc    = $("<input/>");


        show_key_value_table("修改列备注", [
            ["列名", field  ],
            ["备注", id_desc ]
        ],{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                do_ajax("/table_manage/change_field_comment", {
                    "db_name" : db_name,
                    "table_name" : table_name,
                    "field"   : field,
                    "comment":  id_desc.val()
                });
            }
        });

	    
    });


});
