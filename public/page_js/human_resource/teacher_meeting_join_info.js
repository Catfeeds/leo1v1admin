/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-teacher_meeting_join_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			create_time: g_create_time,
			teacherid:	$('#id_teacherid').val(),
            subject:$('#id_subject').val()
        });
    }

    Enum_map.append_option_list("subject", $("#id_subject"),false );
	$('#id_create_time').val(g_args.create_time);
	$('#id_subject').val(g_args.subject);
	$('#id_teacherid').val(g_args.teacherid);
    $("#id_teacherid").admin_select_user({
        "type"   : "teacher",
        "onChange": function(){
            load_data();
        }
    });

    $("#id_select_all").on("click",function(){
        $(".opt-select-item").iCheck("check");
    });

    $("#id_set_teacher_join_info").on("click",function(){
        
        var opt_data=$(this).get_opt_data();
        var select_teacherid_list=[];

        $(".opt-select-item").each(function(){
            var $item=$(this) ;
            if($item.iCheckValue()) {
                select_teacherid_list.push( $item.data("teacherid") ) ;
            }
        } ) ;
        var do_post= function (join_info) {
            $.do_ajax(
                '/teacher_info/set_teacher_join_info',
                {
                    'teacherid_list' : JSON.stringify(select_teacherid_list ),
                    'create_time':g_create_time,
                    'join_info': join_info
                });
        }

        var id_join_info=$("<select/>");
        Enum_map.append_option_list("teacher_join_info", id_join_info ,true);
        var arr=[
            ["出席情况", id_join_info]
        ];
        $.show_key_value_table("录入出席情况", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var join_info=id_join_info.val();
                do_post(join_info);
            }
        });


    });
   
    $(".opt-edit").on("click",function(){
        
        var opt_data=$(this).get_opt_data();       
        var id_join_info=$("<select/>");
        Enum_map.append_option_list("teacher_join_info", id_join_info ,true);
        var arr=[
            ["出席情况", id_join_info]
        ];
        id_join_info.val(opt_data.join_info);
        $.show_key_value_table("录入出席情况", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                $.do_ajax(
                    '/teacher_info/set_teacher_join_info_once',
                    {
                        'teacherid' : opt_data.teacherid,
                        'create_time':g_create_time,
                        'join_info': id_join_info.val()
                    });
            }
        });


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


    $('#id_jhp').bind('input propertychange', function() {
        $(".jhp").each(function(){
            console.log($(this).text().indexOf($("#id_jhp").val()));
            if($(this).text().indexOf($("#id_jhp").val()) >= 0 ){
                $(this).parent().show();
            }else{
               $(this).parent().hide(); 
            }
        });



    });


	$('.opt-change').set_input_change_event(load_data);
});

