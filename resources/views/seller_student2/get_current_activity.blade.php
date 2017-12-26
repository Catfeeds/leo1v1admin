@extends('layouts.app')
@section('content')
    <section class="content">
    <div class="row">
        
        <div class="col-xs-2 col-md-2">
            <div class="input-group">
                <span class="input-group-addon">是否开启</span>
                <select class="opt-change form-control" id="id_open_flag">
                </select>
            </div>
        </div>

        <div class="col-xs-1 col-md-1">
            <div class="input-group">
                <button style="margin-left:10px" id="all_activity" type="button" class="btn btn-primary">所有活动</button>
            </div>
        </div>

    </div>

    <hr/>

    <table   class="common-table" >
        <thead>
            <tr>
                <td >活动ID</td>
                <td >活动标题</td>
                <td >优惠力度</td>
                <td >当前最大合同数</td>
                <td >预期最大合同数</td>
                <td >活动时间</td>
                <td >开启类型</td>
                <td >操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
                <tr>
                    <td >{{$var["id"]}}</td>
                    <td >{{$var["title"]}}</td>
                    <td >{{$var["power_value"]}}</td>
                    <td >{{$var["max_count"]}}</td>
                    <td >{{$var["diff_max_count"]}}</td>
                    <td >{{$var["date_range_time"]}}</td>
                    <td >{{$var["open_flag_str"]}}</td>
                    <td >
                        <div {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}>
                            <a class="fa-hand-o-up opt-stu-origin btn fa" title="编辑优惠力度"></a>
                            <a href="javascript:;" class="fa-edit btn act-edit" title="编辑活动"></a>
                            <a href="javascript:;" class="fa-comment opt-return-back btn fa act-look" title="查看活动"></a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")

    </section>
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>

@endsection

