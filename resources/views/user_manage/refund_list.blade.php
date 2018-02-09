@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_edit.js?v={{@$_publish_version}}"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/page_js/lib/flow.js"></script>
    <script type="text/javascript" >
     var g_adminid_right= <?php  echo json_encode($adminid_right); ?> ;
    </script>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-md-5" data-title="时间段">
                <div id="id_date_range"> </div>
            </div>
            <div class="col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">类型</span>
                    <select  id="id_refund_type" class="opt-change">
                        <option value="-1">全部</option>
                        <option value="0">未付款</option>
                        <option value="1">已付款</option>
                    </select>
                </div>
            </div>
            <div  class="col-xs-6 col-md-4">
                <div class="input-group ">
                    <span class="input-group-addon">申请人选择</span>
                    <input class="opt-change form-control" id="id_seller_groupid_ex" />
                </div>
            </div>

            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span >学生</span>
                    <input id="id_userid"/>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span> 测试用户 </span>
                    <select id="id_is_test_user" class="opt-change" >
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span> QC数据 </span>
                    <select id="id_qc_flag" class="opt-change" >
                        <option value="0">否</option>
                        <option value="1">是</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2" data-always_hide="1">
                <div class="input-group ">
                    <input id="id_sys_operator"  class="opt-change" placeholder="下单人,回车搜索" />
                </div>
            </div>

            <div class="col-xs-6 col-md-2" data-always_hide="1">
                <div class="input-group ">
                    <input id="id_assistant_nick"  class="opt-change" placeholder="助教,回车搜索" />
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">是否有金额</span>
                    <select class="opt-change form-control" id="id_has_money">
                        <option value="-1" >全部</option>
                        <option value="0" >无</option>
                        <option value="1" >有</option>
                    </select>
                </div>
            </div>

            <div class="col-xs-12 col-md-2">
                <div class="input-group ">
                    <button class="btn btn-primary" id="id_add_refund">合同退费</button>
                </div>
            </div>

        </div>
        <hr />
        <div class="body">
            <table class="common-table ">
                <thead>
                    <tr >
                        <td rowspan="2">学生</td>
                        <td >合同年级</td>
                        <td width="130px">合同</td>
                        <td >下单人</td>
                        <td style="display:none;" >下单人姓名</td>
                        <td style="display:none;" >合同时间</td>
                        <td style="display:none;" >合同类型</td>
                        <td style="display:none;" >合同总课时</td>
                        <td >应退课时</td>
                        <td style="display:none;">合同原价</td>
                        <td style="display:none;">合同实付</td>
                        <td >应退金额</td>
                        <td >实退金额</td>
                        <td >是否开有发票</td>
                        <td >发票</td>
                        <td >支付账号</td>
                        <td >帐号持有人</td>
                        <td >退费理由</td>
                        <td >挽单结果</td>
                        <td >申请时间</td>
                        <td >申请人</td>
                        <td style="display:none;" >申请人姓名</td>
                        <td >审批状态</td>
                        <td >审批时间</td>
                        <td >退费状态</td>
                        <td >是否分期</td>
                        <td  style="display:none;">助教</td>
                        <td  style="display:none;">科目</td>
                        <td  style="display:none;">老师</td>
                        <td  style="display:none;">老师责任</td>

                        <td  style="display:none;">首次上课时间</td>
                        <td  style="display:none;">末次上课时间</td>

                        <td  style="display:none;">联系状态</td>
                        <td  style="display:none;">提升状态</td>
                        <td  style="display:none;">学习态度</td>
                        <td  style="display:none;">下单超过3个月</td>


                        <td  style="display:none;">助教部 | 一级原因</td>
                        <td  style="display:none;">助教部 | 二级原因</td>
                        <td  style="display:none;">助教部 | 三级原因</td>
                        <td  style="display:none;">助教部 | 扣分值</td>
                        <td  style="display:none;">助教部 | 原因分析</td>

                        <td  style="display:none;">教务部 | 一级原因</td>
                        <td  style="display:none;">教务部 | 二级原因</td>
                        <td  style="display:none;">教务部 | 三级原因</td>
                        <td  style="display:none;">教务部 | 扣分值</td>
                        <td  style="display:none;">教务部 | 原因分析</td>

                        <td  style="display:none;">老师管理 | 一级原因</td>
                        <td  style="display:none;">老师管理 | 二级原因</td>
                        <td  style="display:none;">老师管理 | 三级原因</td>
                        <td  style="display:none;">老师管理 | 扣分值</td>
                        <td  style="display:none;">老师管理 | 原因分析</td>

                        <td  style="display:none;">教学部 | 一级原因</td>
                        <td  style="display:none;">教学部 | 二级原因</td>
                        <td  style="display:none;">教学部 | 三级原因</td>
                        <td  style="display:none;">教学部 | 扣分值</td>
                        <td  style="display:none;">教学部 | 原因分析</td>

                        <td  style="display:none;">产品问题 | 一级原因</td>
                        <td  style="display:none;">产品问题 | 二级原因</td>
                        <td  style="display:none;">产品问题 | 三级原因</td>
                        <td  style="display:none;">产品问题 | 扣分值</td>
                        <td  style="display:none;">产品问题 | 原因分析</td>

                        <td  style="display:none;">咨询部 | 一级原因</td>
                        <td  style="display:none;">咨询部 | 二级原因</td>
                        <td  style="display:none;">咨询部 | 三级原因</td>
                        <td  style="display:none;">咨询部 | 扣分值</td>
                        <td  style="display:none;">咨询部 | 原因分析</td>

                        <td  style="display:none;">客户情况变化 | 一级原因</td>
                        <td  style="display:none;">客户情况变化 | 二级原因</td>
                        <td  style="display:none;">客户情况变化 | 三级原因</td>
                        <td  style="display:none;">客户情况变化 | 扣分值</td>
                        <td  style="display:none;">客户情况变化 | 原因分析</td>

                        <td  style="display:none;">其他原因</td>
                        <td  style="display:none;">QC整体分析</td>
                        <td  style="display:none;">后期应对措施及工作调整方案</td>

                        <td  style="display:none;">责任鉴定 | 助教部</td>
                        <td  style="display:none;">责任鉴定 | 教务部</td>
                        <td  style="display:none;">责任鉴定 | 老师管理</td>
                        <td  style="display:none;">责任鉴定 | 教学部</td>
                        <td  style="display:none;">责任鉴定 | 产品问题</td>
                        <td  style="display:none;">责任鉴定 | 咨询部</td>
                        <td  style="display:none;">责任鉴定 | 客户情况变化</td>
                        <td  style="display:none;">责任鉴定 | 老师</td>
                        <td  style="display:none;">责任鉴定 | 科目</td>
                        <td  style="display:none;">处理人</td>
                        <td  style="display:none;">处理时间</td>


                        <td style="min-width:120px;">操作</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr>
                            <td >
                                {{$var["user_nick"]}} <br/>
                                {{$var["phone"]}} <br/>
                            </td>
                            <td >{{$var["grade_str"]}}</td>
                            <td >
                                时间:{{$var["order_time_str"]}} <br/>
                                类型:{{$var["contract_type_str"]}} <br/>
                                总课时:{{$var["lesson_total"]}} <br/>
                                原价:{{$var["discount_price"]}} <br/>
                                实付:{{$var["price"]}} <br/>
                            </td>
                            <td >{{$var["sys_operator"]}}</td>
                            <td >{{$var["order_operator"]}}</td>
                            <td >{{$var["order_time_str"]}}</td>
                            <td >{{$var["contract_type_str"]}}</td>
                            <td >{{$var["lesson_total"]}}</td>
                            <td >{{$var["should_refund"]}}</td>
                            <td >{{$var["discount_price"]}}</td>
                            <td >{{$var["price"]}}</td>
                            <td >{{$var["should_refund_money"]}}</td>
                            <td >{{$var["real_refund"]}}</td>
                            <td >{{$var["need_receipt_str"]}}</td>
                            <td >{{$var["invoice"]}}</td>
                            <td >{{$var["pay_account"]}}</td>
                            <td >{{$var["pay_account_admin"]}}</td>
                            <td >{{mb_substr($var["refund_info"],0,50 )}}...</td>
                            <td >{{mb_substr($var["save_info"],0,50 )}}...</td>

                            <td >{{$var["apply_time_str"]}}</td>
                            <td >{{$var["refund_user"]}}</td>
                            <td >{{$var["refund_user_name"]}}</td>
                            <td >{!!$var["flow_status_str"]!!}</td>
                            <td >{{$var["flow_status_time"]}}</td>
                            <td >{{$var["refund_status_str"]}}</td>

                            <td >{!! $var["is_staged_flag_str"] !!}</td>

                            <td >{{$var["ass_nick"]}}</td>
                            <td >{{$var["subject_str"]}}</td>
                            <td >{{$var["tea_nick"]}}</td>
                            <td >{{@$var["duty_str"]}}</td>

                            <td >{{@$var["min_time_str"]}}</td>
                            <td >{{@$var["max_time_str"]}}</td>

                            <td >{{$var["qc_contact_status_str"]}}</td>
                            <td >{{$var["qc_advances_status_str"]}}</td>
                            <td >{{$var["qc_voluntarily_status_str"]}}</td>
                            <td >{!!@$var["is_pass"]!!}</td>

                            <td>{{@$var["助教部一级原因"]}}</td>
                            <td>{{@$var["助教部二级原因"]}}</td>
                            <td>{{@$var["助教部三级原因"]}}</td>
                            <td>{{@$var["助教部dep_score"]}}</td>
                            <td>{{@$var["助教部reason"]}}</td>

                            <td>{{@$var["教务部一级原因"]}}</td>
                            <td>{{@$var["教务部二级原因"]}}</td>
                            <td>{{@$var["教务部三级原因"]}}</td>
                            <td>{{@$var["教务部dep_score"]}}</td>
                            <td>{{@$var["教务部reason"]}}</td>

                            <td>{{@$var["老师管理一级原因"]}}</td>
                            <td>{{@$var["老师管理二级原因"]}}</td>
                            <td>{{@$var["老师管理三级原因"]}}</td>
                            <td>{{@$var["老师管理dep_score"]}}</td>
                            <td>{{@$var["老师管理reason"]}}</td>

                            <td>{{@$var["教学部一级原因"]}}</td>
                            <td>{{@$var["教学部二级原因"]}}</td>
                            <td>{{@$var["教学部三级原因"]}}</td>
                            <td>{{@$var["教学部dep_score"]}}</td>
                            <td>{{@$var["教学部reason"]}}</td>

                            <td>{{@$var["产品问题一级原因"]}}</td>
                            <td>{{@$var["产品问题二级原因"]}}</td>
                            <td>{{@$var["产品问题三级原因"]}}</td>
                            <td>{{@$var["产品问题dep_score"]}}</td>
                            <td>{{@$var["产品问题reason"]}}</td>

                            <td>{{@$var["咨询部一级原因"]}}</td>
                            <td>{{@$var["咨询部二级原因"]}}</td>
                            <td>{{@$var["咨询部三级原因"]}}</td>
                            <td>{{@$var["咨询部dep_score"]}}</td>
                            <td>{{@$var["咨询部reason"]}}</td>

                            <td>{{@$var["客户情况变化一级原因"]}}</td>
                            <td>{{@$var["客户情况变化二级原因"]}}</td>
                            <td>{{@$var["客户情况变化三级原因"]}}</td>
                            <td>{{@$var["客户情况变化dep_score"]}}</td>
                            <td>{{@$var["客户情况变化reason"]}}</td>

                            <td  >{{@$var["qc_other_reason"]}}</td>
                            <td  >{{@$var["qc_analysia"]}}</td>
                            <td  >{{@$var["qc_reply"]}}</td>

                            <td  >{{@$var["助教部责任值"]}}</td>
                            <td  >{{@$var["教务部责任值"]}}</td>
                            <td  >{{@$var["老师管理责任值"]}}</td>
                            <td  >{{@$var["教学部责任值"]}}</td>
                            <td  >{{@$var["产品问题责任值"]}}</td>
                            <td  >{{@$var["咨询部责任值"]}}</td>
                            <td  >{{@$var["客户情况变化责任值"]}}</td>
                            <td  >{{@$var["老师责任值"]}}</td>
                            <td  >{{@$var["科目责任值"]}}</td>

                            <td  >{{@$var["deal_nick"]}}</td>
                            <td  >{{@$var["qc_deal_time"]}}</td>
                            <td >
                                <div
                                    {!!\App\Helper\Utils::gen_jquery_data($var)!!}
                                >
                                    <a class="fa-user opt-user " title="个人信息" ></a>
                                    <a class="btn fa fa-money opt-change-state" title="确认退费"></a>
                                    <a class="btn fa fa-download opt-file_url " title="下载退费附件"></a>
                                    <a class="btn fa fa-list opt-desc" title="明细"></a>
                                    <a class="btn fa fa-facebook opt-flow-node-list" title="审核进度"></a>
                                    <a class="fa-trash-o opt-cancel-refund" title="取消退费"></a>
                                    <a class="fa-gavel opt-confirm" title="退费原因"></a>
                                    <!-- <a class=" fa-list   opt-refund_responsibility" title="退费责任鉴定"></a>
                                         <a class=" fa-comment   opt-analysia" title="QC退费分析"></a> -->
                                    <a href="/user_manage/refund_analysis?orderid={{$var['orderid']}}&apply_time={{$var['apply_time']}}" class=" fa-list " title="QC退费分析总表"></a>
                                    <a class=" btn fa opt-complaint" id="id_complained_adminid" title="退费投诉">投</a>
                                    @if(in_array($acc,["echo","jim","zero"]))
                                        <a class=" btn fa fa-cny opt-set-money" title="修改退费金额"></a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <a style="display:none;" id="adminid" data-adminid="{{$adminid}}"></a>
            @include("layouts.page")
        </div>
    </section>
@endsection
