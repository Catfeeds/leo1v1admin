@extends('layouts.app')
@section('content')

    <section class="content ">
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>订单ID</td>
                    <td>用户</td>
                    <td>电话</td>
                    <td>金额</td>
                    <td>订单完成时间</td>
                    <td>渠道</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["orderid"]}} </td>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["price"]}} </td>
                        <td>{{@$var["pay_time"]}} </td>
                        <td>{{@$var["check_value"]}} </td>
                        <td>
                            <div
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

