@extends('layouts.app')
@section('content')
    <section class="content">
    <table >
        <tr>
            <td>姓名：{{$require_info['nick']}}</td>
            <td>性别：{{$require_info['gender_str']}}</td>
            <td>年级：{{$require_info['grade_str']}}</td>
            <td>科目：{{$require_info['subject_str']}}</td>
            <td>试听上课时间：{{$require_info['request_time']}}</td>
        </tr>
        <tr>
            <td>老师要求：</td>
        </tr>
        <tr>
            <td>试听需求：{{$require_info['test_stu_request_test_lesson_demand']}}</td>
        </tr>
        <tr>
            <td>意向度:报价反应:{{$require_info['quotation_reaction_str']}}</td>
            <td>上课意向:{{$require_info['intention_level_str']}}</td>
        </tr>
    </table>
    <hr/>
    <div class="row">
    </div>
    <table   class="common-table" >
        <thead>
            <tr>
                <td >活动ID</td>
                <td >操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
                <tr>
                    <td >{{$var["id"]}}</td>
                    <td >
                        <div {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}>
                            <a class="fa-hand-o-up opt-stu-origin btn fa" title="编辑优惠力度"></a>
                            <a href="javascript:;" class="fa-edit btn act-edit" title="编辑活动"></a>
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

