/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage_new-lesson_record_server_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			      xmpp_server_name:	$('#id_xmpp_server_name').val(),
			      record_audio_server1:	$('#id_record_audio_server1').val()
        });
    }


	$('#id_record_audio_server1').val(g_args.record_audio_server1);
	$('#id_xmpp_server_name').val(g_args.xmpp_server_name);


    $("#id_xmpp_server_name").admin_select_dlg_ajax({
        "opt_type" :  "select", // or "list"
        "url"          : "/user_deal/get_xmpp_server_list_js",
        select_primary_field   : "server_name",
        select_display         : "server_name",
        select_no_select_value : "",
        select_no_select_title : "[全部]",

        //其他参数
        "args_ex" : {
        },
        //字段列表
        'field_list' :[
            {
            title:"ip",
            render:function(val,item) {return item.ip;}
        },{
            title:"权重",
            render:function(val,item) {return item.weights ;}
        },{
            title:"名称",
            render:function(val,item) {return item.server_name;}
        },{

            title:"说明",
            render:function(val,item) {return item.server_desc;}
        }
        ] ,
        filter_list: [],

        "auto_close"       : true,
        //选择
        "onChange"         : function(v) {
            $("id_xmpp_server_name").val(v);
            load_data();
        },
        //加载数据后，其它的设置
        "onLoadData"       : null,

    });

    $("#id_record_audio_server1").admin_select_dlg_ajax({
        "opt_type" :  "select", // or "list"
        "url"          : "/user_deal/get_record_server_list_js",
        select_primary_field   : "ip",
        select_display         : "ip",
        select_no_select_value : "",
        select_no_select_title : "[全部]",

        //其他参数
        "args_ex" : {
        },
        //字段列表
        'field_list' :[
            {
                title:"ip",
                render:function(val,item) {return item.ip;}
            },{
                title:"权重",
                render:function(val,item) {return item.priority;}
            },{
                title:"上报时间",
                render:function(val,item) {return item.last_active_time;}
            },{

                title:"说明",
                render:function(val,item) {return item.desc;}
            }
        ] ,
        filter_list: [],

        "auto_close"       : true,
        //选择
        "onChange"         : function(v) {
            $("id_record_audio_server1").val(v);
            load_data();
        },
        //加载数据后，其它的设置
        "onLoadData"       : null,

    });

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

    $("#id_set_select_list_xmpp").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var select_id_list=[];

        $(".opt-select-item").each(function(){
            var $item=$(this) ;
            if($item.iCheckValue()) {
                select_id_list.push( $item.data("id") ) ;
            }
        } ) ;

        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" :  "select", // or "list"
            "url"          : "/user_deal/get_xmpp_server_list_js",
            select_primary_field   : "server_name",
            select_display         : "server_name",
            select_no_select_value : "",
            select_no_select_title : "[全部]",

            //其他参数
            "args_ex" : {
            },
            //字段列表
            'field_list' :[
                {
                    title:"ip",
                    render:function(val,item) {return item.ip;}
                },{
                    title:"权重",
                    render:function(val,item) {return item.weights ;}
                },{
                    title:"名称",
                    render:function(val,item) {return item.server_name;}
                },{

                    title:"说明",
                    render:function(val,item) {return item.server_desc;}
                }
            ] ,
            filter_list: [],

            "auto_close"       : true,
            //选择
            "onChange"         : function(v) {
                $.do_ajax(
                    '/ajax_deal/set_xmpp_server_list',
                    {
                        'id_list' : JSON.stringify(select_id_list ),
                        "xmpp_server_name" : v,
                    });
            },
            //加载数据后，其它的设置
            "onLoadData"       : null,

        });


    });

    $("#id_set_select_list").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var select_id_list=[];

        $(".opt-select-item").each(function(){
            var $item=$(this) ;
            if($item.iCheckValue()) {
                select_id_list.push( $item.data("id") ) ;
            }
        } ) ;

        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" :  "select", // or "list"
            "url"          : "/user_deal/get_record_server_list_js",
            select_primary_field   : "ip",
            select_display         : "ip",
            select_no_select_value : "",
            select_no_select_title : "[全部]",

            //其他参数
            "args_ex" : {
            },
            //字段列表
            'field_list' :[
                {
                    title:"ip",
                    render:function(val,item) {return item.ip;}
                },{
                    title:"权重",
                    render:function(val,item) {return item.priority;}
                },{
                    title:"上报时间",
                    render:function(val,item) {return item.last_active_time;}
                },{

                    title:"说明",
                    render:function(val,item) {return item.desc;}
                }
            ] ,
            filter_list: [],

            "auto_close"       : true,
            //选择
            "onChange"         : function(v) {
                $.do_ajax(
                    '/ajax_deal/set_record_server_list',
                    {
                        'id_list' : JSON.stringify(select_id_list ),
                        "record_audio_server1" : v,
                    });
            },
            //加载数据后，其它的设置
            "onLoadData"       : null,

        });


    });

    $(".opt-lesson").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/tea_manage/lesson_list?lessonid="+opt_data.lessonid);
    });



    //$.do_ajax("");

	$('.opt-change').set_input_change_event(load_data);
});


