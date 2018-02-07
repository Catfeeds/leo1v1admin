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
                    <td>手机号</td>
                    <td>年级</td>
                    <td>是否试听</td>                                                                                    
                    <td> 操作</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $list as $k=>$var )
                    <tr>
                        
                        <td>{{ @$var["phone"] }}</td>
                        <td>{{ @$var["grade_str"] }}</td>                       
                        
                        <td class="num"></td>
                       

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
