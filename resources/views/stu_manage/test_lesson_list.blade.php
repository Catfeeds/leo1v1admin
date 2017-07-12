@extends('layouts.stu_header')
@section('content')
    <section class="content ">
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>lessonid </td>
                    <td>上课时间 </td>
                    <td>年级 </td>
                    <td>科目 </td>
                    <td>老师 </td>
                    <td>课程状态 </td>
                    <td>是否无效 </td>
                    <td>操作 </td>
                </tr>
            </thead>
            <tbody id="tbody">
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["lessonid"]}} </td>
                        <td>{{@$var["lesson_time"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["teacher_nick"]}} </td>
                        <td>{{@$var["lesson_status_str"]}} </td>
                        <td>{{@$var["lesson_del_flag_str"]}} </td>
                        <td>
                            <div class="row-data" 
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="btn fa fa-link opt-out-link" title="对外视频发布链接"></a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

