@extends('layouts.app')
@section('content')
    <section class="content">
    <div class="row">
        <div class="col-xs-2 col-md-2">
            <div class="input-group">
                <span class="input-group-addon">是否开启</span>
                <select class="opt-change form-control" id="id_open_flag">
                </select>
            </div>
        </div>
    </div>

    <hr/>

    <table   class="common-table"   >
        <thead>
            <tr>
                <td >活动ID</td>
                <td >活动标题</td>
                <td >活动时间</td>
                <td >活动适配年级</td>
                <td >合同类型</td>
                <td >分期试用</td>
                <td >课时区间</td>
                <td >优惠类型</td>
                <td >是否开启</td>
                <td >当前合同</td>
                <td >预期最大合同</td>
                <td style="display:none">是否需要特殊申请</td>
                <td style="display:none">是否手动开启</td>
                <td style="display:none">优惠力度</td>
                <td style="display:none">最大合同数</td>
                <td style="display:none">最大修改累计值</td>
                <td style="display:none">用户加入时间范围</td>
                <td style="display:none">最近一次试听时间范围</td>
                <td style="display:none">打包活动总配额</td>
                <td style="display:none">优惠信息</td>
                <td >操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
                <tr>
                    <td >{{$var["id"]}}</td>
                    <td >{{$var["title"]}}</td>
                    <td >{{$var["date_range_time"]}}</td>
                    <td >{{$var["grade_list_str"]}}</td>
                    <td >{{$var["contract_type_list_str"]}}</td>
                    <td >{{$var["period_flag_list_str"]}}</td>
                    <td >{{$var["lesson_times_range"]}}</td>
                    <td >{{$var["order_activity_discount_type_str"]}}</td>
                    <td >{{$var["open_flag_str"]}}</td>
                    <td >{{$var["max_count"]}}</td>
                    <td >{{$var["diff_max_count"]}}</td>

                    <td >{{$var["need_spec_require_flag_str"]}}</td>
                    <td >{{$var["can_disable_flag_str"]}}</td>
                    <td >{{$var["power_value"]}}</td>
                    <td >{{$var["max_count"]}}</td>
                    <td >{{$var["max_change_value"]}}</td>
                    <td >{{$var["user_join_time_range"]}}</td>
                    <td >{{$var["last_test_lesson_range"]}}</td>
                    <td >{{$var["activity_type_list_str"]}}</td>
                    <td >{{$var["discount_list"]}}</td>
                    <td >
                        <div 
                            {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                        >
                            <a class="fa-edit opt-set edit_max_count" title="编辑活动"> </a>
                            <a href="javascript:;" class="fa-comment opt-return-back btn fa act-look" title="查看活动"></a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")

    </section>
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>

@endsection

