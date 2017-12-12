
@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span>老师</span>
                        <input class="opt-change" id="id_teacher"/>
                    </div>
                </div>
                
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>老师姓名 </td>
                    <td>迟到5分钟 </td>
                    <td>迟到15分钟 </td>
                    <td>离开20分钟 </td>
                    <td>旷课次数  </td>
                    <td>调课 </td>
                    <td>请假 </td>
                    <td>大单数 </td>
                    <td>预警指数 </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td><a class="opt-detail" data_teacher="{{$var['teacherid']}}">{{$var["nick"]}}</a> </td>
                        <td>{{$var['five_num']}}</td>
                        <td>{{$var['fift_num']}}</td>
                        <td></td>
                        <td>{{$var['absent_num']}}</td>
                        <td>{{$var['adjust_num']}}</td>
                        <td>{{$var['ask_leave_num']}}</td>
                        <td>{{$var['big_order_num']}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
