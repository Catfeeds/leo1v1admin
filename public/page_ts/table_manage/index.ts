/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/table_manage-index.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			db_name:	$('#id_db_name').val(),
			table_name:	$('#id_table_name').val()
        });
    }

    $("#id_edit").on("click",function(){
	    $.wopen( "/table_manage/edit_table_data?db_name="+g_args.db_name +"&table_name=" + g_args.table_name  );
    });

    $('#id_db_name').val(g_args.db_name);
    $('#id_table_name').val(g_args.table_name);

    $("#id_db_name").on("change",function (){
        $.reload_self_page({
            "db_name" : $("#id_db_name").val()
        });
    });
    $("#id_table_name").on("change",function (){
        $.reload_self_page({
            "db_name" : $("#id_db_name").val(),
            "table_name" : $("#id_table_name").val()
        });
    });

    var change_field_comment = function(db_name,table_name,field,comment){
        $.do_ajax("/table_manage/change_field_comment", {
            "db_name"    : db_name,
            "table_name" : table_name,
            "field"      : field,
            "comment"    : comment
        });
    }

    $("#id_change_table_comment").on("click",function(){
        var db_name    = $("#id_db_name").val();
        var table_name = $("#id_table_name").val();
        var id_desc    = $("<input/>");

        $.show_key_value_table("修改表备注", [["备注", id_desc ]],{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/table_manage/change_table_comment", {
                    "db_name"    : db_name,
                    "table_name" : table_name,
                    "comment"    : id_desc.val()
                },function(result){
                    if(result.ret==0){
                        load_data();
                    }else{
                        Bootstrap.alert(result.info);
                    }
                });
            }
        });
    });


    do_get_env(function( env) {
        if (env != "local" && g_account!="adrian") {
            $(".opt-field-comment").hide();
            $(".opt-set-none").hide();
        }
    });

    $(".opt-field-comment").on("click",function(){
        var db_name    = $("#id_db_name").val();
        var table_name = $("#id_table_name").val();
        var field      = $(this).get_opt_data("field") ;
        var comment    = $(this).get_opt_data("comment") ;
        var id_desc    = $("<input/>");

        id_desc.val(comment);
        $.show_key_value_table("修改列备注", [
            ["列名", field  ],
            ["备注", id_desc ]
        ],{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                change_field_comment(db_name,table_name,field,id_desc.val());
            }
        });
    });

    $(".opt-set-none").on("click",function(){
        var db_name    = $("#id_db_name").val();
        var table_name = $("#id_table_name").val();
        var field      = $(this).get_opt_data("field");
        var comment    = $(this).get_opt_data("comment");

        var new_comment = comment+" 无用";
        change_field_comment(db_name,table_name,field,new_comment);
    });


    $('#id_db_name').val(g_args.db_name);
	  $('#id_table_name').val(g_args.table_name);
    $('.opt-change').set_input_change_event(load_data);
});
