@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/page_js/lib/flow.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>

    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script src='/page_js/select_teacher_free_time.js?{{@$_publish_version}}'></script>
    <script src='/page_js/set_lesson_time.js?{{@$_publish_version}}'></script>


    <!--
         <script type="text/javascript">
         var _KDA = _KDA || [];
         window._KDA = _KDA;
         (function(){
         var _dealProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
         var _sdkURL = _dealProtocol + "deal-admin.kuick.cn/sdk/v1/";
         _KDA.push(['SDK_URL', _dealProtocol + "deal-admin.kuick.cn/sdk/v1/"]);
         _KDA.push(['APP_KEY', '128994ec-ba97-4a28-9ecc-faa1b00eba33']);
         _KDA.push(['APP_SECRET', 'e1888aa6-f527-4477-ae9b-409fca29f44c']);
         (function() {
         var dealAdmin = document.createElement('script');
         dealAdmin.type='text/javascript';
         dealAdmin.async = true;
         dealAdmin.src = _sdkURL + 'kuickdealadmin-pc.min.js';
         var s = document.getElementsByTagName('script')[0];
         s.parentNode.insertBefore(dealAdmin, s);
         })();
         })();

         function onKDAReady(){
         // 客户下拉组件
         KDAJsSdk.widget.createCustomerDropMenuWidget({
         selector: ".kda-customer-widget",
         });
         $(function(){
         var $title=$(".kda-customer-widget .KDA_customerDropMenuName "  );
         $title.text("K");
         $(".kda-customer-widget .KDA_customerDropMenuCon"  ).attr( "style" ,"width:30px;");
         });

         }

         if (typeof KDAJsSdk == "undefined"){
         if(document.addEventListener){
         document.addEventListener('KDAReady', onKDAReady, false);
         } else if (document.attachEvent){
         document.attachEvent('KDAReady', onKDAReady);
         document.attachEvent('onKDAReady', onKDAReady);
         }
         } else {
         onKDAReady();
         }
         </script>
    -->

    <style>
     .btn-app {
         border-radius: 3px;
         position: relative;
         padding: 10px 10px 10px 10px;
         margin: 10px 10px 10px 10px;
         min-width: 0px;
         height: 40px;
         text-align: center;
         color: #666;
         border: 1px solid #ddd;
         background-color: #f4f4f4;
         font-size: 12px;
     }
     .call-item .call-item-title {
         background-color: #d2d6de;
         font-size: 18px;
         border-radius: 3px;
         width: 25%;
         display: inline-block;
         text-align: center;
     }



     .call-item  {
         margin-top: 10px;
         padding-left: 20px;
         padding-right: 10px;
     }

     .call-item .call-item-text {
         background-color: #d2d6de;
         border-radius: 3px;
         font-size: 18px;
         width: 70%;
         display: inline-block;
         text-align: center;
     }

     .call-item .phone {
         background-color: #9CE3FF;
     }

     .paper_info div{ margin-top:15px;position:relative }
     .paper_info div .paper_font{ width: 100px;font-weight: bold;display: inline-block;text-align: right; margin-right: 30px;}
     .paper_info #paper_erwei{ position:absolute;top:-10px;left:134px; }
    </style>

    <script type="text/javascript" src="/page_js/lib/select_dlg_edit.js?v={{@$_publish_version}}"></script>
    <script type="text/javascript" src="/page_js/lib/select_date_time_range.js?v={{@$_publish_version}}"></script>

    <script type="text/javascript" src="/page_js/jquery.qrcode.min.js?v={{@$_publish_version}}"></script>

    <section class="content ">

        <!-- 此处为模态框-->
        <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #3c8dbc;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 style="text-align: center;color:white;" class="modal-title">未拨通电话标注</h4>
                    </div>
                    <div class="modal-body" style="text-align:center;">
                        <p>请设置</p>
                        <div class="" id="">
                            <select style="width:35%;" class="invalid_type">
                                 <option value="0">请选择状态</option>
                            </select>
                            <p style="color:red;">请至少拨打3次确认状态</p>
                        </div>
                    </div>
                    <div class="modal-footer" style="text-align:center;">
                        <button type="button" class="btn btn-primary submit_tag">提交</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">再想想</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- 此处为模态框-->
        <div class="modal fade confirm-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #3c8dbc;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 style="text-align: center;color:white;" class="modal-title">未拨通电话标注</h4>
                    </div>
                    <div class="modal-body" style="text-align:center;">
                        <p>是否标注为 <font style="color:red;" class="tip_text">无效-空号？</font></p>
                        <p style="color:red;">提示：如经核验不符，将被罚款！</p>
                    </div>
                    <div class="modal-footer" style="text-align:center;">
                        <button type="button" class="btn btn-primary confirm_tag">确认</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">再想想</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 以上为处理中内容 [james]-->








        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-6"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">大类</span>
                        <select class="opt-change form-control" id="id_group_seller_student_status" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">TQ</span>
                        <select class="opt-change form-control" id="id_tq_called_flag" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">全局TQ</span>
                        <select class="opt-change form-control" id="id_global_tq_called_flag" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">试听申请</span>
                        <select class="opt-change form-control" id="id_current_require_id_flag" >
                        </select>
                    </div>
                </div>


                <div class="col-xs-6 col-md-2"  data-always_hide="1">
                    <div class="input-group ">
                        <span class="input-group-addon">学生</span>
                        <input class="opt-change form-control" id="id_userid" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">资源</span>
                        <select class="opt-change form-control" id="id_seller_resource_type" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-3" data-always_show="1"   >
                    <div class="input-group ">
                        <input class="opt-change form-control" id="id_phone_name" placeholder="电话,姓名,回车搜索"/>
                    </div>
                </div>
                <div  class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <span class="input-group-addon">转介绍申请人选择</span>
                        <input class="opt-change form-control" id="id_seller_groupid_ex" />
                    </div>
                </div>
                <div  class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <span class="input-group-addon">申请人选择</span>
                        <input class="opt-change form-control" id="id_seller_groupid_ex_new" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" data-always_show="1" >
                    <div class="input-group ">
                        <span class="input-group-addon">状态</span>
                        <input class="opt-change form-control" id="id_seller_student_status" />
                    </div>
                </div>
                <div style="display:none;" class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span class="input-group-addon">手机归属地</span>
                        <input class="opt-change form-control" id="id_phone_location" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">科目</span>
                        <select class="opt-change form-control" id="id_subject" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">PAD类型</span>
                        <select class="opt-change form-control" id="id_has_pad" >
                        </select>
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">转介绍负责人</span>
                        <input class="opt-change form-control" id="id_origin_assistantid" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">转介绍学生</span>
                        <input class="opt-change form-control" id="id_origin_userid" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">试听确认</span>
                        <select class="opt-change form-control" id="id_success_flag" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">申请更改状态</span>
                        <select class="opt-change form-control" id="id_seller_require_change_flag" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">微信运营</span>
                        <select class="opt-change form-control" id="id_tmk_student_status" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">是否收藏</span>
                        <select class="opt-change form-control" id="id_favorite_flag" >
                        </select>
                    </div>
                </div>



                <div class="col-xs-6 col-md-2"
                     style="   {{$cur_page!=10002?"display:none;":""}}"
                >
                    <div
                        class="input-group ">
                        <span class="input-group-addon">角色</span>
                        <select class="opt-change form-control" id="id_origin_assistant_role" >
                        </select>
                    </div>
                </div>
                @if ( $cur_page==10001 )
                    <div class="col-xs-6 col-md-1">
                        <button class="btn btn-primary fa fa-plus" id="id_add" > 转介绍</button>
                    </div>
                @elseif ( $cur_page==10002 )

                @else
                @endif
            </div>
        </div>
        <div class="row">
            @if ( $cur_page==10001 )
            @elseif ( $cur_page==10002 )

            @else
                <div class=" col-xs-12 col-md-12" >

                    <div class="input-group">
                        <button class="btn  " id="id_today_new_count" ></button>
                        <button class="btn  " id="id_new_no_called_count" ></button>
                        <button class="btn  " id="id_no_called_count" ></button>
                        <button class="btn  " id="id_next_revisit" value="{{$next_revisit_flag}}"></button>
                        <button class="btn  " id="id_today_free" ></button>
                        <button  class="btn  " id="id_lesson_today"></button>
                        <button  class="btn  " id="id_lesson_tomorrow" ></button>
                        <button  class="btn  " id="id_require_count" ></button>
                        <button class="btn  " id="id_return_back_count"></button>
                        <button class="btn  " id="id_favorite_count"></button>
                    </div>
                </div>

            @endif

        </div>

        <div id="id_today_new_list" class="row">
            <div class=" col-md-12 new_list_title" style="font-size:20px;" >  </div>
        </div>
        <table class="common-table">
            <thead>
                <tr>
                    <td style="display:none;">剩余时间
                        <input id="id_left_time_order_flag" type="hidden" value="{{$left_time_order}}"> 
                        <a class="fa fa-sort td-sort-item" href="javascript:;" id="id_left_time_order" value="0"></a>
                    </td>
                    <td style="display:none;">时间详情</td>
                    <td >电话</td>
                    <td >渠道</td>
                    <td style="min-width:140px;">个人信息</td>
                    <td style="display:none;width:60px" class="th-opt-time-field"></td>
                    <td style="">资源进来时间</td>
                    <td style="display:none;" >来源</td>
                    <!-- <td style="display:block;" >来源</td> -->
                    <td >姓名</td>
                    <td >回访状态</td>
                    <td style="display:none;">TQ状态</td>
                    <td style="" >用户备注(all)</td>
                    <td >年级</td>
                    <td >科目</td>
                    <td >是否有pad</td>
                    <td  style="display:none;">试卷</td>
                    <td style="min-width:200px;" >回访信息</td>
                    <td style="">下次跟进时间</td>

                    <td style="   {{$cur_page<=10000 ?"display:none;":""}}" >销售</td>

                    <td style="display:none;" >分配时间</td>
                    <td style="" >最后一次回访时间</td>
                    <td style="">最后一次回访记录</td>
                    <td style="display:none;min-width:120px;   {{@$page_hide_list["teacher_nick"] && $cur_page<10000 ?"display:none;":""}}"   >课程信息</td>
                    <td style=" display:none; min-width:240px;   {{@$page_hide_list["teacher_nick"] && $cur_page<10000 ? "display:none;":""}}"   >课程确认</td>
                    <td style="display:none;" >教务</td>
                    <td style="display:none;" >老师</td>
                    <td style="" >上课信息</td>
                    <td style="display:none;" >确认成功</td>
                    <td style="display:none;" >出错是否付工资</td>
                    <td style="display:none;" >原因</td>
                    <td style="display:none;" >申请更改时间</td>
                    <td style="" >合同金额</td>
                    <td style="display:none;" >未签单分类</td>
                    <td style="display:none;" >未签单说明</td>
                    <td style="min-width:120px;" >操作</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td class="time" data-endtime='{{$var["left_end_time"]}}'>
                        </td>
                        <td >
                            抢单模式:{{$var["assign_type"]}}
                            <br/>
                            分配时间:{{$var["admin_assign_time"]}}
                            <br/>
                            首次拨通时间:{{$var["first_contact_time"]}}
                            <br/>
                            最后拨打时间:{{$var["last_revisit_time"]}}
                            <br/>
                            最后编辑时间:{{$var["last_edit_time"]}}
                        </td>
                        <td  class="td-phone">
                            <div class="phone-data">
                                @if($var["seller_student_assign_from_type"])
                                   (奖)
                                @endif
                                @if($account == 'jim' || $account_role == 12 || $account == 'tom')
                                    {{$var["phone"]}}
                                @else
                                    {{$var["phone_hide"]}}
                                    @if($var['origin']=='学校-180112')
                                        <font color="red">学校渠道</font>
                                    @endif
                                @endif
                            </div>
                        </td>
                        <td  class="td-phone">
                            <div class="phone-data">
                                @if($var['origin'] == '优学优享' || $var['origin'] == '知识库' || $var['origin'] == 'jingqi-0805' || $var['origin']=='美团—1230' || $var['origin']=='学校-180112')
                                    <font color="red">{{$var["origin"]}}/{{$var["nickname"]}}</font>
                                @endif
                            </div>
                        </td>
                        <td >
                            @if($account == 'jim' || $account_role == 12 || $account == 'tom')
                                {{$var["phone"]}}
                            @else
                                {{$var["phone_hide"]}}
                            @endif
                            {{$var["phone_location"]}} <br/>
                            姓名: {{$var["nick"]}} <br/>
                            年级:{{$var["grade_str"]}}<br/>
                            科目:{{$var["subject_str"]}}<br/>
                            PAD: {{$var["has_pad_str"]}}<br/>
                            @if (!$var["origin_assistantid"]  )
                            @else
                                转介绍申请人:{{$var["origin_assistant_nick"]}}<br>
                                介绍人:{{$var["origin_user_nick"]}}
                            @endif
                        </td>
                        <td >{{$var["opt_time"]}}</td>
                        <td >{{$var["add_time"]}}</td>
                        <td class="">
                            @if (!$var["origin_assistantid"]  )
                            @else
                                转介绍申请人:{{$var["origin_assistant_nick"]}}<br>
                                介绍人:{{$var["origin_user_nick"]}}
                            @endif
                        </td>

                        <td >{{$var["nick"]}}</td>
                        <td >
                            {{$var["seller_student_status_str"]}}<br><br>
                            @if($var["seller_student_status"]==110)
                                驳回理由:{{$var["no_accept_reason"]}}
                            @endif
                        </td>
                        <td>{{$var["tq_called_flag_str"]}}</td>
                        <td >{{$var["user_desc"]}}</td>
                        <td >{{$var["grade_str"]}}</td>
                        <td >{{$var["subject_str"]}}</td>
                        <td >{{$var["has_pad_str"]}}</td>
                        <td >
                            {!!  $var["stu_test_paper_flag_str"] !!}
                            <br/>
                            @if( $var["stu_test_paper_flow_status"])
                                不传审核:{!!   $var["stu_test_paper_flow_status_str"] !!}
                            @endif
                        </td>
                        <td >
                            下次跟进时间: {{$var["next_revisit_time"]}} <br/>
                            最后一次回访时间:{{$var["last_revisit_time"]}}<br/>
                            最后一次回访记录:{{$var["last_revisit_msg_sub"]}}<br/>

                            <br/>
                            备注:{{$var["user_desc_sub"]}}</td>
                    </td>
                    <td >{{$var["next_revisit_time"]}}</td>
                    <td >{{$var["admin_revisiter_nick"]}}</td>
                    <td >{{$var["admin_assign_time"]}}</td>
                    <td >{{$var["last_revisit_time"]}}</td>
                    <td >{{$var["last_revisit_msg_sub"]}}</td>
                    <td >
                        <div
                            style="   {{@$page_hide_list["stu_test_paper_flag_str"]?"display:none;":""}}"
                        >试卷: {!!  $var["stu_test_paper_flag_str"] !!}
                        </div>




                        <br/>
                        是否保留: {!!  $var["lesson_used_flag_str"] !!} <br/>
                        老师: {{$var["teacher_nick"]}} <br/>
                        时间: {{$var["lesson_start"]}} <br/>
                        {!! @$var["notify_lesson_flag_str"]!!}

                    </td>
                    <td >
                        课时确认(是否成功):{!!$var["success_flag_str"]!!} <br/>
                        确认人:{!!$var["confirm_admin_nick"]!!} <br/>
                        确认时间:{!!$var["confirm_time"]!!} <br/>

                        @if ($var["success_flag"]==2)
                            是否付工资:
                            @if ( in_array( $var["test_lesson_fail_flag"], [1,2,3]) )
                                <font color="red"> 付</font>
                            @else
                                <font > 不付</font>
                            @endif
                            <br/>
                            上课4小时前取消: {{$var["fail_greater_4_hour_flag_str"]}} <br/>
                            出错类型:{{$var["test_lesson_fail_flag_str"]}} <br/>
                            说明:{{$var["fail_reason"]}} <br/>

                        @endif


                    </td>
                    <td >{{$var["accept_admin_nick"]}} </td>
                    <td >{{$var["teacher_nick"]}}

                    </td>
                    <td >
                        @if( $var["parent_wx_openid"] )
                            <font color="green">家长已绑定微信 </font>
                        @else
                            <font color="red"> 家长未绑定微信  </font>
                        @endif
                        <br/>
                        版本: {{$var["user_agent"]}}
                        <br/>
                        上课时间: {{$var["lesson_start"]}}
                        <br/>
                        家长确认时间: {{$var["parent_confirm_time"]}}
                        <br/>
                        @if($var["suc_no_call_flag"]==1)
                            <font color="green">课后回访:{{$var["last_revisit_time"]}}</font>
                            <br/>
                        @elseif($var["suc_no_call_flag"]==2)
                            <font color="red">试听成功未回访[未拨打]{{$var["last_succ_test_lessonid"]}}</font>
                            <br/>
                        @elseif($var["suc_no_call_flag"]==3)
                            <font color="red">试听成功未回访[未编辑]{{$var["last_succ_test_lessonid"]}}</font>
                            <br/>
                        @elseif($var["suc_no_call_flag"]==4)
                            <font color="red">试听成功未回访[未拨打+未编辑]{{$var["last_succ_test_lessonid"]}}</font>
                            <br/>
                        @else
                        @endif
                        {!! @$var["notify_lesson_flag_str"]!!}
                    </td>
                    <td >
                        {!!$var["success_flag_str"]!!}
                    </td>
                    <td >

                        @if ($var["success_flag"] ==2 )
                            @if (! $var["success_flag"]   )
                            @elseif ( in_array( $var["test_lesson_fail_flag"], [1,2,3]) )
                                <font color="red"> 付</font>
                            @else
                                <font > 不付</font>
                            @endif
                        @endif

                    </td>
                    <td >
                        @if ($var["success_flag"] ==2 )
                            {{$var["test_lesson_fail_flag_str"]}}
                        @endif
                    </td>

                    <td >{{$var["seller_require_change_flag_str"]}} </td>
                    <td >{{intval($var["order_price"])}} </td>
                    <td >{{$var["test_lesson_order_fail_flag_str"]}} </td>
                    <td >{{$var["test_lesson_order_fail_desc"]}} </td>


                    <td>
                        <div
                            @if($show_son_flag)
                            style="display:none;"
                            @endif
                            {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                        >
                            <a href="javascript:;" title="用户信息" class="fa-user opt-user"></a>
                            <a title="查看回访" class=" show-in-select  fa-comments  opt-return-back-list "></a>
                            <a title="试听申请new" class="fa-chevron-up opt-edit-new_new_two"></a>
                            <a title="录入回访信息" style="display:none;" class="fa-edit opt-edit-new_new"></a>
                            <a title="手机拨打&录入回访信息" class="fa-phone  opt-telphone   "></a>
                            <a title="试听申请" style="display:none;" class="fa fa-headphones opt-post-test-lesson "></a>
                            <a class="fa  opt-flow-node-list fa-facebook " title="不传试卷,审核进度"></a>
                            <a title="试听申请未排-取消"  class="fa fa-undo opt-undo-test-lesson "></a>
                            <a title="查看试听课老师反馈" class="fa fa-bookmark opt-get_stu_performance"></a>
                            <a title="上传试卷"
                               style="   {{@$page_hide_list["stu_test_paper_flag_str"]?"display:none;":""}}"
                               id="upload-test-paper-{{$var["test_lesson_subject_id"]}}"
                               class=" fa-upload opt-upload-test-paper "></a>
                            <a title="下载试卷"
                               style="   {{@$page_hide_list["stu_test_paper_flag_str"]?"display:none;":""}}"
                               class=" fa-download opt-download-test-paper "></a>
                            <a
                                style="   {{@$page_hide_list["stu_test_paper_flag_str"]?"display:none;":""}}"
                                title="设置排课通知家长"  class=" fa-bullhorn opt-notify-lesson"></a>
                            <a
                                style="   {{@$page_hide_list["stu_test_paper_flag_str"]?"display:none;":""}}"
                                title="试听请求列表" class="fa fa-list opt-get-require-list "></a>

                            <a
                                style="   {{$cur_page!=301?"display:none;":""}}"
                                href="javascript:;" class="btn fa fa-gavel opt-confirm" title="确认课时"></a>

                            <a
                                style="   {{$cur_page!=10001?"display:none;":""}}"
                                title="删除" class="fa  fa-trash-o   opt-del "></a>



                            <a href="javascript:;" class="btn fa fa-gavel opt-confirm" title="确认课时"></a>

                            <a title="扩课"  class="  fa-share-alt opt-kuoke"></a>
                            <a class="btn  fa-tumblr-square  opt-seller-require" title="申请更换时间"></a>
                            <a class="btn  opt-seller-qr-code " title="产生二维码">P</a>
                            <a class="btn fa-hand-o-right opt-seller-green-channel" title="申请绿色通道"></a>

                            <div  class="kda-customer-widget"
                                  auid="{{$var["userid"]}}"
                                  duid=""
                                  name="{{$var["nick"]?$var["nick"]:"无昵称"}}"
                                  phone=""
                                  email=""
                                  company=""
                                  title=""
                                  style=" display:inline-block;  "> </div>
                            @if($is_seller_master==1)
                                <a href="javascript:;" class="opt-require-commend-teacher" title="申请推荐老师">推</a>
                            @endif
                            <a title="匹配老师" class="opt-match-teacher show_flag">匹配老师</a>
                            <a title="TMK 信息" class="opt-tmk-valid ">TMK</a>
                            <a class="btn  fa-chevron-left  opt-set_user_free" title="回流公海"></a>
                            <a title="排课解冻" class=" fa-asterisk opt-test_lesson-review"></a>
                            @if($var["favorite_adminid"] == 0)
                                <a title="收藏" class=" fa-star-o opt-favorite"></a>
                            @else
                                <a title="取消收藏" class=" fa-star  opt-favorite"></a>
                            @endif
                            @if($env_is_test == 1)
                                <a title="模拟回访" class=" fa-star  opt-call_back"></a>
                            @endif

                            <a class="fa opt-test-paper fa-file-powerpoint-o" title="评测卷"></a>

                            <a class="fa opt-test-paper-result fa-paste" title="评测结果"></a>

                        </div>

                    </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>


    <div style="display:none;" id="id_dlg_post_user_info">

        <div class="alert alert-danger note-info" style="margin-bottom:0px" >
            <strong>重要提示:</strong> <span>  xx </span>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-6  ">
                <div class="row">
                    <div class="col-xs-12 col-md-6  ">
                        <div class="input-group ">
                            <span class="input-group-addon">学员姓名：</span>
                            <input type="text" class=" form-control "  id="id_stu_nick"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">家长姓名：</span>
                            <input type="text" class=" form-control "  id="id_par_nick"  />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">学生性别：</span>
                            <select id="id_stu_gender" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">是否有pad：</span>
                            <select id="id_stu_has_pad" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-addon">家庭住址：</span>
                            <input type="text" id="id_stu_addr"  class="form-control" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">学生年级：</span>
                            <select id="id_stu_grade" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">　　科目：</span>
                            <select id="id_stu_subject" class=" form-control "   >
                            </select>
                        </div>
                    </div>

                </div>
                <div class="row ">

                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">在读学校：</span>
                            <input type="text" id="id_stu_school"  class="form-control"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">教材版本：</span>
                            <select id="id_stu_editionid" class=" form-control "   >
                            </select>
                        </div>

                    </div>
                </div>

                <div class="row ">

                    <div class="col-xs-12 col-md-7 ">
                        <div class="input-group ">
                            <span class="input-group-addon">回访状态：</span>
                            <select id="id_stu_status" class=" form-control "   >
                            </select>
                            <span> &gt </span>
                            <select id="id_seller_student_sub_status" class=" form-control "   >
                            </select>

                        </div>
                    </div>
                    <div class="col-xs-12 col-md-5 ">
                        <a class="btn  " id="id_stu_rev_info" >回访记录</a>
                        <a class="btn  btn-primary " id="id_send_sms" >发短信给家长</a>
                    </div>
                </div>
                <div class="row ">

                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">下次回访：</span>
                            <input id="id_next_revisit_time" class=" form-control " />

                            <div class=" input-group-btn "  >
                                <button class="btn  btn-primary " id="id_stu_reset_next_revisit_time"  title="取消下次回访">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">是否高意向：</span>
                            <select id="id_intention_level" class=" form-control "   >
                                <option value="0">否</option>
                                <option value="1">是</option>
                            </select>

                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <div class="input-group ">
                            <span >成绩情况:</span>
                            <input type="text" value=""   id="id_stu_score_info"  class="form-control" placeholder="" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <div class="input-group ">
                            <span >性格特点:</span>
                            <input type="text" value=""   id="id_stu_character_info" class="form-control"  placeholder="" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-6  ">
                <div class="row ">
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">试听内容：</span>
                            <select id="id_stu_test_lesson_level" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon"> 连线测试 ：</span>
                            <select id="id_stu_test_ipad_flag" class=" form-control "   >
                            </select>
                        </div>

                    </div>
                </div>
                <div class="row ">
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">试听时间：</span>
                            <input id="id_stu_request_test_lesson_time" class=" form-control "   />
                            <div class=" input-group-btn "  >
                                <button class="btn  btn-primary " id="id_stu_reset_stu_request_test_lesson_time"  title="取消">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <button class="btn  btn-primary " id="id_stu_request_test_lesson_time_info"  title=""> 试听其他时段 </button>
                        <button class="btn  btn-primary " id="id_stu_request_lesson_time_info"  title=""> 正式课时段 </button>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-addon">试听需求：</span>
                            <textarea class="form-control" style="height:60px;"
                                      id="id_stu_request_test_lesson_demand" > </textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-addon" >　　备注：</span>
                            <textarea class="form-control" style="height:70px;" id="id_stu_user_desc" > </textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-addon" >回访信息：</span>
                            <textarea class="form-control" style="height:130px;" id="id_stu_revisite_info"  > </textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="display:none;" id="id_dlg_post_user_info_new">

        <div class="alert alert-danger note-info" style="margin-bottom:0px" >
            <strong>重要提示:</strong> <span>  xx </span>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-12  ">
                <span>基本信息&nbsp<font style="color:red">标记红色星号*的为必填内容</font></span>
            </div>
            <div class="col-xs-12 col-md-12  ">
                <div class="row">
                    <div class="col-xs-12 col-md-3  ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp学员姓名：</span>
                            <input type="text" class=" form-control "  id="id_stu_nick"  />

                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon">家长姓名：</span>
                            <input type="text" class=" form-control "  id="id_par_nick"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp学生性别：</span>
                            <select id="id_stu_gender" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp学生年级：</span>
                            <select id="id_stu_grade" class=" form-control "   >
                            </select>
                        </div>
                    </div>


                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon">　　<font style="color:red">*</font>&nbsp科目：</span>
                            <select id="id_stu_subject" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon">在读学校：</span>
                            <input type="text" id="id_stu_school"  class="form-control"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp教材版本：</span>
                            <select id="id_stu_editionid" class=" form-control "   >
                            </select>
                        </div>

                    </div>



                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp上课设备：</span>
                            <select id="id_stu_has_pad" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-2">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp省</span>
                            <select class="form-control" id="province" name="province">
                            </select>

                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp市</span>
                            <select class="form-control" id="city" name="city">
                            </select>

                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp区(县)</span>
                            <select class="form-control" id="area" name="area">
                            </select>

                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 " style="display:none">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp家庭住址：</span>
                            <input type="text" id="id_stu_addr"  class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12  ">
                <span>学习情况</span>
            </div>
            <div class="col-xs-12 col-md-12  ">
                <div class="row">
                    <div class="col-xs-12 col-md-3  ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp近期成绩：</span>
                            <input type="text" class=" form-control "  id="id_recent_results"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 " style="display:none">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp是否进步：</span>
                            <select id="id_advice_flag" class=" form-control "   >
                            </select>
                        </div>

                    </div>
                    <div class="col-xs-12 col-md-3  ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp班级排名：</span>
                            <input type="text" class=" form-control "  id="id_class_rank"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3  ">
                        <div class="input-group ">
                            <span class="input-group-addon">年级排名：</span>
                            <input type="text" class=" form-control "  id="id_grade_rank"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp升学目标：</span>
                            <select id="id_academic_goal" class=" form-control "   >
                            </select>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12  ">
                <span>核心诉求</span>
            </div>
            <div class="col-xs-12 col-md-12  ">
                <div class="row">
                    <div class="col-xs-12 col-md-3  ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp应试压力：</span>
                            <select id="id_test_stress" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp升学学校要求：</span>
                            <select id="id_entrance_school_type" class=" form-control "   >
                            </select>
                        </div>

                    </div>
                    <div class="col-xs-12 col-md-3 " style="display:none">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp趣味培养：</span>
                            <select id="id_interest_cultivation" class=" form-control "   >
                            </select>
                        </div>

                    </div>

                    <div class="col-xs-12 col-md-3  ">
                        <div class="input-group ">
                            <span class="input-group-addon">课外提高：</span>
                            <select id="id_extra_improvement" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon">习惯重塑：</span>
                            <select id="id_habit_remodel" class=" form-control "   >
                            </select>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12  ">
                <span>非智力因素</span>
            </div>
            <div class="col-xs-12 col-md-12  ">
                <div class="row">
                    <div class="col-xs-12 col-md-3  ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp学习习惯：</span>
                            <input type="text" class=" form-control "  id="id_study_habit"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3  ">
                        <div class="input-group ">
                            <span class="input-group-addon">兴趣爱好：</span>
                            <input type="text" class=" form-control "  id="id_interests_hobbies"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3  ">
                        <div class="input-group ">
                            <span class="input-group-addon"> <font style="color:red">*</font>&nbsp性格特点：</span>
                            <input type="text" class=" form-control "  id="id_character_type"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3  ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp老师要求：</span>
                            <input type="text" class=" form-control "  id="id_need_teacher_style"  />
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-xs-12 col-md-12  ">
                <span>试听需求</span>
            </div>
            <div class="col-xs-12 col-md-9  ">
                <div class="row">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-addon" >　<font style="color:red">*</font>&nbsp　试听内容：</span>
                            <textarea class="form-control" style="height:115px;" id="id_stu_request_test_lesson_demand" > </textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-md-3  ">
                <div class="row">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp上课意向：</span>
                            <select id="id_intention_level" class=" form-control "   >
                            </select>
                        </div>

                    </div>
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp试听时间：</span>
                            <input id="id_stu_request_test_lesson_time" class=" form-control "   />
                            <div class=" input-group-btn "  >
                                <button class="btn  btn-primary " id="id_stu_reset_stu_request_test_lesson_time"  title="取消" >
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>

                        </div>
                    </div>



                    <div class="col-xs-12 col-md-12  ">
                        <div class="input-group ">
                            <span class="input-group-addon">上传试卷：</span>
                            <input type="text" class=" form-control "  id="id_test_paper"   / >
                            <div class=" input-group-btn "  >
                                <button class="btn  btn-primary upload_test_paper"  title="上传" >
                                    上传
                                </button>
                            </div>


                        </div>
                    </div>

                </div>
            </div>

        </div>


        <div class="row" id="id_revisit_info_new">
            <div class="col-xs-12 col-md-12  ">
                <span>回访信息</span>
            </div>
            <div class="col-xs-12 col-md-6  ">
                <div class="row ">

                    <div class="col-xs-12 col-md-7 ">
                        <div class="input-group ">
                            <span class="input-group-addon">回访状态：</span>
                            <select id="id_stu_status" class=" form-control "   >
                            </select>
                            <span> &gt </span>
                            <select id="id_seller_student_sub_status" class=" form-control "   >
                            </select>

                        </div>
                    </div>
                    <div class="col-xs-12 col-md-5 ">
                        <a class="btn  " id="id_stu_rev_info_new" >回访记录</a>
                        <a class="btn  btn-primary " id="id_send_sms" >发短信给家长</a>
                    </div>
                </div>
                <div class="row ">

                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">下次回访：</span>
                            <input id="id_next_revisit_time" class=" form-control " />

                            <div class=" input-group-btn "  >
                                <button class="btn  btn-primary " id="id_stu_reset_next_revisit_time"  title="取消下次回访">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon"> 连线测试 ：</span>
                            <select id="id_stu_test_ipad_flag" class=" form-control "   >
                            </select>
                        </div>

                    </div>



                </div>
            </div>
            <div class="col-xs-12 col-md-6  ">
                <div class="row">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-addon" >　　备注：</span>
                            <textarea class="form-control" style="height:70px;" id="id_stu_user_desc" > </textarea>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <div class="row">
            <div class="col-xs-12 col-md-12  ">
                <span>其他</span>
            </div>
            <div class="col-xs-12 col-md-12  ">
                <div class="row">
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp需求急迫性：</span>
                            <select id="id_demand_urgency" class=" form-control "   >
                            </select>
                        </div>

                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp报价反应：</span>
                            <select id="id_quotation_reaction" class=" form-control "   >
                            </select>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>



    <div style="display:none;" id="id_dlg_post_user_info_new_two" style="z-index:-1">
        <div class="alert alert-danger note-info" style="margin-bottom:0px" >
            <strong>重要提示:</strong> <span>  xx </span>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-12  ">
                <span>基本信息&nbsp<font style="color:red">标记红色星号*的为必填内容</font></span>
            </div>
            <div class="col-xs-12 col-md-12  ">
                <div class="row">
                    <div class="col-xs-12 col-md-2  ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp学员姓名：</span>
                            <input type="text" class=" form-control "  id="id_stu_nick_new_two" style="width:100px;" />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2" style="margin:0 20px 0 30px;">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp性别：</span>
                            <select id="id_stu_gender_new_two" class=" form-control " style="width:120px;" >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2 "  >
                        <div class="input-group " >
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp家长姓名：</span>
                            <input type="text" class=" form-control "  id="id_par_nick_new_two" style="width:88px" />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2 " style="margin:0 0.3% 0 0;width:100px;">
                        <div class="input-group " style="margin:0 0 0 -20%;width:80px;">
                            <select id="id_par_type_new_two" class=" form-control " >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp年级：</span>
                            <select id="id_stu_grade_new_two" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp科目：</span>
                            <select id="id_stu_subject_new_two" name="subject_score_new_two" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-2 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp教材：</span>
                            <select id="id_stu_editionid_new_two" class=" form-control "  style="width:130px;" >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2 " style="margin:0 20px 0 30px;">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp设备：</span>
                            <select id="id_stu_has_pad_new_two" class=" form-control "  style="width:120px;" >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2 " style="width:290px;margin:0 20px 0 0px;">
                        <div class="input-group ">
                            <span class="input-group-addon">在读学校：</span>
                            <input type="text" id="id_stu_school_new_two"  class="form-control" style="width:166px;" />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2  ">
                        <div class="input-group ">
                            <span class="input-group-addon">性格：</span>
                            <input type="text" class=" form-control "  id="id_character_type_new_two"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2  ">
                        <div class="input-group ">
                            <span class="input-group-addon">爱好：</span>
                            <input type="text" class=" form-control "  id="id_interests_hobbies_new_two"  />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-md-2">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp省</span>
                            <select class="form-control" id="province_new_two" name="province"  style="width:155px">
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2" style="margin:0 20px 0 30px">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp市</span>
                            <select class="form-control" id="city_new_two" name="city">
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2">
                        <div class="input-group ">
                            <span class="input-group-addon">区(县)</span>
                            <select class="form-control" id="area_new_two" name="area">
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-5 " >
                        <div class="input-group ">
                            <span class="input-group-addon">详细住址：</span>
                            <input type="text" id="id_stu_addr_new_two" placeholder="请输入详细住址" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-12  ">
                <span>学习概况</span>
            </div>
            <div class="col-xs-12 col-md-12  ">
                <div class="row">
                    <div class="col-xs-12 col-md-1" >
                        <div class="input-group ">
                            <span class="input-group-addon" style="height:34px;"><font style="color:red">*</font>&nbsp综合排名：</span>
                        </div>
                    </div>

                    <div class='col-xs-12 col-md-2' style="">
                        <div class='input-group' style='width:118px;'>
                            <input type="text" class=" form-control "  id="id_class_rank_new_two"  placeholder='' />
                        </div>
                    </div>
                    <!-- <div class='col-xs-3 col-md-1' style=''>
                         <div class='input-group' style='width:78px;'>
                         <input type="text" class=" form-control "  id="id_class_rank_new_two"  placeholder='班级排名' />
                         </div>
                         </div>
                         <div class='col-xs-3 col-md-1' style="margin:0 2% 0 -3%">
                         <div class='input-group' style='width:70px;'>
                         <input type="text" class=" form-control "  id="id_class_num_new_two" placeholder='班级人数' />
                         </div>
                         </div> -->
                    <div class="input-group " style="display:none;">
                        <span class="input-group-addon">年级排名：</span>
                        <input type="text" class=" form-control "  id="id_grade_rank_new_two"  placeholder='年级排名' />
                    </div>



                    <div class="subject_score">
                        <div class='col-xs-12 col-md-1' style='margin:0 0 0 -0.18%'>
                            <div class='input-group'>
                                <span class='input-group-addon' style='height:34px;'><font style='color:red'>*</font>&nbsp科目：</span>
                                <select name='subject_score_new_two' id='id_main_subject_new_two' class='form-control' style='width:70px'>
                                </select>
                            </div>
                        </div>
                        <div class='col-xs-3 col-md-1' style='margin:0 0 0 3.0%'>
                            <div class='input-group' style='width:90px;'>
                                <input type='text' class='form-control' id='id_main_subject_score_one_new_two' name='subject_score_one_new_two' placeholder='' />
                            </div>
                        </div>
                        <!-- <div class='col-xs-3 col-md-1' style='margin:0 0 0 3.5%'>
                             <div class='input-group' style='width:45px;'>
                             <input type='text' class='form-control' id='id_main_subject_score_one_new_two' name='subject_score_one_new_two' placeholder='分数' />
                             </div>
                             </div>
                             <div class='col-xs-3 col-md-1' style='margin:0 0.1% 0 -4.5%'>
                             <div class='input-group' style='width:50px;'>
                             <input type='text' class='form-control' id='id_main_subject_score_two_new_two' name='subject_score_two_new_two' placeholder='满分' />
                             </div>
                             </div> -->
                        <div class='col-xs-3 col-md-1' style='width:8px;margin:0.5% 2.5% 0 0%;cursor: pointer;' >
                            <i class='fa fa-plus' onclick='add_subject_score(this)' title='添加科目'></i>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-xs-12 col-md-3  ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp学习目标：</span>
                            <select id="id_test_stress_new_two" class=" form-control " >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 " style="display:none;">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp升学目标：</span>
                            <select id="id_academic_goal_new_two" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 " >
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp升学目标：</span>
                            <select id="id_entrance_school_type_new_two" class=" form-control " >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 " style="margin:0 0 0 -7px;" >
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp素质培养：</span>
                            <input type="text" class=" form-control "  id="id_cultivation_new_two"  />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-md-3 " style="display:none;">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp近期成绩：</span>
                            <input type="text" class=" form-control "  id="id_recent_results_new_two" />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 " style="display:none">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp是否进步：</span>
                            <select id="id_advice_flag_new_two" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 " style="display:none">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp趣味培养：</span>
                            <select id="id_interest_cultivation_new_two" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3  " style="display:none;">
                        <div class="input-group ">
                            <span class="input-group-addon">课外提高：</span>
                            <select id="id_extra_improvement_new_two" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 " style="display:none;">
                        <div class="input-group ">
                            <span class="input-group-addon">习惯重塑：</span>
                            <select id="id_habit_remodel_new_two" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-md-3  " style="display:none;">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp学习习惯：</span>
                            <input type="text" class=" form-control "  id="id_study_habit_new_two" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-12  ">
                <span>老师要求</span>
            </div>
            <div class="col-xs-12 col-md-12  ">
                <div class="row">
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp风格性格：</span>
                            <input type="text" class=" form-control "  id="id_teacher_nature_new_two" />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp专业能力：</span>
                            <input type="text" class=" form-control "  id="id_pro_ability_new_two" />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp老师身份：</span>
                            <select id="id_tea_status_new_two" class=" form-control " >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp年龄段：</span>
                            <select id="id_tea_age_new_two" class=" form-control " >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp老师性别：</span>
                            <select id="id_tea_gender_new_two" class=" form-control " >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp课堂气氛：</span>
                            <input type="text" class=" form-control "  id="id_class_env_new_two" />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp课件要求：</span>
                            <input type="text" class=" form-control "  id="id_courseware_new_two" />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 " style="display:block;">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp老师类型：</span>
                            <select id="id_teacher_type_new_two" class=" form-control " >
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-12  " >
                <span>试听内容</span>
                <span style="margin-left:70px;" id="id_add_tag_new_two"></span>
            </div>
            <div class="col-xs-12 col-md-9  ">
                <div class="row">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-addon" >　<font style="color:red">*</font>&nbsp　试听要求：</span>
                            <textarea class="form-control" style="height:115px;" class="class_stu_request_test_lesson_demand_new_two" id="id_stu_request_test_lesson_demand_new_two" ></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-md-3  "  style="margin:0 0 0 -2%;">
                <div class="row">
                    <div class="col-xs-12 col-md-12 " style="width:310px;">
                        <div class="input-group " >
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp试听时间：</span>
                            <input id="id_stu_request_test_lesson_time_new_two" placeholder="开始时间" class=" form-control " style="1"  />
                            <input id="id_stu_request_test_lesson_time_end_new_two" placeholder="结束时间" class=" form-control "   />
                            <div class=" input-group-btn "  >
                                <button class="btn  btn-primary" id="id_stu_reset_stu_request_test_lesson_time_new_two"  title="取消" >
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-12  ">
                        <div class="input-group ">
                            <span class="input-group-addon">上传试卷：</span>
                            <input type="text" class=" form-control "  id="id_test_paper_new_two"   / >
                            <div class=" input-group-btn "  >
                                <button class="btn  btn-primary upload_test_paper"  title="上传" >
                                    上传
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-12  ">
                <span>意向度</span>
            </div>

            <div class="col-xs-12 col-md-12  ">
                <div class="row">
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp报价反应：</span>
                            <select id="id_quotation_reaction_new_two" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp上课意向：</span>
                            <select id="id_intention_level_new_two" class=" form-control ">
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 "  style="display:none;">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp需求急迫性：</span>
                            <select id="id_demand_urgency_new_two" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row" id="id_revisit_info_new_two">
            <div class="col-xs-12 col-md-12  ">
                <span>回访信息</span>
            </div>
            <div class="col-xs-12 col-md-6  ">
                <div class="row ">

                    <div class="col-xs-12 col-md-7 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp回访状态：</span>
                            <select id="id_stu_status_new_two" class=" form-control "   >
                            </select>
                            <span> &gt </span>
                            <select id="id_seller_student_sub_status_new_two" class=" form-control ">
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-5 ">
                        <a class="btn  " id="id_stu_rev_info_new_two" >回访记录</a>
                        <a class="btn  btn-primary " id="id_send_sms_new_two" >发短信给家长</a>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">下次回访：</span>
                            <input id="id_next_revisit_time_new_two" class=" form-control " />

                            <div class=" input-group-btn "  >
                                <button class="btn  btn-primary " id="id_stu_reset_next_revisit_time_new_two"  title="取消下次回访">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon"> 连线测试 ：</span>
                            <select id="id_stu_test_ipad_flag_new_two" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-6  ">
                <div class="row">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-addon" >　　备注：</span>
                            <textarea class="form-control" style="height:70px;" id="id_stu_user_desc_new_two" > </textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>

@endsection
