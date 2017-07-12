@extends('layouts.app')
@section('content')
    <section class="content ">
        <div>
            <div class="row">
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td>时间</td>
                    <td>总在读人数</td>
                    <td>在读-新学生</td>
                    <td>在读-老学生</td>
                    <td>人均科目</td>
                    <td>人均科目-新学生</td>
                    <td>人均科目-老学生</td>
                    <td>消耗课时-新学生</td>
                    <td>消耗课时-老学生</td>
                    <td>消耗课时-试听</td>
                    <td class ="caozuo">操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["date"]}}</td>
                        <td>{{$var["stu_all_num"]}}</td>
                        <td>{{$var["stu_new_num"]}}</td>
                        <td>{{$var["stu_old_num"]}}</td>
                        <td>{{$var["subject_num_per"]}}</td>
                        <td>{{$var["subject_new_per"]}}</td>
                        <td>{{$var["subject_old_per"]}}</td>
                        <td>{{$var["lesson_total_new"]}}</td>
                        <td>{{$var["lesson_total_old"]}}</td>
                        <td>{{$var["lesson_trial_total"]}}</td>
                        <td class ="caozuo">
                            <div
                                {!! \App\Helper\Utils::gen_jquery_data($var) !!}
                            >
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endsection
