@extends('layouts.app')

@section('content')


    <div class="alert alert-danger" style="margin:20px;">
        <strong>出错</strong>

        <br><br>
        @if($err_mg)
            <ul>
                {{$err_mg}}。
            </ul>

        @else
            <ul>
                没有权限  ,请查看其它。
            </ul>
        @endif
    </div>

@endsection
