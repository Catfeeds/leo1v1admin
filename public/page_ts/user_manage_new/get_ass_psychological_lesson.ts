/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-get_ass_psychological_lesson.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val()
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
    //实例化一个plupload上传对象
    var uploader = $.plupload_Uploader({
        browse_button : 'id_upload_xls', //触发文件选择对话框的按钮，为那个元素id
       // url : '/ss_deal/upload_psychological_lesson_from_xls', //服务器端的上传页面地址
        url : '/ss_deal/upload_lecture_from_xls', //服务器端的上传页面地址
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

    $('#calendar').fullCalendar({
        header : {
            left   : null,
            center : null,
            right  : null
        },
        lang: 'zh-cn',
        timezone: 'local',
        weekends: true,
        height: 880,
        firstDay: 1,
        minTime:"7:00",

        defaultView: 'agendaWeek',

        events: [{}],
        timeFormat:{agenda: 'H:mm'},
        axisFormat:'H:mm',
        eventClick: function(calEvent) {
            var date_v=new Date(calEvent.start);
            //  alert(calEvent.start_time);
            // start_itme 1-12:10
            var week=date_v.getDay();
            if (week==0) {
                week=7;
            }
            var start_time= calEvent.start_time;
            var id_userid     = $("<input/>");
            

            var arr = [               
                [ "userid",   id_userid] ,
            ];

            $.show_key_value_table("排课", arr ,{
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    $.do_ajax( '/user_manage_new/set_psychological_lesson',{
                        'lesson_start'   : calEvent.lesson_start,
                        'lesson_end'   : calEvent.lesson_end,
                        "tea_list"  : calEvent.tea_list,
                        'userid'     : id_userid.val()
                    });
                }
            },function(){
                $.admin_select_user(id_userid, "student" );
            });


            
        }

    });

    $.do_ajax(
        '/user_manage_new/get_ass_psychological_lesson_detail',
        {'start_time': g_args.start_time,
         'end_time': g_args.end_time
        },
        function(data) {
           // alert(JSON.stringify(data));
            $.each(data.common_lesson_config,function(i,item){
                var common_lesson=[];
                var lesson_config={};
                lesson_config["title"]=  '老师:'+item.realname;
                /*
                  lesson_config.start = (1464235345+3600)* 1000;
                  lesson_config.end   = (1464235345+7600)* 1000;
                */
                lesson_config["start"]= item.start_time_ex;
                lesson_config["end"]= item.end_time_ex;
                lesson_config["color"]= '#17a6e8';
                lesson_config["tea_list"]= item.tea_list;
                lesson_config["start_time"]= item.start_time;
                lesson_config["lesson_start"]= item.lesson_start;
                lesson_config["lesson_end"]= item.lesson_end;

                common_lesson.push(lesson_config);
                $('#calendar').fullCalendar( 'addEventSource', common_lesson);
            });


        });


    $(".fc-day-header").each(function(){
        $(this).text(  $(this).text().split(" ")[0]);
    });
    $(".fc-widget-content  .fc-row " ).hide();
    $(".fc-widget-content  .fc-widget-header" ).hide();



	$('.opt-change').set_input_change_event(load_data);
});








