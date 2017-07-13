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
                    <td>年级</td>
                    <td>1对1课时</td>
                    <td>1对1老师工资</td>
                    <td>1对1收入</td>
                    <td>试听课时</td>
                    <td>试听老师工资</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
				    <tr>
                        <td >{{$var["grade_str"]}}</td>
                        <td >{{$var["normal_count"]}}</td>
                        <td >{{$var["normal_money"]}}</td>
                        <td >{{$var["lesson_price"]}}</td>
                        <td >{{$var["trial_count"]}}</td>
                        <td >{{$var["trial_money"]}}</td>
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

