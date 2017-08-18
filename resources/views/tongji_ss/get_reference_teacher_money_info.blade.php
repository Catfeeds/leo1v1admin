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
                    <td>姓名</td>
                    <td>教学经历</td>
                    <td>联系方式</td>
                    <td>QQ</td>
                    <td>邮箱</td>
                    <td>擅长科目</td>
                    <td>擅长年级</td>
                    <td>毕业院校</td>
                    <td>面试评价</td>
                    <td> 操作</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["name"]}} </td>
                        <td>{{@$var["teacher_type_str"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["qq"]}} </td>
                        <td>{{@$var["email"]}} </td>
                        <td>{{@$var["subject_ex"]}} </td>
                        <td>{{@$var["grade_ex"]}} </td>
                        <td>{{@$var["school"]}} </td>
                        <td class="interview_info"></td>
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

