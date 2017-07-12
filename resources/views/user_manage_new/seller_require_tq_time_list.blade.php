@extends('layouts.app')
@section('content')
    <style>
     #id_content .panel-heading {
         font-size:20px;
         text-align:center;
     }
    </style>
  
    <section class="content " id="id_content">

        <div>
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div  id="id_date_range" >
                    </div>
                </div>
            </div>
        </div>
        <hr/>

        <div class="row">

            <div class="col-xs-12 col-md-6">
                <div class="panel panel-warning"  >
                    <div class="panel-heading">
                        邀约数--红榜
                    </div>
                    <div class="panel-body">
                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>排名</td>
                                    <td>小组</td>
                                    <td>销售人</td>
                                    <td>邀约数量</td>
                                </tr>
                            </thead>
                            <tbody id="id_tr_desc">
                                @foreach ( $tr_desc_info as $key=> $var )
                                    <tr>
                                        <td> <span> {{$key+1}} </span> </td>
                                        <td>{{$var["group_name"]}} </td>
                                        <td>{{$var["account"]}} </td>
                                        <td>{{$var["require_test_count"]*1}} </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="col-xs-12 col-md-6">
                <div class="panel panel-warning"  >
                    <div class="panel-heading">
                        邀约数--黑榜
                    </div>
                    <div class="panel-body">
                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>排名</td>
                                    <td>小组</td>
                                    <td>销售人</td>
                                    <td>邀约数量</td>
                                </tr>
                            </thead>
                            <tbody id="id_tr_asc">
                                @foreach ( $tr_asc_info as $key=> $var )
                                    <tr>
                                        <td> <span> {{$key+1}} </span> </td>
                                        <td>{{$var["group_name"]}} </td>
                                        <td>{{$var["account"]}} </td>
                                        <td>{{$var["require_test_count"]*1}} </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>


            </div>  
            <div class="col-xs-12 col-md-6">
                <div class="panel panel-warning"  >
                    <div class="panel-heading">
                        通话时长--红榜
                    </div>
                    <div class="panel-body">
                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>排名</td>
                                    <td>小组</td>
                                    <td>销售人</td>
                                    <td> 通话时长</td>
                                </tr>
                            </thead>
                            <tbody id="id_list_desc">
                                @foreach ( $list_tq_desc as $key=> $var )
                                    <tr>
                                        <td> <span> {{$key+1}} </span> </td>
                                        <td>{{$var["group_name"]}} </td>
                                        <td>{{$var["account"]}} </td>
                                        <td>{{$var["duration_count_str"]}} </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="col-xs-12 col-md-6">
                <div class="panel panel-warning"  >
                    <div class="panel-heading">
                        通话时长--黑榜
                    </div>
                    <div class="panel-body">
                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>排名</td>
                                    <td>小组</td>
                                    <td>销售人</td>
                                    <td>通话时长</td>
                                </tr>
                            </thead>
                            <tbody id="id_list_asc">
                                @foreach ( $list_tq_asc as $key=> $var )
                                    <tr>
                                        <td> <span> {{$key+1}} </span> </td>
                                        <td>{{$var["group_name"]}} </td>
                                        <td>{{$var["account"]}} </td>
                                        <td>{{$var["duration_count_str"]}} </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>


        </div>

    </section>
    
@endsection

