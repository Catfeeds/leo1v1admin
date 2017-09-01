@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row" >                
                <div class="col-xs-6 col-md-2">
                    <button id="id_get_money" class="btn btn-primary">刷新</button>
                </div > 

            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>teacherid</td>
                    <td>姓名</td>
                    <td>电话</td>
                    <td>入职时间</td>
                    <td>科目</td>
                    <td>年级段</td>
                    <td>工资类别</td>

                    <td>老师类型</td>
                    <td>老师等级</td>
                  
                   

                    <td> 操作</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["teacherid"]}} </td>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["train_through_new_time_str"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>
                            @if(@$var["grade_start"]>0)
                                {{@$var["grade_start_str"]}} 至 {{@$var["grade_end_str"]}}
                            @else
                                {{@$var["grade_part_ex_str"]}}
                            @endif
                        </td>
                        <td>{{@$var["teacher_money_type_str"]}} </td>
                        <td>{{@$var["identity_str"]}} </td>
                        <td>{{@$var["level_str"]}} </td>

                       
                       

                       
                        <td>
                            <div class="row-data" {!! \App\Helper\Utils::gen_jquery_data($var) !!} >
                                <a class="fa fa-list course_plan" title="按课程包排课"> </a>
                            </div>
                        </td>
                    </tr>
                @endforeach

               
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

