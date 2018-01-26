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
                    <td>id</td>
                    <td>名字</td>
                    <td>续费人数</td>
                    <td>结课人数</td>
                    <td>总人数</td>
                    <td>续费率</td>
                                                                     

                    <td> 操作</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $list as $k=>$var )
                    <tr>
                        
                        <td>{{ @$var["id"] }}</td>
                        <td>{{ @$var["name"] }}</td>
                        <td>{{ @$var["renew_num"] }}</td>
                        <td>{{ @$var["end_num"] }}</td>
                        <td>{{ @$var["all"] }}</td>
                        <td>{{ @$var["per"] }}%</td>
                       

                        <td>
                            <div class="row-data"  data-start="{{ @$var["start"] }}" >
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
