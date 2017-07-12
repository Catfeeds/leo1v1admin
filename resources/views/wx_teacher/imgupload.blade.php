@extends('layouts.app')
@section('content')
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <section class="content ">
        <a data_signature_str="{{$signature_str}}" id="signature_str">a</a>

        <button class="btn btn_primary" id="chooseImage">chooseImage</button>
        <button class="btn btn_primary" id="uploadImage">uploadImage</<button type="button" class="btn btn-" ></button>
    </section>

@endsection
