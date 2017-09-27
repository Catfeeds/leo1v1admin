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
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <!-- <div class="col-xs-6 col-md-2">
                     <div class="input-group " >
                     <input type="text" class="opt-change" id="id_search" placeholder="输入用户名／电话，回车查找"/>
                     </div>
                     </div> -->
            </div>
        </div>
        <hr/>
        <div class="col-xs-12 col-md-12">
            <div class="panel panel-warning"  >
                <div class="panel-heading">
                    本月-个人排行榜
                </div>
                <div class="panel-body">
                    <table   class="table table-bordered "   >
                        <thead>
                            <tr>
                                <td>排名</td>
                                <td>销售人</td>
                                <td>签单数</td>
                                <td>总金额</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (  $table_data_list as $var )
                                <tr>
                                    <td> <span> {{$var["index"]}} </span> </td>
                                    <td>{{$var["sys_operator"]}} </td>
                                    <td>{{$var["all_count"]}} </td>
                                    <td>{{$var["all_price"]}} </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>


    </section>

@endsection
