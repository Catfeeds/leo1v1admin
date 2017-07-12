@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="input-group ">
                    <div id="id_date_range">
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span >老师 </span>
                    <input type="text" class="opt-change" id="id_teacherid" placeholder=""/>
                </div>
            </div>
            <div class="col-xs-6 col-md-2" >
                <div class="input-group ">
                    <span >工资分类</span>
                    <select id="id_teacher_money_type" class="opt-change" ></select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span >科目</span>
                    <select id="id_subject" class="opt-change" ></select>
                </div>
            </div>
        </div>
        <hr />
        <div class="body" >
            <table class="common-table">
                <thead>
                    <tr>
                        <td >姓名</td>
                        <td >工资分类</td>
                        <td >科目</td>
                        <td >试听课次</td>
                        <td >金额</td>
                        <td >操作</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr>
                            <td >{{$var["nick"]}}</td>
                            <td >{{$var["teacher_money_type_str"]}}</td>
                            <td >{{$var["subject_str"]}}</td>
                            <td >{{$var["lesson_total"]}}</td>
                            <td >{{$var["trial_money"]}}</td>
                            <td>
                                <div class="opt-div"
                                     {!!  \App\Helper\Utils::gen_jquery_data($var)  !!}
                                >
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include("layouts.page")
        </div>
@endsection
