/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-get_yxyx_member_detail.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
}

$(function(){

    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var opt_obj=this;

        $.do_ajax("/ss_deal/get_user_info",{
            "userid" : opt_data.userid ,
            "test_lesson_subject_id" : opt_data.test_lesson_subject_id ,
        } ,function(ret){
            var data=ret.data;
            var html_node = $.dlg_need_html_by_id( "id_dlg_post_user_info");
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
                html_node.find("#id_stu_rev_info").removeClass("btn-primary");
                html_node.find("#id_stu_rev_info").addClass("btn-warning");
            }else{
                html_node.find("#id_stu_rev_info").addClass("btn-primary");
                html_node.find("#id_stu_rev_info").removeClass("btn-warning");
            }

            html_node.find("#id_stu_rev_info") .on("click",function(){
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
            var id_revisite_info     = html_node.find("#id_stu_revisite_info");
            var id_has_pad           = html_node.find("#id_stu_has_pad");
            var id_editionid         = html_node.find("#id_stu_editionid");
            var id_school            = html_node.find("#id_stu_school");
            var id_intention_level   = html_node.find("#id_intention_level");
            var id_next_revisit_time = html_node.find("#id_next_revisit_time");
            var id_stu_request_test_lesson_time = html_node.find("#id_stu_request_test_lesson_time");
            var id_stu_request_test_lesson_demand= html_node.find("#id_stu_request_test_lesson_demand");
            var id_stu_score_info = html_node.find("#id_stu_score_info");
            var id_stu_character_info = html_node.find("#id_stu_character_info");
            var id_stu_test_lesson_level = html_node.find("#id_stu_test_lesson_level");
            var id_stu_test_ipad_flag = html_node.find("#id_stu_test_ipad_flag");
            var id_stu_request_test_lesson_time_info = html_node.find("#id_stu_request_test_lesson_time_info");
            var id_stu_request_lesson_time_info = html_node.find("#id_stu_request_lesson_time_info");
            id_stu_request_test_lesson_time_info.data("v" , data. stu_request_test_lesson_time_info  );
            id_stu_request_lesson_time_info.data("v" , data.stu_request_lesson_time_info);
            id_stu_request_lesson_time_info.on("click",function(){
                var v=$(this).data("v");
                if(!v) {
                    v="[]";
                }
                var data_list=JSON.parse(v);

                $(this).admin_select_dlg_edit({
                    onAdd:function( call_func ) {
                        var id_week= $("<select> "+
                                       "<option value=1>周1</option> "+
                                       "<option value=2>周2</option> "+
                                       "<option value=3>周3</option> "+
                                       "<option value=4>周4</option> "+
                                       "<option value=5>周5</option> "+
                                       "<option value=6>周6</option> "+
                                       "<option value=0>周日</option> "+
                                       "</select>");
                        var id_start_time=$("<input/>");
                        var id_end_time=$("<input/>");
                        id_start_time.datetimepicker({
                            datepicker:false,
                            timepicker:true,
                            format:'H:i',
                            step:30,
                            onChangeDateTime :function(){
                                var end_time= $.strtotime("1970-01-01 "+id_start_time.val() ) + 7200;
                                id_end_time.val(  $.DateFormat(end_time, "hh:mm"));
                            }
                        });
                        id_end_time.datetimepicker({
                            datepicker:false,
                            timepicker:true,
                            format:'H:i',
                            step:30
                        });
                        var arr=[
                            ["周", id_week],
                            ["开始时间", id_start_time],
                            ["结束时间", id_end_time],
                        ];
                        $.show_key_value_table("增加", arr, {
                            label: '确认',
                            cssClass: 'btn-warning',
                            action: function (dialog) {
                                call_func({
                                    "week" :  id_week.val() ,
                                    "start_time" : $.strtotime( "1970-01-01 "+ id_start_time.val()) ,
                                    "end_time" : $.strtotime ( "1970-01-01 "+ id_end_time.val())
                                });
                                dialog.close();
                            }
                        });





                        /*
                          var div=$("<div/>");
                          div.admin_select_date_time_range({

                          onSelect:function(start_time,end_time) {
                          call_func({
                          "start_time" : start_time ,
                          "end_time" : end_time
                          });
                          }
                          });
                          div.click();
                        */
                    },
                    sort_func : function(a,b){
                        var a_time=a["week"]*10000000+a["start_time"];
                        var b_time=b["week"]*10000000+b["start_time"];
                        if (a_time==b_time ) {
                            return 0;
                        }else{
                            if (a_time>b_time) return 1;
                            else return -1;
                        }
                    }, 'field_list' :[
                        {
                            title:"周",
                            render:function(val,item) {
                                return Enum_map.get_desc("week", item["week"]*1  );
                            }
                        },{

                            title:"时间段",
                            //width :50,
                            render:function(val,item) {
                                return  $.DateFormat(item.start_time, "hh:mm") +"~"+
                                    $.DateFormat(item.end_time, "hh:mm")  ;
                            }
                        }
                    ] ,
                    data_list: data_list,
                    onChange:function( data_list, dialog)  {
                        id_stu_request_lesson_time_info.data("v" , JSON.stringify(data_list));
                    }
                });
            }) ;

            id_stu_request_test_lesson_time_info.on("click",function(){
                var v=$(this).data("v");
                if(!v) {
                    v="[]";
                }
                var data_list=JSON.parse(v);

                $(this).admin_select_dlg_edit({
                    onAdd:function( call_func ) {
                        var div=$("<div/>");
                        div.admin_select_date_time_range({

                            onSelect:function(start_time,end_time) {
                                call_func({
                                    "start_time" : start_time ,
                                    "end_time" : end_time
                                });
                            }
                        });
                        div.click();
                    },
                    sort_func : function(a,b){
                        var a_time=a["start_time"];
                        var b_time=b["start_time"];
                        if (a_time==b_time ) {
                            return 0;
                        }else{
                            if (a_time>b_time) return 1;
                            else return -1;
                        }
                    }, 'field_list' :[
                        {
                            title:"时间段",
                            //width :50,
                            render:function(val,item) {
                                return  $.DateFormat(item.start_time, "yyyy-MM-dd hh:mm") +"~"+
                                    $.DateFormat(item.end_time, "hh:mm")  ;
                            }
                        }
                    ] ,
                    data_list: data_list,
                    onChange:function( data_list, dialog)  {
                        id_stu_request_test_lesson_time_info.data("v" , JSON.stringify(data_list));
                    }
                });


            }) ;

            html_node.find("#id_stu_reset_next_revisit_time").on("click",function(){
                id_next_revisit_time.val("");
            });
            Enum_map.append_option_list("grade", id_grade, true,[101,102,103,104,105,106,201,202,203,301,302,303]);
            Enum_map.append_option_list("pad_type", id_has_pad, true);
            Enum_map.append_option_list("subject", id_subject, true);
            Enum_map.append_option_list("boolean", id_stu_test_ipad_flag, true);
            Enum_map.append_option_list("test_lesson_level", id_stu_test_lesson_level, true);
            id_stu_request_test_lesson_time.datetimepicker( {
                lang:'ch',
                timepicker:true,
                format: "Y-m-d H:i",
                onChangeDateTime :function(){
                }
            });

            html_node.find("#id_stu_reset_stu_request_test_lesson_time").on("click",function(){
                id_stu_request_test_lesson_time.val("");
            });

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
            id_revisite_info.val(data.revisite_info);
            id_has_pad.val(data.has_pad);
            id_school.val(data.school);
            id_editionid.val(data.editionid);
            id_next_revisit_time.val(data.next_revisit_time);


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
            id_stu_score_info.val(data.stu_score_info);
            id_stu_test_lesson_level.val(data.stu_test_lesson_level);
            id_stu_test_ipad_flag.val(data.stu_test_ipad_flag);
            id_stu_character_info.val(data.stu_character_info);

            var origin=data.origin;
            if (  /bm_/.test(origin) ||
                  /bw_/.test(origin) ||
                  /baidu/.test(origin)
               ) {
                origin="百度";
            }

            var title= '用户信息['+opt_data.phone+':'+opt_data.phone_location+']';
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

                        $.do_ajax("/ss_deal/save_user_info",{
                            userid        : opt_data.userid,
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
                            revisite_info : id_revisite_info.val(),
                            next_revisit_time : id_next_revisit_time.val(),
                            editionid : id_editionid.val(),
                            school: id_school.val(),
                            stu_request_test_lesson_time:id_stu_request_test_lesson_time.val(),
                            stu_request_test_lesson_demand:id_stu_request_test_lesson_demand.val(),
                            stu_score_info:id_stu_score_info.val(),
                            stu_test_lesson_level:id_stu_test_lesson_level.val(),
                            stu_test_ipad_flag:id_stu_test_ipad_flag.val(),
                            stu_character_info:id_stu_character_info.val(),
                            stu_request_test_lesson_time_info:id_stu_request_test_lesson_time_info.data("v"),
                            stu_request_lesson_time_info:id_stu_request_lesson_time_info.data("v"),
                            has_pad       : id_has_pad.val(),
                            intention_level       : id_intention_level.val()
                        });

                    }
                }]
            });

            dlg.getModalDialog().css("width","98%");


            var close_btn=$('<div class="bootstrap-dialog-close-button" style="display: block;"><button class="close">×</button></div>');
            dlg.getModalDialog().find(".bootstrap-dialog-header").append( close_btn);
            close_btn.on("click",function(){
                dlg.close();
            } );

        });

    });



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

                BootstrapDialog.confirm("要新增转介绍? 手机["+phone +"] ",function(val){
                    if (val) {
                        $.do_ajax("/ss_deal/ass_add_seller_user", {
                            "phone"         : phone,
                            "origin_userid" : origin_userid,
                            "origin_assistantid" : origin_assistantid,
                            "grade"         : $grade.val(),
                            "subject"       : $subject.val()
                        });
                    }
                } );
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
        init_noit_btn("id_new_no_called_count",   resp.new_not_call_count,    "从未联系", "未回访" );
        init_noit_btn("id_no_called_count",   resp.not_call_count,    "所有未回访","新例子+公海获取例子" );
        init_noit_btn_ex("id_today_free",   resp.today_free_count,    "今日回流"," 今晚24点自动回流公海, 若需保留 请设置下次回访时间","bg-red" );
        init_noit_btn_ex("id_next_revisit",   resp.next_revisit_count,    "今日需回访"," , 下次回访时间 设置在今日的例子","bg-red" );
        init_noit_btn("id_lesson_today",  resp.today,  "今天上课" ,"今天上课须通知数");
        init_noit_btn("id_lesson_tomorrow", resp.tomorrow, "明天上课","明天上课须通知数" );
        init_noit_btn("id_return_back_count", resp.return_back_count, "排课失败","被教务驳回 未处理的课程个数" );
        init_noit_btn("id_require_count",  resp.require_count,"预约未排","已预约未排数" );
        init_noit_btn("id_favorite_count", resp.favorite_count, "收藏夹","您收藏的例子个数" );
    });

    var init_and_reload=function(  set_func ) {
        $('#id_subject').val(-1);
        $('#id_grade').val(-1);
        $('#id_seller_student_status').val(-1);
        $("#id_phone_name").val("");
        $("#id_phone_location").val("");
        $("#id_has_pad").val(-1);
        $("#id_userid").val(-1);
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

    $("#id_no_confirm_count").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 5,  0, now-86400*14,  now);
            $("#id_success_flag").val(0);
        });
    });


    $("#id_new_no_called_count").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 4,  0, now-86400*60 ,  now);
            // $('#id_seller_student_status').val(0);
            // $("#id_seller_resource_type").val(0);
            $("#id_tq_called_flag").val(0);
        });
    });
    $("#id_tmk_new_no_called_count").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 4,  0, now-86400*60 ,  now);
            $('#id_seller_student_status').val(0);
            $('#id_tmk_student_status').val(3);
        });
    });


    $("#id_no_called_count").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 4,  0, now-86400*60 ,  now);
            $('#id_seller_student_status').val(0);
        });
    });





    $("#id_next_revisit").on("click",function(){

        init_and_reload(function(now){
            $.filed_init_date_range( 1,  0, now-7*86400,  now);
        });

    });

    $("#id_today_free").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 1,  1, now-2*86400,   now-2*86400 );
        });

    });


    $("#id_return_back_count").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 3,  0, now-14*86400,  now);
            $('#id_seller_student_status').val(110 );
        });
    });

    $("#id_favorite_count").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 4,  0, now-86400*180 ,  now);
            $('#id_favorite_flag').val(1);
        });
    });

    $("#id_require_count").on("click",function(){

        init_and_reload(function(now){
            $.filed_init_date_range( 3,  0, now-14*86400,  now);
            $('#id_seller_student_status').val(200);
        });
    });

  /*  $("#id_end_class_stu").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 8,  0, now-86400*30 ,  now);
            $('#id_seller_student_status').val(-2);
            $('#id_end_class_flag').val(1);
        });
    });*/




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
            alert("离课程开始不足4小时,不能更换时间");
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

    $(".opt-edit-new").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var opt_obj=this;
        var click_type=1;

        edit_user_info_new(opt_data,opt_obj,click_type);

    });

    var edit_user_info_new=function(opt_data,opt_obj,click_type){
       // var opt_data=$(this).get_opt_data();
        //var opt_obj=this;
       // alert(opt_data.test_lesson_subject_id);

        $.do_ajax("/ss_deal/get_user_info",{
            "userid" : opt_data.userid ,
            "test_lesson_subject_id" : opt_data.test_lesson_subject_id ,
        } ,function(ret){
            var data=ret.data;
            var html_node = $.dlg_need_html_by_id( "id_dlg_post_user_info_new");
            var show_noti_info_flag=false;
            var $note_info=html_node.find(".note-info");
            var note_msg="";
            html_node.find("input").attr("readonly",true);
            html_node.find("textarea").attr("readonly",true);
            html_node.find("select").attr("disabled",true);
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
                html_node.find("#id_stu_rev_info").removeClass("btn-primary");
                html_node.find("#id_stu_rev_info").addClass("btn-warning");
            }else{
                html_node.find("#id_stu_rev_info").addClass("btn-primary");
                html_node.find("#id_stu_rev_info").removeClass("btn-warning");
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

            html_node.find("#id_stu_rev_info") .on("click",function(){
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
            var id_has_pad           = html_node.find("#id_stu_has_pad");
            var id_editionid         = html_node.find("#id_stu_editionid");
            var id_school            = html_node.find("#id_stu_school");
            var id_intention_level            = html_node.find("#id_intention_level");
            var id_next_revisit_time = html_node.find("#id_next_revisit_time");
            var id_stu_request_test_lesson_time = html_node.find("#id_stu_request_test_lesson_time");
           var id_stu_request_test_lesson_demand= html_node.find("#id_stu_request_test_lesson_demand");
            var id_stu_test_ipad_flag = html_node.find("#id_stu_test_ipad_flag");
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

            html_node.find(".upload_test_paper").attr("id","id_upload_test_paper");

            html_node.find("#id_stu_reset_next_revisit_time").on("click",function(){
                id_next_revisit_time.val("");
            });
            Enum_map.append_option_list("grade", id_grade, true,[101,102,103,104,105,106,201,202,203,301,302,303]);
            Enum_map.append_option_list("pad_type", id_has_pad, true);
            Enum_map.append_option_list("subject", id_subject, true);
            Enum_map.append_option_list("boolean", id_stu_test_ipad_flag, true);
            Enum_map.append_option_list("boolean", id_advice_flag, true);
            Enum_map.append_option_list("academic_goal", id_academic_goal, true);
            Enum_map.append_option_list("test_stress", id_test_stress, true);
            Enum_map.append_option_list("habit_remodel", id_habit_remodel, true);
            Enum_map.append_option_list("extra_improvement", id_extra_improvement, true);
            Enum_map.append_option_list("entrance_school_type", id_entrance_school_type, true);
            Enum_map.append_option_list("interest_cultivation", id_interest_cultivation, true);
            Enum_map.append_option_list("intention_level", id_intention_level, true);
            Enum_map.append_option_list("demand_urgency", id_demand_urgency, true);
            Enum_map.append_option_list("quotation_reaction", id_quotation_reaction, true);

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
            id_stu_test_ipad_flag.val(data.stu_test_ipad_flag);

            var origin=data.origin;
            if (  /bm_/.test(origin) ||
                  /bw_/.test(origin) ||
                  /baidu/.test(origin)
               ) {
                origin="百度";
            }

            var title= '用户信息['+opt_data.phone+':'+opt_data.phone_location+']';
            // if( g_args.account_seller_level >=100  && g_args.account_seller_level<400 ) { //S,A, B
            //     title= title+"-渠道:["+origin+"]";
            // }

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
            if(html_node.find("#id_demand_urgency").val() <= 0){
                html_node.find("#id_demand_urgency").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
            }
            if(html_node.find("#id_quotation_reaction").val() <= 0){
                html_node.find("#id_quotation_reaction").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
            }
            if(html_node.find("#id_stu_request_test_lesson_demand").val() == ''){
                html_node.find("#id_stu_request_test_lesson_demand").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
            }
            if(html_node.find("#id_recent_results").val() == ''){
                html_node.find("#id_recent_results").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
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
                }]
            });
            dlg.getModalDialog().css("width","78%");

            var close_btn=$('<div class="bootstrap-dialog-close-button" style="display: block;"><button class="close">×</button></div>');
            dlg.getModalDialog().find(".bootstrap-dialog-header").append( close_btn);
            close_btn.on("click",function(){
                dlg.close();
            } );

        });

    };


});


