@extends('layouts.app')
@section('content')
    <section class="content">
    <div class="row">
        
        <div class="col-xs-2 col-md-2">
            <div class="input-group">
                <span class="input-group-addon">合同类型</span>
                <select class="opt-change form-control" id="id_contract_type">
                </select>
            </div>
        </div>      

        <div class="col-xs-2 col-md-2">
            <div class="input-group">
                <span class="input-group-addon">是否分期</span>
                <select class="opt-change form-control" id="id_period_flag">
                </select>
            </div>
        </div>

        <div class="col-xs-2 col-md-2">
            <div class="input-group">
                <span class="input-group-addon">是否手动开启</span>
                <select class="opt-change form-control" id="id_can_disable_flag">
                </select>
            </div>
        </div>

        <div class="col-xs-2 col-md-2">
            <div class="input-group">
                <span class="input-group-addon">是否开启活动</span>
                <select class="opt-change form-control" id="id_open_flag">
                </select>
            </div>
        </div>

        <div class="col-xs-2 col-md-2">
            <div class="input-group">
                <div class=" input-group-btn ">
                    <button id="id_add_activity" type="submit"  class="btn  btn-warning" >
                     <i class="fa fa-plus"></i>添加活动
                    </button>
                </div>
            </div>
        </div>
        
    </div>

    <hr/>

        <table   class="common-table"   >
            <thead>
                <tr>
                    <td >活动标题</td>
                    <td >活动时间</td>
                    <td >活动适配年级</td>
                    <td >合同类型</td>
                    <td >分期试用</td>
                    <td >课时区间</td>
                    <td >优惠类型</td>
                    <td >是否开启</td>
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
            <tr>
                <td >{{$var["title"]}}</td>
                <td >{{$var["date_range_time"]}}</td>
                <td >{{$var["grade_list_str"]}}</td>
                <td >{{$var["contract_type_list_str"]}}</td>
                <td >{{$var["period_flag_list_str"]}}</td>
                <td >{{$var["lesson_times_range"]}}</td>
                <td >{{$var["order_activity_discount_type_str"]}}</td>
                <td >{{$var["open_flag_str"]}}</td>
                <td >
                    <div 
                        {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                    >
                        <a class=" fa-edit opt-set" title="编辑活动"> </a>
                        <a class="fa fa-times opt-del" title="删除"> </a>
                    </div>
                </td>
            </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")

    </section>
    <div class="grade_arr hide">
        @foreach ($gradeArr as $var => $val)
            <label><input name="re_submit" type="checkbox" value="{{$var}}" />{{$val}}</label>
        @endforeach
    </div>

    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>

@endsection

