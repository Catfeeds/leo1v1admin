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
                            CC转化率统计
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                               
                                <tbody >
                                        <tr>
                                            <td class="">类别</td>
                                            <td >语文</td>
                                            <td >数学</td> 
                                            <td >英语</td>
                                            <td >化学</td>
                                            <td >物理</td>
                                            <td >生物</td>
                                            <td >政治</td>
                                            <td >历史</td>
                                            <td >地理</td>
                                            <td >科学</td>

                                        </tr>
                                        @foreach ( $table_data_list as $var )
                                        <tr>
                                            <td >{{@$var['name']}}</td> 
                                            <td >{{@$var['1']}}</td> 
                                            <td >{{@$var['2']}}</td>
                                            <td >{{@$var['3']}}</td>
                                            <td >{{@$var['4']}}</td> 
                                            <td >{{@$var['5']}}</td> 
                                            <td >{{@$var['6']}}</td> 
                                            <td >{{@$var['7']}}</td> 
                                            <td >{{@$var['8']}}</td> 
                                            <td >{{@$var['9']}}</td> 
                                            <td >{{@$var['10']}}</td> 
                                             
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
