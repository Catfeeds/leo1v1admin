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
                    <td rowspan="2">月份</td>
                    @foreach ( $level as $k=>$var )
                        <td colspan="3">{{$var}}</td>
                    @endforeach

                    <!-- <td>学生名字</td>
                         <td>年级</td>
                         <td>助教</td>
                         <td>对应老师</td> -->
                   
                    <!-- <td>助教经手次数</td>
                         <td>单科目老师更换的最大次数</td> -->
                                                                                      
                    <td rowspan="2"> 操作</td>
                </tr>
                <tr>
                    <td>老师数</td>
                    <td>学生数</td>
                    <td>课时</td>
                    <td>老师数</td>
                    <td>学生数</td>
                    <td>课时</td>
                    <td>老师数</td>
                    <td>学生数</td>
                    <td>课时</td>
                    <td>老师数</td>
                    <td>学生数</td>
                    <td>课时</td>
                    <td>老师数</td>
                    <td>学生数</td>
                    <td>课时</td>
                    <td>老师数</td>
                    <td>学生数</td>
                    <td>课时</td>
                    <td>老师数</td>
                    <td>学生数</td>
                    <td>课时</td>

                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $list as $k=>$var )
                    <tr>
                        
                        <td>{{ @$k}}</td>
                        <td class="num1_tea"></td>                       
                        <td class="num1_stu"></td>                       
                        <td class="num1_lesson"></td>
                        <td class="num2_tea"></td>                       
                        <td class="num2_stu"></td>                       
                        <td class="num2_lesson"></td>                       
                        <td class="num3_tea"></td>                       
                        <td class="num3_stu"></td>                       
                        <td class="num3_lesson"></td>                       
                        <td class="num4_tea"></td>                       
                        <td class="num4_stu"></td>                       
                        <td class="num4_lesson"></td>                       
                        <td class="num5_tea"></td>                       
                        <td class="num5_stu"></td>                       
                        <td class="num5_lesson"></td>                       
                        <td class="num6_tea"></td>                       
                        <td class="num6_stu"></td>                       
                        <td class="num6_lesson"></td>                       
                        <td class="num_tea"></td>                       
                        <td class="num_stu"></td>                       
                        <td class="num_lesson"></td>                       

                                 

                                 
                        
                        <td>
                            <div class="row-data"  data-userid="{{ @$var["start"] }}" >
                                <a class="fa fa-list course_plan"> </a>
                            </div>

                        </td>


                    </tr>

                @endforeach



            </tbody>
        </table>


        @include("layouts.page")
    </section>

  


@endsection
