@extends('layouts.teacher_header')
@section('content')
    <script>
     var lecture_status = {{$lecture_status}}
    </script>
    <section class="content">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div id="id_date_range"></div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">试讲状态</span>
                        <select class="opt-change form-control" id="id_status">
                            <option value="-2">无试讲</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group">已通过</span>
                        <input value="{{@$count_num["pass"]}}">
                        <span class="input-group">无试讲,未通过,可重申,视屏出错</span>
                        <input value="{{@$count_num["not_pass"]}}">
                        <span class="input-group">总计</span>
                        <input value="{{@$count_num["all"]}}">
                    </div>
                </div>
            </div>
        </div>
        <hr />
        <table class="common-table"> 
            <thead>
                <tr>
                    <td width="80px">教师姓名</td>
                    <td>联系方式</td>
                    <td width="80px">审核状态</td>
                    <td>审核原因</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["name"]}} </td>
                        <td>
                            {{@$var["phone"]}}
                            <br/>
                            {{@$var["email"]}}
                        </td>
                        <td>
                            {{@$var["status_str"]}}
                            @if($var["status"]==1)
                                <br>
                                确认时间:{{@$var["confirm_time_str"]}}
                            @endif
                        </td>
                        <td>{{@$var["reason"]}} </td>
                        <td>
                            <div {!!  \App\Helper\Utils::gen_jquery_data($var)  !!} >
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
