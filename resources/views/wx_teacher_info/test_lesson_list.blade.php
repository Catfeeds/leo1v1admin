@extends('layouts.teacher_header')
@section('content')

    <section class="content">
        <div>
            <div class="row">
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >分类</span>
                        <select class="opt-change" id="id_opt_type">
                            <option value="0">可抢单</option>
                            <option value="1">已抢单</option>
                            <option value="2">过期或已被人抢单</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <table class="common-table " style="margin-top:10px">
            <thead>
                <tr>
                    <td >年级</td>
                    <td >科目</td>
                    <td >上课时间</td>
                    <td>操作 </td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td>{{$var["grade_str"]}}</td>
                        <td>{{$var["subject_str"]}}</td>
                        <td>{{$var["st_class_time_str"]}}</td>
                        
                        <td>
                            <div 
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class=" opt-confrim " title="抢单" > </a>
                            </div>
                        </td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")

    </section>
@endsection

