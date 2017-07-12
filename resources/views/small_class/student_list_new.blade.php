@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/page_js/set_lesson_time.js"></script>
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/small_class/set_teacher_clothes.js"></script>

<section class="content ">
    <div class="row">
        <div class="col-xs-1 col-md-6">
            <div class="input-group ">
                <div class="input-group-btn ">
                    <a id="id_studentid"  class=" btn btn-primary fa fa-plus" >加入学生 </a>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <table     class="common-table"  > 
        <thead>
            <tr>
                <td>课程id </td>
                <td>课次 </td>
                <td>时间 </td>
                <td>学生id </td>
                <td>学生姓名 </td>
                <td>作业状态 </td>
                <td> 操作  </td>
            </tr>
        </thead>
        <tbody>
            @foreach ( $table_data_list as $var )
                <tr>
                    <td>{{@$var["lessonid"]}} </td>
                    <td>{{@$var["lesson_num"]}} </td>
                    <td>{{@$var["lesson_start"]}} </td>
                    <td>{{@$var["studentid"]}} </td>
                    <td class="td-student-nick">{{@$var["student_nick"]}} </td>
                    <td>{{@$var["work_status_str"]}} </td>
                    <td>
                        <div
                            {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                        >
                            <a title="pdf" class="btn fa   fa-download     opt-show-pdf"></a>
                            <a title="删除" class="btn fa   fa-times opt-del"></a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @include("layouts.page")
</section>

@endsection

