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
                    <td>提现金额 </td>
                    <td>提现类型 </td>
                    <td>财务审核 </td>
                    <td>提现状态 </td>
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
                        @if($var['check_money_flag'] == 1)
                            <td>通过</td>
                        @else
                            <td>未通过</td>
                        @endif
                        @if($var['is_suc_flag'] == 1)
                            <td>成功</td>
                        @else
                            <td>失败</td>
                        @endif
                        <td>{{@$var["create_time"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <!-- <a class="fa fa-edit opt-edit"  title="编辑"> </a> -->
                                <a class="fa-gavel opt-money-check " title="财务确认" ></a>
                                <a class="fa fa-times opt-del" title="删除"> </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
