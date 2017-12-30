@extends('layouts.app')
@section('content')
    <script src="/js/jquery-2.1.4.js" type="text/javascript"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/js/jquery.query.js"></script>
    <script type="text/javascript" src="/js/jquery.admin.js"></script>
    <script type="text/javascript" src="/js/jquery.websocket.js"></script>
    <section class="content ">
        <div>
            <div class="row  row-query-list" >
            </div>
        </div>
        <hr/>
        @include("layouts.page")
    </section>
@endsection

