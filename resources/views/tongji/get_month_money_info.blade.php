@extends('layouts.app')
@section('content')
    <section class="content ">
        <div>
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td>月份 </td>
                    <td>收入</td>
                    <td>签单课时</td>
                    <td>签单数</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["order_month"]}} </td>
                        <td>{{$var["all_money"]}} </td>
                        <td>{{(float)$var["order_total"]/100}} </td>
                        <td>{{@$var["count"]}} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection

