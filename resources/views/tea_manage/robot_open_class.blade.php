@extends('layouts.app')
@section('content')

<script type="text/javascript" src="/page_js/select_user.js"></script>
<section class="content">
    <div class="row">
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span >上课状态</span>
                <select id="id_lesson_status" class="opt-change">
                    <option value="-1">全部</option>
                    <option value="0">未上</option>
                    <option value="2">已上</option>
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span >老师</span>
                <input id="id_search_teacher" class="opt-change"/> 
            </div>
        </div>
        <div class="col-xs-6 col-md-4">
            <div class="input-group ">
                <span class="input-group-addon">时间</span>
                <input id="id_start_date" class="opt-change form-control"/>
                <span class="input-group-addon">-</span>
                <input id="id_end_date" class="opt-change form-control"/>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <a id="id_add_lesson" class="btn btn-warning">添加课程</a>
            </div>
        </div>
    </div>
    <hr/>
    <table class="common-table">
        <thead>
        <tr>
            <td >lessonid</td>
            <td style="widtd:100px;" class="remove-for-xs">操作</td>
        </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
                <tr>
                    <td>{{$var["lessonid"]}}</td>
                    <td class="remove-for-xs">
                        <div 
                            {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                        >
                            <a href="javascript:;" title="课件上传" class="btn fa fa-upload opt-upload"></a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @include("layouts.page")
    <section>
@endsection
