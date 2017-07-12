@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">有试听课</span>
                        <select class="opt-change form-control" id="id_test_lesson_flag" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">有正式课</span>
                        <select class="opt-change form-control" id="id_l_1v1_flag" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">科目</span>
                        <select class="opt-change form-control" id="id_tutor_subject" >
                        </select>
                    </div>
                </div>


            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>加入时间 </td>
                    <td>老师id </td>
                    <td>姓名 </td>
                    <td>第一科目</td>
                    <td>试听个数 </td>
                    <td>1v1个数 </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["create_time"]}} </td>
                        <td>{{@$var["teacherid"]}} </td>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["tutor_subject_str"]}} </td>
                        <td>{{$var["test_lesson_count"]}} </td>
                        <td>{{$var["l_1v1_count"]}} </td>
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

