@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >

            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>id </td>
                    <td>申请人 </td>
                    <td>手机 </td>
                    <td>可提现 </td>
                    <td>提现金额 </td>
                    <td>提现类型 </td>
                    <td>银行卡号 </td>
                    <td>银行卡类型 </td>
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
                    <tr>
                        <td>{{@$var["id"]}} </td>
                        <td>{{@$var["nickname"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["cash"]}} </td>
                        @if($var['type'] == 1)
                            <td>银行卡</td>
                        @elseif($var['type'] == 2)
                            <td>支付宝</td>
                        @else
                            <td></td>
                        @endif
                        <td>{{@$var["bankcard"]}} </td>
                        <td>{{@$var["bank_type"]}} </td>
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
                                <!-- <a class="fa fa-times opt-del" title="删除"> </a> -->
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
