/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-interview_remind.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val(),
            user_name: $("#id_user_name").val(),

        });
    }


  $("#id_user_name").val(g_args.user_name);
    $(".for_input").on ("keypress",function(e){
    if (e.keyCode==13){
      var field_name=$(this).data("field");
      var value=$(this).val();
      load_data();
    }
  });

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

    $(".id_add").on("click", function(){
        var $name         = $("<input/>");
        var $interviewer  = $("<input/>");
        var $post         = $("<input/>");
        var $dept         = $("<input/>");
        var $interview_time = $("<input/>");

        $interview_time.datetimepicker( {
            lang:'ch',
            timepicker:true,
            format: "Y-m-d H:i",
            onChangeDateTime :function(){
            }
        });

        $interview_time.datetimepicker( {
            lang:'ch',
            timepicker:true,
            format: "Y-m-d H:i",
            onChangeDateTime :function(){
            }
        });




        var arr=[
            ["姓名", $name] ,
            ["面试时间", $interview_time] ,
            ["面试官",   $interviewer],
            ["岗位", $post],
            ["部门", $dept],
        ];

        $.show_key_value_table("新增面试信息", arr ,[{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                $.do_ajax ('/ss_deal/add_interview_remind', {
                    'name': $name.val(),
                    'interview_time': $interview_time.val(),
                    'interviewer': $interviewer.val(),
                    'post': $post.val(),
                    'dept': $dept.val(),
                });
            }
        }],function(){
            $.admin_select_user( $interviewer, "admin");
        });
    });


    //id_test
    $(".id_test").on("click", function(){
        $.do_ajax("/test_james/wx_news",{
            "id"  : 1,
        } );

    } );





    $(".opt-del").on("click", function(){
        var opt_data=$(this).get_opt_data();

        BootstrapDialog.confirm("要删除？！",function(val){
            if(val) {
                $.do_ajax("/ss_deal/interview_del",{
                    "id"  : opt_data.id,
                } );
            }

        } );
    });



    $(".opt-edit").on("click",function(){
        var opt_data = $(this).get_opt_data();

        var $name         = $("<input/>");
        var $interviewer  = $("<input id='id_interviewer_name'/>");
        var $post         = $("<input/>");
        var $dept         = $("<input/>");
        var $interview_time = $("<input />");

        $name.val( opt_data.name);
        $post.val( opt_data.post);
        $dept.val( opt_data.dept);
        $interview_time.val(opt_data.interview_time);

        $interview_time.datetimepicker( {
            lang:'ch',
            timepicker:true,
            format: "Y-m-d H:i",
            onChangeDateTime :function(){
            }
        });

        var arr=[
            ["姓名", $name] ,
            ["面试时间", $interview_time] ,
            ["面试官",   $interviewer],
            ["岗位", $post],
            ["部门", $dept],
        ];


        $.show_key_value_table("编辑", arr ,[{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax ('/ss_deal/edit_interview_remind', {
                    'name': $name.val(),
                    'id'  : opt_data.id,
                    'interview_time': $interview_time.val(),
                    'interviewer': $interviewer.val(),
                    'post': $post.val(),
                    'dept': $dept.val(),
                });
            }
        }],function(){
            $('#id_interviewer_name').val(opt_data.interviewer_id);
            $.admin_select_user( $interviewer, "admin");
        });
    });

    $('.opt-change').set_input_change_event(load_data);
});
