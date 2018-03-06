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
                    <td>原绩效(回访)</td>
                    <td>绩效(回访)-new</td>
                   
                    <!-- <td>助教经手次数</td>
                         <td>单科目老师更换的最大次数</td> -->
                                                                                      
                    <td > 操作</td>
                </tr>
              
            </thead>
            <tbody id="id_tbody">
                @foreach ( $list as $k=>$var )
                    <tr>
                        
                        <td>{{ @$var["adminid"]}}</td>
                        <td>{{ @$var["name"]}}</td>
                        <td>{{ @$var["revisit_reword"]}}</td>
                        <td class ="num"></td>
                                       

                                 

                                 
                        
                        <td>
                            <div class="row-data"  data-userid="{{ @$var["adminid"] }}" >
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
