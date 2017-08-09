/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-assign_sub_adminid_list.d.ts" />
function load_data(){
    $.reload_self_page ( {

        order_by_str                   : g_args.order_by_str,
        date_type:	$('#id_date_type').val(),
        opt_date_type:	$('#id_opt_date_type').val(),
			  call_phone_count:	$('#id_call_phone_count').val(),
        seller_level:	$('#id_seller_level').val(),
        publish_flag:	$('#id_publish_flag').val(),
        sys_invaild_flag:	$('#id_sys_invaild_flag').val(),
        admin_del_flag:	$('#id_admin_del_flag').val(),
        start_time:	$('#id_start_time').val(),
        account_role:	$('#id_account_role').val(),
        end_time:	$('#id_end_time').val(),
        userid:	$('#id_userid').val(),
        origin:	$('#id_origin').val(),
        origin_ex:	$('#id_origin_ex').val(),
        grade:	$('#id_grade').val(),
        origin_level:	$('#id_origin_level').val(),
        subject:	$('#id_subject').val(),
        phone_location:	$('#id_phone_location').val(),
        admin_revisiterid:	$('#id_admin_revisiterid').val(),
        first_seller_adminid:	$('#id_first_seller_adminid').val(),
        seller_student_status:	$('#id_seller_student_status').val(),
        seller_student_sub_status:	$('#id_seller_student_sub_status').val(),
        has_pad:	$('#id_has_pad').val(),
        sub_assign_adminid_2:	$('#id_sub_assign_adminid_2').val(),
        origin_assistantid:	$('#id_origin_assistantid').val(),
        tq_called_flag:	$('#id_tq_called_flag').val(),
        global_tq_called_flag:	$('#id_global_tq_called_flag').val(),
        tmk_adminid:	$('#id_tmk_adminid').val(),
        tmk_student_status:	$('#id_tmk_student_status').val(),

        seller_resource_type:	$('#id_seller_resource_type').val(),
        //wx
        wx_invaild_flag:$('#id_wx_invaild_flag').val(),
        filter_flag:	$('#id_filter_flag').val()


    });
}


