@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/page_js/set_lesson_time.js"></script>
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/small_class/set_teacher_clothes.js"></script>
<section class="content">
    <table class="common-table" > 
        <thead>
            <tr>
                <td>课次id </td>
                <td>时间 </td>
                <td>老师 </td>
                <td>助教 </td>
                <td>科目 </td>
                <td>年级 </td>
                <td>操作 </td>
            </tr>
        </thead>
        <tbody>
            @foreach ( $table_data_list as $var )
                <tr>
                    <td>{{@$var["lessonid"]}} </td>
                    <td>{{@$var["lesson_time"]}} </td>
                    <td>{{@$var["teacher_nick"]}} </td>
                    <td>{{@$var["assistant_nick"]}} </td>
                    <td>{{@$var["subject_str"]}} </td>
                    <td>{{@$var["grade_str"]}} </td>
                    <td>
                        <div
                            {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                        >
                            <a title="更改老师" class="btn fa fa-male opt-alloc-teacher"></a>
                            <a title="设置上课时间" class="btn fa fa-clock-o opt-set-time"></a>
                            <a title="学生信息列表" class="btn fa fa-group opt-student-list"></a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @include("layouts.page")
</section>
@endsection
