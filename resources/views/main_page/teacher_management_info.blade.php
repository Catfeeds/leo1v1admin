@extends('layouts.app')
@section('content')
    <style>
     .center-title {
         font-size:20px;
         text-align:center;
     }
     .huge {
         font-size: 40px;
     }
     .panel-green {
        background-color: #5cb85c;
     }
     .panel-gray {
         background-color: #808080;
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
        background-color: #d9534f;
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
         background-color: #f0ad4f;
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
     .panel-blue {
         background-color: #9ff;
     }

     #id_content .panel-body {
         text-align:center;
     }

    </style>

  


    <section class="content " id="id_content" style="max-width:1200px;">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div id="id_date_range" >
                    </div>
                </div>
            </div>
            <hr/>
           

            <div class="row">

                <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            总体                           
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                               
                                <tbody >
                                        <tr>
                                            <td class="panel-gray">类型</td>
                                            <td class="panel-gray">咨询转化率</td>
                                            <td class="panel-gray">教务转化率</td> 
                                            <td class="panel-gray">老师转化率</td> 
                                        </tr>
                                        <tr>
                                            <td >1000精排</td> 
                                            <td >{{@$top_seller_total["have_order"]}}/{{@$top_seller_total["person_num"]}}</td> 
                                            <td >{{@$top_jw_total["have_order"]}}/{{@$top_jw_total["person_num"]}}</td> 
                                            <td >{{@$top_seller_total["have_order"]}}/{{@$top_seller_total["person_num"]}}</td> 
                                        </tr>
                                        <tr>
                                            <td >绿色通道</td> 
                                            <td >{{@$green_seller_total["have_order"]}}/{{@$green_seller_total["person_num"]}}</td> 
                                            <td >{{@$green_jw_total["have_order"]}}/{{@$green_jw_total["person_num"]}}</td> 
                                            <td >{{@$green_seller_total["have_order"]}}/{{@$green_seller_total["person_num"]}}</td> 

                                        </tr>
                                        <tr>
                                            <td >常规排课</td> 
                                            <td >{{@$normal_seller_total["have_order"]}}/{{@$normal_seller_total["person_num"]}}</td> 
                                            <td >{{@$normal_jw_total["have_order"]}}/{{@$normal_jw_total["person_num"]}}</td> 
                                            <td >{{@$normal_seller_total["have_order"]}}/{{@$normal_seller_total["person_num"]}}</td> 

                                        </tr>

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    
@endsection
