@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row" >

                <div class="col-xs-6 col-md-8">
                    <div class="input-group">
                        <span class="input-group">老师总人数</span>
                        <span id="id_teacher_money">{{$total}}</span>
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>老师姓名</td>
                    <td>单击次数 </td>
                    <td>分享次数 </td>
                    <td>注册次数 </td>
                    <td>当前积分 </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $info as $var )
                    <tr>
                        <td>{{$var["nick"]}}</td>
                        <td>{{$var['click_num']}}</td>
                        <td>{{$var['share_num']}}</td>
                        <td>{{$var['register_num']}}</td>
                        <td>{{$var['score']}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @include("layouts.page")
    </section>

@endsection
