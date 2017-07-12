@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="input-group ">
                    <div id="id_date_range">
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-2">
                <div class="input-group ">
                    <span >平均月耗课时:</span>
                    <input type="text" id="id_month_cost" />
                </div>
            </div>
            <div class="col-xs-12 col-md-2">
                <div class="input-group ">
                    <span >7,8 月耗课时:</span>
                    <input type="text" id="id_month_cost_ex" />
                </div>
            </div>
            <div class="col-xs-12 col-md-3">
                <div class="input-group ">
                    <span >续费率(填写整数,如85%,填写85即可):</span>
                    <input type="text" id="id_renewal_rate" />
                </div>
            </div>
            <div class="col-xs-12 col-md-2">
                <div class="input-group ">
                    <span >筛选归类方式:</span>
                    <select id="id_select_type">
                        <option value="0">学生</option>
                        <option value="1">科目</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-2">
                <div class="input-group ">
                    <span >学生类型:</span>
                    <select id="id_student_type">
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-2">
                <div class="input-group ">
                    <span>排序类型:</span>
                    <select id="id_order_str">
                        <option value="0">升序</option>
                        <option value="1">降序</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span>人数统计:</span>
                    <input type="text" id="id_count" value="{{$list_count}}" readOnly="true" />
                </div>
            </div>
            <div class="col-xs-3 col-md-1">
                <div class="input-group ">
                    <button class="btn btn-primary" id="id_submit">提交</button>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group">
                    <button class="btn btn-primary" id="id_change" data-show="1">显示人数分布</button>
                </div>
            </div>
        </div>
        <hr />
        <div class="body" id="stu_lesson_total_info">
            <table class="common-table">
                <thead>
                    <tr>
                        <td >姓名</td>
                        <td >科目</td>
                        <td >剩余课时</td>
                        <td >续费时长</td>
                        <td >操作</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr>
                            <td >{{@$var["nick"]}}</td>
                            <td >{{@$var["subject_str"]}}</td>
                            <td >{{@$var["lesson_left"]}}</td>
                            <td >{{@$var["renewal_day"]}}</td>
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
        <div class="body" id="time_count_list" style="display:none">
            <table class="common-table ">
                <thead>
                    <tr>
                        <td >月份</td>
                        <td >人数</td>
                        <td >操作</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach($time_count_list as $key=>$var)
                            <tr>
                                <td >{{@$key}}</td>
                                <td >{{@$var}}</td>
                                <td>
                                    <div class="opt-div"
                                    >
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
@endsection
