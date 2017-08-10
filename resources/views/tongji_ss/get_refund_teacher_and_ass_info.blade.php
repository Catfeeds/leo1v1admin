@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/page_js/lib/select_dlg_record.js?v={{@$_publish_version}}"></script>
    <style>
    .panel-heading {
         font-size:20px;
         text-align:center;
     }
    </style>

    <section class="content ">

        <div>
            <div class="row " >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
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
                        老师退费详情
                    </div>
                    <div class="panel-body">
                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>编号</td>
                                    <td>老师</td>
                                    <td>次数</td>
                                </tr>
                            </thead>
                            <tbody id="id_tea">
                                @foreach ( $tea as $key=> $var )
                                    <tr>

                                        <td>{{$key+1}}</td>
                                        <td >
                                            <a  href="/human_resource/index_tea_qua?teacherid={{$var["teacherid"]}}" target="_blank" >
                                                {{$var["nick"]}}
                                            </a>
                                        </td>
                                        <td class="tea_num" data-teacherid='{{@$var["teacherid"]}}' data-num='{{@$var["num"]}}' >
                                            <a >{{$var["num"]}}</a>
                                        </td>
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
                        助教退费详情
                    </div>
                    <div class="panel-body">
                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>编号</td>
                                    <td>助教</td>
                                    <td>次数</td>
                                </tr>
                            </thead>
                            <tbody id="id_ass">
                                @foreach ( $ass as $key=> $var )
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td > {{$var["account"]}} </td>
                                        <td class="ass_num" data-adminid='{{@$var["uid"]}}' data-num='{{@$var["num"]}}' >
                                            <a  href="javascript:;" >{{$var["num"]}}</a>
                                        </td>

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
