@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content">
        <div class="row">
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span >时间</span>
                    <select id="id_year" class="opt-change">
                        <option value="2016-12">2016年12月 </option>
                        <option value="2017-1">2017年1月 </option>
                    </select>
                </div>
            </div>
            @if($teacher_honor>0)
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">荣誉奖:</span>
                    <input value="{{@$teacher_honor}}"> 
                </div>
            </div>
            @endif
            @if($teacher_money_type==4)
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">签单奖:</span>
                    <input value="{{@$teacher_trial}}"> 
                </div>
            </div>
            @endif
            @if($teacher_tax_money>0)
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">平台管理费:</span>
                    <input value="{{@$teacher_tax_money}}"> 
                </div>
            </div>
            @endif
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">总工资:</span>
                    <input value="{{@$teacher_all_money}}"> 
                </div>
            </div>
        </div>
        <hr />
        <div class="body">
            <table class="common-table ">
                <thead>
                    <tr>
                        <td >学生</td>
                        <td >课时消耗分组</td>
                        <td >课程类型</td>
                        <td >合计金额</td>
                        <td >课时数</td>
                        <td >课时基础价格</td>
                        <td >奖金基础价格</td>
                        <td >全勤奖</td>
                        <td >课程扣款</td>
                        <td >扣款信息</td>
                        <td >上课时间</td>
                        <td >状态</td>
                        <td >年级</td>
                        <td >科目</td>
                        <td >老师等级</td>
                        <td >累计课时数</td>
                        <td >操作</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr class="{{$var["level"]}}">
                            <td data-class_name="{{$var["key1_class"]}}" class="key1" >{{@$var["stu_nick"]}}</td>
                            <td data-class_name="{{$var["key2_class"]}}" class="key2  {{$var["key1_class"]}}  {{$var["key2_class"]}} " >{{@$var["lesson_count_level_str"]}} </td>
                            <td data-class_name="{{$var["key3_class"]}}" class="key3  {{$var["key2_class"]}} {{$var["key3_class"]}}  "  >{{@$var["lesson_type_str"]}}</td>
                            <td >{{$var["price"]}}</td>
                            <td style="{{@$var["lesson_count_err"]}}">
                                {{$var["lesson_count"]/100}}</td>

                            <td >{{@$var["pre_price"]}}</td>
                            <td >{{@$var["pre_reward"]}}</td>
                            <td >{{@$var["lesson_full_reward"]}}</td>
                            <td >{{@$var["lesson_cost"]}}</td>
                            <td >{{@$var["lesson_cost_info"]}}</td>
                            <td >{{@$var["lesson_time"]}}</td>
                            <td >{{@$var["confirm_flag_str"]}}</td>
                            <td >{{@$var["grade_str"]}}</td>
                            <td >{{@$var["subject_str"]}}</td>
                            <td >{{@$var["level_str"]}}</td>
                            <td >{{isset($var["already_lesson_count"])?@$var["already_lesson_count"]/100:""}}</td>
                            <td>
                                <div class="opt-div"
                                     {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include("layouts.page")
        </div>
    <script src="/js/qiniu/plupload/plupload.full.min.js"></script>
@endsection

