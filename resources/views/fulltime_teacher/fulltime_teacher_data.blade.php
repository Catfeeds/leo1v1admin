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
                            武汉全职老师面试数据
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >

                                <tbody >
                                        <tr>
                                            <td >属性</td>
                                            <td >值</td>
                                        </tr>

                                        <tr>
                                            <td >成功注册人数</td>
                                            <td id="id_apply_num" ></td>
                                        </tr>
                                        <tr>
                                            <td >一面到面人数</td>
                                            <td id="id_arrive_num"></td>
                                        </tr>
                                        <tr>
                                            <td >一面通过人数</td>
                                            <td id="id_arrive_through"></td>
                                        </tr>

                                        <tr>
                                            <td >二面通过人数</td>
                                            <td id="id_second_through"></td>
                                        </tr>

                                        <tr>
                                            <td >入职人数</td>
                                            <td id="id_enter_num"></td>
                                        </tr>
                                        <tr>
                                            <td >离职人数</td>
                                            <td id="id_leave_num"></td>
                                        </tr>


                                        <tr>
                                            <td >一面到面率</td>
                                            <td id="id_arrive_num_per"></td>
                                        </tr>

                                        <tr>
                                            <td >一面通过率</td>
                                            <td id="id_arrive_through_per"></td>
                                        </tr>
                                        <tr>
                                            <td >录用率</td>
                                            <td id="id_second_through_per"></td>
                                        </tr>
                                        <tr>
                                            <td >入职率</td>
                                            <td id="id_enter_num_per"></td>
                                        </tr>
                                        <tr>
                                            <td >离职率</td>
                                            <td id="id_leave_per"></td>
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
