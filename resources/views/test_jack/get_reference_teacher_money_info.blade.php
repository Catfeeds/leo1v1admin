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
                    <td>月份</td>
                    @foreach ( $level as $k=>$var )
                        <td>{{$var}}</td>
                    @endforeach

                    <!-- <td>助教经手次数</td>
                         <td>单科目老师更换的最大次数</td> -->
                                                                                      
                    <td> 操作</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $list as $k=>$var )
                    <tr>
                        
                        <td>{{ @$k }}</td>
                        <td class="num1"></td>                       
                        <td class="num2"></td>                       
                        <td class="num3"></td>                       
                        <td class="num4"></td>                       
                        <td class="num5"></td>                       
                        <td class="num6"></td>                        
                        <td class="num"></td>                        
                                 
                        
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
