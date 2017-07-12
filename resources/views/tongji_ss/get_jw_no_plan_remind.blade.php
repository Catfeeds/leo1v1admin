@extends('layouts.app')
@section('content')
    <style>
     .center-title {
         font-size:20px;
         text-align:center;
     }
     .huge {
         font-size: 35px;
     }
     .subjects {
         font-size: 20px;
         text-align:center;
     }
     .plan_font{
         font-size: 18px;
     }
     .panel-green {
         border-color: #5cb85c;
     }
     .panel-green .panel-heading {
         background-color: #5cb85c;
         border-color: #5cb85c;
         color: #fff;
     }
     .panel-green a {
         color: #5cb85c;
     }
     .panel-green a:hover {
         color: #3d8b3d;
     }
     .panel-red {
         border-color: #d9534f;
     }
     .panel-red .panel-heading {
         background-color: #d9534f;
         border-color: #d9534f;
         color: #fff;
     }
     .panel-red a {
         color: #d9534f;
     }
     .panel-red a:hover {
         color: #b52b27;
     }
     .panel-yellow {
         border-color: #f0ad4e;
     }
     .panel-yellow .panel-heading {
         background-color: #f0ad4e;
         border-color: #f0ad4e;
         color: #fff;
     }
     .panel-yellow a {
         color: #f0ad4e;
     }
     .panel-yellow a:hover {
         color: #df8a13;
     }

     #id_content .panel-heading {
         font-size:20px;
         text-align:center;
     }

    </style>


    <section class="content " id="id_content" style="max-width:1400px;">
        <div>
            <div class="row">
            </div>
            <hr/>
 
            

            <div class="row">

                <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            教务未排课统计
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>教务</td>
                                        <td>课前6小时未排</td>
                                        <td>课前4小时未排</td>
                                        <td>课前2小时未排</td>
                                        
                                    </tr>
                                </thead>
                                <tbody id="id_per_count_list">
                                    @foreach ( $list as $key=> $var )
                                        <tr>
                                            <td>{{@$var["account"]}} </td> 
                                            <td>{{@$var["six_count"]}} </td> 
                                            <td>{{@$var["four_count"]}} </td> 
                                            <td>{{@$var["two_count"]}} </td> 
                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
                
            </div>

        </div>

    </section>
    
@endsection



