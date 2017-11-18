@extends('layouts.app')
@section('content')
    <script src='/page_js/select_teacher_free_time.js?{{@$_publish_version}}'></script>
    <script src='/page_js/set_lesson_time.js?{{@$_publish_version}}'></script>

    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/js/svg.js"></script>
    <script type="text/javascript" src="/js/wb-reply/audio.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>

    <script type="text/javascript" src="/page_js/seller_student/common.js?v=121"></script>

    <script type="text/javascript" src="/page_js/lib/select_dlg.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="/js/svg.js"></script>
    <script type="text/javascript" src="/js/wb-reply/audio.js"></script>
       
  
    <section class="content ">
        
        <div>
            <div class="row">
                
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead >
                <tr>
                    <td>学生</td>
                    <td>合同年级</td>
                    <td>合同</td>
                    <td>下单人</td>
                    <td>合同时间</td>
                    <td>合同类型</td>
                    <td>合同总课时</td>
                    <td>应退课时</td>
                    <td>合同原价</td>
                    <td>合同实付</td>
                    <td>实退金额</td>
                    <td>是否有开发票</td>
                    <td>发票</td>
                    <td>支付帐号</td>
                    <td>退费理由</td>
                    <td>挽单结果</td>
                    <td>申请时间</td>
                    <td>申请人</td>
                    <td>审批状态</td>
                    <td>审批时间</td>
                    <td>退费状态</td>
                    <td>是否分期</td>
                    <td>助教</td>
                    <td>科目</td>
                    <td>老师</td>
                    <td>联系状态</td>
                    <td>提升状态</td>
                    <td>学习态度</td>
                    <td>下单超过三个月</td>
                    <td>助教部|一级原因</td>
                    <td>助教部|二级原因</td>
                    <td>助教部|三级原因</td>
                    <td>助教部|扣分值</td>
                    <td>助教部|原因分析</td>
                    <td>教务部|一级原因</td>
                    <td>教务部|二级原因</td>
                    <td>教务部|三级原因</td>
                    <td>教务部|扣分值</td>
                    <td>教务部|原因分析</td>
                    <td>老师管理|一级原因</td>
                    <td>老师管理|二级原因</td>
                    <td>老师管理|三级原因</td>
                    <td>老师管理|扣分值</td>
                    <td>老师管理|原因分析</td>
                    <td>教学部|一级原因</td>
                    <td>教学部|二级原因</td>
                    <td>教学部|三级原因</td>
                    <td>教学部|扣分值</td>
                    <td>教学部|原因分析</td>
                    <td>产品问题|一级原因</td>
                    <td>产品问题|二级原因</td>
                    <td>产品问题|三级原因</td>
                    <td>产品问题|扣分值</td>
                    <td>产品问题|原因分析</td>
                    <td>咨询部|一级原因</td>
                    <td>咨询部|二级原因</td>
                    <td>咨询部|三级原因</td>
                    <td>咨询部|扣分值</td>
                    <td>咨询部|原因分析</td>
                    <td>客户情况变化|一级原因</td>
                    <td>客户情况变化|二级原因</td>
                    <td>客户情况变化|三级原因</td>
                    <td>客户情况变化|扣分值</td>
                    <td>客户情况变化|原因分析</td>
                    <td>老师|一级原因</td>
                    <td>老师|二级原因</td>
                    <td>老师|三级原因</td>
                    <td>老师|扣分值</td>
                    <td>老师|原因分析</td>
                    <td>科目|一级原因</td>
                    <td>科目|二级原因</td>
                    <td>科目|三级原因</td>
                    <td>科目|扣分值</td>
                    <td>科目|原因分析</td>


                    <td>其他原因</td>
                    <td>QC整体分析</td>
                    <td>后期应对措施及工作调整方案</td>
                    <td>责任鉴定|助教部</td>
                    <td>责任鉴定|教务部</td>
                    <td>责任鉴定|老师管理</td>
                    <td>责任鉴定|教学</td>
                    <td>责任鉴定|产品问题</td>
                    <td>责任鉴定|咨询部</td>
                    <td>责任鉴定|客户情况变化</td>
                    <td>责任鉴定|老师</td>
                    <td>责任鉴定|科目</td>
                  
                    <td>操作 </td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $k=>$var )
                    <tr>                      
                        <td>
                            {{@$var["nick"]}}<br>
                            {{@$var["phone"]}}
                        </td>
                        <td>{{@$var["grade"]}}</td>
                        <td>{!! @$var["order_custom_str"] !!}</td>
                        <td>{{@$var["sys_operator"]}}</td>
                        <td>{{@$var["order_time_str"]}}</td>
                        <td>{{@$var["contract_type"]}}</td>
                        <td>{{@$var["lesson_total"]/100}}</td>
                        <td>{{@$var["refund_lesson_count"]/100}}</td>
                        <td>{{@$var["order_cost_price"]/100}}</td>
                        <td>{{@$var["order_price"]/100}}</td>
                        <td>{{@$var["refund_price"]/100}}</td>
                        <td>{{@$var["is_invoice"]}}</td>
                        <td>{{@$var["invoice"]}}</td>
                        <td>{{@$var["payment_account_id"]}}</td>
                        <td>{{@$var["refund_info"]}}</td>
                        <td>{{@$var["save_info"]}}</td>
                        <td>{{@$var["apply_time_str"]}}</td>
                        <td>{{@$var["apply_account"]}}</td>
                        <td>{{@$var["approve_status"]}}</td>
                        <td>{{@$var["approve_time_str"]}}</td>
                        <td>{{@$var["refund_status"]}}</td>
                        <td>{{@$var["period_flag"]}}</td>
                        <td>{{@$var["assistant_name"]}}</td>
                        <td>{{@$var["subject"]}}</td>
                        <td>{{@$var["teacher_realname"]}}</td>
                        <td>{{@$var["connection_state"]}}</td>
                        <td>{{@$var["lifting_state"]}}</td>
                        <td>{{@$var["learning_attitude"]}}</td>
                        <td>{{@$var["order_three_month_flag"]}}</td>
                        <td>{{@$var["assistant_one_level_cause"]}}</td>
                        <td>{{@$var["assistant_two_level_cause"]}}</td>
                        <td>{{@$var["assistant_three_level_cause"]}}</td>
                        <td>{{@$var["assistant_deduction_value"]}}</td>
                        <td>{{@$var["assistant_cause_analysis"]}}</td>
                        <td>{{@$var["registrar_one_level_cause"]}}</td>
                        <td>{{@$var["registrar_two_level_cause"]}}</td>
                        <td>{{@$var["registrar_three_level_cause"]}}</td>
                        <td>{{@$var["registrar_deduction_value"]}}</td>
                        <td>{{@$var["registrar_cause_analysis"]}}</td>
                        <td>{{@$var["teacher_manage_one_level_cause"]}}</td>
                        <td>{{@$var["teacher_manage_two_level_cause"]}}</td>
                        <td>{{@$var["teacher_manage_three_level_cause"]}}</td>
                        <td>{{@$var["teacher_manage_deduction_value"]}}</td>
                        <td>{{@$var["teacher_manage_cause_analysis"]}}</td>
                        <td>{{@$var["dvai_one_level_cause"]}}</td>
                        <td>{{@$var["dvai_two_level_cause"]}}</td>
                        <td>{{@$var["dvai_three_level_cause"]}}</td>
                        <td>{{@$var["dvai_deduction_value"]}}</td>
                        <td>{{@$var["dvai_cause_analysis"]}}</td>
                        <td>{{@$var["product_one_level_cause"]}}</td>
                        <td>{{@$var["product_two_level_cause"]}}</td>
                        <td>{{@$var["product_three_level_cause"]}}</td>
                        <td>{{@$var["product_deduction_value"]}}</td>
                        <td>{{@$var["product_cause_analysis"]}}</td>
                        <td>{{@$var["advisory_one_level_cause"]}}</td>
                        <td>{{@$var["advisory_two_level_cause"]}}</td>
                        <td>{{@$var["advisory_three_level_cause"]}}</td>
                        <td>{{@$var["advisory_deduction_value"]}}</td>
                        <td>{{@$var["advisory_cause_analysis"]}}</td>
                        <td>{{@$var["customer_changes_one_level_cause"]}}</td>
                        <td>{{@$var["customer_changes_two_level_cause"]}}</td>
                        <td>{{@$var["customer_changes_three_level_cause"]}}</td>
                        <td>{{@$var["customer_changes_deduction_value"]}}</td>
                        <td>{{@$var["customer_changes_cause_analysis"]}}</td>
                        <td>{{@$var["teacher_one_level_cause"]}}</td>
                        <td>{{@$var["teacher_two_level_cause"]}}</td>
                        <td>{{@$var["teacher_three_level_cause"]}}</td>
                        <td>{{@$var["teacher_deduction_value"]}}</td>
                        <td>{{@$var["teacher_cause_analysis"]}}</td>
                        <td>{{@$var["subject_one_level_cause"]}}</td>
                        <td>{{@$var["subject_two_level_cause"]}}</td>
                        <td>{{@$var["subject_three_level_cause"]}}</td>
                        <td>{{@$var["subject_deduction_value"]}}</td>
                        <td>{{@$var["subject_cause_analysis"]}}</td>

                        <td>{{@$var["other_cause"]}}</td>
                        <td>{{@$var["quality_control_global_analysis"]}}</td>
                        <td>{{@$var["later_countermeasure"]}}</td>
                        <td>{{@$var["assistant_cause_rate"]}}</td>
                        <td>{{@$var["registrar_cause_rate"]}}</td>
                        <td>{{@$var["teacher_manage_cause_rate"]}}</td>
                        <td>{{@$var["dvai_cause_rate"]}}</td>
                        <td>{{@$var["product_cause_rate"]}}</td>
                        <td>{{@$var["advisory_cause_rate"]}}</td>
                        <td>{{@$var["customer_changes_cause_rate"]}}</td>
                        <td>{{@$var["teacher_cause_rate"]}}</td>
                        <td>{{@$var["subject_cause_rate"]}}</td>                     
                                                                                      
                        <td>
                            <div class="row-data"
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

