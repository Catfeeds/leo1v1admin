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
            <div class="col-xs-6 col-md-2" >
                <div class="input-group ">
                    <span >教师分类</span>
                    <select id="id_identity" class="opt-change" ></select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span >科目</span>
                    <select id="id_subject" class="opt-change" ></select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span >新教师筛选</span>
                    <select id="id_is_new_teacher" class ="opt-change" >
                        <option value="1"> 全部老师</option>
                        <option value="2"> 最新入职老师</option>
                        <option value="3"> 最近一周入职老师</option>
                        <option value="4"> 最近两周入职老师</option>
                        <option value="5"> 最近30天入职老师</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2" >
                <div class="input-group ">
                    <span >课堂次数</span>
                    <select id="id_count_type" class="opt-change" >
                        <option value="1">前10次</option>
                        <option value="2">中间10次</option>
                        <option value="3">后10次</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-3 col-md-1">
                <div class="input-group ">
                    <button class="btn btn-primary" id="id_submit">提交</button>
                </div>
            </div>
            <div class="col-xs-3 col-md-1">
                <div class="input-group ">
                    <button class="btn btn-primary" id="id_rate">计算签单率</button>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span>人数统计:</span>
                    <input type="text" id="id_count" value="{{$teacher_count}}" readOnly="true" />
                </div>
            </div>
        </div>
        <hr />
        <div class="body" id="stu_lesson_total_info">
            <table class="common-table">
                <thead>
                    <tr>
                        <td >姓名</td>
                        <td >教师分类</td>
                        <td >等级</td>
                        <td >科目</td>
                        <td class="lesson_time">试听时间</td>
                        <td class="have_order">签约数(合同)</td>
                        <td class="order_number">签约数(最终结果)</td>
                        <td class="order_per">签约率</td>
                        <td >操作</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr>
                            <td >{{@$var["nick"]}}</td>
                            <td >{{@$var["teacher_money_type_str"]}}</td>
                            <td >{{@$var["level_str"]}}</td>
                            <td >{{@$var["subject_str"]}}</td>
                            <td >{{@$var["lesson_time"]}}</td>
                            <td >{{@$var["have_order"]}}</td>
                            <td >{{@$var["order_number"]}}</td>
                            <td >{{@$var["order_per"]}}%</td>
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
