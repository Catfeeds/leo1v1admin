@extends('layouts.app')
@section('content')
    <style>
     #id_content .panel-heading {
         font-size:20px;
         text-align:center;
     }
    </style>
    <section class="content " id="id_content">
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
                                <td>销售人 </td>
                            </tr>
                        </thead>
                        <tbody id="id_person_body">
                            @foreach (  $table_data_list as $var )
                                <tr>
                                    <td> <span> {{$var["index"]}} </span> </td>
                                    <td>{{$var["sys_operator"]}} </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
