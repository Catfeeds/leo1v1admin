/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student-ass_add_student_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			ass_adminid:	$('#id_ass_adminid').val()
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
	$('#id_ass_adminid').val(g_args.ass_adminid);

    $.admin_select_user( $('#id_ass_adminid'), "admin", load_data );


	$('.opt-change').set_input_change_event(load_data);

    $("#id_add").on("click",function(){
        var $phone=$("<input/>");
        var arr=[
            ["电话"  , $phone ]
         ];
        $.show_key_value_table("新增用户", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var phone        = $.trim($phone.val());
                if (phone.length != 11  ) {
                    BootstrapDialog.alert('电话不是11位的!');
                    return;
                }
                $.do_ajax( '/user_deal/seller_student_ass_add', {
                    'phone': phone
                });

            }
        });
    });
    
    $(".opt-del").on("click",function(){
        var opt_data=$(this).get_opt_data();
	    BootstrapDialog.confirm("要删除["+opt_data.phone+":"+opt_data.nick +"]？", function(val){
            if (val) {
                $.do_ajax( '/user_deal/seller_student_ass_del', {
                    'phone'         : opt_data.phone ,
                });
            } 
        });
    });

    
    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();
        
        var  get_userid= function ( phone,grade, callback ) {
            $.do_ajax("/user_manage/get_userid_by_phone", {
                phone: phone 
            }, function (result) {
                var userid = result.userid;

                if (!userid) {
                    var phone_ex = ("" + phone).split("-")[0];
                    $.do_ajax('/login/register', {
                        'telphone': phone_ex,
                        'passwd': 123456,
                        'grade': grade 
                    },function(){
                        $.do_ajax("/user_manage/get_userid_by_phone", {
                            phone: phone 
                        }, function (result) {
                            var userid = result.userid;
                            //do function 
                            callback (userid);

                        });
                    });
                }else{
                    callback (userid);
                }
            });
        };

        get_userid ( opt_data.phone,opt_data.grade, function(userid){
            var $nick=$("<input/>");
            var $grade=$("<select/>");
            var $subject=$("<select/>");
            var $origin_userid=$("<input/>");
            var $user_desc=$("<textarea/>");
            Enum_map.append_option_list("grade",$grade,true);
            Enum_map.append_option_list("subject",$subject,true);
            $nick.val( opt_data.nick );
            $grade.val( opt_data.grade );
            $subject.val( opt_data.subject);
            $origin_userid.val( opt_data.origin_userid);
            $user_desc.val( opt_data.user_desc);
            var arr=[
                ["电话", opt_data.phone ],
                ["昵称", $nick],
                ["年级", $grade],
                ["科目", $subject],
                ["备注", $user_desc],
                ["转介绍学生", $origin_userid],
            ];

            $.show_key_value_table("修改用户", arr ,{
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    $.do_ajax( '/user_deal/seller_student_ass_set', {
                        'phone'         : opt_data.phone ,
                        'userid'        : userid,
                        'nick'          : $nick.val(),
                        'grade'         : $grade.val(),
                        'subject'       : $subject.val(),
                        'user_desc'     : $user_desc.val(),
                        'origin_userid' : $origin_userid.val(),
                        'old_user_desc' : opt_data.user_desc
                    });

                }
            } ,function (){
                $.admin_select_user( $origin_userid, "student" );
            });
        });
    });
    
    if (window.location.pathname=="/seller_student/ass_add_student_list_ass") {
        $("#id_ass_adminid").parent().parent().hide();
    }

    if (window.location.pathname=="/seller_student/ass_add_student_list_seller") {
        $("#id_ass_adminid").parent().parent().hide();
    }


});

