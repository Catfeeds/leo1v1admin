@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax_more.js?v={{@$_publish_version}}"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_second.js?v={{@$_publish_version}}"></script>

    <script type="text/javascript" src="/page_js/lib/select_dlg_forbid.js?v={{@$_publish_version}}"></script>
    <style>

     #opt-test-enum{width:250px}
    </style>
    <section class="content">
        <div class="row">
            <div class="col-xs-2 col-md-2">
                <div class="input-group">
                    <span class="input-group-addon">测试</span>
                    <input class="opt-change form-control" id="opt-test-paper" type="text">
                </div>
            </div>

            <div class="col-xs-2 col-md-2">
                <div class="input-group">
                    <span class="input-group-addon">测试枚举</span>
                    <input class="opt-change form-control" id="opt-test-enum" type="text" value="看听学英语、快乐学英语、先锋英语、新编儿童入门英语、新标准、" books="107,112,185,196,198,">
                </div>
            </div>

        </div>


    </div>

    <hr/>
@endsection

