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
                    <td>电话</td>
                    <td>入职时间</td>
                    <td>第一周试听课数</td>
                    <td>第一周签单数</td>
                    <td>第一周签单率</td>
                    <td>第二周试听课数</td>
                    <td>第二周签单数</td>
                    <td>第二周签单率</td>

                    <td> 操作</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["train_through_new_time_str"]}} </td>
                       
                        <td class="first_lesson_num"></td>
                        <td class="first_order_num"></td>
                        <td class="first_per"></td>
                        <td class="second_lesson_num"></td>
                        <td class="second_order_num"></td>
                        <td class="second_per"></td>

                       
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

