@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content">
        <div class="row">
            <div class="col-xs-6 col-md-3">
                <div id="id_date_range">
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td>教师分类</td>
                    <td>教师等级</td>
                    <td>教师人数</td>
                    <td>教师人数(%)</td>
                    <td>课堂数</td>
                    <td>课堂数(%)</td>
                    <td>课时数</td>
                    <td>课时数(%)</td>
                    <td>老师最终工资</td>
                    <td>老师最终工资(%)</td>
                    <td>课程金额</td>
                    <td>课程金额(%)</td>
                    <td>人工成本占比(%)</td>
                    <td>老师扣款</td>
                    <td>老师扣款(%)</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
				    <tr>
                        <td >{{$var["teacher_money_type_str"]}}</td>
                        <td >{{$var["level_str"]}}</td>
                        <td >{{$var["tea_num"]}}</td>
                        <td >{{$var["tea_num_percent"]}}</td>
                        <td >{{$var["lesson_num"]}}</td>
                        <td >{{$var["lesson_num_percent"]}}</td>
                        <td >{{$var["lesson_total"]}}</td>
                        <td >{{$var["lesson_total_percent"]}}</td>
                        <td >{{$var["last_price"]}}</td>
                        <td >{{$var["last_price_percent"]}}</td>
                        <td >{{$var["lesson_price"]}}</td>
                        <td >{{$var["lesson_price_percent"]}}</td>
                        <td >{{$var["final_percent"]}}</td>
                        <td >{{$var["cost"]}}</td>
                        <td >{{$var["cost_percent"]}}</td>
                        <td >
                            <div class="btn-group">
                            </div>
                        </td>
				    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection

