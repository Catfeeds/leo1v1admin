@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax_more.js?v={{@$_publish_version}}"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax_second.js?v={{@$_publish_version}}"></script>

    <script type="text/javascript" src="/page_js/lib/select_dlg_forbid.js?v={{@$_publish_version}}"></script>
    <section class="content">
        <div class="row">
            
            <div class="col-xs-2 col-md-2">
                <div class="input-group">
                    <span class="input-group-addon">是否开启</span>
                    <input class="opt-change form-control" id="test_ajax_more">
                </div>
            </div>

            <div class="col-xs-2 col-md-2">
                <div class="input-group">
                    <span class="input-group-addon">测试是否选这</span>
                    <input class="opt-change form-control" id="test_forbid">
                </div>
            </div>

            <div class="col-xs-2 col-md-2">
                <div class="input-group">
                    <span class="input-group-addon">测试</span>
                    <input class="opt-change form-control" id="opt-test-paper">
                </div>
            </div>

        </div>


    </div>

    <hr/>
@endsection

