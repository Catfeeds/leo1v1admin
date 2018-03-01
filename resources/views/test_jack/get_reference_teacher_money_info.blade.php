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
                    <td>userid</td>
                    <td>学生名字</td>
                    <td>年级</td>
                    <td>助教</td>
                    <td>对应老师</td>
                   
                    <!-- <td>助教经手次数</td>
                         <td>单科目老师更换的最大次数</td> -->
                                                                                      
                    <td> 操作</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $list as $k=>$var )
                    <tr>
                        
                        <td>{{ @$var["userid"]}}</td>
                        <td>{{ @$var["nick"]}}</td>
                        <td>{{ @$var["grade_str"]}}</td>
                        <td>{{ @$var["ass_name"]}}</td>
                        <td class="tea"></td>
                        <!-- <td class="num1"></td>                       
                             <td class="num2"></td>                       
                             <td class="num3"></td>                       
                             <td class="num4"></td>                       
                             <td class="num5"></td>                       
                             <td class="num6"></td>                        
                             <td class="num"></td>                         -->
                                 
                        
                        <td>
                            <div class="row-data"  data-userid="{{ @$var["userid"] }}" >
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
