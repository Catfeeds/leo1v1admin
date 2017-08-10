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
                        被换老师详情
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
                                                {{$var["realname"]}}
                                            </a>
                                        </td>
                                        <td >
                                            <a  href="javascript:;" class="tea_num" data-teacherid='{{@$var["teacherid"]}}' data-num='{{@$var["num"]}}'>{{$var["num"]}}</a>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>总计</td>
                                    <td>老师总数:{{$all["tea_num"]}}/换老师数:{{$all["change_tea_num"]}}</td>
                                    <td>{{$all["change_tea_all_num"]}}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="col-xs-12 col-md-6">
                <div class="panel panel-warning"  >
                    <div class="panel-heading">
                        助教换老师详情
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
                                        <td >
                                            <a  href="javascript:;" class="ass_num" data-adminid='{{@$var["uid"]}}' data-num='{{@$var["num"]}}'>{{$var["num"]}}</a>
                                        </td>

                                    </tr>
                                @endforeach
                                <tr>
                                    <td>总计</td>
                                    <td>助教总数:{{$all["ass_num"]}}/换老师助教数:{{$all["change_ass_num"]}}</td>
                                    <td>{{$all["change_ass_all_num"]}}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </section>

@endsection
