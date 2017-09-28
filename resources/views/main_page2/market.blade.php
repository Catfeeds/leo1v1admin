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


    </style>



    <section class="content " id="id_content">
        <div class="row  row-query-list" >
            <div class="col-xs-12 col-md-5"  data-title="时间段">
                <div  id="id_date_range" >
                </div>
            </div>
        </div>
        <hr/>


        <div>
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title">
                            本月特殊申请配额
                        </div>
                        <div class="panel-body">
                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>角色</td>
                                        <td>配额  </a> </td>
                                        <td> 已用</td>
                                        <td>剩余</td>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr>
                                        <td>销售</td>
                                        <td> <span> {{$role_2_diff_money_def}}</span> <a id="id_edit_seller_diff_money_def" class="fa fa-edit" href="#" ></td>
                                        <td> {{$role_2_diff_money}} </td>
                                        <td> {{$role_2_diff_money_def-$role_2_diff_money}} </td>
                                    </tr>

                                    <tr>
                                        <td>助教</td>
                                        <td>  </td>
                                        <td> {{$role_1_diff_money}} </td>
                                        <td>  </td>
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
