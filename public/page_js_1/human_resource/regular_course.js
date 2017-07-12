$(function(){
//==
    $("#id_teacherid").val(g_args.teacherid);
    admin_select_user($("#id_teacherid"), "teacher",function(){
        load_data();
    });
 	function load_data(){
        reload_self_page({
            teacherid : $("#id_teacherid").val()
        });
	}

    Enum_map.append_option_list("week", $("#id_week"),true);
    $("#id_add_config").on('click',function(){
        var teacherid = $("#id_teacherid").val();
        if(teacherid == -1){
            alert("请先选择老师再添加");
            return;
        }

        var id_week       = obj_copy_node("#id_week");
        var id_start_time = $("<input/>");
        var id_end_time   = $("<input/>");
        var id_userid     = $("<input/>");

        var myDate = new Date();
        //时间插件
	    id_start_time.datetimepicker({
		    datepicker:false,
		    timepicker:true,
		    format:'H:i',
		    step:30,
	        onChangeDateTime :function(){
                var end_time= parseInt(strtotime(myDate.getFullYear()+'-'+myDate.getMonth()+'-'+myDate.getDay()+' '+id_start_time.val()+':00')) + 7200;
                id_end_time.val(  DateFormat(end_time, "hh:mm"));
            }
            
	    });
        id_end_time.datetimepicker({
            datepicker:false,
            timepicker:true,
            format:'H:i',
            step:30
        });

        
        var arr                = [
            [ "星期",  id_week ] ,
            [ "开始时间",  id_start_time ] ,
            [ "结束时间",   id_end_time  ] ,
            [ "userid",   id_userid] ,
        ];
        show_key_value_table("新增常规课表", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                do_ajax( '/human_resource/otp_common_config',
                         {
                             'week'       : id_week.val(),
                             'opt_type'   : 'add',
                             "teacherid"  : g_args.teacherid, 
                             'start_time' : ""+id_week.val()+"-"+id_start_time.val(),
                             'end_time'   : id_end_time.val(),
                             'userid'     : id_userid.val()
                         },function(data){
                             if (data.ret!=0) {
                                 alert(data.info);
                             }else{
                                 window.location.reload();
                             }
                         }
                       );

			    dialog.close();

            }
        });

        id_userid.admin_select_dlg_ajax({
            "opt_type" : "select", // or "list"
            "url"      : "/user_manage/get_user_list",
            //其他参数
            "args_ex" : {
                type  :  "student"
            },

            select_primary_field   : "id",   //要拿出来的值
            select_display         : "nick",
            select_no_select_value : 0,
            select_no_select_title : "[未设置]",

            //字段列表
            'field_list' :[
                {
                    title:"userid",
                    width :50,
                    field_name:"id"
                },{
                    title:"性别",
                    render:function(val,item) {
                        return item.gender;
                    }
                },{
                    title:"昵称",
                    //width :50,
                    render:function(val,item) {
                        return item.nick;
                    }
                },{
                    title:"电话",
                    field_name:"phone"
                }

            ] ,
            //查询列表
            filter_list:[
                [
                    {
                        size_class: "col-md-4" ,
                        title :"性别",
                        type  : "select" ,
                        'arg_name' :  "gender"  ,
                        select_option_list: [ {
                            value : -1 ,
                            text :  "全部" 
                        },{
                            value :  1 ,
                            text :  "男" 
                        },{
                            value :  2 ,
                            text :  "女" 

                        }]
                    },{
                        size_class: "col-md-8" ,
                        title :"姓名/电话",
                        'arg_name' :  "nick_phone"  ,
                        type  : "input" 
                    }

                ] 
            ],
            "auto_close"       : true,
            //选择
            "onChange"         : null,
            //加载数据后，其它的设置
            "onLoadData"       : null

        });


        

    });
    $('#calendar').fullCalendar({
		header: {
            left: null, 
			center: null,
			right:null
		},	
		lang: 'zh-cn',
		timezone: 'local',
		weekends: true,
		height: 800,
		firstDay: 1,
        minTime:"8:00",

		defaultView: 'agendaWeek',

		events: [{}],
		timeFormat:{agenda: 'H:mm'},
		axisFormat:'H:mm',
        eventClick: function(calEvent) {
            var date_v=new Date(calEvent.start);
            // start_itme 1-12:10 
            var week=date_v.getDay();
            if (week==0) {
                week=7;
            }
            var start_time=""+week+"-"+DateFormat (  calEvent.start/1000,  "hh:mm" );
            //var start_time= ""+  date_v.getDay() + 
            var html_node=$('<div></div>').html(dlg_get_html_by_class('cl_dlg_change_type'));
            BootstrapDialog.show({
                title: '选择删改',
                message : html_node,
                closable: false, 
                buttons: [{
                    label: '返回',
                    action: function(dialog) {
                        dialog.close();
                    }
                }]
            }); 

//-------------------------

            $('body').on("click","#fat-edit",function(){
                do_ajax ( "/human_resource/get_eve_config", {
                    "teacherid"      : g_args.teacherid, 
                    "old_start_time" : start_time 
                },function(result){
                    var id_week       = obj_copy_node("#id_week");
                    var id_start_time = $("<input/>");
                    var id_end_time   = $("<input/>");
                    var id_userid     = $("<input/>");

                    var myDate = new Date();
                    //时间插件
	                id_start_time.datetimepicker({
		                datepicker       : false,
		                timepicker       : true,
		                format           : 'H:i',
		                step             : 30,
	                    onChangeDateTime : function(){
                            var end_time = parseInt(strtotime(myDate.getFullYear()+'-'+myDate.getMonth()+'-'+myDate.getDay()+' '+id_start_time.val()+' : 00')) + 7200;
                            id_end_time.val(  DateFormat(end_time, "hh : mm"));
                        }
                        
	                });
                    id_end_time.datetimepicker({
                        datepicker : false,
                        timepicker : true,
                        format     : 'H:i',
                        step       : 30
                    });
                    
                    var arr = [
                        [ "星期",  id_week ] ,
                        [ "开始时间",  id_start_time ] ,
                        [ "结束时间",   id_end_time  ] ,
                        [ "userid",   id_userid] ,
                        
                    ];
                    id_week.val(result.data.week);
                    id_start_time.val(result.data.start_time);
                    id_end_time.val(result.data.end_time);
                    id_userid.val(result.data.userid);

                    show_key_value_table("编辑页面", arr ,{
                        label    : '确认',
                        cssClass : 'btn-warning',
                        action   : function(dialog) {
                            var grade = id_week.val();
                            $.ajax({
                                url: '/human_resource/otp_common_config',
                                type     : 'POST',
                                dataType : 'json',
                                data     : {
                                    'opt_type'       : 'update',
                                    "teacherid"      : g_args.teacherid, 
                                    "old_start_time" : start_time, 
                                    "start_time"     : id_start_time.val(), 
                                    "end_time"       : id_end_time.val(), 
                                    "userid"         : id_userid.val(),
                                    "week"           : id_week.val()
                             	},
                                success  : function(data) {
                                    alert(data.info);
                                    if(data.ret != -1){
                                        window.location.reload();
                                    }
                                }
                            });
                        }
                    });
                    
                    id_userid.admin_select_dlg_ajax({
                        "opt_type" : "select", // or "list"
                        "url"      : "/user_manage/get_user_list",
                        //其他参数
                        "args_ex" : {
                            type  :  "student"
                        },

                        select_primary_field   : "id",   //要拿出来的值
                        select_display         : "nick",
                        select_no_select_value : 0,
                        select_no_select_title : "[未设置]",

                        //字段列表
                        'field_list' :[
                            {
                                title:"userid",
                                width :50,
                                field_name:"id"
                            },{
                                title:"性别",
                                render:function(val,item) {
                                    return item.gender;
                                }
                            },{
                                title:"昵称",
                                //width :50,
                                render:function(val,item) {
                                    return item.nick;
                                }
                            },{
                                title:"电话",
                                field_name:"phone"
                            }

                        ] ,
                        //查询列表
                        filter_list:[
                            [
                                {
                                    size_class: "col-md-4" ,
                                    title :"性别",
                                    type  : "select" ,
                                    'arg_name' :  "gender"  ,
                                    select_option_list: [ {
                                        value : -1 ,
                                        text :  "全部" 
                                    },{
                                        value :  1 ,
                                        text :  "男" 
                                    },{
                                        value :  2 ,
                                        text :  "女" 

                                    }]
                                },{
                                    size_class: "col-md-8" ,
                                    title :"姓名/电话",
                                    'arg_name' :  "nick_phone"  ,
                                    type  : "input" 
                                }

                            ] 
                        ],
                        "auto_close"       : true,
                        //选择
                        "onChange"         : null,
                        //加载数据后，其它的设置
                        "onLoadData"       : null

                    });
                });
            });

            $('body').on("click","#fat-del",function(){
                do_ajax('/human_resource/otp_common_config',
                        {
                            'opt_type'       : 'del',
                            "teacherid"      : g_args.teacherid, 
                            "old_start_time" : start_time 
                        },function(data)  {
                            if (data.ret!=0) {
                                alert(data.info);
                            }else{
                                alert("删除成功");
                                window.location.reload();
                            }
                        });

            });

        }

    });

    $.ajax({
        url: '/human_resource/get_common_config',
        type: 'POST',
        data: {'teacherid': g_args.teacherid},
        dataType: 'json',
        success: function(data) {
            //alert(JSON.stringify(data));
            $.each(data.common_lesson_config,function(i,item){
                var common_lesson=[];
                var lesson_config={};
                if(item.teacher == ''){
                    item.teacher = 'xxx';
                }
                lesson_config.title =  '学生:'+item.nick+'\n'+'老师:'+item.teacher;
                /*
                 lesson_config.start = (1464235345+3600)* 1000;
                 lesson_config.end   = (1464235345+7600)* 1000;
                 */
                lesson_config.start = item.start_time_ex;
                lesson_config.end   = item.end_time_ex;
	            lesson_config.color = '#17a6e8';

                
                common_lesson.push(lesson_config);
                $('#calendar').fullCalendar( 'addEventSource', common_lesson);
            });

        }
    });

























    
});
