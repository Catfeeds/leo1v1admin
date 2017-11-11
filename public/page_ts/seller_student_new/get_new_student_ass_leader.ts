/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-get_new_student_ass_leader.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			assistantid:	$('#id_assistantid').val()
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
	$('#id_assistantid').val(g_args.assistantid);
    $.admin_select_user($("#id_assistantid"), "assistant",function(){
        load_data();
    });

    $("#id_add").on("click",function(){
        var $phone=$("<input/>");
        var $name=$("<input/>");
        var $grade=$("<select/>");
        var $subject=$("<select/>");
        var $origin=$("<input/>");
        var $admin_revisiterid=$("<input/>");
        var $origin_userid=$("<input/>");
        var arr=[
            ["姓名",  $name],
            ["电话",  $phone],
            ["年级",  $grade],
            ["科目",  $subject],
            ["渠道",  $origin],
            ["转介绍学生",  $origin_userid],
            ["助教",  $admin_revisiterid],
        ];
        Enum_map.append_option_list("grade",$grade, true);
        Enum_map.append_option_list("subject",$subject, true);

        $.show_key_value_table("新增申请", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ss_deal/add_ss_ass_new",{
                    "name" :$name.val(),
                    "phone" : $phone.val() ,
                    "admin_revisiterid" : $admin_revisiterid.val() ,
                    "grade" : $grade.val() ,
                    "subject" : $subject.val() ,
                    "origin_userid" : $origin_userid.val() ,
                    "origin" : $origin.val()
                });
            }
        },function(){
            $.admin_select_user(
                $admin_revisiterid,
                "admin", null,false,{"main_type":1});
            $.admin_select_user( $origin_userid, "student"  );
 
        });
    });


    $("#id_set_select_to_admin_list").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var select_userid_list=[];

        $(".opt-select-item").each(function(){
            var $item=$(this) ;
            if($item.iCheckValue()) {
                select_userid_list.push( $item.data("userid") ) ;
            }
        } ) ;

        var do_post= function (opt_adminid) {
            $.do_ajax(
                '/ss_deal/set_adminid',
                {
                    'userid_list' : JSON.stringify(select_userid_list ),
                    "opt_type" : 0,
                    "opt_adminid" : opt_adminid,
                });
        }

        $.admin_select_user(
            $('#id_set_select_list'),
            "admin", function(val){
                do_post( val);
            },true   );
    });


    //点击进入个人主页
    $('.opt-user').on('click', function () {
        var opt_data= $(this).get_opt_data();
        $.wopen('/stu_manage?sid=' + opt_data.userid);
    });

    $(".opt-set_ass").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $(this).admin_select_user({
            "show_select_flag" : true,
            "type"             : "assistant",
            "onChange"         : function(val){
                var id = val;
                $.do_ajax( '/stu_manage/set_assistantid',{
                    'sid'         : opt_data.userid,
                    'assistantid' : id
                });
            }
        });
    });


   






	$('.opt-change').set_input_change_event(load_data);
});
