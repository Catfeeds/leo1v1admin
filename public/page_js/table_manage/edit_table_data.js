$(function(){
    
   var load_data = function ()  {
       reload_self_page ({
           "db_name" : g_args.db_name ,
           "table_name" : g_args.table_name,
           "id1" : $("#id_id1").val(),
           "id2" : $("#id_id2").val()
       });
   };

    set_input_enter_event ( $("#id_id1") ,function(){
        load_data();
    });
    set_input_enter_event ( $("#id_id2") ,function(){
        load_data();
    });

    $("#id_id1").val(g_args.id1);
    $("#id_id2").val(g_args.id2);


    
    $("#id_del").on("click",function(){
	    //
        BootstrapDialog.confirm("要删除  "+ g_args.id1 + " - " + g_args.id2, function(ret){
            if (ret) {
                $.do_ajax("/table_manage/del_row", {
                    "db_name" : g_args.db_name,
                    "table_name" : g_args.table_name,
                    "id1": g_args.id1,
                    "id2": g_args.id2
                });
            }
        });
    });

    
    $(".opt-field-value").on("click",function(){
	    //
        var field= $(this).get_opt_data("field") ;
        var value= $(this).get_opt_data("value") ;
        var id_value = $("<input/>");
        id_value.val(value);

        show_key_value_table("修改列", [
            ["列名", field  ],
            ["值", id_value]
        ],{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                do_ajax("/table_manage/change_field_value", {
                    "db_name" : g_args.db_name,
                    "table_name" : g_args.table_name,
                    "id1": g_args.id1,
                    "id2": g_args.id2,
                    "field"   : field,
                    "value":  id_value.val()
                });
            }
        });

	    
    });





});
