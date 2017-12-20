@extends('layouts.app')
@section('content')

    <section class="content " style="overflow-x:scroll;">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-6 col-md-2" data-always_show="1">
                    <div class="input-group ">
                        <span class="input-group-addon">财务审核状态</span>
                        <select class="opt-change form-control" id="id_agent_check_money_flag" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">手机</span>
                        <input class="opt-change form-control" id="id_phone" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">提现人昵称</span>
                        <input class="opt-change form-control" id="id_nickname" />
                    </div>
                </div>
                <div class="col-xs-12 col-md-6"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">单笔提现额度</span>
                        <input class="opt-change form-control" id="id_cash_range" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">审核人</span>
                        <select id="id_check_money_admin_nick">
                            <option value ="">全部</option>
                            <option value ="amamda">amanda</option>
                            <option value ="echo">echo</option>
                            <option value="chenyu">chenyu</option>
                            <option value="-1">其他</option>
                        </select>
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <div class="row have_userid">
            <div class="col-xs-12 col-md-4">
                <div class="input-group ">
                    <span class="input-group-addon">申请人数:{{@$cash_person_count}}人</span>
                    <span class="input-group-addon">申请次数:{{@$cash_count}}次</span>
                    <span class="input-group-addon">驳回金额:{{@$cash_refuse_money}}元</span>
                    <span class="input-group-addon">冻结金额:{{@$cash_freeze_money}}元</span>
                </div>
            </div>
        </div>


        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>id </td>
                    <td>申请人 </td>
                    <td>手机 </td>
                    <td>可提现 </td>
                    <td>已提现 </td>
                    <td>提现金额 </td>
                    <td>冻结金额</td>
                    <td>提现类型 </td>
                    <td>银行卡号 </td>
                    <td>银行卡类型 </td>
                    <td>持卡人 </td>
                    <td>银行预留手机号 </td>
                    <td>开户行和支行 </td>
                    <td>开户省 </td>
                    <td>开户市 </td>
                    <td>支付宝姓名 </td>
                    <td>支付宝账户 </td>
                    <td>财务审核状态 </td>
                    <td>财务审核人 </td>
                    <td>财务通过时间 </td>
                    <td>财务审批说明 </td>
                    <td>创建时间 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr @if(@$var['cash'] > @$var['all_open_cush_money']) style="color:red;" @endif>
                        <td>{{@$var["id"]}} </td>
                        <td>{{@$var["nickname"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["all_open_cush_money"]}} </td>
                        <td>{{@$var["all_have_cush_money"]}} </td>
                        <td>{{@$var["cash"]}} </td>
                        <td>{{@$var["agent_cash_money_freeze"]}} </td>
                        @if($var['type'] == 1)
                            <td>银行卡</td>
                        @elseif($var['type'] == 2)
                            <td>支付宝</td>
                        @else
                            <td></td>
                        @endif
                        <td>{{@$var["bankcard"]}} </td>
                        <td>{{@$var["bank_type"]}} </td>
                        <td>{{@$var["bank_account"]}} </td>
                        <td>{{@$var["bank_phone"]}} </td>
                        <td>{{@$var["bank_address"]}} </td>
                        <td>{{@$var["bank_province"]}} </td>
                        <td>{{@$var["bank_city"]}} </td>
                        <td>{{@$var["zfb_name"]}} </td>
                        <td>{{@$var["zfb_account"]}} </td>
                        <td>{{@$var["agent_check_money_flag_str"]}} </td>
                        <td>{{@$var["check_money_admin_nick"]}} </td>
                        <td>{{@$var["check_money_time"]}} </td>
                        <td>{{@$var["check_money_desc"]}} </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <!-- <a class="fa fa-edit opt-edit"  title="编辑"> </a> -->
                                <a class="fa-gavel opt-money-check " title="财务确认" ></a>
                                <!--  <a class="fa fa-times opt-del" title="删除"> </a> -->
                                <a class="fa fa-hourglass-2 opt-freeze" title="冻结"> </a>
                                <a class="fa fa-sticky-note-o opt-freeze_reason" title="冻结原因"> </a>
                                <a class="fa fa-send-o opt-money_detail" title="提现金额来源明细"> </a>
                                
                                <a class="fa fa-wechat opt-wechat-desc"  title="微信数据"> </a>
                                <a class="fa fa-group  opt-user-link"  title="下线"> </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
