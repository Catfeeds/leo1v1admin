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
                    <td>小学</td>
                    <td>初中</td>
                    <td>高中</td>
                                                                     

                    <td> 操作</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $list as $k=>$var )
                    <tr>
                        
                        <td>{{ $var["time"] }}</td>
                        <td class="small_grade">  </td>
                        <td class="middle_grade">  </td>
                        <td class="high_grade">  </td>                       
                        

                        <td>
                            <div class="row-data"  data-start="{{ $var["start"] }}" >
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
