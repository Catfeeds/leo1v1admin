@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/js/sparkmd5.js"></script>
    <!-- <script type="text/javascript" src="/js/qetag.js"></script> -->
    <section class="content ">

        <input type="file" id="file" />
        <div id="box"></div>
        <button id="cal" type="button" onclick="calculate()">计算md5</button>

        @include("layouts.page")
    </section>

@endsection
