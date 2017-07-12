/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-get_test_lesson_require_teacher_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			teacherid:	$('#id_teacherid').val()
        });
    }


    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

	$('#id_teacherid').val(g_args.teacherid);
    $.admin_select_user($('#id_teacherid'),
                        "teacher", load_data);

    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id = opt_data.id;
      
        BootstrapDialog.confirm("确定要删除？", function(val){
            if (val) {
                $.do_ajax( '/user_deal/del_test_lesson_require_teacher_info', {
                    'id' : id
                });
            } 
        });

    });

    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var id= opt_data.id;
       // var show_list="["+opt_data.teacher_info+"]";
       // alert(show_list);
        
        //var show_all_flag=($.get_action_str()=="manager_list");

       // var permission  = opt_data["permission"];
        $.do_ajax("/ss_deal/get_test_require_teacher_info",{
            "id" : id
        },function(response){
            var data_list   = [];
            var select_list = [];
            $.each( response.data,function(){
                data_list.push([this["teacherid"], this["realname"]  ]);
                select_list.push (this["teacherid"]) ;

            });

            $(this).admin_select_dlg({
                header_list     : [ "id","姓名" ],
                data_list       : data_list,
                multi_selection : true,
                select_list     : select_list,
                onChange        : function( select_list,dlg) {
                    $.do_ajax("/ss_deal/set_test_require_teacher_info",{
                        "id": id,
                        "teacherid_list":JSON.stringify(select_list)
                    });
                }
            });
        }) ;



    });


    $("#id_update_require_teacher_info").on("click",function(){
        BootstrapDialog.confirm("确定刷新预分配数据？", function(val){
            if (val) {
                $.do_ajax( '/user_deal/update_test_lesson_require_teacher_info', {});
            } 
        });

    });

	$('.opt-change').set_input_change_event(load_data);
});
