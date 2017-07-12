@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/seller_student/common.js"></script>
 <script type="text/javascript" src="//g.alicdn.com/sj/aliphone-sdk/aliphone.min.js" charset="utf-8"></script>
    <section class="content">
        <div class="book_filter">

            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div class="input-group ">
                        <span >时间:</span>
                        <input type="text" id="id_start_date" class="opt-change"/>
                        <span >-</span>
                        <input type="text" id="id_end_date" class="opt-change"/>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >渠道:</span>
                        <input type="text" id="id_origin" class="opt-change"/>
                    </div>
                </div>
                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span >渠道-EX:</span>
                        <input type="text" id="id_origin_ex" class="opt-change"/>
                    </div>
                </div>



              </div>
        </div>
        <hr />
        <div class="body">
            <table class="common-table ">
                <thead>
                    <tr>
                        <td >渠道</td>
                        <td >总人数</td>
                        <td >有效意向人数</td>
                        <td >总试听人数</td>
                        <td >已签多少人</td>
                        <td>操作 </td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr>
                            <td >{{$var["origin"]}}</td>
                            <td >{{$var["all_count"]}}</td>
                            <td >{{$var["effective"]}}</td>
                            <td >{{$var["listened"]}}</td>
                            <td >{{$var["listened_yi"]}}</td>
                            <td >
                                <div >
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include("layouts.page")
        </div>

    <script src="/js/qiniu/plupload/plupload.full.min.js"></script>

@endsection

