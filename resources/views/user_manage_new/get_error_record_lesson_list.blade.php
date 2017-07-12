@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">类型</span>
                        <select class="opt-change form-control" id="id_lesson_type" >
                        </select>
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>                   
                    <td>课程id </td >
                    <td>学生姓名 </td>
                    <td>教师信息 </td>
                    <td>课程时间 </td>
                    <td>课程类型 </td>
                    <td>画笔信息 </td>
                    <td>声音信息 </td>
                    <td>信息生成时间 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["lessonid"]}} </td>
                        <td>{{@$var["student_nick"]}} </td>
                        <td>{{@$var["teacher_nick"]}} </td>
                        <td>{{@$var["lesson_time"]}} </td>
                        <td>{{@$var["lesson_type_str"]}} </td>
                        <td>{{@$var["draw"]}} </td>
                        <td>{{@$var["audio"]}} </td>
                        <td>{{@$var["lesson_upload_time"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                                <a class="fa fa-times opt-del" title="删除"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

