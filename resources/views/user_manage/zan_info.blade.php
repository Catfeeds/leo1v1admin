@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
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
                <div class="col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">类型</span>
                        <select  id="id_praise_type" class="opt-change">
                            <option value="-1">[全部]</option>
                        </select>
                    </div>
                </div> 
                <div class="col-xs-1 col-md-2">
                    <div class="input-group ">
                        <span >学生</span>
                        <input id="id_userid"  /> 
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >课程id</span>
                        <input id="id_lessonid"  /> 
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <a id="id_add_mypraise" class="btn btn-warning" > <li  class="fa fa-plus">添加</li></a>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <div class="body">
            <table class="common-table ">
                <thead>
                    <tr>
                        <td >学生id</td>
                        <td >学生姓名</td>
                        <td >记录时间</td>
                        <td >类型</td>
                        <td >获赞课堂id</td>
                        <td >获赞数</td>
                        <td >添加人</td>
                        <td>操作 </td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr>
                            <td >{{$var["userid"]}}</td>
                            <td >{{$var["name"]}}</td>
                            <td >{{$var["ts"]}}</td>
                            <td >{{$var["type"]}}</td>
                            <td >{{$var["lessonid"]}}</td>
                            <td >{{$var["praise_num"]}}</td>
                            <td >{{$var["add_user_name"]}}</td>
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

