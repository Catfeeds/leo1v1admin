@extends('layouts.app')
@section('content')
    <section class="content ">
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

            </div>
        </div>

        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td>月份 </td>
                    <td>收入</td>
                    <td>签单课时</td>
                    <td>签单数</td>
                    <td>课耗收入</td>
                    <td>已消耗课时</td>
                    <td>上课人数</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["order_month"]}} </td>
                        <td>{{$var["all_money"]}} </td>
                        <td >{{(float)$var["order_total"]/100}} </td>
                        <td >{{@$var["count"]}} </td>
                        <td >{{@$var["lesson_count_money"]}} </td>
                        <td >{{@$var["lesson_count"]}} </td>
                        <td >{{@$var["lesson_stu_num"]}} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
