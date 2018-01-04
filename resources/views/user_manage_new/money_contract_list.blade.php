@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/flow.js"></script>
    <script type="text/javascript" >
    </script>

    <section class="content">
        <div class="row row-query-list">
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">分类</span>
                    <select class="c_sel form-control" id="id_contract_type">
                        <option value="-2">正式1v1课程</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">1v1分类</span>
                    <select class="c_sel form-control" id="id_from_type">
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="input-group ">
                    <span class="input-group-addon">下单时间:</span>
                    <input type="text" class=" form-control " id="id_start_time" />
                    <span class="input-group-addon">-</span>
                    <input type="text" class=" form-control "  id="id_end_time" />
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">检查状态</span>
                    <select class="c_sel form-control" id="id_check_money_flag">
                    </select>
                </div>
            </div>

            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">状态</span>
                    <input class="opt-change form-control" id="id_contract_status" /> 
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span >学生</span>
                    <input id="id_studentid"  />
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">角色</span>
                    <select class="c_sel form-control" id="id_account_role">
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <input type="text" value="" class="form-control opt-change"  data-field="origin" id="id_origin"  placeholder="渠道, 回车查找" />
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <input id="id_sys_operator"  class="opt-change" placeholder="下单人,回车搜索" />
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">需要发票</span>
                    <select class="opt-change form-control" id="id_need_receipt" >
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">测试用户</span>
                    <select class="opt-change form-control" id="id_is_test_user" >
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">是否分期</span>
                    <select class="opt-change form-control" id="id_can_period_flag" >
                    </select>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row have_userid">
            <div class="col-xs-12 col-md-8">
                <div class="input-group ">
                    <span class="input-group-addon">当前页总金额:{{$money_all}}</span>
                    <span class="input-group-addon">合同数:{{$order_count}}</span>
                    <span class="input-group-addon">人数:{{$user_count}}</span>
                    <span class="input-group-addon"> <a href="javascript:;" id="id_show_all">全部</a></span>
                </div>
            </div>
        </div>
        <table   class="common-table have_userid"   >
            <thead>
                <tr>
                    <td >上课时间</td>
                    <td >一天内下单</td>
                    <td >一天内确认</td>
                    <td >下单时间</td>
                    <td >下单人</td>
                    <td >学员姓名</td>
                    <td style="display:none;" >渠道</td>
                    <td style="display:none;" >家长姓名</td>
                    <td style="display:none;" >1v1分类</td>
                    <td style="display:none;" >年级</td>
                    <td style="display:none;" >联系电话</td>
                    <td >合同状态</td>
                    <td >合同类型</td>
                    <td style="display:none;" >生效日期</td>
                    <td >课时数</td>
                    <td >原价</td>
                    <td style="min-width:80px;" >促销</td>
                    <td style="min-width:100px;">特殊折扣申请状态</td>
                    <td >实付金额</td>
                    <td >定金</td>
                    <td >定金订单号</td>
                    <td >(尾款)订单号</td>
                    <td style="display:none;" >包类型</td>
                    <td >是否需要发票</td>
                    <td >发票信息</td>
                    <td >发票编号</td>
                    <td >是否合同盖章</td>
                    <td >财务确认状态</td>
                    <td >财务确认人</td>
                    <td >财务确认时间</td>
                    <td >财务确认说明/是否分期</td>
                    <td  >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td >{{$var["lesson_start"]}} </td>
                        <td >{{$var["order_time_1_day_flag_str"]}}</td>
                        <td >{{$var["check_money_time_1_day_flag_str"]}}</td>
                        <td >{{$var["order_time"]}} </td>
                        <td >
                            @if($var["order_set_name"])
                                {{$var["order_set_name"]}}
                            @else
                                {{$var["sys_operator"]}}
                            @endif
                        </td>
                        <td class="stu_nick" >{{$var["stu_nick"]}} </td>
                        <td >{{$var["origin"]}}</td>
                        <td >{{$var["parent_nick"]}}</td>
                        <td >{{$var["stu_from_type_str"]}}</td>
                        <td >{{$var["grade_str"]}}</td>
                        <td >{{$var["phone"]}}</td>
                        <td >{{$var["contract_status"]}}</td>
                        <td >
                            @if ($var["contract_type"]==1)
                                {{$var["from_parent_order_type_str"]}}
                            @else
                                {{$var["contract_type_str"]}}
                            @endif
                        </td>

                        <td class="contract_starttime"  >{{$var["contract_starttime"]}}</td>
                        <td >{{$var["lesson_total"] * $var["default_lesson_count"]/100}}</td>
                        <td >{{$var["discount_price"]/100}}</td>
                        <td >
                            {{$var["order_promotion_type_str"]}}
                            @if  ( $var["order_promotion_type"] ==1 )
                              :{{$var["promotion_present_lesson"]/100}}课时
                            @elseif ( $var["order_promotion_type"]==2 )
                              :{{$var["promotion_discount_price"]/100}}元
                              ({{intval($var["promotion_discount_price"]*100/$var["discount_price"])}}折)
                            @endif
                        </td>
                        <td>
                            {!!$var["flow_status_str"]!!}<br/>
                            @if ($var["flow_status"]  )
                                @if ($var["promotion_present_lesson"] !=$var["promotion_spec_present_lesson"])
                                    赠送 :{{$var["promotion_spec_present_lesson"]/100}}课时
                                @endif
                                @if ($var["promotion_discount_price"] !=$var["promotion_spec_discount"])
                                    价格:{{$var["promotion_spec_discount"]/100}}元
                                    ({{intval($var["promotion_spec_discount"]*10000/$var["discount_price"])/100}}折)
                                @endif
                            @endif
                        </td>
                        <td >{{$var["price"]}}</td>
                        <td >{{$var["pre_status"]}}</td>
                        <td >{{$var["pre_from_orderno"]}}</td>
                        <td >{{$var["from_orderno"]}}</td>
                        <td >{{$var["from_type_str"]}}</td>
                        <td >{{$var["is_invoice_str"]}}</td>
                        <td >{{$var["title"]}}</td>
                        <td >{{$var["invoice"]}}</td>
                        <td >{{$var["order_stamp_flag_str"]}}</td>
                        <td >{{$var["check_money_flag_str"]}}</td>
                        <td >{{$var["check_money_admin_nick"]}}</td>
                        <td >{{$var["check_money_time"]}}</td>
                        <td >{{$var["check_money_desc"]}}/{!! $var["can_period_flag_str"] !!}</td>
                        <td >
                            <div class="btn-group"
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa-user opt-user " title="个人信息" ></a>
                                <a class="fa-gavel opt-money-check " title="财务确认" ></a>
                                <a class="fa-edit opt-edit-invoice " title="编辑" ></a>
                                <a class="fa-link opt-relation-order" title="关联的合同信息"> </a>
                                <a class="fa opt-flow-node-list fa-facebook " title="审核进度"></a>
                                <a class="fa opt-update-order-time" title="修改下单时间">下单时间</a>
                                <a class="fa opt-update-price" title="修改合同价格">价格</a>
                                <a class="opt-del" title="删除合同"> 删除</a>
                                @if($var["price"]>0)
                                    <a class="fa opt-order-partition-info" title="子合同详情">子合同详情</a>
                                    @if(in_array($account,["jack","zero","jim","adrian"]) )
                                        <a class="fa opt-child-order-trandfer" title="子合同转移">子合同转移</a>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
            @include("layouts.page")

    </section>

    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>


@endsection
