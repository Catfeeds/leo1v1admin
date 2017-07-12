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
                    <div class="input-group " >
                        <span >xx</span>
                        <input type="text" value=""  class="opt-change"  id="id_"  placeholder=""  />
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>课程id</td>
                    <td>上课时间</td>
                    <td>学生</td>
                    <td>老师</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["lessonid"]}} </td>
                        <td>{{@$var["lesson_time"]}} </td>
                        <td>{{@$var["student_nick"]}} </td>
                        <td>{{@$var["teacher_nick"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a style="display:none;" class="fa fa-edit opt-edit"  title="编辑"> </a>
                                <a style="display:none;" class="fa fa-times opt-del" title="删除"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