$(function(){
    //实例化一个plupload上传对象
    var uploader = $.plupload_Uploader({
        browse_button : 'id_upload_xls', //触发文件选择对话框的按钮，为那个元素id
        url : '/ss_deal/upload_from_xls', //服务器端的上传页面地址
        flash_swf_url : '/js/qiniu/plupload/Moxie.swf', //swf文件，当需要使用swf方式进行上传时需要配置该参数
        silverlight_xap_url : '/js/qiniu/plupload/Moxie.xap', //silverlight文件，当需要使用silverlight方式进行上传时需要配置该参数
        filters: {
            mime_types : [ //只允许上传图片和zip文件
                { title : "xls files", extensions : "xls" },
                { title : "xlsx files", extensions : "xlsx" }
            ],
            max_file_size : '40m', //最大只能上传400kb的文件
            prevent_duplicates : true //不允许选取重复文件
        }
    });

    uploader.init();
    uploader.bind('FilesAdded',function(up, files) {
        uploader.start();
    });

    Enum_map.append_option_list("grade",$("#id_grade"));
    Enum_map.append_option_list("boolean",$("#id_admin_del_flag"));
    Enum_map.append_option_list("subject",$("#id_subject"));
    Enum_map.append_option_list("boolean",$("#id_sys_invaild_flag"));
    Enum_map.append_option_list("seller_student_status",$("#id_seller_student_status"));
    Enum_map.append_option_list("seller_student_sub_status",$("#id_seller_student_sub_status"));
    Enum_map.append_option_list("pad_type",$("#id_has_pad"));
    Enum_map.append_option_list("seller_resource_type",$("#id_seller_resource_type"));
    Enum_map.append_option_list("tq_called_flag",$("#id_tq_called_flag"));
    Enum_map.append_option_list("tq_called_flag",$("#id_global_tq_called_flag"));

    Enum_map.append_option_list("boolean",$("#id_publish_flag"));
    Enum_map.append_option_list("tmk_student_status",$("#id_tmk_student_status"));
    //wx
    Enum_map.append_option_list("boolean",$("#id_wx_invaild_flag"));
    Enum_map.append_option_list("boolean",$("#id_filter_flag"));

    $('#id_tmk_adminid').val(g_args.tmk_adminid);

    var is_assign_group=g_args.self_groupid ==-1 ;

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
    $('#id_userid').val(g_args.userid);
    $('#id_origin').val(g_args.origin);
    $('#id_origin_ex').val(g_args.origin_ex);
    $('#id_grade').val(g_args.grade);
    $('#id_subject').val(g_args.subject);
    $('#id_phone_location').val(g_args.phone_location);
    $('#id_admin_revisiterid').val(g_args.admin_revisiterid);
    $('#id_first_seller_adminid').val(g_args.first_seller_adminid);
    $('#id_seller_student_status').val(g_args.seller_student_status);
    $('#id_sys_invaild_flag').val(g_args.sys_invaild_flag);
    $('#id_seller_student_sub_status').val(g_args.seller_student_sub_status);
    $('#id_origin_level').val(g_args.origin_level);
    $('#id_seller_student_status').val(g_args.seller_student_status);
    //wx
    $('#id_wx_invaild_flag').val(g_args.wx_invaild_flag);
    $('#id_filter_flag').val(g_args.filter_flag);
	  $('#id_call_phone_count').val(g_args.call_phone_count);


    $.enum_multi_select( $('#id_origin_level'), 'origin_level', function(){load_data();},null, {
        "非S类": [0, 2 , 3,4,5 ]
    });


    $('#id_seller_level').val(g_args.seller_level);
    $.enum_multi_select( $('#id_seller_level'), 'seller_level', function(){load_data();} )

    $('#id_has_pad').val(g_args.has_pad);
    $('#id_admin_del_flag').val(g_args.admin_del_flag);
    $('#id_sub_assign_adminid_2').val(g_args.sub_assign_adminid_2);
    $('#id_seller_resource_type').val(g_args.seller_resource_type);
    $('#id_tmk_student_status').val(g_args.tmk_student_status);
    $('#id_global_tq_called_flag').val(g_args.global_tq_called_flag);
    $('#id_origin_assistantid').val(g_args.origin_assistantid);
    $('#id_tq_called_flag').val(g_args.tq_called_flag);
    $('#id_publish_flag').val(g_args.publish_flag);
    //wx
    $('#id_wx_invaild_flag').val(g_args.wx_invaild_flag);
    $('#id_filter_flag').val(g_args.filter_flag);



    $('#id_account_role').val(g_args.account_role);

    $.enum_multi_select( $('#id_account_role'), "account_role", function(){load_data();} );


    $.admin_select_user(
        $('#id_first_seller_adminid'),
        "admin", load_data ,false, {
            " main_type": 2,
            select_btn_config: [
                {
                    "label": "[已分配]",
                    "value": -2
                }, {
                    "label": "[未分配]",
                    "value": 0
                }]
        }
    );
    $.admin_select_user(
        $('#id_admin_revisiterid'),
        "admin", load_data ,false, {
            " main_type": 2,
            select_btn_config: [
                {
                    "label": "[已分配]",
                    "value": -2
                }, {
                    "label": "[未分配]",
                    "value": 0
                }]
        }
    );
    $.admin_select_user(
        $('#id_tmk_adminid'),
        "admin", load_data ,false, {
            " main_type": 2,
            select_btn_config: [
                {
                    "label": "[已分配]",
                    "value": -2
                }, {
                    "label": "[未分配]",
                    "value": 0
                }]
        }
    );

    $.admin_select_user(
        $('#id_origin_assistantid'),
        "admin", load_data ,false, {
            "main_type": 1,
            select_btn_config: [
                {
                    "label": "[是]",
                    "value": -2
                }, {
                    "label": "[不是]",
                    "value": 0
                }]
        }
    );



    $.admin_select_user(
        $('#id_sub_assign_adminid_2'),
        "admin_group_master", load_data ,false, {
            "groupid": g_args.self_groupid,
            select_btn_config: [
                {
                    "label": "[已分配]",
                    "value": -2
                }, {
                    "label": "[未分配]",
                    "value": 0
                }]
        }
    );

    $.admin_select_user(
        $('#id_userid'),
        "student", load_data);



    $('.opt-change').set_input_change_event(load_data);

    $("#id_select_all").on("click",function(){
        $(".opt-select-item").iCheck("check");
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


    $("#id_set_level_b").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var select_userid_list=[];

        $(".opt-select-item").each(function(){
            var $item=$(this) ;
            if($item.iCheckValue()) {
                select_userid_list.push( $item.data("userid") ) ;
            }
        } ) ;



        var $origin_level=$("<select/>");
        var arr=[
            ["等级",  $origin_level],
        ];
        Enum_map.append_option_list("origin_level",$origin_level, true);

        $.show_key_value_table("新增申请", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax('/ss_deal/set_level_b', {
                    'userid_list' : JSON.stringify(select_userid_list ),
                    "origin_level" : $origin_level.val(),
                });

            }
        });


    });
    $("#id_set_history_to_new").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var select_userid_list=[];

        $(".opt-select-item").each(function(){
            var $item=$(this) ;
            if($item.iCheckValue()) {
                select_userid_list.push( $item.data("userid") ) ;
            }
        } ) ;
        BootstrapDialog.confirm("要把所选 设置成新例子?!" ,function(val){
            if (val) {
                $.do_ajax('/ss_deal/set_history_to_new', {
                    'userid_list' : JSON.stringify(select_userid_list ),
                });
            }
        });
    });

    $("#id_add").on("click",function(){
        var $phone=$("<input/>");
        var $subject=$("<select/>");
        var $grade=$("<select/>");
        var $origin=$("<input/>");
        var arr=[
            ["电话",  $phone],
            ["年级",  $grade],
            ["科目",  $subject],
            ["渠道",  $origin],
        ];
        Enum_map.append_option_list("grade",$grade, true);
        Enum_map.append_option_list("subject",$subject, true);

        $.show_key_value_table("新增申请", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ss_deal/add_ss",{
                    "phone" : $phone.val() ,
                    "subject" : $subject.val() ,
                    "grade" : $grade.val() ,
                    "origin" : $origin.val()
                });
            }
        });
    });

    //点击进入个人主页
    $('.opt-user').on('click', function () {
        var opt_data= $(this).get_opt_data();
        $.wopen('/stu_manage?sid=' + opt_data.userid);
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


    $("#id_tmk_set_select_to_cc_list").on("click",function(){
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
                    "opt_type" : 3,
                    "opt_adminid" : opt_adminid,
                });
        }

        $.admin_select_user(
            $('#id_set_select_list'),
            "admin", function(val){
                do_post( val);
            },true   );
    });




    $("#id_set_select_to_tmk_list").on("click",function(){
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
                    "opt_type" : 2,
                    "opt_adminid" : opt_adminid,
                });
        }

        $.admin_select_user(
            $('#id_set_select_list'),
            "admin", function(val){
                do_post( val);
            },true   );
    });


    $("#id_set_origin_list").on("click",function(){

        var opt_data=$(this).get_opt_data();
        var select_userid_list=[];
        $(".opt-select-item").each(function(){
            var $item=$(this) ;
            if($item.iCheckValue()) {
                select_userid_list.push( $item.data("userid") ) ;
            }
        } ) ;
        $.show_input("批量设置渠道","" , function(val){
            $.do_ajax(
                '/ss_deal/set_origin_list',
                {
                    'userid_list' : JSON.stringify(select_userid_list ),
                    "origin" : val,
                });

        } );



    });

    $("#id_set_select_list").on("click",function(){
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
                    "opt_type" : g_args.self_groupid==-1?1:0,
                    "opt_adminid" : opt_adminid,
                });
        }

        if (is_assign_group) {
            $.admin_select_user (
                $('#id_set_select_list'),
                "admin_group_master", function(val){
                    do_post( val);
                } ,true, {"main_type": 2 }   );
        }else{
            $.admin_select_user(
                $('#id_set_select_list'),
                "admin_group_member", function(val){
                    do_post( val);
                },true, {"groupid": g_args.self_groupid  }   );
        }

    });

    if(is_assign_group) {
        //$("#id_admin_revisiterid").parent().parent().hide();
    }else{
        $("#id_set_select_to_admin_list").parent().hide();
        $("#id_set_select_to_tmk_list").parent().hide();
        $("#id_sub_assign_adminid_2").parent().parent().hide();
        $("#id_add").hide();
    }
    $(".opt-del").on("click",function(){
        var opt_data=$(this).get_opt_data();
        BootstrapDialog.confirm(
            "要删除? 电话:" + opt_data.phone+ " 科目:"+ opt_data.subject_str  ,
            function(val){
                if (val) {
                    $.do_ajax("/ss_deal/del_seller_student", {
                        "test_lesson_subject_id"         : opt_data.test_lesson_subject_id
                    });

                }
            });
    });

    var init_noit_btn=function( id_name, title) {
        var btn=$('#'+id_name);
        btn.tooltip({
            "title":title,
            "html":true
        });
        var value =btn.data("value");
        btn.text(value);
        if (value >0 ) {
            btn.addClass("btn-warning");
        }
    };


    var init_field_list=function() {
        $('#id_admin_revisiterid').val(-1);
        $('#id_tmk_adminid').val(-1);
        $('#id_tmk_student_status').val(-1);
        $('#id_sub_assign_adminid_2').val(-1);
        $('#id_origin_assistantid').val(-1);
        $('#id_global_tq_called_flag').val(-1);
        $('#id_seller_resource_type').val(-1);
    }
    var init_and_reload=function( set_field_func ) {
        init_field_list();
        set_field_func();
        load_data();
    };


    init_noit_btn("id_unallot",    "未分配的转介绍" );

    $("#id_unallot").on("click",function(){
        init_field_list();

        $('#id_tmk_adminid').val(-1);

        if (g_args.self_groupid >0 ) {//主管
            $("#id_origin_assistantid").val(-2);
            $('#id_admin_revisiterid').val(0);

        }else{ //
            $('#id_sub_assign_adminid_2').val(0);
            $("#id_origin_assistantid").val(-2);
            $('#id_admin_revisiterid').val(-1);
        }

        load_data();
    });

    init_noit_btn("id_by_hand_all_uncall_count",    "需要-手工分配数" );
    $("#id_by_hand_all_uncall_count").on("click",function(){
        init_field_list();
        $('#id_tmk_adminid').val(0);

        $('#id_seller_resource_type').val(0);
        $('#id_sys_invaild_flag').val(0 );
        $('#id_global_tq_called_flag').val(0 );
        $('#id_origin_level').val("0" );

        //$('#id_global_tq_called_flag').val(0);

        if (g_args.self_groupid  >0) { //主管
            $("#id_origin_assistantid").val(-1);
            $('#id_admin_revisiterid').val(0);
        }else{
            $("#id_origin_assistantid").val(-1);
            $('#id_sub_assign_adminid_2').val(0);
            $('#id_admin_revisiterid').val(0);
        }

        load_data();
    });
    init_noit_btn("id_unset_admin_revisiterid",    "未分配" );

    init_noit_btn("id_all_uncall_count",    "抢单-未拨打数" );
    $("#id_all_uncall_count").on("click",function(){
        init_field_list();
        $('#id_tmk_adminid').val(0);

        $('#id_seller_resource_type').val(0);
        $('#id_sys_invaild_flag').val(0 );
        $('#id_global_tq_called_flag').val(0 );
        $('#id_origin_level').val("1,2,3,4,5" );

        //$('#id_global_tq_called_flag').val(0);

        if (g_args.self_groupid  >0) { //主管
            $("#id_origin_assistantid").val(-1);
            $('#id_admin_revisiterid').val(0);
        }else{
            $("#id_origin_assistantid").val(-1);
            $('#id_sub_assign_adminid_2').val(0);
            $('#id_admin_revisiterid').val(0);
        }

        load_data();
    });
    init_noit_btn("id_unset_admin_revisiterid",    "未分配" );
    $("#id_unset_admin_revisiterid").on("click",function(){
        init_field_list();
        $('#id_tmk_adminid').val(0);

        $('#id_seller_resource_type').val(0);
        $('#id_sys_invaild_flag').val(0 );
        $('#id_origin_level').val("0,1,2,3,4" );

        //$('#id_global_tq_called_flag').val(0);

        if (g_args.self_groupid  >0) { //主管
            $("#id_origin_assistantid").val(-1);
            $('#id_admin_revisiterid').val(0);
        }else{
            $("#id_origin_assistantid").val(-1);
            $('#id_sub_assign_adminid_2').val(0);
            $('#id_admin_revisiterid').val(0);
        }

        load_data();
    });

    init_noit_btn("id_tmk_unallot",    "TMK未分配" );
    $("#id_tmk_unallot").on("click",function(){
        init_field_list();
        $('#id_tmk_student_status').val(3);
        $('#id_seller_resource_type').val(0);
        $('#id_origin_level').val(90); //T类
        if (g_args.self_groupid  >0) { //主管
            $('#id_admin_revisiterid').val(0);
        }else{
            $('#id_sub_assign_adminid_2').val(0);
        }

        load_data();
    });


    $("#id_tq_no_call_btn").on("click",function(){
        init_and_reload(function(){
            $('#id_global_tq_called_flag').val(0);
        });
    });



    $(".opt-telphone").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen('/tq/get_list?phone=' + opt_data.phone);

    });




    $(".opt-publish-flag").on("click",function(){
        var opt_data               = $(this).get_opt_data();
        var $seller_student_status = $("<select></selelct>");
        var $wx_invaild_flag       = $("<select></selelct>");
        //var res = JSON.stringify(opt_data);
        //alert(res);

        Enum_map.append_option_list("seller_student_status",$seller_student_status,true, need_list );
        Enum_map.append_option_list("boolean",$wx_invaild_flag,true, need_wx );

        var need_list=[];
        if (opt_data.seller_student_status==50) {
            need_list=[0,50];
        }else{
            need_list=[ opt_data.seller_student_status, 50];
        }

        $seller_student_status.val(opt_data.seller_student_status );

        var need_wx=[];
        if (opt_data.wx_invaild_flag==50) {
            need_wx=[0,50];
        }else{
            need_wx=[ opt_data.wx_invaild_flag,50];
        }

       $wx_invaild_flag.val(opt_data.wx_invaild_flag);

        var arr=[
            ["回访状态",  $seller_student_status],
            ["微信可见",  $wx_invaild_flag],


        ];

        $.show_key_value_table("设置是否公海可见", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ss_deal/set_seller_student_status",{
                    "test_lesson_subject_id" : opt_data.test_lesson_subject_id,
                    "seller_student_status" : $seller_student_status.val(),
                    "wx_invaild_flag" : $wx_invaild_flag.val()

                });
            }
        });

    });



    if ($.get_action_str()=="tmk_assign_sub_adminid_list") {
        $("#id_set_select_to_admin_list").parent().hide();

    }

    $(" .opt-reset-sys_invaild_flag").on("click",function(){

        var opt_data=$(this).get_opt_data();
        $.do_ajax("/ss_deal2/reset_sys_invaild_flag",{"userid": opt_data.userid});

    });



    $(".opt-seller-list").on("click",function(){
        var opt_data=$(this).get_opt_data();


        $(this).admin_select_dlg_ajax({
            "opt_type" :  "list", // or "list"
            "url"          : "/ss_deal2/test_subject_free_list_get_list_js",
            //其他参数
            "args_ex" : {
                "userid" : opt_data.userid
            },
            //字段列表
            'field_list' :[
                {
                    title:"生成时间",
                    render:function(val,item) {return item.add_time;}
                },{
                    title:"类型",
                    render:function(val,item) {return item.test_subject_free_type_str;}
                },{
                    title:"操作人",
                    render:function(val,item) {return item.admin_nick ;}
                },{
                    title:"说明",
                    render:function(val,item) {return item.test_subject_free_reason ;}
                }
            ] ,
            filter_list: [],

            "auto_close"       : true,
            //选择
            "onChange"         : null,
            //加载数据后，其它的设置
            "onLoadData"       : null,

        });
    });



    $('#id_set_shaixuan').on('click',function(){
        $.do_ajax("/seller_student_new/do_filter",{"filter_flag": 1},function(result){
            load_data();
        });

    });







});
