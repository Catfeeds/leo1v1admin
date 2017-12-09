/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info_admin-free_time.d.ts" />
$(function(){
    function load_data(){
        $.reload_self_page ( {
			      teacherid: g_args.teacherid	
        });
    }

	$('.opt-change').set_input_change_event(load_data);
    $("#cal_week tbody td").on("click",function(){
        if (!$(this).text()) {
            if ( $(this).hasClass("select_free_time")) {
                $(this).removeClass("select_free_time");
            }else{
                $(this).addClass("select_free_time");
            }

        }
    });

    var init_title=function( id_name, start_time ) {
        start_time=start_time*1;
        var th_list=$("#"+id_name+"> th");
        for ( var i=0;i<7;i++) {
            var title=$.DateFormat(start_time+i*86400, "MM-dd")+ $(th_list[i+1]).text();
            $(th_list[i+1]).text(title);
        }
    };
    var week_start_time=$("#id_week_info").data("week_start_time")*1;

    init_title("th_list_1", week_start_time );
    init_title("th_list_2", week_start_time+7*86400 );
    init_title("th_list_3", week_start_time+14*86400 );

    $("#id_update").on("click",function(){
        var free_list=[];
        get_free_table_data(free_list,"id_time_body_1", week_start_time );
        get_free_table_data(free_list,"id_time_body_2", week_start_time+7*86400 );
        get_free_table_data(free_list,"id_time_body_3", week_start_time+14*86400 );

        $.do_ajax("/teacher_info_admin/update_free_time",{
            teacherid : g_args.teacherid,
            free_time : JSON.stringify(free_list)
        });
    });

    var get_free_table_data=function(free_list, id_name, start_time ){
        $("#"+id_name+"  tr").each(function() {
            var $this=$(this);
            var timeid=$this.data("timeid");
            $this.find("td").each(function(i,item){
                if (i!=0) {//过滤１
                    var $td=$(item);
                    if ($td.hasClass("select_free_time")) {
                        var tmp_date=$.DateFormat(start_time+(i-1)*86400,"yyyy-MM-dd" );
                        free_list.push ([
                            ""+tmp_date +" "+ timeid+ ":00",
                            ""+ timeid + ":59",
                        ]);
                    }
                }
            });
        });
    };


    $.do_ajax('/teacher_info_admin/get_free_time_js',{
        'teacherid': g_args.teacherid
    },function(resp) {

        var init_table=function( id_name, start_time, data ) {
            var $tbody   = $("#"+id_name);
            var end_time = start_time + 7*86400;
            //console.log(start_time+" "+end_time);
            $.each(data,function(i,item){
                var arr = item[0].split(" ");
                var tmp_time = $.strtotime(arr[0]);
                //console.log(tmp_time);
                if (tmp_time >= start_time && tmp_time < end_time) {
                    var tmp_date = new Date(tmp_time*1000);
                    var weekid   = tmp_date.getDay() ;
                    if (weekid==0) {
                        weekid=7;
                    }
                    var timeid   = arr[1].split(":")[0];
                    var $td_list = $tbody.find( "tr[data-timeid='"+timeid+"'] td" );
                    //console.log(weekid);
                    $($td_list[weekid]).addClass("select_free_time");
                }
            });
        };
        init_table( "id_time_body_1", week_start_time, resp.data );
        init_table( "id_time_body_2", week_start_time+7*86400, resp.data );
        init_table( "id_time_body_3", week_start_time+14*86400, resp.data );
        var init_lesson=function( id_name, start_time, data ) {
            var $tbody   = $("#"+id_name);
            $.each(data,function(i,item){
                var start = item["lesson_start"];
                var end = item["lesson_end"];
                var weekid = item["week"];
                for (var i=start;i<=end;i++)
                {
                    if(i<10 && i.toString().length==1){
                        i="0"+i;
                    }
                    var $td_list = $tbody.find( "tr[data-timeid='"+i+"'] td" );
                   // console.log(weekid);
                    $($td_list[weekid]).addClass("have_lesson"); 
                }
            });
        };
        init_lesson( "id_time_body_1", week_start_time, resp.lesson );
        init_lesson( "id_time_body_2", week_start_time+7*86400, resp.lesson );
        init_lesson( "id_time_body_3", week_start_time+14*86400, resp.lesson );

        var init_test_lesson=function( id_name, start_time, data ) {
            
            var $tbody   = $("#"+id_name);
            var end_time = start_time + 7*86400;
            $.each(data,function(i,item){
                var start = item["start"];
                var end = item["end"];
                var weekid = item["week"];
                var lesson_start = item["lesson_start"];
                if (lesson_start >= start_time && lesson_start < end_time) {
                    for (var i=start;i<=end;i++)
                    {
                        if(i<10 && i.toString().length==1){
                            i="0"+i;
                        }

                        var $td_list = $tbody.find( "tr[data-timeid='"+i+"'] td" );
                        // console.log(weekid);
                        $($td_list[weekid]).addClass("have_lesson"); 
                    }
                }
            });
        };
        init_test_lesson( "id_time_body_1", week_start_time, resp.test_lesson );
        init_test_lesson( "id_time_body_2", week_start_time+7*86400, resp.test_lesson );
        init_test_lesson( "id_time_body_3", week_start_time+14*86400, resp.test_lesson );


    });

});

