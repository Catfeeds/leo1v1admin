/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-seller_student_list.d.ts" />

var init_and_reload=function(  set_func ) {
        $('#id_subject').val(-1);
        $('#id_grade').val(-1);
        $('#id_seller_student_status').val(-1);
        $("#id_phone_name").val("");
        $("#id_phone_location").val("");
        $("#id_has_pad").val(-1);
        $("#id_userid").val(-1);
        $("#id_global_tq_called_flag").val(-1);
        $("#id_seller_resource_type").val(-1);
        $("#id_origin_assistantid").val(-1);
        $("#id_success_flag").val(-1);
        $("#id_tmk_student_status").val(-1);
       // $("#id_end_class_flag").val(-1);
        $('#id_favorite_flag').val(-1);
        var now=new Date();
        var t=now.getTime()/1000;

        set_func(t);
        load_data();
};
var show_name_key="";
function init_today_new()  {
    if (g_args.date_type==4 && g_args.start_time== $.DateFormat( (new Date()).getTime()/1000 ,"yyyy-MM-dd") ) { //是查看今天的新例子

        var opt_data=$(this).get_opt_data();
        var $id_today_new_list= $("#id_today_new_list");
        var userid_list=[];
        var user_admin_assign_time_map={};
        var opt_data_map={};
        if(g_args.env_is_test==1)
            var call_button =' <button class=" call-opt-call-fail btn btn-warning  fa fa-phone fa-1x "  style="width:30%" > 未拨通</button>'+' <button class=" call-opt-call-succ btn btn-warning  fa fa-phone fa-1x "  style="width:30%" > 拨通</button>';
        else
            var call_button ='<button class=" call-opt-call-phone btn btn-warning  fa fa-phone fa-2x "  style="width:70%" > 拨打</button>';


        $(".opt-user").each( function(){
            var opt_data=$(this).get_opt_data();
            //alert (opt_data.nick);
            var edit_flag=(opt_data.nick != "");
            var $p_div=$(this).parent();


            if (  !edit_flag || ! opt_data.tq_called_flag  ) {//
                $(this).closest("tr").hide();
                userid_list.push( opt_data.userid  );
                user_admin_assign_time_map[opt_data.userid] =opt_data.admin_assign_time;
                opt_data_map[opt_data.userid] =opt_data;
                var $seller_item=$(
                    '<div class="item-'+opt_data.userid+'" style=" float: left; width:280px;display:inline-block; margin-left:20px "  >'+
                        '    <!-- DIRECT CHAT PRIMARY -->'+
                        '    <div class="box box-primary direct-chat direct-chat-primary">'+
                        '        <!-- /.box-header -->'+
                        '        <div class="box-body call-item">'+
                        '            <div class="call-item">'+
                        '                <div class="call-item-title phone " >'+
                        '                    电话'+
                        '                </div>'+
                        '                <div class="call-item-text phone  " >'+
                        opt_data.phone +  '<span class="assign_type" style="color:red; display:none;" >(奖)</span>'  +
                        '                </div>'+
                        '            </div>'+
                        '            <div class="call-item">'+
                        '                <span class="call-item-title" >'+
                        '                    地区'+
                        '                </span>'+
                        '                <span class="call-item-text " >'+
                        opt_data.phone_location +
                        '                </span>'+
                        '            </div>'+
                        ''+
                        ''+
                        '            <div class="call-item">'+
                        '                <span class="call-item-title" >'+
                        '                    年级'+
                        '                </span>'+
                        '                <span class="call-item-text " >'+
                        opt_data.grade_str +
                        '                </span>'+
                        '            </div>'+
                        ''+
                        ''+
                        ''+
                        '            <div class="call-item">'+
                        '                <span class="call-item-title" >'+
                        '                    科目'+
                        '                </span>'+
                        '                <span class="call-item-text " >'+
                        opt_data.subject_str +
                        '                </span>'+
                        '            </div>'+
                        '            <!-- Conversations are loaded here -->'+
                        '            <div class="call-item">'+
                        '                <span class="call-item-title" >'+
                        '                    设备'+
                        '                </span>'+
                        '                <span class="call-item-text " >'+
                        opt_data.has_pad_str +
                        '                </span>'+
                        '            </div>'+
                        ''+
                        '            <!-- Conversations are loaded here -->'+
                        '            <div class="call-item">'+
                        '                <button class=" call-opt-edit  btn btn-warning  fa fa-edit fa-2x" style="width:25%" title="编辑,同时同步tq" >'+
                        '                </button>'+call_button+
                        '            </div>'+
                        '            <!-- Contacts are loaded here -->'+
                        '            <!-- /.direct-chat-pane -->'+
                        '        </div>'+
                        '        <!-- /.box-body -->'+
                        '        <div class="box-footer" style="text-align:center;height:60px;"  >'+
                        '        </div>'+
                        '    </div>'+
                        '</div>');
                $seller_item.find(".call-opt-edit").on("click" ,function() {
                    $p_div.find(".opt-edit-new_new").click();

                    $.do_ajax("/ss_deal/sync_tq",{
                        "phone" : opt_data.phone,
                        "userid" : opt_data.userid,
                        "tq_called_flag" : opt_data.tq_called_flag ,
                    },function(resp){
                        if (resp.reload_flag) {
                            $.reload();
                        }
                    });
                });

                $seller_item.find(".call-opt-call-phone").on("click" ,function() {
                    $p_div.find(".opt-telphone").click();
                }) ;
                //模拟拨打失败[测试用]
                $seller_item.find(".call-opt-call-fail").on("click" ,function() {
                    $.do_ajax("/common_new/test_simulation_call",{
                        "call_flag" : 1, //模拟拨打失败
                        "phone" : opt_data.phone
                    },function(resp){
                        if (resp.result) {
                            alert('模拟拨打失败信息添加成功!');
                            $.reload();
                        }else{
                            alert('模拟拨打失败信息添加失败!');
                        }
                    });

                }) ;
                //模拟拨打成功[测试用]
                $seller_item.find(".call-opt-call-succ").on("click" ,function() {
                    $.do_ajax("/common_new/test_simulation_call",{
                        "call_flag" : 2, //模拟拨打成功
                        "phone" : opt_data.phone
                    },function(resp){
                        if (resp.result) {
                            alert('模拟拨打成功信息添加成功!');
                            $.reload();
                        }else{
                            alert('模拟拨打成功信息添加失败!');
                        }
                    });

                }) ;


                /*
                  ' <span style="color:red;font-weight: bolder; " > 未拨打 <span>' +
                  ' <span style="color:red;font-weight: bolder; " > 未拨打 <span>' +
                */
                //opt-telphone
                //opt-telphone
                if(opt_data.tq_called_flag ==2 ) { //已接通

                    var $opt_edit=$seller_item.find(".call-opt-edit");
                    $opt_edit.removeClass("btn-warning");
                    $opt_edit.attr("title","请编辑姓名");

                }
                $id_today_new_list.append($seller_item);
            }
        });

        function update_left_time(){

            $.do_ajax( "/ajax_deal3/get_new_seller_student_info",{
                "userid_list" : userid_list.join(","),
                "user_admin_assign_time_map" : JSON.stringify( user_admin_assign_time_map),
            },function(resp){
                var hold_msg="";
                if ( resp.max_hold_count <= resp.hold_count ) {
                    alert('新例子分配失败,例子库空间已满，请尽快清理');
                    hold_msg=' <span  style="color:red;">请释放不要是例子回公海,不然无法得到新例子 </span> ';
                }else if(resp.max_hold_count-resp.hold_count<=10){
                    alert('例子库空间过少，请尽快清理 已使用'+resp.hold_count+'/'+resp.max_hold_count);
                    hold_msg=' <span  style="color:red;">例子库空间过少，请尽快清理 已使用'+resp.hold_count+'/'+resp.max_hold_count+'</span> ';
                }
                if(resp.no_call_test_succ > 0 && resp.seller_student_assign_type){
                    // alert('有'+resp.no_call_test_succ+'个试听成功用户未回访,不能获得新例子,请尽快完成回访,【回访后15分钟内自动分配新例子】');
                    // init_and_reload(function(now){
                    //     $.filed_init_date_range( 1,  0, now-7*86400,  now);
                    //     $('#id_next_revisit').val(1);
                    // });
                    // return false;
                    // var url = "http://admin.leo1v1.com/seller_student_new/no_lesson_call_end_time_list?adminid="+resp.adminid;
                    // window.location.href = url;
                }
                var $title=('今天 获得新例子 <span  style="color:red;">'+ resp.new_count +'</span>个, 奖励例子 <span  style="color:red;">'+ resp.no_connected_count+'</span>个, 目前拥有例子'+ resp.hold_count+', 上限: '+ resp.max_hold_count+hold_msg);
                $id_today_new_list.find(".new_list_title").html ($title);

                $.each(resp.user_list, function(i, user_item ){
                    console.log(user_item);
                    var userid= user_item["userid"];
                    var opt_data=opt_data_map[userid];
                    var $item= $id_today_new_list.find(".item-"+userid);
                    if(user_item["seller_student_assign_from_type"]==1 ) {
                        $item.find(".assign_type").show();
                    }
                    if (user_item["show_left_time_flag"]) {
                        if (opt_data.tq_called_flag==2) {
                            $item.find(".box-footer").html("<span style=\"color:green; \" >"+ opt_data.tq_called_flag_str +" </span> ,请编辑资料" );
                        }else if(opt_data.tq_called_flag == 1){
                            $item.find(".box-footer").html("<span style=\"color:green; \" >"+ opt_data.tq_called_flag_str +" </span>");
                        }else{
                            var msg="会被系统分走,请尽快联系";
                            $item.find(".box-footer").html("剩余:<span style=\"color:red; font-weight:bolder;font-size:18px; \">"+ user_item.left_time_str+"</span><br> <span style=\"color:red; \" >"+ opt_data.tq_called_flag_str +" </span> " +msg);
                        }

                    }else{
                        $item.find(".box-footer").html("<span style=\"color:red; font-weight:bolder; \">"+ opt_data.tq_called_flag_str +"</span>");
                    }

                });


            }) ;

        }

        update_left_time();
        //一分钟请求一次剩余时间
        setInterval(update_left_time,1000*60);

    }
}


function load_data(){
    if ($.trim($("#id_phone_name").val()) != g_args.phone_name ) {
        $.do_ajax("/user_deal/set_item_list_add",{
            "item_key" :show_name_key,
            "item_name":  $.trim($("#id_phone_name").val())
        },function(){});
    }

    $.reload_self_page ( {
        date_type:  $('#id_date_type').val(),
        opt_date_type:  $('#id_opt_date_type').val(),
        start_time: $('#id_start_time').val(),
        group_seller_student_status:    $('#id_group_seller_student_status').val(),
        seller_groupid_ex:  $('#id_seller_groupid_ex').val(),
        seller_groupid_ex_new:  $('#id_seller_groupid_ex_new').val(),
        end_time:   $('#id_end_time').val(),
        userid: $('#id_userid').val(),
        success_flag:   $('#id_success_flag').val(),
        phone_name: $('#id_phone_name').val(),
        seller_student_status:  $('#id_seller_student_status').val(),
        phone_location: $('#id_phone_location').val(),
        subject:    $('#id_subject').val(),
        origin_assistant_role:  $('#id_origin_assistant_role').val(),
        has_pad:    $('#id_has_pad').val(),
        tq_called_flag: $('#id_tq_called_flag').val(),
        global_tq_called_flag: $('#id_global_tq_called_flag').val(),
        origin_assistantid: $('#id_origin_assistantid').val(),
        origin_userid:  $('#id_origin_userid').val(),
        seller_require_change_flag: $('#id_seller_require_change_flag').val(),
        tmk_student_status: $('#id_tmk_student_status').val(),
       // end_class_flag:$("#id_end_class_flag").val(),
        seller_resource_type:   $('#id_seller_resource_type').val(),
        favorite_flag:  $('#id_favorite_flag').val(),
        left_time_order:$('#id_left_time_order_flag').val(),
        next_revisit_flag:$('#id_next_revisit').val(),
    });
}




function add_subject_score(obj){
    $(obj).parent().parent().parent().append("<div class='subject_score'><div class='col-xs-12 col-md-1' ><div class='input-group'><span class='input-group-addon' style='height:34px;'>科目：</span><select name='subject_score_new_two' class='form-control' style='width:70px'></select> </div></div><div class='col-xs-3 col-md-1' style='margin:0 0 0 2.0%'><div class='input-group' style='width:90px;'><input type='text' class='form-control' name='subject_score_one_new_two' placeholder='' /></div></div><div class='col-xs-3 col-md-1' style='width:8px;margin:0.5% 3% 0 -0.5%;cursor: pointer;' ><i class='fa fa-plus' onclick='add_subject_score(this)' title='添加科目'></i></div><div class='col-xs-3 col-md-1' style='width:8px;margin:1% 2% 0 -1.5%;cursor: pointer;padding:0 0 0 0;' ><i class='fa fa-minus' onclick='del_subject_score(this)' title='删除科目'></i></div></div>");
    var id_subject_score = $(obj).parent().parent().parent().find("select[name='subject_score_new_two']").last();
    var id_grade = $(obj).parent().parent().parent().parent().parent().parent().parent().find('#id_stu_grade_new_two').val();
    if(id_grade==101 || id_grade==102 || id_grade==103 || id_grade==104 || id_grade==105 || id_grade==106){
        Enum_map.append_option_list("subject", id_subject_score, true,[0,1,2,3]);
    }else if(id_grade==201 || id_grade==202 || id_grade==203){
        Enum_map.append_option_list("subject", id_subject_score, true,[0,1,2,3,4,5,6,7,8,9,10]);
    }else if(id_grade==301 || id_grade==302 || id_grade==303){
        Enum_map.append_option_list("subject", id_subject_score, true,[0,1,2,3,4,5,6,7,8,9]);
    }
}
function del_subject_score(obj){
    $(obj).parent().parent().remove();
}
function add0(m){return m<10?'0'+m:m }

