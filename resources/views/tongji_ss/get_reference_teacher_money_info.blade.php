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
                    <td>4月（试听课）</td>
                    <td>4月（签单）</td>
                    <td>4月（签单率）</td>

                    <td>5月（试听课）</td>
                    <td>5月（签单）</td>
                    <td>5月（签单率）</td>
                    <td>6月（试听课）</td>
                    <td>6月（签单）</td>
                    <td>6月（签单率）</td>
                    <td>7月（试听课）</td>
                    <td>7月（签单）</td>
                    <td>7月（签单率）</td>
                  
                   

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
                        <td class="first_lesson_num"></td>
                        <td class="first_order_num"></td>
                        <td class="first_per"></td>
                        <td class="second_lesson_num"></td>
                        <td class="second_order_num"></td>
                        <td class="second_per"></td>
                        <td class="third_lesson_num"></td>
                        <td class="third_order_num"></td>
                        <td class="third_per"></td>
                        <td class="fourth_lesson_num"></td>
                        <td class="fourth_order_num"></td>
                        <td class="fourth_per"></td>

                       
                       

                       
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

