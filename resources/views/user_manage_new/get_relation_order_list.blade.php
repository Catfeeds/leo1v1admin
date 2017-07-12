@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>当前</td>
                    <td>时间</td>
                    <td>申请人</td>
                    <td>分类</td>
                    <td>赠送原因</td>
                    <td>课时数</td>
                    <td>金额</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["self_flag_str"]}} </td>
                        <td>{{$var["order_time"]}} </td>
                        <td>{{$var["sys_operator"]}} </td>
                        <td>{{$var["contract_type_str"]}} </td>
                        <td>{{@$var["from_parent_order_type_str"]}} </td>
                        <td>{{$var["lesson_count"]}} </td>
                        <td>{{$var["price"]}} </td>
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