$(function(){
    var starttime = new Date().getTime()/1000; 
    function actionDo(){
    	return setInterval(function(){ 
	$("#id_tbody .time").each(function(){
            var end_time = $(this).data('endtime')-starttime;
	    if(end_time>0){
	        var day = parseInt(end_time/(24*3600)),
	        hour = parseInt((end_time-day*24*3600)/3600),
	        minue = parseInt((end_time-day*24*3600-hour*3600)/60),
		second = parseInt(end_time-day*24*3600-hour*3600-minue*60);
		$(this).html("");
		if(day>0){
		    $(this).append("<span>"+day+"天</span>");
                    if(hour>0){
		        $(this).append("<span>"+hour+"时</span>");
		    }
                    if(minue>0){
		        $(this).append("<span>"+minue+"分</span>");
	            }
		    if(second>0){
		        $(this).append("<span>"+second+"秒</span>");
		    }
		}else{
                    if(hour>0){
		        $(this).append("<span style='color:red'>"+hour+"时</span>");
		    }
                    if(minue>0){
		        $(this).append("<span style='color:red'>"+minue+"分</span>");
	            }
		    if(second>0){
		        $(this).append("<span style='color:red'>"+second+"秒</span>");
		    }
		}
	    }else{
		$(this).html("过期");
	    }
	});
	starttime++;
        },1000);
    }
    actionDo(); 

    

    show_name_key="stu_info_name_"+g_adminid;
    var status_opt_list=[];
    $.each( (""+g_args.status_list_str).split(",") ,function(){
        status_opt_list.push(parseInt(this) );
    }) ;
    if(g_args.cur_page==10000 ) {
        status_opt_list =null;
    }


    $('#id_seller_student_status').val(g_args.seller_student_status);
    $.enum_multi_select( $('#id_seller_student_status'), 'seller_student_status', function(){load_data();} )

    Enum_map.append_option_list("seller_require_change_flag",$("#id_seller_require_change_flag"),false,[1,2,3]);
    Enum_map.append_option_list("pad_type",$("#id_has_pad"));
    Enum_map.append_option_list("tq_called_flag",$("#id_tq_called_flag"));
    Enum_map.append_option_list("tq_called_flag",$("#id_global_tq_called_flag"));
    Enum_map.append_option_list("subject",$("#id_subject"));
    Enum_map.append_option_list("group_seller_student_status",$("#id_group_seller_student_status"));
    Enum_map.append_option_list("seller_resource_type",$("#id_seller_resource_type"));
    Enum_map.append_option_list("set_boolean",$("#id_success_flag"));
    Enum_map.append_option_list("account_role",$("#id_origin_assistant_role"));
    Enum_map.append_option_list("tmk_student_status",$("#id_tmk_student_status"));
    Enum_map.append_option_list("seller_favorite_flag",$("#id_favorite_flag"));

    $('#id_origin_assistant_role').val(g_args.origin_assistant_role);
    $('#id_global_tq_called_flag').val(g_args.global_tq_called_flag);
    $( "#id_phone_name" ).autocomplete({
        source: "/user_deal/get_item_list?list_flag=1&item_key="+show_name_key,
        minLength: 0,
        select: function( event, ui ) {
            $("#id_phone_name").val(ui.item.value);
            load_data();
        }
    });

    $(".opt-refresh-call").on("click",function(){
        var me=this;
        var opt_data=$(this).get_opt_data();
        if(opt_data.lessonid){
            $.do_ajax("/seller_student_new/refresh_call_end",{
                "lessonid" : opt_data.lessonid,
            },function(ret){
                if(ret){
                    if(ret == 3){
                        alert('该试听课已回访!');
                    }else{
                        alert('刷新成功!');
                        window.location.href = "http://admin.leo1v1.com/seller_student_new/deal_new_user";
                    }
                }else{
                    alert('还有试听课未回访!');
                    $(location).attr('href','http://admin.leo1v1.com/seller_student_new/no_lesson_call_end_time_list?adminid='+opt_data.admin_revisiterid);
                }
            });
        }else{
            alert('请先排试听课!');
        }
    });


    $(".opt-match-teacher").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var $teacherid= $("<input/>") ;
        var $lesson_start= $("<input/>") ;
        // alert(opt_data.grade);
        if(opt_data.grade == 100 || opt_data.grade == 200 || opt_data.grade == 300){
            alert("请先填写具体年级");
            return;
        }
        $("<div></div>").admin_select_dlg_ajax({
            "width"    : 1200,
            "opt_type" : "list",
            "url"      : "/ss_deal/get_teacher_list",
            "args_ex" : {
                "lesson_time" : opt_data.except_lesson_time,
                "subject"     : opt_data.subject,
                "grade"       : opt_data.grade
            },

            select_primary_field   : "teacherid",
            select_display         : "package_name",
            select_no_select_value : 0,
            select_no_select_title : "[未设置]",
            'field_list' : [
                {
                    title      : "teacherid",
                    width      : 50,
                    field_name : "teacherid"
                },{
                    title      : "姓名",
                    width      : 50,
                    field_name : "realname"
                },{
                    title      : "电话",
                    width :50,
                    field_name : "phone"
                },{
                    title      : "邮箱",
                    width      : 50,
                    field_name : "email"
                },{
                    title      : "科目",
                    width      : 50,
                    field_name : "subject"
                },{
                    title      : "年级",
                    width      : 50,
                    field_name : "grade"
                },{
                    title      : "剩余试听课数",
                    width      : 50,
                    field_name : "week_left_num"
                },{
                    title      : "教材",
                    width      : 200,
                    field_name : "textbook"
                }
            ] ,
            //查询列表
            filter_list : [
            ],
            "auto_close" : true,
            "onChange"   : false,
            "onLoadData" : null
        });
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
    $('#id_status_list_str').val(g_args.status_list_str);
    $('#id_userid').val(g_args.userid);
    $('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);
    $('#id_seller_groupid_ex_new').val(g_args.seller_groupid_ex_new);
    $("#id_seller_groupid_ex").init_seller_groupid_ex();
    $("#id_seller_groupid_ex_new").init_seller_groupid_ex();
    $('#id_phone_location').val(g_args.phone_location);
    $('#id_subject').val(g_args.subject);
    $('#id_has_pad').val(g_args.has_pad);
    $('#id_seller_resource_type').val(g_args.seller_resource_type);
    $('#id_origin_assistantid').val(g_args.origin_assistantid);
    $('#id_origin_userid').val(g_args.origin_userid);
    $('#id_phone_name').val(g_args.phone_name);
    $('#id_success_flag').val(g_args.success_flag);
    $('#id_group_seller_student_status').val(g_args.group_seller_student_status);
    $('#id_tq_called_flag').val(g_args.tq_called_flag);
    $('#id_seller_require_change_flag').val(g_args.seller_require_change_flag);
    $('#id_tmk_student_status').val(g_args.tmk_student_status);
    $('#id_favorite_flag').val(g_args.favorite_flag);
   // $('#id_end_class_flag').val(g_args.end_class_flag);

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
        $('#id_origin_userid'),
        "student", load_data );

    $('.opt-change').set_input_change_event(load_data);
    /*
      $.admin_select_user(
      $('#id_userid'),
      "seller_student", load_data ,false, {
      "adminid": g_args.admin_revisiterid,
      select_btn_config: [{
      "label": "[未分配]",
      "value": 0
      }]
      }
      );
    */




    $(".opt-post-test-lesson").on("click",function(){
        var me=this;
        var opt_data=$(this).get_opt_data();

        var do_add_test_lesson= function() {
            $.do_ajax("/ss_deal/get_user_info",{
                "userid"                 : opt_data.userid ,
                "test_lesson_subject_id" : opt_data.test_lesson_subject_id ,
            },function(ret){
                ret=ret.data;

                if( ret.editionid == 0) {
                    alert("没有设置教材版本!");
                    $(me).parent().find(".opt-edit-new_new").click();
                    return;
                }

                if( ret.stu_request_test_lesson_time  =="无" ) {
                    alert("没有试听时间!");
                    $(me).parent().find(".opt-edit-new_new").click();
                    return;
                }
                if(ret.subject <=0){
                    alert("没有设置科目!");
                    $(me).parent().find(".opt-edit-new_new").click();
                    return;
                }
                if(ret.new_demand_flag ==1){
                    if(ret.stu_nick=="" || ret.grade==0 || ret.gender==0 || ret.region==""  || ret.city=="" || ret.area=="" || ret.class_rank=="" || ret.academic_goal==0 || ret.test_stress==0 || ret.entrance_school_type==0 || ret.study_habit=="" || ret.character_type=="" || ret.need_teacher_style=="" || ret.intention_level==0 || ret.demand_urgency==0 || ret.quotation_reaction==0 || ret.stu_request_test_lesson_demand=="" || ret.recent_results=="" ){
                        $(me).parent().find(".opt-edit-new_new").click();
                        return;

                    }
                }

                var require_time= $.strtotime( ret.stu_request_test_lesson_time);
                var need_start_time=0;
                var now=(new Date()).getTime()/1000;
                var min_date_time="";
                var nowDayOfWeek = (new Date()).getDay();
                if ( (new Date()).getHours() <18 ) {
                    min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 08:00:00"  );
                }else{
                    if( nowDayOfWeek==5 ||  nowDayOfWeek==6){

                        min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 16:00:00"  );
                    }else{
                        min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 14:00:00"  );
                    }
                }
                need_start_time=$.strtotime(min_date_time );
                if ($.inArray( ret.grade*1, [101,102,103,104,105,106,201,202,203, 301,302,303 ]  ) == -1 ) {
                    alert("年级不对,请确认准确年级!"+ ret.grade );
                    $(me).parent().find(".opt-edit-new_new").click();
                    return;
                }

                if (require_time < need_start_time ) {
                    alert("申请时间不能早于 "+ min_date_time );
                    $(me).parent().find(".opt-edit-new_new").click();
                    return;
                    //申请时间
                }

                var id_stu_test_ipad_flag   = $("<select/>");
                var id_not_test_ipad_reason = $("<textarea>");
                var id_user_agent           = $("<div />");
                var id_grade_select         = $("<select />");

                Enum_map.append_option_list("boolean", id_stu_test_ipad_flag, true);
                Enum_map.append_option_list("grade", id_grade_select, true);

                if(ret.user_agent ==""){
                    id_user_agent.html("您还没有设备信息!");
                    id_user_agent.css("color","red");
                }else if(ret.user_agent.indexOf("ipad") <0 && ret.user_agent.indexOf("iPad")<0){
                    id_user_agent.html(ret.user_agent);
                    id_user_agent.css("color","red");
                }else{
                    id_user_agent.html(ret.user_agent);
                }

                var arr=[
                    ["姓名",  ret.stu_nick ],
                    ["年级", id_grade_select ],
                    ["科目", ret.subject_str ],
                    ["学校", ret.school ],
                    ["试听时间",  ret.stu_request_test_lesson_time ],
                    ["试听需求",  ret.stu_request_test_lesson_demand ],
                    ["机器版本",  id_user_agent ],
                    ["是否已经连线测试 ", id_stu_test_ipad_flag],
                    ["未连线测试原因", id_not_test_ipad_reason]
                ];

                id_grade_select.val(ret.grade);

                id_stu_test_ipad_flag.val(ret.stu_test_ipad_flag);
                id_not_test_ipad_reason.val(ret.not_test_ipad_reason);

                id_stu_test_ipad_flag.on("change",function(){
                    if(id_stu_test_ipad_flag.val() == 1){
                        id_not_test_ipad_reason.parent().parent().hide();
                    }else{
                        id_not_test_ipad_reason.parent().parent().show();
                    }
                });

                $.show_key_value_table("试听申请", arr, {
                    label: '确认',
                    cssClass: 'btn-warning',
                    action: function (dialog) {
                        $.do_ajax("/ss_deal/require_test_lesson", {
                            "test_lesson_subject_id"  : opt_data.test_lesson_subject_id,
                            "userid" : opt_data.userid ,
                            "stu_test_ipad_flag" : id_stu_test_ipad_flag.val(),
                            "not_test_ipad_reason" : id_not_test_ipad_reason.val(),
                            "test_stu_grade" : id_grade_select.val(),
                        },function(resp){

                            if(resp.ret !=0){
                                BootstrapDialog.alert(resp.info);
                            }else{
                                if(resp.seller_top_flag==1){
                                    if(11){
                                        var uu=40-resp.top_num;
                                        dialog.close();
                                        alert("试听申请成功,您的精排名额剩余"+uu+"个");
                                        window.location.reload();

                                    }else if(resp.top_num==29){
                                        dialog.close();
                                        BootstrapDialog.alert("试听申请成功,您的精排名额剩余10个");
                                    } else if(resp.top_num==34){
                                        dialog.close();
                                        BootstrapDialog.alert("试听申请成功,您的精排名额剩余5个");
                                    } else if(resp.top_num==38){
                                        dialog.close();
                                        BootstrapDialog.alert("试听申请成功,您的精排名额剩余1个");
                                    }else{
                                        dialog.close();
                                        window.location.reload();
                                    }


                                }else{
                                     window.location.reload();
                                }
                            }
                        });
                    }
                },function(){
                    if(id_stu_test_ipad_flag.val() == 1){
                        id_not_test_ipad_reason.parent().parent().hide();
                    }else{
                        id_not_test_ipad_reason.parent().parent().show();
                    }
                });
            });
        };

        $.do_ajax("/ajax_deal2/check_add_test_lesson",{
            "userid" : opt_data.userid
        }, function(resp){
            if (resp.ret==-1) {
                alert (resp.info);
                if (resp.flag=="goto_test_lesson_list") {
                    $.wopen("/seller_student_new/seller_get_test_lesson_list");
                }
                return;
            }
            if(!opt_data.parent_wx_openid && g_args.account_role != 12 && g_args.jack_flag !=349 && g_args.jack_flag !=99
                && g_args.jack_flag !=68 && g_args.jack_flag!=213 && g_args.jack_flag!=75 && g_args.jack_flag!=186
                && g_args.jack_flag!=944
              ){
                alert("家长未关注微信,不能提交试听课");
                $(me).parent().find(".opt-seller-qr-code").click();
                return;
            }

            $.do_ajax("/seller_student_new/test_lesson_cancle_rate",{"userid" : opt_data.userid},function(resp){
                if(g_args.account_role != 12){
                    if(resp.ret==1){
                        alert("由于您上周试听排课取消率已超过25%,为"+resp.rate+"%,本周已被限制排课,可点击排课解冻申请继续排课");
                        return;
                    }else if(resp.ret==2){
                        alert("由于您上周试听排课取消率已超过25%,为"+resp.rate+"%,今天可再排1节试听课");
                    }else if(resp.ret==3){
                        alert("您本周的取消率已达20%,大于25%下周将被限制排课,每天将只能排1节试听课,请谨慎处理");
                    }
                }
            });

            $.do_ajax("/seller_student_new/test_lesson_order_fail_list_new",{
            } ,function(ret){
                if(ret){
                    alert('您有签单失败原因未填写,请先填写完哦!');
                    var jump_url_1="/seller_student_new/test_lesson_order_fail_list_seller";
                    window.location.href = jump_url_1+"?"+"order_flag="+0;
                    return;
                }
                do_add_test_lesson();
            });
            // do_add_test_lesson();
        });
    });


    init_edit();

    $(" .opt-download-test-paper ").on("click", function () {
        var opt_data= $(this).get_opt_data();
        $.custom_show_pdf(opt_data.stu_test_paper );

    });

    $(".opt-telphone").on("click",function(){
        //
        var me=this;
        var opt_data= $(this).get_opt_data();
        var phone    = ""+ opt_data.phone;
        phone=phone.split("-")[0];

        try{
            window.navigate(
                "app:1234567@"+phone+"");
        } catch(e){

        };
        $.do_ajax_t("/ss_deal/call_ytx_phone", {
            "phone": opt_data.phone,
            "userid": opt_data.userid
        } );
        $(me).parent().find(".opt-edit-new_new_two").click();
    });


    //点击进入个人主页
    $('.opt-user').on('click', function () {
        var opt_data= $(this).get_opt_data();
        $.wopen('/stu_manage?sid=' + opt_data.userid);
    });

    $(".opt-upload-test-paper").on("click",function(){
        var $this = $(this);
        var opt_data = $this.get_opt_data();
        if (!$this.data("isset_flag") ) {
            $.custom_upload_file(
                $this.attr("id"),
                false,
                function (up, info, file) {
                    var res = $.parseJSON(info);
                    $.do_ajax('/ss_deal/set_stu_test_paper', {
                        'stu_test_paper': res.key,
                        'test_lesson_subject_id': opt_data.test_lesson_subject_id
                    });
                }, null,
                ["pdf", "zip", "rar", "png", "jpg"]);
            $this.data("isset_flag",true);
            alert("再点一下");
        }

    });

    $(".opt-get-require-list ").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" :  "list", // or "list"
            "url"          : "/ss_deal/get_require_list_js",
            //其他参数
            "args_ex" : {
                test_lesson_subject_id:opt_data.test_lesson_subject_id
            },
            //字段列表
            'field_list' :[
                {
                    title:"id",
                    render:function(val,item) {
                        return item.require_id;
                    }

                },{

                    title:"时间",
                    render:function(val,item) {
                        return item.require_time ;
                    }
                },{
                    title:"接受处理人",
                    //width :50,
                    render:function(val,item) {
                        return item.accept_admin_nick;
                    }
                },{
                    title:"是否接受",
                    //width :50,
                    render:function(val,item) {
                        return $(item.accept_flag_str );
                    }
                },{
                    title:"不接受原因",
                    //width :50,
                    render:function(val,item) {
                        return item.no_accept_reason;
                    }

                },{
                    title:"确认人",
                    //width :50,
                    render:function(val,item) {
                        return item.confirm_admin_nick;
                    }

                },{
                    title:"课程是否成功",

                    render:function(val,item) {
                        return $(item.success_flag_str);
                    }

                },{
                    title:"老师",
                    render:function(val,item) {
                        return item.teacher_nick;
                    }
                },{
                    title:"上课时间",
                    render:function(val,item) {
                        return item.lesson_start;
                    }

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



    $(".opt-undo-test-lesson").on("click",function(){
        var opt_data=$(this).get_opt_data();
        if ( opt_data.seller_student_status==200) { //未排课
            BootstrapDialog.confirm(
                opt_data.nick+":"+ opt_data.subject_str+ "要取消,",
                function(val){
                    $.do_ajax("/ss_deal/set_no_accpect",{
                        'require_id'       : opt_data.current_require_id,
                        'fail_reason'       :""
                    });
                });
        }else{
            alert("不是未排课!");
        }
    });

    $(".opt-tmk-valid").on("click",function(){

        var opt_data=$(this).get_opt_data();
        var $tmk_desc= $("<textarea/>") ;
        var $tmk_student_status= $("<select/>") ;
        Enum_map.append_option_list("tmk_student_status",$tmk_student_status,true);
        $tmk_desc.val( opt_data.tmk_desc  );
        $tmk_student_status.val( opt_data.tmk_student_status );


        var arr=[
            ["状态", $tmk_student_status ],
            ["说明", $tmk_desc ],
        ];

        $.show_key_value_table("TMK 信息", arr, {
            label: '确认',
            cssClass: 'btn-warning',
            action: function (dialog) {
                var opt_func =function() {
                    $.do_ajax("/ajax_deal2/set_tmk_valid",{
                        userid : opt_data.userid,
                        tmk_student_status: $tmk_student_status.val(),
                        tmk_student_status_old: opt_data.tmk_student_status,
                        tmk_desc: $tmk_desc.val(),
                    });
                };
                if (  $tmk_student_status.val()==3 ) {
                    BootstrapDialog.confirm(
                        "设置有效, 例子将会设置未tmk例子,不在这里出现 ",
                        function(val){
                            opt_func();
                        });
                }else{
                    opt_func();
                }

            }
        });
    });



    // james-start [暂时隐藏]
    // var set_user_free = function(opt_data){
    //     $.do_ajax("/seller_student_new/test_lesson_order_fail_list_new",{'userid':opt_data.userid} ,function(ret){
    //         if(ret){
    //             alert("回流前签单失败原因不能为'考虑中',请重新设置!");
    //             window.location.href = 'http://admin.leo1v1.com/seller_student_new/test_lesson_order_fail_list_seller?order_flag=0&userid='+opt_data.userid;
    //         }
    //     });

    //     BootstrapDialog.confirm(
    //         "设置释放到公海:" + opt_data.phone ,
    //         function(val){
    //             if (val) {
    //                 $.do_ajax("/ss_deal2/set_user_free",{
    //                     "userid" :  opt_data.userid
    //                 });
    //             }
    //         });
    // }

    // var do_submit = function(){
    //     var invalid_type = $('.invalid_type_new').val();
    //     if(invalid_type == 0){
    //         alert(1);
    //         $('.submit_type').attr('disabled','disabled');
    //     }else{
    //         $('.submit_type').removeAttr('disabled');
    //     }
    // }

    // $('.invalid_type_new').on("change",function(){
    //     do_submit();
    // });
    // james-end







    var test_arr = ['99','684','1173','1273'];

    // if($.inArray(g_adminid,test_arr)>=0){// 测试功能 [james]
    //     return ; // 临时终止
    //     $(".opt-set_user_free").on("click",function(){
    //         var opt_data=$(this).get_opt_data();

    //         var table_obj=$('<div style="text-align:center;"><div>请设置</div><select style="width:35%;" class="invalid_type_new"><option value="0">请选择状态</option><option value="1001">无效-空号</option><option value="1002">无效-停机</option><option value="1012">无效-屏蔽音</option><option value="1004">无效-不接电话</option><option value="1005">无效-秒挂</option><option value="1006">无效-无意向</option><option value="1007">无效-没时间</option><option value="1008">无效-价格贵</option><option value="1009">无效-设备问题</option><option value="1010">无效-网络问题</option><option value="1011">无效-其他</option></select><div style="color:red">回流公海需要设为无效资源</div></div>');

    //         function set_flow_check_flag(dialog,opt_data) {
    //             var checkText=$(".invalid_type_new").find("option:selected").text();
    //             $.show_input("无效资源标注", "",function( v){
    //                 $.do_ajax("/ajax_deal3/sign_phone", {
    //                     "userid" : opt_data.userid,
    //                     "adminid": g_adminid,
    //                     "type"   : 1,
    //                     "confirm_type" : $('.invalid_type_new').val()
    //                 },function(ret){
    //                     set_user_free(opt_data);
    //                 });
    //             } , $("<div style='text-align:center;'><div>是否标注为 <span style='color:red'>"+checkText+"?</span></div><div style='color:red'>提示：如经核验不符，将被罚款！</div></div>"));
    //         }

    //         var all_btn_config=[{
    //             label: '再想想',
    //             cssClass: 'btn-default ',

    //             action: function(dialog) {
    //                 dialog.close();
    //             }
    //         },{
    //             label: '提交',
    //             cssClass: 'btn-primary submit_type',
    //             action: function(dialog) {
    //                 var invalid_type = $('.invalid_type_new').val();
    //                 if(invalid_type != 0){
    //                     set_flow_check_flag(dialog,opt_data );
    //                 }else{
    //                     alert('请选择状态类型!');
    //                     return ;
    //                 }
    //             }
    //         }];

    //         BootstrapDialog.show({
    //             title: "无效资源标注",
    //             message :  table_obj ,
    //             closable: true,
    //             buttons: all_btn_config
    //         });
    //     });
    // }else{ // 原有功能
        $(".opt-set_user_free").on("click",function(){
            var opt_data = $(this).get_opt_data();
            $.do_ajax("/seller_student_new/test_lesson_order_fail_list_new",{'userid':opt_data.userid} ,function(ret){
                if(ret){
                    alert("回流前签单失败原因不能为'考虑中',请重新设置!");
                    window.location.href = 'http://admin.leo1v1.com/seller_student_new/test_lesson_order_fail_list_seller?order_flag=0&userid='+opt_data.userid;
                }
            });

            BootstrapDialog.confirm(
                "设置释放到公海:" + opt_data.phone ,
                function(val){
                    if (val) {
                        $.do_ajax("/ss_deal2/set_user_free",{
                            "userid" :  opt_data.userid
                        });
                    }
                });
        });
    // }






    if (g_args.account_seller_level !=9000 ) {
        $(".opt-tmk-valid").hide();
    }
    init_today_new();

});

function init_edit() {




    $(".opt-del").on("click",function(){
        var opt_data=$(this).get_opt_data();
        BootstrapDialog.confirm(
            "要删除? 电话:" + opt_data.phone+ " 科目:"+ opt_data.subject_str  ,
            function(val){
                if (val) {
                    $.do_ajax("/ss_deal/del_seller_student", {
                        "test_lesson_subject_id"         : opt_data.test_lesson_subject_id,
                    });

                }
            });
    });


    $(".opt-jump").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var pageid=1;
        switch (opt_data.seller_student_status ){
        case 0:
        case 2:
            pageid=0; break;

        case 1:
            pageid=1; break;


        case 100:
        case 103:
            pageid=103; break;

        case 101:
        case 102:
            pageid=101; break;

        case 110:
        case 120:
            pageid=110; break;

        case 200:
            pageid=200; break;

        case 210:
            pageid=210; break;

        case 220:
            pageid=220; break;

        case 290:
            pageid=290; break;

        case 301:
        case 302:
        case 303:
            pageid=301; break;
        case 400:
            pageid=400; break;

        case 410:
            pageid=410; break;


        }

        $.wopen("/seller_student_new/seller_student_list_"+pageid+"?no_jump=1&userid=" + opt_data.userid,true);
    });


    $(".opt-confirm").on("click",function(){
        var opt_data=$(this).get_opt_data();
        if(!( opt_data.current_lessonid )) {
            alert("还没有排课,无需确认");
            return;
        }
        var now= (new Date()).getTime()/1000;
        /*
          if ( $.strtotime(opt_data.lesson_start) >now ) {
          alert('还没上课不能确认课程!');
          return;
          }
        */

        var $fail_greater_4_hour_flag =$("<select> <option value=0>否</option> <option value=1>是</option>  </select>") ;
        var $success_flag=$("<select> <option value=0>未设置</option> <option value=1>成功</option>  <option value=2>失败</option>  </select>") ;
        var $test_lesson_fail_flag=$("<select></select>") ;
        var $fail_reason=$("<textarea></textarea>") ;
        Enum_map.append_option_list("test_lesson_fail_flag", $test_lesson_fail_flag, true );
        $success_flag.val(opt_data.success_flag );
        $fail_reason.val(opt_data.fail_reason);
        $test_lesson_fail_flag.val(opt_data.test_lesson_fail_flag);
        //$fail_greater_4_hour_flag .val(opt_data.fail_greater_4_hour_flag);
        var fail_greater_4_hour_flag=0;
        if ( $.strtotime( opt_data.lesson_start) - (new Date()).getTime()/1000  > 4*3600 ) {
            fail_greater_4_hour_flag=1;
        }



        var arr=[
            ["学生", opt_data.nick  ],
            ["老师", opt_data.teacher_nick ],
            ["上课时间", opt_data.lesson_start   ],
            ["是否成功",  $success_flag ],
            //["是否离上课4个小时以前(不付老师工资)", $fail_greater_4_hour_flag],
            ["失败类型", $test_lesson_fail_flag],
            ["失败原因", $fail_reason],
        ];

        var update_show_status =function ()  {
            var show_flag=  $success_flag.val()==2 ;
            //alert(show_flag);
            //$fail_greater_4_hour_flag.key_value_table_show( show_flag);
            $test_lesson_fail_flag.key_value_table_show( show_flag);
            $fail_reason.key_value_table_show( show_flag);
            $test_lesson_fail_flag.html("");
            if (fail_greater_4_hour_flag ==1 ) { //不付老师工资
                Enum_map.append_option_list("test_lesson_fail_flag", $test_lesson_fail_flag, true, [100] );
            }else{
                //已开课
                if ( $.strtotime( opt_data.lesson_start) < (new Date()).getTime()/1000  ) {
                    Enum_map.append_option_list("test_lesson_fail_flag", $test_lesson_fail_flag, true ,
                                                 [1,2,109,110,111,112,113] );

                }else{ //课前4小时内
                    Enum_map.append_option_list("test_lesson_fail_flag", $test_lesson_fail_flag, true ,
                                                [1,2,109,110,111,112,113]   );
                }
            }
        };

        $success_flag.on("change",update_show_status);
        //$fail_greater_4_hour_flag.on("change",update_show_status);



        $.show_key_value_table("课程确认", arr, {
            label    : '提交',
            cssClass : 'btn-danger',
            action   : function(dialog) {
                $.do_ajax("/ss_deal/confirm_test_lesson", {
                    "require_id"             : opt_data.current_require_id,
                    "success_flag"             : $success_flag.val(),
                    "fail_reason"              : $fail_reason.val(),
                    "test_lesson_fail_flag"    : $test_lesson_fail_flag.val(),
                    "fail_greater_4_hour_flag" : fail_greater_4_hour_flag,
                });
            }
        },function(){
            update_show_status();
        });

    });

    $(".opt-notify-lesson").on("click",function(){
        var opt_data=$(this).get_opt_data();
        if (opt_data.notify_lesson_flag ==0) {
            alert("上课前两天内，才可设置");
            return;
        }
        var set_flag=1;
        var title="要设置［"+opt_data.nick+"］:";

        if (opt_data.notify_lesson_flag==2)  {
            set_flag=0;
            title+="未通知";

        }else{
            title+="已通知";
            set_flag=1;
        }

        BootstrapDialog.confirm(title,function(val){
            if (val) {
                $.do_ajax("/ss_deal/seller_student_lesson_set_notify_flag",{
                    require_id: opt_data.current_require_id,
                    notify_flag: set_flag
                });
            }
        });
        //alert(opt_data.notify_lesson_flag);

    });


    //init ui
    if (g_args.cur_page==10001 || g_args.cur_page==10002  ){
        $(".opt-telphone").hide();
    }

    if ( g_args.cur_page==10001  ) {
        $("#id_origin_assistantid").parent().parent().hide();
    }else{

    }




    $(".opt-kuoke").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var $subject=$("<select/>");
        Enum_map.append_option_list("subject",$subject,true);
        var arr=[
            ["姓名",  opt_data.nick],
            ["电话", opt_data.phone ],
            ["科目", $subject],
        ];
        $.show_key_value_table("扩课", arr, {
            label: '确认',
            cssClass: 'btn-warning',
            action: function (dialog) {
                $.do_ajax("/ss_deal/seller_student_add_subject", {
                    "userid"  : opt_data.userid ,
                    "subject"  : $subject.val()
                });
            }
        });


    });
    $("#id_add").on("click",function(){
        var $origin_assistantid = $("<input/>") ;
        var $phone = $("<input/>") ;
        var $subject= $("<select/>") ;
        var $grade= $("<select/>") ;
        var $origin_userid = $("<input/>") ;
        Enum_map.append_option_list("subject",$subject,true);
        Enum_map.append_option_list("grade",$grade,true);
        var arr=[
            ["负责人(cc/助教)", $origin_assistantid ],
            ["电话", $phone ],
            ["年级", $grade],
            ["科目", $subject],
            ["介绍人", $origin_userid],
        ];

        $.show_key_value_table("新增转介绍", arr, {
            label: '确认',
            cssClass: 'btn-warning',
            action: function (dialog) {
                var phone=$.trim($phone.val());
                if (phone.length!=11) {
                    alert("手机号要11位") ;
                    return;
                }
                var origin_assistantid= $origin_assistantid.val();

                if (!(origin_assistantid>0)) {
                    alert("请选择负责人") ;
                    return;
                }

                var origin_userid=$origin_userid.val();
                if (!(origin_userid>0)) {
                    alert("请选择介绍人") ;
                    return;
                }

                $.do_ajax('/ajax_deal2/check_origin_assistantid_info',{
                    "origin_userid" : origin_userid,
                    "origin_assistantid" : origin_assistantid,
                },function(result){
                    var account_role = result.account_role;
                    var cc_flag = result.cc_flag;
                    if(account_role==1){
                        if(cc_flag==0){
                            var origin_flag=$("<select><option value= \"1\">自行跟进</option><option value= \"2\">分配CC总监</option></select>");
                            var arr=[
                                ["分配方式",origin_flag]
                            ];
                            $.show_key_value_table("选择分配方式", arr, {
                                label    : '提交',
                                cssClass : 'btn-danger',
                                action   : function(dialog) {
                                    $.do_ajax("/ss_deal/ass_add_seller_user", {
                                        "phone"         : phone,
                                        "origin_userid" : origin_userid,
                                        "origin_assistantid" : origin_assistantid,
                                        "grade"         : $grade.val(),
                                        "subject"       : $subject.val(),
                                        "origin_flag"   : origin_flag.val()
                                    });

                                }
                            });


                        }else{
                            BootstrapDialog.confirm("要新增转介绍? 手机["+phone +"] ",function(val){
                                if (val) {
                                    $.do_ajax("/ss_deal/ass_add_seller_user", {
                                        "phone"         : phone,
                                        "origin_userid" : origin_userid,
                                        "origin_assistantid" : origin_assistantid,
                                        "grade"         : $grade.val(),
                                        "subject"       : $subject.val(),
                                        "origin_flag"   : 0
                                    });
                                }
                            } );

                        }
                    }else{
                        BootstrapDialog.confirm("要新增转介绍? 手机["+phone +"] ",function(val){
                            if (val) {
                                $.do_ajax("/ss_deal/ass_add_seller_user", {
                                    "phone"         : phone,
                                    "origin_userid" : origin_userid,
                                    "origin_assistantid" : origin_assistantid,
                                    "grade"         : $grade.val(),
                                    "subject"       : $subject.val(),
                                    "origin_flag"   : 0
                                });
                            }
                        } );

                    }
                });

            }
        },function(){
            $.admin_select_user( $origin_userid, "student"  );
            $.admin_select_user( $origin_assistantid , "account"  );
        });

    });
    $(".opt-get_stu_performance").on("click",function(){
        var opt_data=$(this).get_opt_data();

        $.do_ajax('/ss_deal/get_stu_performance_for_seller',{
            "require_id":opt_data.current_require_id
        },function(result){
            var $stu_lesson_content     = $("<div></div>");
            var $stu_lesson_status      = $("<div></div>");
            var $stu_study_status       = $("<div></div>");
            var $stu_advantages         = $("<div></div>");
            var $stu_disadvantages      = $("<div></div>");
            var $stu_lesson_plan        = $("<div></div>");
            var $stu_teaching_direction = $("<div></div>");
            var $stu_advice             = $("<div></div>");

            var arr = [
                ["试听情况", $stu_lesson_content],
                ["学习态度", $stu_lesson_status],
                ["学习基础情况", $stu_study_status],
                ["学生优点", $stu_advantages],
                ["学生有待提高", $stu_disadvantages],
                ["培训计划", $stu_lesson_plan],
                ["教学方向", $stu_teaching_direction],
                ["意见,建议", $stu_advice],
            ];

            $stu_lesson_content.html(result.data.stu_lesson_content);
            $stu_lesson_status.html(result.data.stu_lesson_status);
            $stu_study_status.html(result.data.stu_study_status);
            $stu_advantages.html(result.data.stu_advantages);
            $stu_disadvantages.html(result.data.stu_disadvantages);
            $stu_lesson_plan.html(result.data.stu_lesson_plan);
            $stu_teaching_direction.html(result.data.stu_teaching_direction);
            $stu_advice.html(result.data.stu_advice);

            $.show_key_value_table("试听评价", arr);
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


    $.do_ajax( "/ss_deal/seller_noti_info",{},function(resp){

        init_noit_btn_ex("id_today_new_count",   resp.today_new_count,    "今天新例子","今天分配给你的例子","bg-red" );
        init_noit_btn("id_new_no_called_count",   resp.new_not_call_count,    "从未联系", "未回访" );
        init_noit_btn("id_no_called_count",   resp.not_call_count,    "所有未回访","新例子+公海获取例子" );
        init_noit_btn_ex("id_today_free",   resp.today_free_count,    "今日回流"," 今晚24点自动回流公海, 若需保留 请设置下次回访时间","bg-red" );

        init_noit_btn_ex("id_next_revisit",   resp.next_revisit_count, "今日需回访","试听成功+7日回访+下次回访时间设置为今日的例子","bg-red" );
        init_noit_btn("id_lesson_today",  resp.today,  "今天上课" ,"今天上课须通知数");
        init_noit_btn("id_lesson_tomorrow", resp.tomorrow, "明天上课","明天上课须通知数" );
        init_noit_btn("id_return_back_count", resp.return_back_count, "排课失败","被教务驳回 未处理的课程个数" );
        init_noit_btn("id_require_count",  resp.require_count,"预约未排","已预约未排数" );
        init_noit_btn("id_favorite_count", resp.favorite_count, "收藏夹","您收藏的例子个数" );
        init_noit_btn("id_test_no_return", resp.test_no_return, "试听未回访","试听成功未回访例子个数" );
    });

    

    $("#id_today_new_count").on("click",function(){
        $.do_ajax("/ajax_deal3/set_work_start_time",{});
        init_and_reload(function(now){
            $.filed_init_date_range( 4,  1, now,now );
            $("#id_seller_resource_type").val(0);
            $('#id_next_revisit').val(0);
        });
    });


    $("#id_no_confirm_count").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 5,  0, now-86400*14,  now);
            $("#id_success_flag").val(0);
            $('#id_next_revisit').val(0);
        });
    });


    $("#id_new_no_called_count").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 4,  0, now-86400*60 ,  now);
            // $('#id_seller_student_status').val(0);
            // $("#id_seller_resource_type").val(0);
            // $("#id_tq_called_flag").val(0);
            $("#id_global_tq_called_flag").val(0);
            $('#id_next_revisit').val(0);
        });
    });
    $("#id_tmk_new_no_called_count").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 4,  0, now-86400*60 ,  now);
            $('#id_seller_student_status').val(0);
            $('#id_tmk_student_status').val(3);
            $('#id_next_revisit').val(0);
        });
    });


    $("#id_no_called_count").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 4,  0, now-86400*60 ,  now);
            $("#id_global_tq_called_flag").val(-1);
            $('#id_seller_student_status').val(0);
            $('#id_next_revisit').val(0);
        });
    });

    $("#id_next_revisit").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 1,  0, now-7*86400,  now);
            $('#id_next_revisit').val(1);
        });
    });

    $("#id_today_free").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 1,  1, now-2*86400,   now-2*86400 );
            $('#id_next_revisit').val(0);
        });
    });


    $("#id_return_back_count").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 3,  0, now-14*86400,  now);
            $('#id_seller_student_status').val(110 );
            $('#id_next_revisit').val(0);
        });
    });

    $("#id_favorite_count").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 4,  0, now-86400*180 ,  now);
            $('#id_favorite_flag').val(1);
            $('#id_next_revisit').val(0);
        });
    });

    $("#id_require_count").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 3,  0, now-14*86400,  now);
            $('#id_seller_student_status').val(200);
            $('#id_next_revisit').val(0);
        });
    });

    $("#id_lesson_tomorrow ,#id_lesson_today").on("click",function(){
        var me=this;
        init_and_reload(function(now){
            var start_time=0 ;
            var end_time=0 ;
            if ($(me).attr("id")=="id_lesson_today") {
                start_time= now;
                end_time= now;
            }else{
                start_time= now+86400;
                end_time= now+86400;
            }
            $.filed_init_date_range( 5,  1, start_time ,  end_time);
            $('#id_next_revisit').val(0);
        });
    });



    var init_show_name_list_flag=false;


    $(".opt-seller-require").on("click",function(){
        var opt_data=$(this).get_opt_data();
        if(!( opt_data.current_lessonid )) {
            alert("还没有排课");
            return;
        }
        if(opt_data.seller_require_change_flag == 1){
            alert("已有申请在进行中");
            return;
        }
        var now= (new Date()).getTime()/1000;
        var lesson_time = $.strtotime(opt_data.lesson_start);
        if(now > lesson_time - 3600*4){

            return;
        }
        var $seller_require_change_type =$("<select></select>") ;
        var $require_change_lesson_time =$("<input></input>") ;
        var $teacherid = $("<input></input>") ;
        Enum_map.append_option_list("seller_require_change_type", $seller_require_change_type, true ,[1]);
        $require_change_lesson_time.datetimepicker( {
            lang:'ch',
            timepicker:true,
            format: "Y-m-d H:i",
            step:30,
            onChangeDateTime :function(){
            }
        });
        var arr=[
            ["申请类型", $seller_require_change_type  ],
            ["更改课程时间", $require_change_lesson_time ],
            ["更换老师",$teacherid]
        ];
        var show_field=function (jobj,show_flag) {
            if ( show_flag ) {
                jobj.parent().parent().show();
            }else{
                jobj.parent().parent().hide();
            }
        };

        $seller_require_change_type.on("change",function(){
            var val = $seller_require_change_type.val();
            if(val==1){
                show_field($require_change_lesson_time,true);
                show_field($teacherid,false);
            }else{
                show_field($require_change_lesson_time,false);
                show_field($teacherid,true);
            }
        });
        $.show_key_value_table("申请更改时间", arr, {
            label    : '提交',
            cssClass : 'btn-danger',
            action   : function(dialog) {
                $.do_ajax("/ss_deal/test_lesson_time_change", {
                    "require_id"             : opt_data.current_require_id,
                    "seller_require_change_type"  : $seller_require_change_type.val(),
                    "old_lesson_start":opt_data.lesson_start,
                    "userid":opt_data.userid,
                    "nick":opt_data.nick,
                    "teacherid":opt_data.teacherid,
                    "require_change_lesson_time"              : $require_change_lesson_time.val()

                });
            }
        },function(){
            var val = $seller_require_change_type.val();
            if(val==1){
                show_field($require_change_lesson_time,true);
                show_field($teacherid,false);
            }else{
                show_field($require_change_lesson_time,false);
                show_field($teacherid,true);
            }

            $.admin_select_user( $teacherid, "teacher");

        });

    });



    $(".opt-flow-node-list").on("click",function(){
        var opt_data=$(this).get_opt_data();

        /*
          var arr=[];
          $.show_key_value_table( "不传试卷审核进度",arr);
        */
        $.flow_show_node_list( opt_data.stu_test_paper_flowid );

        //$.flow_show_define_list( opt_data.stu_test_paper_flowid );
    });

    $(".opt-seller-green-channel").on("click",function(){
       // if(g_args.rank >=1 && g_args.rank <=10){
        var opt_data=$(this).get_opt_data();
        console.log(opt_data.current_require_id);
        if(opt_data.current_require_id <= 0){
            alert("你还没有试听申请哦!!");
            return;
        }
        if(opt_data.seller_student_status != 200){
            alert("只有预约未排的课才能申请绿色通道哦!");
            return;
        }


        var $green_channel_teacherid = $("<input></input>") ;
        var arr=[
            ["选择老师",$green_channel_teacherid]
        ];
        if(opt_data.is_test_user > 0){
            $.show_key_value_table("申请绿色通道", arr, {
                label    : '提交',
                cssClass : 'btn-danger',
                action   : function(dialog) {
                    if($green_channel_teacherid.val() <= 0){
                        alert("请选择老师!");
                        return;
                    }
                    $.do_ajax("/ss_deal/set_green_channel_teacherid", {
                        "require_id"             : opt_data.current_require_id,
                        "green_channel_teacherid":$green_channel_teacherid.val()
                    });
                }
            },function(){

                $.admin_select_user( $green_channel_teacherid, "teacher");
            });
        }else{
             $.show_key_value_table("申请绿色通道", arr, {
                label    : '提交',
                cssClass : 'btn-danger',
                action   : function(dialog) {
                    if($green_channel_teacherid.val() <= 0){
                        alert("请选择老师!");
                        return;
                    }
                    $.do_ajax("/ss_deal/set_green_channel_teacherid", {
                        "require_id"             : opt_data.current_require_id,
                        "green_channel_teacherid":$green_channel_teacherid.val()
                    });
                }
            },function(){

                $.admin_select_user( $green_channel_teacherid, "teacher");
            });
        }
               // }else{
           // alert("您没有权限进行绿色通道申请!");
            //return;
       // }

    });


    $('.opt-seller-qr-code').on("click", function(){
        var opt_data = $(this).get_opt_data();
        $.do_ajax("/stu_manage/set_stu_parent",{
            "studentid" : opt_data.userid,
            "sid" : opt_data.userid,
            "phone"     : opt_data.phone,
        },function(){

            $.do_ajax("/ajax_deal/check_parent_count_and_clean",{
                "userid" : opt_data.userid
            },function(resp){
                if (resp.ret==0) {
                    var dlg = BootstrapDialog.show({
                        title: "分享给家长-关注微信家长端 学生["+ opt_data.phone +":"+ opt_data.nick+ "]",
                        message:
                        $('<img src= "/seller_student_new/erweima?phone='+opt_data.phone+'"/>'),
                        closable: true
                    });
                }else{
                    alert(resp.info);
                }

            });
                //dlg.getModalDialog().css("width", "600px");

        });

    });
    //check power 转介绍
    if (!$.check_power(1004) ) {
        $("#id_add").hide();
    }


    $(".opt-require-commend-teacher").on("click",function(){
        var opt_data=$(this).get_opt_data();
        //alert(opt_data.grade);
        if(opt_data.stu_request_test_lesson_time_old <= 0){
            alert("你还没有设置期待试听时间!!");
            return;
        }
        if(opt_data.seller_student_status > 200){
            alert("已排课或者已试听");
            return;
        }

        var id_except_teacher = $("<textarea />") ;
        var id_textbook = $("<input />") ;
        var id_stu_request_test_lesson_demand = $("<textarea />") ;
        var id_stu_score_info = $("<input />") ;
        var id_stu_character_info = $("<input />") ;
        var arr=[
            ["学生成绩", id_stu_score_info],
            ["学生性格",id_stu_character_info],
            ["教材版本",id_textbook],
            ["试听需求",id_stu_request_test_lesson_demand],
            ["备注(特殊要求)",id_except_teacher],
        ];
        id_textbook.val(opt_data.editionid_str);
        id_stu_request_test_lesson_demand.val(opt_data.stu_request_test_lesson_demand);
        id_stu_score_info.val(opt_data.stu_score_info);
        id_stu_character_info.val(opt_data.stu_character_info);


        $.show_key_value_table("申请推荐老师", arr, {
            label    : '提交',
            cssClass : 'btn-danger',
            action   : function(dialog) {
                $.do_ajax("/user_deal/add_seller_require_commend_teacher", {
                    "except_teacher"             : id_except_teacher.val(),
                    "subject"                    : opt_data.subject,
                    "grade"                      : opt_data.grade,
                    "textbook"                   : id_textbook.val(),
                    "stu_request_test_lesson_demand" :  id_stu_request_test_lesson_demand.val(),
                    "stu_request_test_lesson_time"   : opt_data.stu_request_test_lesson_time_old,
                    "stu_request_lesson_time_info"   : opt_data.stu_request_lesson_time_info ,
                    "phone_location"                 : opt_data.phone_location ,
                    "stu_score_info"                 : id_stu_score_info.val(),
                    "stu_character_info"             : id_stu_character_info.val(),
                    "userid"                         : opt_data.userid,
                    "commend_type"                   : 2
                },function(res){
                    if(res.ret==-1){
                        BootstrapDialog.alert(res.info);
                    }else if(res.ret==1){
                        BootstrapDialog.alert(res.info,function(){
                                window.location.reload();
                        });

                    }
                });
            }
        });

    });

    $(".opt-test_lesson-review").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        var $id_phone = $("<input readonly='true' />");
        var $id_desc  = $("<textarea rows='' cols=''>");
        $.do_ajax("/seller_student_new/test_lesson_cancle_rate",{'userid':opt_data.userid,} ,function(ret){
            if(ret.ret==1){
                var arr=[
                    ["学生",  $id_phone],
                    ["申请说明",  $id_desc],
                ];

                $id_phone.val(opt_data.phone);

                $.show_key_value_table("排课申请", arr ,{
                    label: '确认',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                        $.do_ajax("/test_lesson_review/test_lesson_review_add",{
                            "userid" : opt_data.userid,
                            "review_desc"   : $id_desc.val(),
                        },function(ret){
                            if(ret==1){
                                alert('申请成功!');
                            }else if(ret==2){
                                alert('您已提交过该申请,请耐心等待审核!');
                            }else{
                                alert('限排后一周最多提交3次申请!');
                            }
                            window.location.reload();
                        })
                    }
                })
            }
        });
    });

    $(".opt-favorite").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        if(opt_data.favorite_adminid == 0){
            $.do_ajax("/ajax_deal/seller_student_new_favorite",{'userid':opt_data.userid,} ,function(ret){
                if(ret){
                    alert('收藏成功!');
                    window.location.reload();
                }
            });
        }else{
            $.do_ajax("/ajax_deal/seller_student_new_favorite_del",{'userid':opt_data.userid,} ,function(ret){
                if(ret){
                    alert('取消收藏成功!');
                    window.location.reload();
                }
            });
        }
    });

    $(".opt-call_back").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        $.do_ajax("/seller_student_new/call_back",{
            'lessonid':opt_data.lessonid,
        });
    });

    //评测卷
    $(".opt-test-paper").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        var user_id = opt_data.userid;
        var phone = opt_data.phone;
         $("<div></div>").admin_select_dlg_ajax({
            "opt_type" : "select", // or "list"
            "url"      : "/test_paper/get_papers",
            //其他参数
            "args_ex" : {
                //type  :  "teacher"
            },
            select_primary_field   : "paper_id",   //要拿出来的值
            select_display         : "paper_id",
            select_no_select_value : -1,
            select_no_select_title : "[全部]",
            width:1000,
            //字段列表
             'field_list' :[
                 {
                 title:"测试卷名称",
                 width:400,
                 render:function(val,item) {

                     var paper_url = "https://ks.wjx.top/jq/" + item.paper_id + ".aspx?sojumpparm="+item.paper_id+"-"+user_id+"-"+phone;
                     return "<a href='"+paper_url+"' target='_blank'>" + item.paper_name + "</a>";
                 }
             },
                {
                title:"科目",
                width:200,
                render:function(val,item) {
                    return item.subject_str;
                }
            },
                {
                title:"年级",
                width:200,
                render:function(val,item) {
                    return item.grade_str;
                }
            },
                {
                title:"教材版本",
                width:200,
                render:function(val,item) {
                    return item.book_str;
                }
            }
            ] ,
            //查询列表
             filter_list:[[
                 {
                size_class: "col-md-4 paper_subject" ,
                title :"科目",
                type  : "select" ,
                 'arg_name' :  "subject",
                 select_option_list: [{
                     value : -1 ,
                     text :  "全部"
                 },{
                     value : 1 ,
                     text :  "语文"
                 },{
                     value : 2,
                     text :  "数学"
                 },{
                     value : 3,
                     text :  "英语"
                 },{
                     value : 4,
                     text :  "化学"
                 },{
                     value : 5,
                     text :  "物理"
                 },{
                     value : 6,
                     text :  "生物"
                 },{
                     value : 7,
                     text :  "政治"
                 },{
                     value : 8,
                     text :  "历史"
                 },{
                     value : 9,
                     text :  "地理"
                 },{
                     value : 10,
                     text :  "科学"
                 },{
                     value : 11,
                     text :  "教育学"
                 }]
            },{
                size_class: "col-md-4 paper_grade" ,
                title :"年级",
                type  : "select" ,
                'arg_name' :  "grade"  ,
                select_option_list: [{
                    value : -1 ,
                    text :  "全部"
                },{
                    value : 101,
                    text :  "小一"
                },{
                    value : 102,
                    text :  "小二"
                },{
                    value : 103,
                    text :  "小三"
                },{
                    value : 104,
                    text :  "小四"
                },{
                    value : 105,
                    text :  "小五"
                },{
                    value : 106,
                    text :  "小六"
                },{
                    value : 201,
                    text :  "初一"
                },{
                    value : 202,
                    text :  "初二"
                },{
                    value : 203,
                    text :  "初三"
                },{
                    value : 301,
                    text :  "高一"
                },{
                    value : 302,
                    text :  "高二"
                },{
                    value : 303,
                    text :  "高三"
                } ]

            }
                 ]],
             "auto_close"       : false,
             "onChange"         : function(require_id,row_data){
                 var paper = "<div class='paper_info'>"
                 paper += "<div><span class='paper_font'>评测卷名称</span><span>"+row_data.paper_name+"</span></div>";
                 var paper_url = "https://ks.wjx.top/jq/" + row_data.paper_id + ".aspx?sojumpparm="+row_data.paper_id+"-"+user_id+"-"+phone;
                 paper += "<div><span class='paper_font'>评测卷链接</span><span><a href='"+paper_url+"' target='_blank'>"+paper_url+"</a></span></div>";
                 paper += "<div><span class='paper_font'>链接标题</span><span>理优教育【学生测评卷】</span></div>";
                 paper += "<div><span class='paper_font'>链接简介</span><span>"+row_data.paper_name+"，请认真答题，您的测评成绩将帮助我们更好地为您制定课程规划</span></div>";
                 paper += "<div style='height:250px'><span class='paper_font'>二维码</span><div id='paper_erwei'></div></div>";

                 paper += "</div>";
                 var dlg= BootstrapDialog.show({
                     title: "评测卷链接",
                     message : paper,
                     buttons: [{
                         label: '返回',
                         cssClass: 'btn-warning',
                         action: function(dialog) {
                             dialog.close();
                         }
                     }],
                     onshown: function(){
                         $('#paper_erwei').qrcode(paper_url);
                     }

                 });
                 dlg.getModalDialog().css("width", "730px");
             },
             "onLoadData"       : function(require_id,data){
             
             }
         });
    });

    //评测结果
    $(".opt-test-paper-result").on("click",function(){
        BootstrapDialog.alert("暂无测评结果");
    });

    $(".opt-edit-new_new").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var opt_obj=this;
        var click_type=2;
        edit_user_info_new(opt_data,opt_obj,click_type);
    });

    //@desn:重置试听成功未回访状态
    $('#id_reset').on('click',function(){
        $.do_ajax("/seller_student_new/reset_cc_no_return_call",{
        });
    });


    var edit_user_info_new=function(opt_data,opt_obj,click_type){
        $.do_ajax("/ss_deal/get_user_info",{
            "userid" : opt_data.userid ,
            "test_lesson_subject_id" : opt_data.test_lesson_subject_id ,
        } ,function(ret){
            var data=ret.data;
            var html_node = $.dlg_need_html_by_id( "id_dlg_post_user_info_new");
            var show_noti_info_flag=false;
            var $note_info=html_node.find(".note-info");
            var note_msg="";
            if (data.test_lesson_count >0 ) {
                show_noti_info_flag=true;
                note_msg="已有试听课:"+data.test_lesson_count +"次" ;
            }

            if (!show_noti_info_flag) {
                $note_info.hide();
            }else{
                $note_info.find("span").html( note_msg);
            }

            if( data.status !=0 ) {
                html_node.find("#id_stu_rev_info_new").removeClass("btn-primary");
                html_node.find("#id_stu_rev_info_new").addClass("btn-warning");
            }else{
                html_node.find("#id_stu_rev_info_new").addClass("btn-primary");
                html_node.find("#id_stu_rev_info_new").removeClass("btn-warning");
            }
            html_node.find("#id_send_sms").on("click",function(){
                $.do_ajax("/user_deal/get_admin_wx_info",{},function(resp){
                    var data=resp.data;
                    var xing=$.trim(data.name).substr(0,1);
                    var dlg=BootstrapDialog.show({
                        title: "发送信息内容:",
                        message : "您好，我是刚刚联系您的"+xing+"老师 ，如果您还需要申请我们的试听课，请添加一下我的微信："+data.wx_id+"。我们会尽快帮您安排，理优教育服务热线："+data.phone,
                        closable: true,
                        buttons: [{
                            label: '返回',
                            action: function(dialog) {
                                dialog.close();
                            }
                        },{
                            label: '发送',
                            cssClass: 'btn-warning',
                            action: function(dialog) {
                                $.do_ajax("/user_deal/send_seller_sms_msg", {
                                    "phone":opt_data.phone,
                                    "name":xing,
                                    "wx_id":data.wx_id,
                                    "seller_phone":data.phone,
                                },function( resp){
                                    alert("发送成功");
                                } );
                            }
                        }]
                    });


                    /*
                      BootstrapDialog.show();
                    */

                });
            });

            html_node.find("#id_stu_rev_info_new") .on("click",function(){
                $(opt_obj).parent().find(".opt-return-back-list").click();
            });
            var id_stu_nick          = html_node.find("#id_stu_nick");
            var id_par_nick          = html_node.find("#id_par_nick");
            var id_grade             = html_node.find("#id_stu_grade");
            var id_gender            = html_node.find("#id_stu_gender");
            var id_address           = html_node.find("#id_stu_addr");
            var id_subject           = html_node.find("#id_stu_subject");
            var id_status            = html_node.find("#id_stu_status");
            var id_seller_student_sub_status = html_node.find("#id_seller_student_sub_status");
            var id_user_desc         = html_node.find("#id_stu_user_desc");
            //var id_revisite_info     = html_node.find("#id_stu_revisite_info");
            var id_has_pad           = html_node.find("#id_stu_has_pad");
            var id_editionid         = html_node.find("#id_stu_editionid");
            var id_school            = html_node.find("#id_stu_school");
            var id_intention_level            = html_node.find("#id_intention_level");
            var id_next_revisit_time = html_node.find("#id_next_revisit_time");
            var id_stu_request_test_lesson_time = html_node.find("#id_stu_request_test_lesson_time");
            var id_stu_request_test_lesson_demand= html_node.find("#id_stu_request_test_lesson_demand");
          //  var id_stu_score_info = html_node.find("#id_stu_score_info");
           // var id_stu_character_info = html_node.find("#id_stu_character_info");
           // var id_stu_test_lesson_level = html_node.find("#id_stu_test_lesson_level");
            var id_stu_test_ipad_flag = html_node.find("#id_stu_test_ipad_flag");
           // var id_stu_request_test_lesson_time_info = html_node.find("#id_stu_request_test_lesson_time_info");
           // var id_stu_request_lesson_time_info = html_node.find("#id_stu_request_lesson_time_info");
            var id_advice_flag = html_node.find("#id_advice_flag");
            var id_academic_goal = html_node.find("#id_academic_goal");
            var id_test_stress = html_node.find("#id_test_stress");
            var id_entrance_school_type = html_node.find("#id_entrance_school_type");
            var id_extra_improvement = html_node.find("#id_extra_improvement");
            var id_habit_remodel = html_node.find("#id_habit_remodel");
            var id_interest_cultivation = html_node.find("#id_interest_cultivation");
            var id_study_habit = html_node.find("#id_study_habit");
            var id_interests_hobbies = html_node.find("#id_interests_hobbies");
            var id_character_type = html_node.find("#id_character_type");
            var id_need_teacher_style = html_node.find("#id_need_teacher_style");
            var id_intention_level = html_node.find("#id_intention_level");
            var id_test_paper = html_node.find("#id_test_paper");
            var id_demand_urgency = html_node.find("#id_demand_urgency");
            var id_quotation_reaction = html_node.find("#id_quotation_reaction");
            var id_revisit_info_new = html_node.find("#id_revisit_info_new");
            if(click_type==1){
                //id_revisit_info_new.hide();
            }

            html_node.find(".upload_test_paper").attr("id","id_upload_test_paper");

            html_node.find("#id_stu_reset_next_revisit_time").on("click",function(){
                id_next_revisit_time.val("");
            });
            Enum_map.append_option_list("grade", id_grade, true,[101,102,103,104,105,106,201,202,203,301,302,303]);
            Enum_map.append_option_list("pad_type", id_has_pad, true);
            Enum_map.append_option_list("subject", id_subject, true);
            Enum_map.append_option_list("boolean", id_stu_test_ipad_flag, true);
            Enum_map.append_option_list("boolean", id_advice_flag, true);
          //  Enum_map.append_option_list("test_lesson_level", id_stu_test_lesson_level, true);
            Enum_map.append_option_list("academic_goal", id_academic_goal, true);
            Enum_map.append_option_list("test_stress", id_test_stress, true);
            Enum_map.append_option_list("habit_remodel", id_habit_remodel, true);
            Enum_map.append_option_list("extra_improvement", id_extra_improvement, true);
            Enum_map.append_option_list("entrance_school_type", id_entrance_school_type, true);
            Enum_map.append_option_list("interest_cultivation", id_interest_cultivation, true);
            Enum_map.append_option_list("intention_level", id_intention_level, true);
            Enum_map.append_option_list("demand_urgency", id_demand_urgency, true);
            Enum_map.append_option_list("quotation_reaction", id_quotation_reaction, true);




           /* id_stu_request_test_lesson_time.datetimepicker( {
                lang:'ch',
                timepicker:true,
                format: "Y-m-d H:i",
                onChangeDateTime :function(){
                }
            });*/

            id_stu_request_test_lesson_time.datetimepicker({
                lang             : 'ch',
                timepicker       : true,
                format:'Y-m-d H:i',
                step             : 30,
                onGenerate       : function(){
                    check_disable_time();
                }

            });
            //检测该时间该人是否排课
            var check_disable_time = function() {

                var cur_time = id_stu_request_test_lesson_time.val();
                var cur_day = new Date(cur_time).getTime() / 1000;

                $.do_ajax("/seller_student_new/get_stu_request_test_lesson_time_by_adminid",{
                    "cur_day" : cur_day
                },function(res){
                    var ret = res.list;
                    $(ret).each(function(i){
                        var dis_time = ret[i];
                        console.log(dis_time)
                        $('.xdsoft_time').each(function(){
                            var add_attr = function(obj){
                                $(obj).css('border','1px solid red');
                                $(obj).css('background-color','#ccc');
                                $(obj).on('click',function(){
                                    BootstrapDialog.alert('你已经在该时间段内排过一节课!');
                                    return false;
                                });
                            };

                            if ( $(this).text() == dis_time ) {
                                var that = $(this);
                                var prev_that = $(this).prev();
                                var next_that = $(this).next();
                                add_attr(prev_that);
                                add_attr(that);
                                add_attr(next_that);
                            }

                        });
                    });
                });

            };


            html_node.find("#id_stu_reset_stu_request_test_lesson_time").on("click",function(){
                id_stu_request_test_lesson_time.val("");
            });

            id_study_habit.data("v",data.study_habit);
            id_study_habit.on("click",function(){
                // var study_habit= data.study_habit;
                var study_habit  = id_study_habit.data("v");
                $.do_ajax("/ss_deal2/get_stu_study_habit_list",{
                    "study_habit" : study_habit
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["num"], this["study_habit"]  ]);

                        if (this["has_study_habit"]) {
                            select_list.push (this["num"]) ;
                        }

                    });

                    $(this).admin_select_dlg({
                        header_list     : [ "id","学习习惯" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {

                            $.do_ajax("/ss_deal2/get_stu_study_habit_name",{
                                "study_habit" : JSON.stringify(select_list)
                            },function(res){
                                id_study_habit.val(res.data);
                                id_study_habit.data("v",res.data);
                            });

                            dlg.close();
                        }
                    });

                });

            });

            id_interests_hobbies.data("v",data.interests_and_hobbies);
            id_interests_hobbies.on("click",function(){
                // var interests_hobbies= data.interests_hobbies;
                var interests_hobbies  = id_interests_hobbies.data("v");
                $.do_ajax("/ss_deal2/get_stu_interests_hobbies_list",{
                    "interests_hobbies" : interests_hobbies
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["num"], this["interests_hobbies"]  ]);

                        if (this["has_interests_hobbies"]) {
                            select_list.push (this["num"]) ;
                        }

                    });

                    $(this).admin_select_dlg({
                        header_list     : [ "id","兴趣爱好" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {

                            $.do_ajax("/ss_deal2/get_stu_interests_hobbies_name",{
                                "interests_hobbies" : JSON.stringify(select_list)
                            },function(res){
                                id_interests_hobbies.val(res.data);
                                id_interests_hobbies.data("v",res.data);
                            });

                            dlg.close();
                        }
                    });

                });

            });

            id_character_type.data("v",data.character_type);
            id_character_type.on("click",function(){
                // var character_type= data.character_type;
                var character_type  = id_character_type.data("v");
                $.do_ajax("/ss_deal2/get_stu_character_type_list",{
                    "character_type" : character_type
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["num"], this["character_type"]  ]);

                        if (this["has_character_type"]) {
                            select_list.push (this["num"]) ;
                        }

                    });

                    $(this).admin_select_dlg({
                        header_list     : [ "id","性格特点" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {

                            $.do_ajax("/ss_deal2/get_stu_character_type_name",{
                                "character_type" : JSON.stringify(select_list)
                            },function(res){
                                id_character_type.val(res.data);
                                id_character_type.data("v",res.data);
                            });

                            dlg.close();
                        }
                    });

                });

            });

            id_need_teacher_style.data("v",data.need_teacher_style);
            id_need_teacher_style.on("click",function(){
                // var need_teacher_style= data.need_teacher_style;
                var need_teacher_style  = id_need_teacher_style.data("v");
                $.do_ajax("/ss_deal2/get_stu_need_teacher_style_list",{
                    "need_teacher_style" : need_teacher_style
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["num"], this["need_teacher_style"]  ]);

                        if (this["has_need_teacher_style"]) {
                            select_list.push (this["num"]) ;
                        }

                    });

                    $(this).admin_select_dlg({
                        header_list     : [ "id","老师要求" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {

                            $.do_ajax("/ss_deal2/get_stu_need_teacher_style_name",{
                                "need_teacher_style" : JSON.stringify(select_list)
                            },function(res){
                                id_need_teacher_style.val(res.data);
                                id_need_teacher_style.data("v",res.data);
                            });

                            dlg.close();
                        }
                    });

                });

            });



            var old_province = data.region;
            if(old_province == ''){
                old_province="选择省（市）";
            }

            var old_city = data.city;
            if(old_city == ''){
                old_city="选择市（区）";
            }
            var old_area = data.area;
            if(old_area == ''){
                old_city="选择区（县）";
            }



            var province = html_node.find("#province");
            var city = html_node.find("#city");
            var area = html_node.find("#area");
            var preProvince = "<option value=\"\">"+old_province+"</option>";
            var preCity = "<option value=\"\">"+old_city+"</option>";
            var preArea = "<option value=\"\">"+old_area+"</option>";
            //初始化
            province.html(preProvince);
            city.html(preCity);
            area.html(preArea);

            //文档加载完毕:即从province_city_select_Info.xml获取数据,成功之后采用
            //func_suc_getXmlProvice进行 省的 解析
            $.ajax({
                type : "GET",
                url : "/province_city_select_Info.xml",
                success : func_suc_getXmlProvice
            });

            //省 下拉选择发生变化触发的事件
            province.change(function() {
                //province.val()  : 返回是每个省对应的下标,序号从0开始
                if (province.val() != "") {
                    if(data.region != html_node.find("#province").find("option:selected").text()){
                        var preCity = "<option value=\"\">选择市（区）</option>";
                        var preArea = "<option value=\"\">选择区（县）</option>";
                    }
                    city.html(preCity);
                    area.html(preArea);

                    //根据下拉得到的省对于的下标序号,动态从从province_city_select_Info.xml获取数据,成功之后采用
                    //func_suc_getXmlProvice进行省对应的市的解析
                    $.ajax({
                        type : "GET",
                        url : "/province_city_select_Info.xml",
                        success : func_suc_getXmlCity
                    });

                }
            });

            //市 下拉选择发生变化触发的事件
            city.change(function() {
                if(data.city != html_node.find("#city").find("option:selected").text()){
                    var preArea = "<option value=\"\">选择区（县）</option>";
                }

                area.html(preArea);
                $.ajax({
                    type : "GET",
                    url : "/province_city_select_Info.xml",

                    //根据下拉得到的省、市对于的下标序号,动态从从province_city_select_Info.xml获取数据,成功之后采用
                    //func_suc_getXmlArea进行省对应的市对于的区的解析
                    success : func_suc_getXmlArea
                });
            });

            //区 下拉选择发生变化触发的事件
            area.change(function() {
                var value = province.find("option:selected").text()
                    + city.find("option:selected").text()
                    + area.find("option:selected").text();
                id_address.val(value);
                $("#txtProCity").val(value);
            });

            //解析获取xml格式文件中的prov标签,得到所有的省,并逐个进行遍历 放进下拉框中
            function func_suc_getXmlProvice(xml) {
                //jquery的查找功能
                var sheng = $(xml).find("prov");

                //jquery的遍历与查询匹配 eq功能,并将其放到下拉框中
                sheng.each(function(i) {
                    province.append("<option value=" + i + ">"
                                    + sheng.eq(i).attr("text") + "</option>");
                });
            }

            function func_suc_getXmlCity(xml) {
                var xml_sheng = $(xml).find("prov");
                var pro_num = parseInt(province.val());
                var xml_shi = xml_sheng.eq(pro_num).find("city");
                xml_shi.each(function(j) {
                    city.append("<option  value=" + j + ">"
                                + xml_shi.eq(j).attr("text") + "</option>");
                });
            }

            function func_suc_getXmlArea(xml) {
                var xml_sheng = $(xml).find("prov");
                var pro_num = parseInt(province.val());
                var xml_shi = xml_sheng.eq(pro_num).find("city");
                var city_num = parseInt(city.val());
                var xml_xianqu = xml_shi.eq(city_num).find("county");
                xml_xianqu.each(function(k) {
                    area.append("<option  value=" + k + ">"
                                + xml_xianqu.eq(k).attr("text") + "</option>");
                });
            }


            /*
              array(0,"","未回访" ),
              array(1,"","无效资源" ),
              array(2,"","未接通" ),
              array(3,"","有效-意向A档" ),
              array(4,"","有效-意向B档" ),
              array(5,"","有效-意向C档" ),
              array(6,"","已试听-待跟进" ),
              array(7,"","已试听-未签A档" ),
              array(20,"","已试听-未签B档" ),
              array(21,"","已试听-未签C档" ),
              array(8,"","已试听-已签" ),
              array(9,"test_lesson_report","试听-预约" ),
              array(10,"test_lesson_set_lesson","试听-已排课" ),
              array(11,"","试听-时间待定" ), //,有预约意向，但时间没有确定
              array(12,"","试听-时间确定" ), //
              array(13,"","试听-无法排课" ),
              array(14,"","试听-驳回" ),
              array(15,"","试听-课程取消" ),

            */


            var now=(new Date()).getTime()/1000;

            var status=data.status*1;
            var show_status_list=[];

            var cur_page= g_args.cur_page;

            show_status_list=[];

            /*
              return $this->seller_student_list_ex(0,"0,2");
              return $this->seller_student_list_ex(103,"100,103");
              return $this->seller_student_list_ex(101,"101,102");
              return $this->seller_student_list_ex(110,"110,120");
              return $this->seller_student_list_ex(200);
              return $this->seller_student_list_ex(1);
              return $this->seller_student_list_ex(210);
              return $this->seller_student_list_ex(220);
              return $this->seller_student_list_ex(290);
              return $this->seller_student_list_ex(301, "300,301,302,420");
            */

            if(opt_data.stu_type==1){
                switch ( opt_data.seller_student_status) {
                case 0:
                case  2:
                    show_status_list=[ 1,2, 100,101,102,103 ];
                    break;
                case 1:
                    show_status_list=[  100, 101,102,103 ];
                    break;
                case  60:
                    show_status_list=[ 1,2,61, 100,101,102,103 ];
                    break;
                case 61:
                    show_status_list=[1,2,60,  100, 101,102,103 ];
                    break;

                case 101:
                case  102:
                    show_status_list=[ 100, 101,102,103, 1  ];
                    break;

                case 100:case 103:
                    show_status_list=[ 1, 100, 101,102,103 ];
                    break;

                case 110:case 120:
                    show_status_list=[ 1,100, 101,102,103 ];
                    break;

                case 200:
                    show_status_list=[ ];
                    break;

                case 210:
                    show_status_list=[ 220, 290  ];
                    break;
                case 220: //待开课
                    show_status_list=[ 290,300,301,302 ];
                    break;

                case 290: //待跟进
                    show_status_list=[ 300,301,302 ];
                    break;

                case 300:case 301:case 302:   //未签
                    show_status_list=[ 300,301,302,420  ];
                    break;
                case 400:case 410:case 420: //
                    show_status_list=[  60,61 ];
                    break;
                }

            }else{
                switch ( opt_data.seller_student_status) {
                case 0:
                case  2:
                    show_status_list=[ 1,2, 100,101,102,103 ];
                    break;
                case 1:
                    show_status_list=[  100, 101,102,103 ];
                    break;
                case 101:
                case  102:
                    show_status_list=[ 100, 101,102,103, 1  ];
                    break;

                case 100:case 103:
                    show_status_list=[ 1, 100, 101,102,103 ];
                    break;

                case 110:case 120:
                    show_status_list=[ 1,100, 101,102,103 ];
                    break;

                case 200:
                    show_status_list=[ ];
                    break;

                case 210:
                    show_status_list=[ 220, 290  ];
                    break;
                case 220: //待开课
                    show_status_list=[ 290,300,301,302 ];
                    break;

                case 290: //待跟进
                    show_status_list=[ 300,301,302 ];
                    break;

                case 300:case 301:case 302:case 420 :   //未签
                    show_status_list=[ 300,301,302,420  ];
                    break;
                case 400:case 410:case 420: //
                    show_status_list=[   ];
                    break;
                }

            }

            show_status_list.push(status);

            Enum_map.append_option_list("seller_student_status", id_status ,true , show_status_list );
            Enum_map.append_option_list("gender", id_gender, true);
            Enum_map.append_option_list("region_version", id_editionid, true);

            id_stu_nick.val(data.stu_nick);
            id_par_nick.val(data.par_nick);
            id_grade.val(data.grade);
            id_gender.val(data.gender);
            id_address.val(data.address);
            id_subject.val(data.subject);
            id_status.val(data.status);
            id_user_desc.val(data.user_desc);
           // id_revisite_info.val(data.revisite_info);
            id_has_pad.val(data.has_pad);
            id_school.val(data.school);
            id_editionid.val(data.editionid);
            id_next_revisit_time.val(data.next_revisit_time);
            html_node.find("#id_class_rank").val(data.class_rank);
            html_node.find("#id_grade_rank").val(data.grade_rank);
            html_node.find("#id_academic_goal").val(data.academic_goal);
            html_node.find("#id_test_stress").val(data.test_stress);
            html_node.find("#id_entrance_school_type").val(data.entrance_school_type);
            html_node.find("#id_interest_cultivation").val(data.interest_cultivation);
            html_node.find("#id_extra_improvement").val(data.extra_improvement);
            html_node.find("#id_habit_remodel").val(data.habit_remodel);
            html_node.find("#id_study_habit").val(data.study_habit);
            html_node.find("#id_interests_hobbies").val(data.interests_and_hobbies);
            html_node.find("#id_character_type").val(data.character_type);
            html_node.find("#id_need_teacher_style").val(data.need_teacher_style);
            html_node.find("#id_intention_level").val(data.intention_level);
            html_node.find("#id_demand_urgency").val(data.demand_urgency);
            html_node.find("#id_quotation_reaction").val(data.quotation_reaction);
            html_node.find("#id_recent_results").val(data.recent_results);
            html_node.find("#id_advice_flag").val(data.advice_flag);
            html_node.find("#id_test_paper").val(data.stu_test_paper);
            if(!data.knowledge_point_location ){
                html_node.find("#id_knowledge_point_location").val(data.stu_request_test_lesson_demand);
            }else{
                html_node.find("#id_knowledge_point_location").val(data.knowledge_point_location);
            }



            var reset_seller_student_status_options=function()  {
                var opt_list=[0];
                var desc_map=g_enum_map["seller_student_sub_status"]["desc_map"];
                var seller_student_status=  parseInt( id_status.val());
                $.each(desc_map, function(k,v){
                    if(k>0 ) {
                        if (  Math.floor(k/1000) == seller_student_status ){
                            opt_list.push(parseInt(k));
                        }
                    }
                });
                id_seller_student_sub_status.html("");
                Enum_map.append_option_list("seller_student_sub_status", id_seller_student_sub_status,true, opt_list );
            };

            reset_seller_student_status_options();
            id_seller_student_sub_status.val(data.seller_student_sub_status);
            id_status.on("change",function(){
                reset_seller_student_status_options();
            });


            id_stu_request_test_lesson_time.val(data.stu_request_test_lesson_time);
            id_stu_request_test_lesson_demand.val(data.stu_request_test_lesson_demand );
           // id_stu_score_info.val(data.stu_score_info);
           // id_stu_test_lesson_level.val(data.stu_test_lesson_level);
            id_stu_test_ipad_flag.val(data.stu_test_ipad_flag);
           // id_stu_character_info.val(data.stu_character_info);

            id_next_revisit_time.datetimepicker( {
                lang:'ch',
                timepicker:true,
                format: "Y-m-d H:i",
                onChangeDateTime :function(){
                }
            });
            var origin=data.origin;
            if (  /bm_/.test(origin) ||
                  /bw_/.test(origin) ||
                  /baidu/.test(origin)
               ) {
                //origin="百度:"+ origin;
                origin="百度";
            }

            var title= '用户信息['+opt_data.phone+':'+opt_data.phone_location+']';
            // if( g_args.account_seller_level >=100  && g_args.account_seller_level<400 ) { //S,A, B
            //     title= title+"-渠道:["+origin+"]";
            // }

            if(click_type == 1){
                if(html_node.find("#id_stu_editionid").val() == 0){
                    html_node.find("#id_stu_editionid").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_stu_request_test_lesson_time").val() == 0){
                    html_node.find("#id_stu_request_test_lesson_time").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_stu_subject").val() <= 0){
                    html_node.find("#id_stu_subject").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_stu_request_test_lesson_time").val() == '无'){
                    html_node.find("#id_stu_request_test_lesson_time").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }else{
                    var require_time= $.strtotime(html_node.find("#id_stu_request_test_lesson_time").val());
                    var need_start_time=0;
                    var now=(new Date()).getTime()/1000;
                    var min_date_time="";
                    var nowDayOfWeek = (new Date()).getDay();
                    if ( (new Date()).getHours() <18 ) {
                        min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 08:00:00"  );
                    }else{
                        if( nowDayOfWeek==5 ||  nowDayOfWeek==6){
                            min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 16:00:00"  );
                        }else{
                            min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 14:00:00"  );
                        }
                    }
                    need_start_time=$.strtotime(min_date_time );
                    if (require_time < need_start_time ) {
                        html_node.find("#id_stu_request_test_lesson_time").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                    }
                }
                if(html_node.find("#id_stu_nick").val() == ''){
                    html_node.find("#id_stu_nick").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_stu_grade").val() <= 0){
                    html_node.find("#id_stu_grade").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_stu_gender").val() == 0){
                    html_node.find("#id_stu_gender").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(data.region == ''){
                    html_node.find("#province").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(data.city == ''){
                    html_node.find("#city").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(data.area == ''){
                    html_node.find("#area").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_class_rank").val() == ''){
                    html_node.find("#id_class_rank").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_grade_rank").val() == ''){
                    html_node.find("#id_grade_rank").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_academic_goal").val() <= 0){
                    html_node.find("#id_academic_goal").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_test_stress").val() <= 0){
                    html_node.find("#id_test_stress").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_entrance_school_type").val() <= 0){
                    html_node.find("#id_entrance_school_type").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_entrance_school_type").val() <= 0){
                    html_node.find("#id_entrance_school_type").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_study_habit").val() == ''){
                    html_node.find("#id_study_habit").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_character_type").val() == ''){
                    html_node.find("#id_character_type").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_need_teacher_style").val() == ''){
                    html_node.find("#id_need_teacher_style").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_intention_level").val() <= 0){
                    html_node.find("#id_intention_level").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                // if(html_node.find("#id_demand_urgency").val() <= 0){
                //     html_node.find("#id_demand_urgency").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                // }
                // if(html_node.find("#id_quotation_reaction").val() <= 0){
                //     html_node.find("#id_quotation_reaction").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                // }
                if(html_node.find("#id_stu_request_test_lesson_demand").val() == ''){
                    html_node.find("#id_stu_request_test_lesson_demand").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_recent_results").val() == ''){
                    html_node.find("#id_recent_results").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
            }





            var dlg=BootstrapDialog.show({
                title:  title,
                size: "size-wide",
                message : html_node,
                closable: false,
                buttons: [{
                    label: '返回',
                    action: function(dialog) {
                        dialog.close();
                    }
                },{
                    label: '提交',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                        if (  id_seller_student_sub_status.find("option").length>1  && id_seller_student_sub_status.val()=="0" ) {
                            alert("请选择回访状态的子分类");
                            return;
                        }

                        var region = html_node.find("#province").find("option:selected").text();
                        var province = html_node.find("#province").val();
                        var city = html_node.find("#city").find("option:selected").text();
                        var area = html_node.find("#area").find("option:selected").text();
                        // alert(province);
                        if(province==""){
                            region="";
                            city="";
                            area="";
                        }
                        if(html_node.find("#city").val()==""){
                             city="";
                        }
                        if(html_node.find("#area").val()==""){
                            area="";
                        }
                        $.do_ajax("/ss_deal/save_user_info_new",{
                            new_demand_flag   : 1,
                            click_type        : click_type,
                            userid            : opt_data.userid,
                            test_lesson_subject_id : opt_data.test_lesson_subject_id,
                            phone: opt_data.phone,
                            stu_nick      : id_stu_nick.val(),
                            par_nick      : id_par_nick.val(),
                            grade         : id_grade.val(),
                            gender        : id_gender.val(),
                            address       : id_address.val(),
                            subject       : id_subject.val(),
                            seller_student_status : id_status.val(),
                            seller_student_sub_status : id_seller_student_sub_status.val(),
                            user_desc     : id_user_desc.val(),
                           // revisite_info : id_revisite_info.val(),
                            next_revisit_time : id_next_revisit_time.val(),
                            editionid : id_editionid.val(),
                            school: id_school.val(),
                            stu_request_test_lesson_time:id_stu_request_test_lesson_time.val(),
                            stu_request_test_lesson_demand:id_stu_request_test_lesson_demand.val(),
                           // stu_score_info:id_stu_score_info.val(),
                           // stu_test_lesson_level:id_stu_test_lesson_level.val(),
                            stu_test_ipad_flag:id_stu_test_ipad_flag.val(),
                          //  stu_character_info:id_stu_character_info.val(),
                          //  stu_request_test_lesson_time_info:id_stu_request_test_lesson_time_info.data("v"),
                          //  stu_request_lesson_time_info:id_stu_request_lesson_time_info.data("v"),
                            has_pad       : id_has_pad.val(),
                            intention_level       : id_intention_level.val(),
                            class_rank: html_node.find("#id_class_rank").val(),
                            grade_rank: html_node.find("#id_grade_rank").val(),
                            academic_goal: html_node.find("#id_academic_goal").val(),
                            test_stress: html_node.find("#id_test_stress").val(),
                            entrance_school_type: html_node.find("#id_entrance_school_type").val(),
                            interest_cultivation: html_node.find("#id_interest_cultivation").val(),
                            extra_improvement : html_node.find("#id_extra_improvement").val(),
                            habit_remodel: html_node.find("#id_habit_remodel").val(),
                            study_habit : html_node.find("#id_study_habit").val(),
                            interests_and_hobbies: html_node.find("#id_interests_hobbies").val(),
                            character_type: html_node.find("#id_character_type").val(),
                            need_teacher_style: html_node.find("#id_need_teacher_style").val(),
                            demand_urgency: html_node.find("#id_demand_urgency").val(),
                            quotation_reaction: html_node.find("#id_quotation_reaction").val(),
                           // knowledge_point_location: html_node.find("#id_knowledge_point_location").val(),
                            recent_results: html_node.find("#id_recent_results").val(),
                            advice_flag: html_node.find("#id_advice_flag").val(),
                            province: province,
                            city: city,
                            area: area,
                            region: region,
                            test_paper: html_node.find("#id_test_paper").val(),
                        });
                    }
                }]
            });


            dlg.getModalDialog().css("width","78%");


            var close_btn=$('<div class="bootstrap-dialog-close-button" style="display: block;"><button class="close">×</button></div>');
            dlg.getModalDialog().find(".bootstrap-dialog-header").append( close_btn);
            close_btn.on("click",function(){
                dlg.close();
            } );
            var th = setTimeout(function(){
                $.custom_upload_file('id_upload_test_paper', false,function (up, info, file) {
                    var res = $.parseJSON(info);
                    console.log(res);
                    id_test_paper.val(res.key);

                }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);
                clearTimeout(th);
            }, 1000);
        });
    };


    $(".opt-edit-new_new_two").on("click",function(){
        var opt_data   = $(this).get_opt_data();
        var opt_obj    = this;
        var click_type = 2;
        edit_user_info_new_two(opt_data,opt_obj,click_type);
    });

    var edit_user_info_new_two = function(opt_data,opt_obj,click_type){
        $.do_ajax("/ss_deal/get_user_info",{
            "userid" : opt_data.userid ,
            "test_lesson_subject_id" : opt_data.test_lesson_subject_id ,
        },function(ret){
            var data                = ret.data;
            var html_node           = $.dlg_need_html_by_id( "id_dlg_post_user_info_new_two");
            var show_noti_info_flag = false;
            var $note_info          = html_node.find(".note-info");
            var note_msg            = "";
            if (data.test_lesson_count >0 ) {
                show_noti_info_flag=true;
                note_msg="已有试听课:"+data.test_lesson_count +"次" ;
            }



            if (!show_noti_info_flag) {
                $note_info.hide();
            }else{
                $note_info.find("span").html( note_msg);
            }

            if( data.status !=0 ) {
                html_node.find("#id_stu_rev_info_new_two").removeClass("btn-primary");
                html_node.find("#id_stu_rev_info_new_two").addClass("btn-warning");
            }else{
                html_node.find("#id_stu_rev_info_new_two").addClass("btn-primary");
                html_node.find("#id_stu_rev_info_new_two").removeClass("btn-warning");
            }
            html_node.find("#id_send_sms_new_two").on("click",function(){
                $.do_ajax("/user_deal/get_admin_wx_info",{},function(resp){
                    var data=resp.data;
                    var xing=$.trim(data.name).substr(0,1);
                    var dlg=BootstrapDialog.show({
                        title: "发送信息内容:",
                        message : "您好，我是刚刚联系您的"+xing+"老师 ，如果您还需要申请我们的试听课，请添加一下我的微信："+data.wx_id+"。我们会尽快帮您安排，理优教育服务热线："+data.phone,
                        closable: true,
                        buttons: [{
                            label: '返回',
                            action: function(dialog) {
                                dialog.close();
                            }
                        },{
                            label: '发送',
                            cssClass: 'btn-warning',
                            action: function(dialog) {
                                $.do_ajax("/user_deal/send_seller_sms_msg", {
                                    "phone":opt_data.phone,
                                    "name":xing,
                                    "wx_id":data.wx_id,
                                    "seller_phone":data.phone,
                                },function( resp){
                                    alert("发送成功");
                                } );
                            }
                        }]
                    });
                });
            });

            html_node.find("#id_stu_rev_info_new_two") .on("click",function(){
                $(opt_obj).parent().find(".opt-return-back-list").click();
            });
            var id_stu_nick          = html_node.find("#id_stu_nick_new_two");
            var id_gender            = html_node.find("#id_stu_gender_new_two");
            var id_par_nick          = html_node.find("#id_par_nick_new_two");
            var id_par_type          = html_node.find("#id_par_type_new_two");
            var id_grade             = html_node.find("#id_stu_grade_new_two");
            var id_subject           = html_node.find("#id_stu_subject_new_two");
            var id_editionid         = html_node.find("#id_stu_editionid_new_two");
            var id_has_pad           = html_node.find("#id_stu_has_pad_new_two");
            var id_school            = html_node.find("#id_stu_school_new_two");
            var id_interests_hobbies = html_node.find("#id_interests_hobbies_new_two");
            var id_character_type    = html_node.find("#id_character_type_new_two");
            var id_address           = html_node.find("#id_stu_addr_new_two");

            var id_main_subject = html_node.find("#id_main_subject_new_two");
            var id_main_subject_score_one = html_node.find("#id_main_subject_score_one_new_two");
            var id_subject_score     = html_node.find("select[name='subject_score_new_two']");

            var id_test_stress = html_node.find("#id_test_stress_new_two");
            var id_academic_goal = html_node.find("#id_academic_goal_new_two");
            var id_entrance_school_type = html_node.find("#id_entrance_school_type_new_two");
            var id_advice_flag = html_node.find("#id_advice_flag_new_two");
            var id_interest_cultivation = html_node.find("#id_interest_cultivation_new_two");
            var id_extra_improvement = html_node.find("#id_extra_improvement_new_two");
            var id_habit_remodel = html_node.find("#id_habit_remodel_new_two");
            var id_study_habit = html_node.find("#id_study_habit_new_two");
            var id_need_teacher_style = html_node.find("#id_need_teacher_style_new_two");
            var id_stu_request_test_lesson_demand= html_node.find("#id_stu_request_test_lesson_demand_new_two");
            var id_intention_level = html_node.find("#id_intention_level_new_two");
            var id_stu_request_test_lesson_time = html_node.find("#id_stu_request_test_lesson_time_new_two");
            var id_stu_request_test_lesson_time_end = html_node.find("#id_stu_request_test_lesson_time_end_new_two");
            var id_test_paper = html_node.find("#id_test_paper_new_two");
            var id_status            = html_node.find("#id_stu_status_new_two");
            var id_seller_student_sub_status = html_node.find("#id_seller_student_sub_status_new_two");
            var id_next_revisit_time = html_node.find("#id_next_revisit_time_new_two");
            var id_stu_test_ipad_flag = html_node.find("#id_stu_test_ipad_flag_new_two");
            var id_user_desc         = html_node.find("#id_stu_user_desc_new_two");
            var id_demand_urgency = html_node.find("#id_demand_urgency_new_two");
            var id_quotation_reaction = html_node.find("#id_quotation_reaction_new_two");
            var id_revisit_info_new = html_node.find("#id_revisit_info_new_two");

            var id_cultivation = html_node.find("#id_cultivation_new_two");
            var id_teacher_nature = html_node.find("#id_teacher_nature_new_two");
            var id_pro_ability = html_node.find("#id_pro_ability_new_two");
            var id_tea_status = html_node.find("#id_tea_status_new_two");
            var id_tea_age = html_node.find("#id_tea_age_new_two");
            var id_tea_gender = html_node.find("#id_tea_gender_new_two");
            var id_class_env = html_node.find("#id_class_env_new_two");
            var id_courseware = html_node.find("#id_courseware_new_two");
            var id_teacher_type = html_node.find("#id_teacher_type_new_two");
            var id_add_tag = html_node.find("#id_add_tag_new_two");

            var wuyaoqiu_html = "<option value='0'>无要求</option>";
            html_node.find(".upload_test_paper").attr("id","id_upload_test_paper");
            html_node.find("#id_stu_reset_next_revisit_time_new_two").on("click",function(){
                id_next_revisit_time.val("");
            });
            Enum_map.append_option_list("grade", id_grade, true,[101,102,103,104,105,106,201,202,203,301,302,303]);
            Enum_map.append_option_list("pad_type", id_has_pad, true);
            Enum_map.append_option_list("boolean", id_stu_test_ipad_flag, true);
            Enum_map.append_option_list("boolean", id_advice_flag, true);
            Enum_map.append_option_list("academic_goal", id_academic_goal, true);
            Enum_map.append_option_list("test_stress", id_test_stress, true,[1,2,3]);
            id_test_stress.append(wuyaoqiu_html);
            Enum_map.append_option_list("habit_remodel", id_habit_remodel, true);
            Enum_map.append_option_list("extra_improvement", id_extra_improvement, true);
            Enum_map.append_option_list("entrance_school_type", id_entrance_school_type, true,[1,2,3,4,5,6,7]);
            id_entrance_school_type.append(wuyaoqiu_html);
            Enum_map.append_option_list("interest_cultivation", id_interest_cultivation, true);
            Enum_map.append_option_list("intention_level", id_intention_level, true);
            Enum_map.append_option_list("demand_urgency", id_demand_urgency, true);
            Enum_map.append_option_list("quotation_reaction", id_quotation_reaction, true);
            Enum_map.append_option_list("identity", id_tea_status, true,[5,6,7,8]);
            id_tea_status.append(wuyaoqiu_html);
            Enum_map.append_option_list("gender", id_tea_gender, true,[1,2]);
            id_tea_gender.append(wuyaoqiu_html);
            Enum_map.append_option_list("tea_age", id_tea_age, true,[1,2,3,4]);
            id_tea_age.append(wuyaoqiu_html);
            Enum_map.append_option_list("teacher_type", id_teacher_type, true,[1,3]);
            id_teacher_type.append(wuyaoqiu_html);
            id_stu_request_test_lesson_time.datetimepicker({
                lang             : 'ch',
                timepicker       : true,
                format:'Y-m-d H:i',
                step             : 30,
                onGenerate       : function(){
                    check_disable_time();
                }
            });
            id_stu_request_test_lesson_time_end.datetimepicker({
                lang             : 'ch',
                timepicker       : true,
                format:'Y-m-d H:i',
                step             : 30,
                onGenerate       : function(){
                    check_disable_time();
                }
            });
            //检测该时间该人是否排课
            var check_disable_time = function() {
                var cur_time = id_stu_request_test_lesson_time.val();
                var cur_time_end = id_stu_request_test_lesson_time_end.val();
                var cur_day = new Date(cur_time).getTime() / 1000;
                $.do_ajax("/seller_student_new/get_stu_request_test_lesson_time_by_adminid",{
                    "cur_day" : cur_day
                },function(res){
                    var ret = res.list;
                    $(ret).each(function(i){
                        var dis_time = ret[i];
                        console.log(dis_time)
                        $('.xdsoft_time').each(function(){
                            var add_attr = function(obj){
                                $(obj).css('border','1px solid red');
                                $(obj).css('background-color','#ccc');
                                $(obj).on('click',function(){
                                    BootstrapDialog.alert('你已经在该时间段内排过一节课!');
                                    return false;
                                });
                            };

                            if ( $(this).text() == dis_time ) {
                                var that = $(this);
                                var prev_that = $(this).prev();
                                var next_that = $(this).next();
                                add_attr(prev_that);
                                add_attr(that);
                                add_attr(next_that);
                            }
                        });
                    });
                });
            };
            html_node.find("#id_stu_reset_stu_request_test_lesson_time_new_two").on("click",function(){
                id_stu_request_test_lesson_time.val("");
                id_stu_request_test_lesson_time_end.val("");
            });
            id_study_habit.data("v",data.study_habit);
            id_study_habit.on("click",function(){
                var study_habit  = id_study_habit.data("v");
                $.do_ajax("/ss_deal2/get_stu_study_habit_list",{
                    "study_habit" : study_habit
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["num"], this["study_habit"]  ]);

                        if (this["has_study_habit"]) {
                            select_list.push (this["num"]) ;
                        }

                    });

                    $(this).admin_select_dlg({
                        header_list     : [ "id","学习习惯" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {

                            $.do_ajax("/ss_deal2/get_stu_study_habit_name",{
                                "study_habit" : JSON.stringify(select_list)
                            },function(res){
                                id_study_habit.val(res.data);
                                id_study_habit.data("v",res.data);
                            });

                            dlg.close();
                        }
                    });

                });

            });

            id_interests_hobbies.data("v",data.interests_and_hobbies);
            id_interests_hobbies.on("click",function(){
                var interests_hobbies  = id_interests_hobbies.data("v");
                $.do_ajax("/ss_deal2/get_stu_interests_hobbies_list",{
                    "interests_hobbies" : interests_hobbies
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["num"], this["interests_hobbies"]  ]);

                        if (this["has_interests_hobbies"]) {
                            select_list.push (this["num"]) ;
                        }

                    });

                    $(this).admin_select_dlg({
                        header_list     : [ "id","兴趣爱好" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {

                            $.do_ajax("/ss_deal2/get_stu_interests_hobbies_name",{
                                "interests_hobbies" : JSON.stringify(select_list)
                            },function(res){
                                id_interests_hobbies.val(res.data);
                                id_interests_hobbies.data("v",res.data);
                            });

                            dlg.close();
                        }
                    });

                });

            });

            id_cultivation.on("click",function(){
                var interests_hobbies  = id_interests_hobbies.data("v");
                $.do_ajax("/ss_deal2/get_stu_cultivation_list",{
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["tag_id"], this["tag_name"]]);
                        if (this["has_tag_name"]) {
                            select_list.push (this["tag_id"]) ;
                        }
                    });
                    $(this).admin_select_dlg({
                        header_list     : [ "id","素质培养" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {
                            $.do_ajax("/ss_deal2/get_stu_cultivation_name",{
                                "cultivation" : JSON.stringify(select_list)
                            },function(res){
                                id_cultivation.val(res.data);
                                id_cultivation.data("v",res.data);
                            });
                            dlg.close();
                        }
                    });
                });
            });

            id_teacher_nature.on("click",function(){
                var teacher_nature  = id_teacher_nature.data("v");
                $.do_ajax("/ss_deal2/get_teacher_nature_list",{
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["tag_id"], this["tag_name"]]);
                        if (this["has_tag_name"]) {
                            select_list.push (this["tag_id"]) ;
                        }
                    });
                    $(this).admin_select_dlg({
                        header_list     : [ "id","风格性格" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {
                            $.do_ajax("/ss_deal2/get_teacher_nature_name",{
                                "teacher_nature" : JSON.stringify(select_list)
                            },function(res){
                                id_teacher_nature.val(res.data);
                                id_teacher_nature.data("v",res.data);
                            });
                            dlg.close();
                        }
                    });
                });
            });

            id_pro_ability.on("click",function(){
                var pro_ability  = id_pro_ability.data("v");
                $.do_ajax("/ss_deal2/get_pro_ability_list",{
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["tag_id"], this["tag_name"]]);
                        if (this["has_tag_name"]) {
                            select_list.push (this["tag_id"]) ;
                        }
                    });
                    $(this).admin_select_dlg({
                        header_list     : [ "id","专业能力" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {
                            $.do_ajax("/ss_deal2/get_pro_ability_name",{
                                "pro_ability" : JSON.stringify(select_list)
                            },function(res){
                                id_pro_ability.val(res.data);
                                id_pro_ability.data("v",res.data);
                            });
                            dlg.close();
                        }
                    });
                });
            });

            id_class_env.on("click",function(){
                var class_env  = id_class_env.data("v");
                $.do_ajax("/ss_deal2/get_class_env_list",{
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["tag_id"], this["tag_name"]]);
                        if (this["has_tag_name"]) {
                            select_list.push (this["tag_id"]) ;
                        }
                    });
                    $(this).admin_select_dlg({
                        header_list     : [ "id","课堂气氛" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {
                            $.do_ajax("/ss_deal2/get_class_env_name",{
                                "class_env" : JSON.stringify(select_list)
                            },function(res){
                                id_class_env.val(res.data);
                                id_class_env.data("v",res.data);
                            });
                            dlg.close();
                        }
                    });
                });
            });
            id_courseware.on("click",function(){
                var courseware  = id_courseware.data("v");
                $.do_ajax("/ss_deal2/get_courseware_list",{
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["tag_id"], this["tag_name"]]);
                        if (this["has_tag_name"]) {
                            select_list.push (this["tag_id"]) ;
                        }
                    });
                    $(this).admin_select_dlg({
                        header_list     : [ "id","课件要求" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {
                            $.do_ajax("/ss_deal2/get_courseware_name",{
                                "courseware" : JSON.stringify(select_list)
                            },function(res){
                                id_courseware.val(res.data);
                                id_courseware.data("v",res.data);
                            });
                            dlg.close();
                        }
                    });
                });
            });

            id_character_type.data("v",data.character_type);
            id_character_type.on("click",function(){
                var character_type  = id_character_type.data("v");
                $.do_ajax("/ss_deal2/get_stu_character_type_list",{
                    "character_type" : character_type
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["num"], this["character_type"]  ]);

                        if (this["has_character_type"]) {
                            select_list.push (this["num"]) ;
                        }

                    });

                    $(this).admin_select_dlg({
                        header_list     : [ "id","性格特点" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {

                            $.do_ajax("/ss_deal2/get_stu_character_type_name",{
                                "character_type" : JSON.stringify(select_list)
                            },function(res){
                                id_character_type.val(res.data);
                                id_character_type.data("v",res.data);
                            });

                            dlg.close();
                        }
                    });

                });

            });

            id_need_teacher_style.data("v",data.need_teacher_style);
            id_need_teacher_style.on("click",function(){
                var need_teacher_style  = id_need_teacher_style.data("v");
                $.do_ajax("/ss_deal2/get_stu_need_teacher_style_list",{
                    "need_teacher_style" : need_teacher_style
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["num"], this["need_teacher_style"]  ]);

                        if (this["has_need_teacher_style"]) {
                            select_list.push (this["num"]) ;
                        }

                    });

                    $(this).admin_select_dlg({
                        header_list     : [ "id","老师要求" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {

                            $.do_ajax("/ss_deal2/get_stu_need_teacher_style_name",{
                                "need_teacher_style" : JSON.stringify(select_list)
                            },function(res){
                                id_need_teacher_style.val(res.data);
                                id_need_teacher_style.data("v",res.data);
                            });

                            dlg.close();
                        }
                    });
                });
            });


            var old_province = data.region;
            if(old_province == ''){
                old_province="选择省（市）";
            }
            var old_city = data.city;
            if(old_city == ''){
                old_city="选择市（区）";
            }
            var old_area = data.area;
            if(old_area == ''){
                old_area="选择区（县）";
            }
            var province = html_node.find("#province_new_two");
            var city = html_node.find("#city_new_two");
            var area = html_node.find("#area_new_two");
            var preProvince = "<option value=\"\">"+old_province+"</option>";
            var preCity = "<option value=\"\">"+old_city+"</option>";
            var preArea = "<option value=\"\">"+old_area+"</option>";
            //初始化
            province.html(preProvince);
            city.html(preCity);
            area.html(preArea);

            //文档加载完毕:即从province_city_select_Info.xml获取数据,成功之后采用
            //func_suc_getXmlProvice进行 省的 解析
            $.ajax({
                type : "GET",
                url : "/province_city_select_Info.xml",
                success : func_suc_getXmlProvice
            });
            //省 下拉选择发生变化触发的事件
            province.change(function() {
                //province.val()  : 返回是每个省对应的下标,序号从0开始
                if (province.val() != "") {
                    if(data.region != html_node.find("#province_new_two").find("option:selected").text()){
                        var preCity = "<option value=\"\">选择市（区）</option>";
                        var preArea = "<option value=\"\">选择区（县）</option>";
                    }
                    city.html(preCity);
                    area.html(preArea);

                    //根据下拉得到的省对于的下标序号,动态从从province_city_select_Info.xml获取数据,成功之后采用
                    //func_suc_getXmlProvice进行省对应的市的解析
                    $.ajax({
                        type : "GET",
                        url : "/province_city_select_Info.xml",
                        success : func_suc_getXmlCity
                    });

                }
            });

            //市 下拉选择发生变化触发的事件
            city.change(function() {
                if(data.city != html_node.find("#city_new_two").find("option:selected").text()){
                    var preArea = "<option value=\"\">选择区（县）</option>";
                }
                area.html(preArea);
                $.ajax({
                    type : "GET",
                    url : "/province_city_select_Info.xml",

                    //根据下拉得到的省、市对于的下标序号,动态从从province_city_select_Info.xml获取数据,成功之后采用
                    //func_suc_getXmlArea进行省对应的市对于的区的解析
                    success : func_suc_getXmlArea
                });
            });

            //区 下拉选择发生变化触发的事件
            area.change(function() {
                var value = province.find("option:selected").text()
                    + city.find("option:selected").text()
                    + area.find("option:selected").text();
                id_address.val(value);
                $("#txtProCity").val(value);
            });

            //解析获取xml格式文件中的prov标签,得到所有的省,并逐个进行遍历 放进下拉框中
            function func_suc_getXmlProvice(xml) {
                //jquery的查找功能
                var sheng = $(xml).find("prov");

                //jquery的遍历与查询匹配 eq功能,并将其放到下拉框中
                sheng.each(function(i) {
                    province.append("<option value=" + i + ">"
                                    + sheng.eq(i).attr("text") + "</option>");
                });
            }

            function func_suc_getXmlCity(xml) {
                var xml_sheng = $(xml).find("prov");
                var pro_num = parseInt(province.val());
                var xml_shi = xml_sheng.eq(pro_num).find("city");
                xml_shi.each(function(j) {
                    city.append("<option  value=" + j + ">"
                                + xml_shi.eq(j).attr("text") + "</option>");
                });
            }

            function func_suc_getXmlArea(xml) {
                var xml_sheng = $(xml).find("prov");
                var pro_num = parseInt(province.val());
                var xml_shi = xml_sheng.eq(pro_num).find("city");
                var city_num = parseInt(city.val());
                var xml_xianqu = xml_shi.eq(city_num).find("county");
                xml_xianqu.each(function(k) {
                    area.append("<option  value=" + k + ">"
                                + xml_xianqu.eq(k).attr("text") + "</option>");
                });
            }

            var now=(new Date()).getTime()/1000;
            var status=data.status*1;
            var show_status_list=[];
            var cur_page= g_args.cur_page;
            show_status_list=[];
            if(opt_data.stu_type==1){
                switch ( opt_data.seller_student_status) {
                case 0:
                case  2:
                    show_status_list=[ 1,2, 100,101,102,103 ];
                    break;
                case 1:
                    show_status_list=[  100, 101,102,103 ];
                    break;
                case  60:
                    show_status_list=[ 1,2,61, 100,101,102,103 ];
                    break;
                case 61:
                    show_status_list=[1,2,60,  100, 101,102,103 ];
                    break;

                case 101:
                case  102:
                    show_status_list=[ 100, 101,102,103, 1  ];
                    break;

                case 100:case 103:
                    show_status_list=[ 1, 100, 101,102,103 ];
                    break;

                case 110:case 120:
                    show_status_list=[ 1,100, 101,102,103 ];
                    break;

                case 200:
                    show_status_list=[ ];
                    break;

                case 210:
                    show_status_list=[ 220, 290  ];
                    break;
                case 220: //待开课
                    show_status_list=[ 290,300,301,302 ];
                    break;

                case 290: //待跟进
                    show_status_list=[ 300,301,302 ];
                    break;

                case 300:case 301:case 302:   //未签
                    show_status_list=[ 300,301,302,420  ];
                    break;
                case 400:case 410:case 420: //
                    show_status_list=[  60,61 ];
                    break;
                }
            }else{
                switch ( opt_data.seller_student_status) {
                case 0:
                case  2:
                    show_status_list=[ 1,2, 100,101,102,103 ];
                    break;
                case 1:
                    show_status_list=[  100, 101,102,103 ];
                    break;
                case 101:
                case  102:
                    show_status_list=[ 100, 101,102,103, 1  ];
                    break;

                case 100:case 103:
                    show_status_list=[ 1, 100, 101,102,103 ];
                    break;

                case 110:case 120:
                    show_status_list=[ 1,100, 101,102,103 ];
                    break;

                case 200:
                    show_status_list=[ ];
                    break;

                case 210:
                    show_status_list=[ 220, 290  ];
                    break;
                case 220: //待开课
                    show_status_list=[ 290,300,301,302 ];
                    break;

                case 290: //待跟进
                    show_status_list=[ 300,301,302 ];
                    break;

                case 300:case 301:case 302:case 420 :   //未签
                    show_status_list=[ 300,301,302,420  ];
                    break;
                case 400:case 410:case 420: //
                    show_status_list=[   ];
                    break;
                }
            }

            show_status_list.push(status);

            Enum_map.append_option_list("seller_student_status", id_status ,true , show_status_list );
            Enum_map.append_option_list("gender", id_gender, true,[0,1,2]);
            Enum_map.append_option_list("parent_type", id_par_type, true);
            Enum_map.append_option_list("region_version", id_editionid, true);

            id_stu_nick.val(data.stu_nick);
            id_par_nick.val(data.par_nick);
            if(data.par_type>0){
                id_par_type.val(data.par_type);
            }else{
                id_par_type.val(1);
            }
            id_grade.val(data.grade);
            if(id_grade.val()==101 || id_grade.val()==102 || id_grade.val()==103 || id_grade.val()==104 || id_grade.val()==105 || id_grade.val()==106){
                Enum_map.append_option_list("subject", id_subject, true,[0,1,2,3]);
                Enum_map.append_option_list("subject", id_subject_score, true,[0,1,2,3]);
            }else if(id_grade.val()==201 || id_grade.val()==202 || id_grade.val()==203){
                Enum_map.append_option_list("subject", id_subject, true,[0,1,2,3,4,5,6,7,8,9,10]);
                Enum_map.append_option_list("subject", id_subject_score, true,[0,1,2,3,4,5,6,7,8,9,10]);
            }else if(id_grade.val()==301 || id_grade.val()==302 || id_grade.val()==303){
                Enum_map.append_option_list("subject", id_subject, true,[0,1,2,3,4,5,6,7,8,9]);
                Enum_map.append_option_list("subject", id_subject_score, true,[0,1,2,3,4,5,6,7,8,9]);
            }
            id_gender.val(data.gender);
            id_address.val(data.address);
            id_subject.val(data.subject);
            id_main_subject.val(data.subject);
            $.each(data.subject_score.split(','),function(index,value){
                if(value !== ''){
                    var arr = value.split(':');
                    if(arr[0] == id_subject.find("option:selected").text()){
                        html_node.find("#id_main_subject_score_one_new_two").val(arr[1]);
                    }else{
                        html_node.find("#id_main_subject_score_one_new_two").parent().parent().parent().append("<div class='subject_score'><div class='col-xs-12 col-md-1' ><div class='input-group'><span class='input-group-addon' style='height:34px;'>科目：</span><select name='subject_score_new_two' id='subject_score_"+index+"' class='form-control' style='width:70px'><option>"+arr[0]+"</option></select> </div></div><div class='col-xs-3 col-md-1' style='margin:0 0 0 2.0%'><div class='input-group' style='width:90px;'><input type='text' class='form-control' value='"+arr[1]+"' name='subject_score_one_new_two' placeholder='' /></div></div><div class='col-xs-3 col-md-1' style='width:8px;margin:0.5% 3% 0 -0.5%;cursor: pointer;' ><i class='fa fa-plus' onclick='add_subject_score(this)' title='添加科目'></i></div><div class='col-xs-3 col-md-1' style='width:8px;margin:1% 2% 0 -1.5%;cursor: pointer;padding:0 0 0 0;' ><i class='fa fa-minus' onclick='del_subject_score(this)' title='删除科目'></i></div></div>");
                    }
                }
            });
            id_status.val(data.status);
            id_user_desc.val(data.user_desc);
            id_has_pad.val(data.has_pad);
            id_school.val(data.school);
            id_editionid.val(data.editionid);
            id_next_revisit_time.val(data.next_revisit_time);
            html_node.find("#id_class_rank_new_two").val(data.class_rank);
            // html_node.find("#id_class_num_new_two").val(data.class_num);
            html_node.find("#id_grade_rank_new_two").val(data.grade_rank);
            html_node.find("#id_recent_results_new_two").val(data.recent_results);
            html_node.find("#id_advice_flag_new_two").val(data.advice_flag);
            html_node.find("#id_interest_cultivation_new_two").val(data.interest_cultivation);
            html_node.find("#id_extra_improvement_new_two").val(data.extra_improvement);
            html_node.find("#id_habit_remodel_new_two").val(data.habit_remodel);
            html_node.find("#id_study_habit_new_two").val(data.study_habit);
            html_node.find("#id_need_teacher_style_new_two").val(data.need_teacher_style);
            html_node.find("#id_test_paper_new_two").val(data.stu_test_paper);
            html_node.find("#id_test_stress_new_two").val(data.test_stress);
            html_node.find("#id_academic_goal_new_two").val(data.academic_goal);
            html_node.find("#id_entrance_school_type_new_two").val(data.entrance_school_type);
            html_node.find("#id_interests_hobbies_new_two").val(data.interests_and_hobbies);
            html_node.find("#id_character_type_new_two").val(data.character_type);
            html_node.find("#id_intention_level_new_two").val(data.intention_level);
            html_node.find("#id_demand_urgency_new_two").val(data.demand_urgency);
            html_node.find("#id_quotation_reaction_new_two").val(data.quotation_reaction);
            id_tea_status.val(data.tea_identity);
            id_tea_age.val(data.tea_age);
            id_tea_gender.val(data.tea_gender);
            id_teacher_type.val(data.teacher_type);
            if(!data.knowledge_point_location ){
                html_node.find("#id_knowledge_point_location").val(data.stu_request_test_lesson_demand);
            }else{
                html_node.find("#id_knowledge_point_location").val(data.knowledge_point_location);
            }
            var subject_tag_arr = [];
            $.each(data.subject_tag,function(index,value){
                if(index == '学科化标签'){
                    $.each(value.split(','),function(index_v,value_v){
                        if(value_v !== ''){
                            subject_tag_arr.push(value_v);
                        }
                    });
                }
            });
            if(id_grade.val()>0 && id_subject.val()>0){
                $.do_ajax("/product_tag/get_all_tag", {
                },function(resp){
                    var data=resp.data;
                    $.each(data,function(i,item){
                        if(item['tag_l1_sort'] == '学科化内容标签' && item['tag_l2_sort'] == id_grade.find("option:selected").text() && item['tag_l3_sort'] == id_subject.find("option:selected").text()){
                            var checked = '';
                            $.each(subject_tag_arr,function(index,value){
                                if(value == item['tag_name']){
                                    checked = "checked='checked'";
                                }
                            });
                            id_add_tag.parent().append("<span class='sub_tag_name'>"+item['tag_name']+"</span><input name='subject_tag' type='checkbox' value='"+item['tag_name']+"' "+checked+" style='margin:0 10px 0 0' />");
                        }
                    });
                });
            }

            id_grade.change(function(){
                $.do_ajax("/product_tag/get_all_tag", {
                },function(resp){
                    $("select[name='subject_score_new_two']").each(function(){
                        $(this).find("option").remove();
                        if(id_grade.val()==101 || id_grade.val()==102 || id_grade.val()==103 || id_grade.val()==104 || id_grade.val()==105 || id_grade.val()==106){
                            Enum_map.append_option_list("subject", $(this), true,[0,1,2,3]);
                        }else if(id_grade.val()==201 || id_grade.val()==202 || id_grade.val()==203){
                            Enum_map.append_option_list("subject", $(this), true,[0,1,2,3,4,5,6,7,8,9,10]);
                        }else if(id_grade.val()==301 || id_grade.val()==302 || id_grade.val()==303){
                            Enum_map.append_option_list("subject", $(this), true,[0,1,2,3,4,5,6,7,8,9]);
                        }
                    });
                    id_add_tag.parent().children("span[class='sub_tag_name']").remove();
                    id_add_tag.parent().children('input[type=checkbox]').remove();
                    var data=resp.data;
                    $.each(data,function(i,item){
                        if(item['tag_l1_sort'] == '学科化内容标签' && item['tag_l2_sort'] == id_grade.find("option:selected").text() && item['tag_l3_sort'] == id_subject.find("option:selected").text()){
                            var checked = '';
                            $.each(subject_tag_arr,function(index,value){
                                if(value == item['tag_name']){
                                    checked = "checked='checked'";
                                }
                            });
                            id_add_tag.parent().append("<span class='sub_tag_name'>"+item['tag_name']+"</span><input name='subject_tag' type='checkbox' value='"+item['tag_name']+"' "+checked+" />");
                        }
                    })
                        })
            });
            id_subject.change(function(){
                $.do_ajax("/product_tag/get_all_tag", {
                },function(resp){
                    id_add_tag.parent().children("span[class='sub_tag_name']").remove();
                    id_add_tag.parent().children('input[type=checkbox]').remove();
                    var data=resp.data;
                    $.each(data,function(i,item){
                        if(item['tag_l1_sort'] == '学科化内容标签' && item['tag_l2_sort'] == id_grade.find("option:selected").text() && item['tag_l3_sort'] == id_subject.find("option:selected").text()){
                            var checked = '';
                            $.each(subject_tag_arr,function(index,value){
                                if(value == item['tag_name']){
                                    checked = "checked='checked'";
                                }
                            });
                            id_add_tag.parent().append("<span class='sub_tag_name'>"+item['tag_name']+"</span></button><input name='subject_tag' type='checkbox' value='"+item['tag_name']+"' "+checked+" />");
                        }
                    })
                        });
                id_main_subject.val(id_subject.val());
                id_main_subject_score_one.val('');
            });
            $.each(data.subject_tag,function(index,value){
                if(value == ''){
                    value = '无要求';
                }
                if(index == '素质培养'){
                    id_cultivation.val(value);
                }else if(index == '风格性格'){
                    id_teacher_nature.val(value);
                }else if(index == '专业能力'){
                    id_pro_ability.val(value);
                }else if(index == '课堂气氛'){
                    id_class_env.val(value);
                }else if(index == '课件要求'){
                    id_courseware.val(value);
                }
            });
            var reset_seller_student_status_options=function()  {
                var opt_list=[0];
                var desc_map=g_enum_map["seller_student_sub_status"]["desc_map"];
                var seller_student_status=  parseInt( id_status.val());
                $.each(desc_map, function(k,v){
                    if(k>0 ) {
                        if (  Math.floor(k/1000) == seller_student_status ){
                            opt_list.push(parseInt(k));
                        }
                    }
                });
                id_seller_student_sub_status.html("");
                Enum_map.append_option_list("seller_student_sub_status", id_seller_student_sub_status,true, opt_list );
            };

            reset_seller_student_status_options();
            id_seller_student_sub_status.val(data.seller_student_sub_status);
            id_status.on("change",function(){
                reset_seller_student_status_options();
            });

            if(data.stu_request_test_lesson_time == '无' || data.stu_request_test_lesson_time == ''){
                var myDate = Date.parse((new Date()).toString() )+3600*24*1000;
                var time = new Date(myDate);
                var year = time.getFullYear();
                var month = time.getMonth()+1;
                var date = time.getDate();
                // var hours = myDate.getHours();
                var hours = 10;
                // var minutes = myDate.getMinutes();
                var minutes = 0;
                var start_date = year+'-'+add0(month)+'-'+add0(date)+' '+add0(hours)+':'+add0(minutes);
                id_stu_request_test_lesson_time.val(start_date);
            }else{
                id_stu_request_test_lesson_time.val(data.stu_request_test_lesson_time);
            }
            if(data.stu_request_test_lesson_time_end == '无'){
                var start_time = Date.parse((new Date(id_stu_request_test_lesson_time.val())).toString() )+3600*2*1000;
                var time = new Date(start_time);
                var year = time.getFullYear();
                var month = time.getMonth()+1;
                var date = time.getDate();
                var hours = time.getHours();
                var minutes = time.getMinutes();
                var start_date = year+'-'+add0(month)+'-'+add0(date)+' '+add0(hours)+':'+add0(minutes);
                id_stu_request_test_lesson_time_end.val(start_date);
            }else{
                id_stu_request_test_lesson_time_end.val(data.stu_request_test_lesson_time_end);
            }
            id_stu_request_test_lesson_demand.val(data.stu_request_test_lesson_demand );
            id_stu_test_ipad_flag.val(data.stu_test_ipad_flag);

            id_next_revisit_time.datetimepicker( {
                lang:'ch',
                timepicker:true,
                format: "Y-m-d H:i",
                onChangeDateTime :function(){
                }
            });
            id_stu_request_test_lesson_time.change(function(){
                var start_time = Date.parse(
                    ( new Date(id_stu_request_test_lesson_time.val()).toString()
                    ))+3600*2*1000;
                var time = new Date(start_time);
                var year = time.getFullYear();
                var month = time.getMonth()+1;
                var date = time.getDate();
                var hours = time.getHours();
                var minutes = time.getMinutes();
                var start_date = year+'-'+add0(month)+'-'+add0(date)+' '+add0(hours)+':'+add0(minutes);
                id_stu_request_test_lesson_time_end.val(start_date);
            });
            var origin=data.origin;
            if (  /bm_/.test(origin) ||
                  /bw_/.test(origin) ||
                  /baidu/.test(origin)
               ) {
                //origin="百度:"+ origin;
                origin="百度";
            }

            var title= '用户信息['+opt_data.phone+':'+opt_data.phone_location+']';
            // if( g_args.account_seller_level >=100  && g_args.account_seller_level<400 ) { //S,A, B
            //     title= title+"-渠道:["+origin+"]";
            // }

            if(click_type == 1){
                if(html_node.find("#id_stu_editionid").val() == 0){
                    html_node.find("#id_stu_editionid").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_stu_request_test_lesson_time").val() == 0){
                    html_node.find("#id_stu_request_test_lesson_time").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_stu_subject").val() <= 0){
                    html_node.find("#id_stu_subject").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_stu_request_test_lesson_time").val() == '无'){
                    html_node.find("#id_stu_request_test_lesson_time").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }else{
                    var require_time= $.strtotime(html_node.find("#id_stu_request_test_lesson_time").val());
                    var require_time_end= $.strtotime(html_node.find("#id_stu_request_test_lesson_time_end").val());
                    var need_start_time=0;
                    var now=(new Date()).getTime()/1000;
                    var min_date_time="";
                    var nowDayOfWeek = (new Date()).getDay();
                    if ( (new Date()).getHours() <18 ) {
                        min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 08:00:00"  );
                    }else{
                        if( nowDayOfWeek==5 ||  nowDayOfWeek==6){
                            min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 16:00:00"  );
                        }else{
                            min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 14:00:00"  );
                        }
                    }
                    need_start_time=$.strtotime(min_date_time );
                    if (require_time < need_start_time ) {
                        html_node.find("#id_stu_request_test_lesson_time_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                    }
                }
            }
            var dlg=BootstrapDialog.show({
                title:  title,
                size: "size-wide",
                message : html_node,
                closable: false,
                buttons: [{
                    label: '保存',
                    cssClass:"btn-danger",
                    action: function(dialog) {
                        var region = html_node.find("#province_new_two").find("option:selected").text();
                        var province = html_node.find("#province_new_two").val();
                        var city = html_node.find("#city_new_two").find("option:selected").text();
                        var area = html_node.find("#area_new_two").find("option:selected").text();
                        if(province==""){
                            region="";
                            city="";
                            area="";
                        }
                        if(html_node.find("#city_new_two").val()==""){
                             city="";
                        }
                        if(html_node.find("#area_new_two").val()==""){
                            area="";
                        }
                        var subject_str = '';
                        $(".subject_score ").each(function(){
                            var subject_score = $(this).children("div").children("div").children("select[name='subject_score_new_two']").find("option:selected").text();
                            var subject_score_one = $(this).children("div").children("div").children("input[name='subject_score_one_new_two']").val();
                            if(subject_score == ''){
                            }else{
                                subject_str += subject_score+':'+subject_score_one+',';
                            }
                        });
                        var add_tag = '';
                        $("[name = subject_tag]:checkbox").each(function(){
                            if($(this).is(":checked")){
                                add_tag += $(this).attr('value')+',';
                            }
                        });
                        $.do_ajax("/ss_deal2/save_user_info_new",{
                            save   : 1,
                            new_demand_flag   : 1,
                            click_type        : click_type,
                            userid            : opt_data.userid,
                            test_lesson_subject_id : opt_data.test_lesson_subject_id,
                            phone: opt_data.phone,
                            stu_nick      : id_stu_nick.val(),
                            gender        : id_gender.val(),
                            par_nick      : id_par_nick.val(),
                            par_type      : id_par_type.val(),
                            grade         : id_grade.val(),
                            subject       : id_subject.val(),
                            editionid     : id_editionid.val(),
                            has_pad       : id_has_pad.val(),
                            school        : id_school.val(),
                            character_type: id_character_type.val(),
                            interests_and_hobbies: id_interests_hobbies.val(),
                            province: province,
                            city: city,
                            area: area,
                            region: region,
                            address       : id_address.val(),
                            class_rank: html_node.find("#id_class_rank_new_two").val(),
                            grade_rank: html_node.find("#id_grade_rank_new_two").val(),
                            subject_score: subject_str,
                            test_stress: html_node.find("#id_test_stress_new_two").val(),
                            academic_goal: id_academic_goal.val(),
                            entrance_school_type: id_entrance_school_type.val(),
                            cultivation:id_cultivation.val(),
                            add_tag:add_tag,
                            teacher_nature:id_teacher_nature.val(),
                            pro_ability:id_pro_ability.val(),
                            class_env:id_class_env.val(),
                            courseware:id_courseware.val(),
                            recent_results: html_node.find("#id_recent_results_new_two").val(),
                            advice_flag: id_advice_flag.val(),
                            interest_cultivation: id_interest_cultivation.val(),
                            extra_improvement : id_extra_improvement.val(),
                            habit_remodel: id_habit_remodel.val(),
                            study_habit : id_study_habit.val(),
                            stu_request_test_lesson_demand : id_stu_request_test_lesson_demand.val(),
                            stu_request_test_lesson_time:id_stu_request_test_lesson_time.val(),
                            stu_request_test_lesson_time_end:id_stu_request_test_lesson_time_end.val(),
                            test_paper: id_test_paper.val(),
                            tea_identity:id_tea_status.val(),
                            tea_age:id_tea_age.val(),
                            tea_gender:id_tea_gender.val(),
                            teacher_type:id_teacher_type.val(),
                            need_teacher_style: id_need_teacher_style.val(),
                            quotation_reaction: id_quotation_reaction.val(),
                            intention_level : id_intention_level.val(),
                            demand_urgency: id_demand_urgency.val(),
                            seller_student_status : id_status.val(),
                            seller_student_sub_status : id_seller_student_sub_status.val(),
                            next_revisit_time : id_next_revisit_time.val(),
                            stu_test_ipad_flag:id_stu_test_ipad_flag.val(),
                            user_desc     : id_user_desc.val(),
                        });
                    }
                },{
                    label: '返回',
                    action: function(dialog) {
                        dialog.close();
                    }
                },{
                    label: '提交',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                        if (  id_seller_student_sub_status.find("option").length>1  && id_seller_student_sub_status.val()=="0" ) {
                            alert("请选择回访状态的子分类");
                            return;
                        }

                        var region = html_node.find("#province_new_two").find("option:selected").text();
                        var province = html_node.find("#province_new_two").val();
                        var city = html_node.find("#city_new_two").find("option:selected").text();
                        var area = html_node.find("#area_new_two").find("option:selected").text();
                        if(province==""){
                            region="";
                            city="";
                            area="";
                        }
                        if(html_node.find("#city_new_two").val()==""){
                             city="";
                        }
                        if(html_node.find("#area_new_two").val()==""){
                            area="";
                        }
                        var subject_str = '';
                        $(".subject_score ").each(function(){
                            var subject_score = $(this).children("div").children("div").children("select[name='subject_score_new_two']").find("option:selected").text();
                            var subject_score_one = $(this).children("div").children("div").children("input[name='subject_score_one_new_two']").val();
                            // var subject_score_two = $(this).children("div").children("div").children("input[name='subject_score_two_new_two']").val();
                            if(subject_score == ''){
                            }else{
                                subject_str += subject_score+':'+subject_score_one+',';
                            }
                        });
                        var add_tag = '';
                        $("[name = subject_tag]:checkbox").each(function(){
                            if($(this).is(":checked")){
                                add_tag += $(this).attr('value')+',';
                            }
                        });
                        if(html_node.find("#id_stu_nick_new_two").val() == ''){
                            html_node.find("#id_stu_nick_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_stu_nick_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_stu_gender_new_two").val() == 0){
                            html_node.find("#id_stu_gender_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_stu_gender_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_par_nick_new_two").val() == 0){
                            html_node.find("#id_par_nick_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_par_nick_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_par_type_new_two").val() == 0){
                            html_node.find("#id_par_type_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_par_type_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_stu_grade_new_two").val() <= 0){
                            html_node.find("#id_stu_grade_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_stu_grade_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_stu_subject_new_two").val() <= 0){
                            html_node.find("#id_stu_subject_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_stu_subject_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_stu_editionid_new_two").val() <= 0){
                            html_node.find("#id_stu_editionid_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_stu_editionid_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_stu_has_pad_new_two").val() < 0){
                            html_node.find("#id_stu_has_pad_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_stu_has_pad_new_two").parent().attr('style','');
                        }
                        // if(html_node.find("#id_stu_school_new_two").val() <= 0){
                        //     html_node.find("#id_stu_school_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                        //     return false;
                        // }else{
                        //     html_node.find("#id_stu_school_new_two").parent().attr('style','');
                        // }
                        if(html_node.find("#province_new_two").text() == ''){
                            html_node.find("#province_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#province_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#city_new_two").text() == ''){
                            html_node.find("#city_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#city_new_two").parent().attr('style','');
                        }
                        // if(html_node.find("#id_stu_addr_new_two").val() <= 0){
                        //     html_node.find("#id_stu_addr_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                        //     return false;
                        // }else{
                        //     html_node.find("#id_stu_addr_new_two").parent().attr('style','');
                        // }
                        var r = /^\+?[1-9][0-9]*$/;　　//判断是否为正整数
                        // if(html_node.find("#id_class_rank_new_two").val() == '' || html_node.find("#id_class_num_new_two").val() == ''){
                        //     html_node.find("#id_class_rank_new_two").attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                        //     return false;
                        // }else{
                        //     if(r.test(html_node.find("#id_class_rank_new_two").val())){
                        //         html_node.find("#id_class_rank_new_two").attr('style','');
                        //     }else{
                        //         alert('请输入正整数!');
                        //         html_node.find("#id_class_rank_new_two").attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                        //         return false;
                        //     }
                        //     if(r.test(html_node.find("#id_class_num_new_two").val())){
                        //         html_node.find("#id_class_num_new_two").attr('style','');
                        //     }else{
                        //         alert('请输入正整数!');
                        //         html_node.find("#id_class_num_new_two").attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                        //         return false;
                        //     }
                        // }

                        // if(html_node.find("#id_main_subject_new_two").val() == '' || html_node.find("#id_main_subject_score_one_new_two").val() == '' || html_node.find("#id_main_subject_score_two_new_two").val() == ''){
                        //     html_node.find("#id_main_subject_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                        //     return false;
                        // }else{
                        //     var check = true;
                        //     html_node.find("#id_main_subject_new_two").parent().attr('style','');
                        //     $("input[name='subject_score_one_new_two'],input[name='subject_score_two_new_two']").each(function(){
                        //         var r = /^\+?[1-9][0-9]*$/;　　//判断是否为正整数
                        //         if($(this).val() !== ''){
                        //             if(r.test($(this).val())){
                        //                 $(this).attr('style','');
                        //             }else{
                        //                 $(this).attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                        //                 check = false;
                        //                 return false;
                        //             }
                        //         }
                        //     });
                        //     if(!check){
                        //         alert('请输入正整数!');
                        //         return false;
                        //     }
                        // }
                        if(html_node.find("#id_main_subject_score_one_new_two").val() == ''){
                            html_node.find("#id_main_subject_score_one_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                        }else{
                            html_node.find("#id_main_subject_score_one_new_two").parent().attr('style','');
                        }
                        // if(html_node.find("#id_test_stress_new_two").val() <= 0){
                        //     html_node.find("#id_test_stress_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                        //     return false;
                        // }else{
                        //     html_node.find("#id_test_stress_new_two").parent().attr('style','');
                        // }
                        // if(html_node.find("#id_entrance_school_type_new_two").val() <= 0){
                        //     html_node.find("#id_entrance_school_type_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                        //     return false;
                        // }else{
                        //     html_node.find("#id_entrance_school_type_new_two").parent().attr('style','');
                        // }

                        if(html_node.find("#id_cultivation_new_two").val() == ''){
                            html_node.find("#id_cultivation_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_cultivation_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_stu_request_test_lesson_demand_new_two").val() == ''){
                            html_node.find("#id_stu_request_test_lesson_demand_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_stu_request_test_lesson_demand_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_stu_request_test_lesson_time_new_two").val() == ''){
                            html_node.find("#id_stu_request_test_lesson_time_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_stu_request_test_lesson_time_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_teacher_nature_new_two").val() == ''){
                            html_node.find("#id_teacher_nature_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_teacher_nature_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_pro_ability_new_two").val() == ''){
                            html_node.find("#id_pro_ability_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_pro_ability_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_class_env_new_two").val() == ''){
                            html_node.find("#id_class_env_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_class_env_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_courseware_new_two").val() == ''){
                            html_node.find("#id_courseware_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_courseware_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_quotation_reaction_new_two").val() <= 0){
                            html_node.find("#id_quotation_reaction_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_quotation_reaction_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_intention_level_new_two").val() <= 0){
                            html_node.find("#id_intention_level_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_intention_level_new_two").parent().attr('style','');
                        }



                        if((id_stu_request_test_lesson_time.val() != '' && id_stu_request_test_lesson_time.val() != '无') && (id_stu_request_test_lesson_time_end.val() != '' && id_stu_request_test_lesson_time_end.val() != '无')){
                            var min_time = Date.parse(
                                (new Date(id_stu_request_test_lesson_time.val())).toString()
                            );
                            var start_time = Date.parse(
                                (new Date(id_stu_request_test_lesson_time.val()).toString()))+3600*2*1000;
                            var time = new Date(start_time);
                            var year = time.getFullYear();
                            var month = time.getMonth()+1;
                            var date = time.getDate();
                            var hours = time.getHours();
                            var minutes = time.getMinutes();
                            var seconds = 0;
                            var end_date = year+'-'+add0(month)+'-'+add0(date)+' '+add0(hours)+':'+add0(minutes)+':'+add0(seconds);
                            var max_time = Date.parse((new Date(end_date)).toString());
                            var end_time = Date.parse((new Date(id_stu_request_test_lesson_time_end.val()+':00')).toString());
                            if(end_time<min_time){
                                alert('试听最晚时间不能小于'+id_stu_request_test_lesson_time.val());
                                return false;
                            }else if(end_time>max_time){
                                alert('试听最晚时间不能大于'+end_date);
                                return false;
                            }
                            var require_time= $.strtotime(id_stu_request_test_lesson_time.val());
                            var need_start_time=0;
                            var now=(new Date()).getTime()/1000;
                            var min_date_time="";
                            var nowDayOfWeek = (new Date()).getDay();
                            if ( (new Date()).getHours() <18 ) {
                                min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 08:00:00"  );
                            }else{
                                if( nowDayOfWeek==5 ||  nowDayOfWeek==6){
                                    min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 16:00:00"  );
                                }else{
                                    min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 14:00:00"  );
                                }
                            }
                            need_start_time=$.strtotime(min_date_time );
                            if (require_time < need_start_time ) {
                                alert("申请时间不能早于 "+ min_date_time );
                                html_node.find("#id_stu_request_test_lesson_time").attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                return false;
                            }else{
                                html_node.find("#id_stu_request_test_lesson_time").attr('style','');
                            }
                        }
                        $.do_ajax("/ss_deal2/save_user_info_new",{
                            save   : 2,
                            new_demand_flag   : 1,
                            click_type        : click_type,
                            userid            : opt_data.userid,
                            test_lesson_subject_id : opt_data.test_lesson_subject_id,
                            phone: opt_data.phone,
                            stu_nick      : id_stu_nick.val(),
                            gender        : id_gender.val(),
                            par_nick      : id_par_nick.val(),
                            par_type      : id_par_type.val(),
                            grade         : id_grade.val(),
                            subject       : id_subject.val(),
                            editionid     : id_editionid.val(),
                            has_pad       : id_has_pad.val(),
                            school        : id_school.val(),
                            character_type: id_character_type.val(),
                            interests_and_hobbies: id_interests_hobbies.val(),
                            province: province,
                            city: city,
                            area: area,
                            region: region,
                            address       : id_address.val(),
                            class_rank: html_node.find("#id_class_rank_new_two").val(),
                            // class_num: html_node.find("#id_class_num_new_two").val(),
                            grade_rank: html_node.find("#id_grade_rank_new_two").val(),
                            subject_score: subject_str,
                            test_stress: html_node.find("#id_test_stress_new_two").val(),
                            academic_goal: id_academic_goal.val(),
                            entrance_school_type: id_entrance_school_type.val(),
                            cultivation:id_cultivation.val(),
                            add_tag:add_tag,
                            teacher_nature:id_teacher_nature.val(),
                            pro_ability:id_pro_ability.val(),
                            class_env:id_class_env.val(),
                            courseware:id_courseware.val(),
                            recent_results: html_node.find("#id_recent_results_new_two").val(),
                            advice_flag: id_advice_flag.val(),
                            interest_cultivation: id_interest_cultivation.val(),
                            extra_improvement : id_extra_improvement.val(),
                            habit_remodel: id_habit_remodel.val(),
                            study_habit : id_study_habit.val(),
                            stu_request_test_lesson_demand : id_stu_request_test_lesson_demand.val(),
                            stu_request_test_lesson_time:id_stu_request_test_lesson_time.val(),
                            stu_request_test_lesson_time_end:id_stu_request_test_lesson_time_end.val(),
                            test_paper: id_test_paper.val(),
                            tea_identity:id_tea_status.val(),
                            tea_age:id_tea_age.val(),
                            tea_gender:id_tea_gender.val(),
                            teacher_type:id_teacher_type.val(),
                            need_teacher_style: id_need_teacher_style.val(),
                            quotation_reaction: id_quotation_reaction.val(),
                            intention_level : id_intention_level.val(),
                            demand_urgency: id_demand_urgency.val(),
                            seller_student_status : id_status.val(),
                            seller_student_sub_status : id_seller_student_sub_status.val(),
                            next_revisit_time : id_next_revisit_time.val(),
                            stu_test_ipad_flag:id_stu_test_ipad_flag.val(),
                            user_desc     : id_user_desc.val(),
                        },function(){
                            if(!opt_data.parent_wx_openid && g_args.account_role != 12 && g_args.jack_flag !=349 && g_args.jack_flag !=99
                               && g_args.jack_flag !=68 && g_args.jack_flag!=213 && g_args.jack_flag!=75 && g_args.jack_flag!=186
                               && g_args.jack_flag!=944
                              ){//研发,jack,alan,adrian,sam
                                alert("家长未关注微信,不能提交试听课");
                                $(opt_obj).parent().find(".opt-seller-qr-code").click();
                                return false;
                            }

                            var id_grade_select         = $("<select />");
                            var id_user_agent           = $("<div />");
                            var id_stu_test_ipad_flag   = $("<select/>");
                            var id_not_test_ipad_reason = $("<textarea>");

                            Enum_map.append_option_list("boolean", id_stu_test_ipad_flag, true);
                            Enum_map.append_option_list("grade", id_grade_select, true);

                            if(data.user_agent ==""){
                                id_user_agent.html("您还没有设备信息!");
                                id_user_agent.css("color","red");
                            }else if(data.user_agent.indexOf("ipad") <0 && data.user_agent.indexOf("iPad")<0){
                                id_user_agent.html(data.user_agent);
                                id_user_agent.css("color","red");
                            }else{
                                id_user_agent.html(data.user_agent);
                            }

                            var arr=[
                                ["姓名", id_stu_nick.val()],
                                ["年级", id_grade_select],
                                ["科目", html_node.find('#id_stu_subject_new_two').find("option:selected").text()],
                                ["学校", id_school.val()],
                                ["试听时间", id_stu_request_test_lesson_time.val()+'~'+id_stu_request_test_lesson_time_end.val()],
                                ["试听需求", id_stu_request_test_lesson_demand.val()],
                                ["机器版本",  id_user_agent ],
                                ["是否已经连线测试 ", id_stu_test_ipad_flag],
                                ["未连线测试原因", id_not_test_ipad_reason]
                            ];

                            id_grade_select.val(id_grade.val());
                            id_stu_test_ipad_flag.val(data.stu_test_ipad_flag);
                            id_not_test_ipad_reason.val(data.not_test_ipad_reason);
                            id_stu_test_ipad_flag.on("change",function(){
                                if(id_stu_test_ipad_flag.val() == 1){
                                    id_not_test_ipad_reason.parent().parent().hide();
                                }else{
                                    id_not_test_ipad_reason.parent().parent().show();
                                }
                            });

                            $.show_key_value_table("试听申请", arr, {
                                label: '确认',
                                cssClass: 'btn-warning',
                                action: function (dialog) {
                                    $.do_ajax("/ss_deal/require_test_lesson", {
                                        "test_lesson_subject_id"  : opt_data.test_lesson_subject_id,
                                        "userid" : opt_data.userid ,
                                        "stu_test_ipad_flag" : id_stu_test_ipad_flag.val(),
                                        "not_test_ipad_reason" : id_not_test_ipad_reason.val(),
                                        "test_stu_grade" : id_grade_select.val(),
                                    },function(resp){
                                        if(resp.ret !=0){
                                            BootstrapDialog.alert(resp.info);
                                        }else{
                                            if(resp.seller_top_flag==1){
                                                if(11){
                                                    var uu=40-resp.top_num;
                                                    dialog.close();
                                                    alert("试听申请成功,您的精排名额剩余"+uu+"个");
                                                    window.location.reload();
                                                }else if(resp.top_num==29){
                                                    dialog.close();
                                                    BootstrapDialog.alert("试听申请成功,您的精排名额剩余10个");
                                                } else if(resp.top_num==34){
                                                    dialog.close();
                                                    BootstrapDialog.alert("试听申请成功,您的精排名额剩余5个");
                                                } else if(resp.top_num==38){
                                                    dialog.close();
                                                    BootstrapDialog.alert("试听申请成功,您的精排名额剩余1个");
                                                }else{
                                                    dialog.close();
                                                    window.location.reload();
                                                }
                                            }else{
                                                window.location.reload();
                                            }
                                        }
                                    });
                                }
                            },function(){
                                if(id_stu_test_ipad_flag.val() == 1){
                                    id_not_test_ipad_reason.parent().parent().hide();
                                }else{
                                    id_not_test_ipad_reason.parent().parent().show();
                                }
                            });
                        });
                    }
                }]
            });

            dlg.getModalDialog().css("width","78%");
            var close_btn=$('<div class="bootstrap-dialog-close-button" style="display: block;"><button class="close">×</button></div>');
            dlg.getModalDialog().find(".bootstrap-dialog-header").append( close_btn);
            close_btn.on("click",function(){
                dlg.close();
            } );
            var th = setTimeout(function(){
                $.custom_upload_file('id_upload_test_paper', false,function (up, info, file) {
                    var res = $.parseJSON(info);
                    console.log(res);
                    id_test_paper.val(res.key);

                }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);
                clearTimeout(th);
            }, 1000);
        });
    };

    $('#id_left_time_order').click(function(){
        if($('#id_left_time_order_flag').val() == 1){
            $('#id_left_time_order_flag').val(2);
        }else{
            $('#id_left_time_order_flag').val(1);
        }
        load_data();
    })

    if(g_adminid==540){
        window["download_show"]();
    }

}
