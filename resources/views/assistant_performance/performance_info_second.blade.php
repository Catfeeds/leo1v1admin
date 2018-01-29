@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script src='/page_js/select_teacher_free_time.js?{{@$_publish_version}}'></script>
    <script src='/page_js/set_lesson_time.js?{{@$_publish_version}}'></script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>


    <section class="content ">
        
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-12 col-md-5">
                    <div class="input-group ">
                        <span>*2018年1月以后数据准确</span>
                    </div>
                </div>

                @if(in_array($account,["jack","jim"]))
                    <button class="btn btn-warning" id="add_ass">新增助教</button>
                @endif


               
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>员工状态</td>
                    <td>工号</td>
                    <td>员工姓名</td>
                    <td>英文名</td>
                    <td>部门</td>
                    <td>小组</td>
                    <td>入职日期</td>
                    <td>转正日期</td>
                    <td>离职日期</td>
                    <td>绩效(回访) </td>
                    <td style="display:none;">月初在册人数 </td>
                    <td style="display:none;">平均学员数 </td>
                    <td style="display:none;">销售月总课时 </td>
                    <td style="display:none;">预估月课时消耗总量 </td>
                    <td>绩效(课程消耗) </td>
                    <td style="display:none;">扩课数量(old)</td>
                    <td>绩效(扩课old) </td>
                    <td style="display:none;">扩课数量</td>
                    <td>绩效(扩课) </td>
                    <td style="display:none;">停课数量</td>
                    <td>绩效(停课) </td>
                    <td style="display:none;">结课未续费数量</td>
                    <td>绩效(结课未续费) </td>
                    <td>课时消耗奖金</td>
                    <td>续费目标 </td>
                    <td>续费业绩 </td>
                    <td>续费提成奖金 </td>
                    <td>续费业绩(打8折) </td>
                    <td>转介绍数量 </td>
                    <td>转介绍金额 </td>
                    <td>转介绍提成 </td>
                    <td>转介绍奖金</td>
                    <td>总计</td>
                       
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $key=>$var )
                    <tr>
                        <td>{{@$var["del_flag_str"]}}</td> 
                        <td>{{@$var["adminid"]}}</td> 
                        <td>{{@$var["name"]}}</td> 
                        <td>{{@$var["account"]}}</td> 
                        <td>{{@$var["account_role_str"]}}</td> 
                        <td>{{@$var["group_name"]}}</td> 
                        <td>{{@$var["become_member_time_str"]}}</td> 
                        <td>{{@$var["become_full_member_time_str"]}}</td> 
                        <td>{{@$var["leave_member_time_str"]}}</td> 
                        <td>
                            <a href="/assistant_performance/get_ass_revisit_history_detail_info?adminid={{ $var["adminid"] }}&date_type_config=undefined&date_type=null&opt_date_type=3&start_time={{ $start }}&end_time={{ $end }}" target="_blank" >{{@$var["revisit_reword"]}}</a> 
                            
                        </td>
                        <td>{{@$var["last_registered_num"]}}</td> 
                        <td class="seller_week_stu_num_info" data-adminid="{{ $var["adminid"] }}"><a href="javascript:;" >{{@$var["seller_week_stu_num"]}}</a></td> 
                        <td>
                            <a href="/tea_manage/lesson_list?order_by_str=lesson_start%20asc&date_type=null&opt_date_type=0&start_time={{ $week_start }}&end_time={{ $week_end }}&lesson_status=-1&lesson_type=-2&confirm_flag=0%2C1&subject=-1&grade=-1&studentid=-1&teacherid=-1&lessonid=&assistantid={{  $var["assistantid"] }}&test_seller_id=-1&is_with_test_user=0&has_performance=-1&lesson_count=-1&lesson_del_flag=0&origin=&has_video_flag=-1&lesson_cancel_reason_type=-1&lesson_user_online_status=-1&fulltime_teacher_type=-1" target="_blank" >
                                {{@$var["seller_month_lesson_count"]/100}}
                            </a>
                        </td>
                        <td>
                            <a href="/assistant_performance/show_ass_regular_lesson_info?adminid={{ $var["adminid"] }}&date_type_config=undefined&date_type=null&opt_date_type=3&start_time={{ $start }}&end_time={{ $end }}" target="_blank" >
                                {{@$var["estimate_month_lesson_count"]/100}}
                            </a>
                        </td>
                        <td>{{@$var["kpi_lesson_count_finish_reword"]}}</td> 
                        <td>{{@$var["kk_num_old"]}}</td>
                        <td>{{@$var["kk_reword_old"]}}</td>
                        <td class="opt_kk_suc" data-uid='{{@$var["adminid"]}}'> <a href="javascript:;" >{{@$var["kk_all"]}}</a></td>
                        <td>{{@$var["kk_reword"]}}</td> 
                        <td>
                            <a href="/user_manage/ass_archive?order_by_str=ass_assign_time desc&assistantid={{  $var["assistantid"] }}&grade=-1&student_type=2&user_name=&revisit_flag=-1&warning_stu=-1" target="_blank" >
                                {{@$var["stop_student"]}}
                            </a>
                        </td>
                        <td>{{@$var["stop_reword"]}}</td> 
                        <td>
                            <a href="/user_manage_new/get_two_weeks_old_stu_seller?test_user=-1&originid=-1&grade=-1&user_name=&phone=undefined&assistantid={{  $var["assistantid"] }}&student_type=-1&order_type=-1&seller_adminid=-1&seller_groupid_ex=&start_time={{ $start }}&end_time={{ $end }}" target="_blank" >
                                {{@$var["end_stu_num"]}}
                            </a>
                        </td>
                        <td>{{@$var["end_no_renw_reword"]}}</td> 
                        <td>{{@$var["lesson_count_finish_reword"]}}</td> 
                        <td>{{@$var["renw_target"]/100}}</td> 
                        <td>
                            <a href="/assistant_performance/get_ass_self_order_info?adminid={{ $var["adminid"] }}&date_type_config=undefined&date_type=null&opt_date_type=3&start_time={{ $start }}&end_time={{ $end }}" target="_blank" >
                                {{@$var["renw_price"]/100}}</td>
                            </a>
                            
                        <td>{{@$var["renw_reword"]}}</td> 
                        <td>{{@$var["old_ewnew_money"]}}</td> 
                        <td class="cc_tran_num" data-leader_num='{{@$var["hand_tran_num"]}}' data-new_num='{{@$var["performance_cr_new_num"]}}' data-tran_num='{{@$var["performance_cc_tran_num"]}}' data-uid='{{@$var["adminid"]}}'><a href="javascript:;" >{{@$var["cc_tran_num"]}}</a></td>                        
                        
                        <td>{{@$var["performance_cc_tran_money"]/100}}</td> 

                        <td>
                            <a href="/assistant_performance/get_seller_tran_order_info?adminid={{ $var["adminid"] }}&date_type_config=undefined&date_type=null&opt_date_type=3&start_time={{ $start }}&end_time={{ $end }}" target="_blank" >
                                {{@$var["cc_tran_price_reword"]/100}}
                            </a>
                        </td>
                        <td>{{@$var["cc_tran_reword"]}}</td> 
                        <td>{{@$var["all_reword"]}}</td> 

                        <td>
                            <div 
                                 
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                @if(in_array($account,["jack","jim"]))
                                    <a class="opt-reset-data" data-id="1"> 重置学生数据</a>
                                    <a class="opt-reset-data" data-id="2"> 重置回访数据</a>
                                    <a class="opt-reset-data" data-id="3"> 重置课时数据</a>
                                    <a class="opt-reset-data" data-id="4"> 重置合同数据</a>
                                    <a class="opt-reset-data" data-id="5"> 重置扩课数据</a>
                                    <a class="opt-reset-data" data-id="6"> 重置第一次课数据</a>
                                    <a class="opt-edit"> 编辑</a>
                                @endif
                                
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

