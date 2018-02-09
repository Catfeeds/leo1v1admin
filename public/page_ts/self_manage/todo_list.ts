/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/self_manage-todo_list.d.ts" />


$(function(){
//	alert("xxx");
    function load_data(){
        $.reload_self_page ( {
            date_type_config:	$('#id_date_type_config').val(),
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val(),
            todo_type:	$('#id_todo_type').val(),
            todo_status:	$('#id_todo_status').val()
        });
    }


    $('#id_date_range').select_date_range({
        'date_type'     : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });
    $('#id_todo_type').val(g_args.todo_type);
    $.enum_multi_select( $('#id_todo_type'), 'todo_type', function(){load_data();} )
    $('#id_todo_status').val(g_args.todo_status);
    $.enum_multi_select( $('#id_todo_status'), 'todo_status', function(){load_data();} )

//	alert("222");

    $('.opt-change').set_input_change_event(load_data);


    $(".opt-jump").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen(opt_data["jump_url"] );
    });

    $(".opt-reset").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.do_ajax("/ajax_deal/todo_reset",{
            "todoid": opt_data["todoid"],
        });

    });
    //添加
    $('#id_self_todo_new').click(function(){
       // var opt_data   = $(this).get_opt_data();
        var $userid     = $("<input/>");
        var $start_time = $("<input/>");
        $start_time.datetimepicker({
            lang:'ch',
            timepicker:true,
            format:'Y-m-d H:i'
        });


        var arr=[
            ["学生id",$userid],
            ["开始时间",$start_time],
          ];

       // Enum_map.append_option_list("userid",userid, true);
        //Enum_map.append_option_list("subject",$subject, true);

        $.show_key_value_table("新增回访", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/self_manage/todo_add",{
                    "userid"     : $userid.val(),
                    "start_time" : $start_time.val(),
                });
            }
        }, function (){
            $.admin_select_user($userid,"student");
        });




    });


    var init_noit_btn_ex=function( id_name, count, title,desc ,value_class) {
        var btn=$('#'+id_name);
        count=count*1;
        btn.data("value",count);
        btn.tooltip({
            "title":title + "("+desc+")",
            "html":true
        });
        btn.addClass("btn-app") ;

        var value =btn.data("value");

        var str="<span class=\"badge  \">"+count+"</span>" + title;
        btn.html(str);
        if (!value_class) value_class="bg-yellow";
        if (value >0 ) {
            btn.addClass(value_class);
            btn.find("span"). addClass(value_class);
        }
    };
    var init_noit_btn=function( id_name, count, title,desc) {
        init_noit_btn_ex( id_name, count, title, desc, null);
    };



    init_noit_btn("id_assign_lesson_count",   g_args.assign_lesson_count,    "可赠送课时", "可赠送课时数" );

//	alert("xxxxx22");

});
