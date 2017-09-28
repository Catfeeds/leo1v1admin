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
                    <td>teacherid </td>
                    <td>老师</td>
                    <td>类型</td>
                    <td>等级</td>
                    <td>常规学生数</td>
                    <td>课耗</td>                                                      
                    <td>CC转化率</td>                                                      
                    <td>CR转化率</td>                                                      
                    <td>教学反馈得分</td>                                                      
                    <td> 操作</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["teacherid"]}} </td>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["teacher_money_type_str"]}} </td>
                        <td class="level"></td>
                        <td>{{@$var["stu_num"]}} </td>
                        <td>{{@$var["lesson_count"]/100}} </td>
                        <td class="cc_per"></td>
                        <td class="cr_per"></td>
                        <td class="record_score"></td>
                       
                                              
                        <td>
                            <div class="row-data" data-teacherid="{{$var["teacherid"]}}" >
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

