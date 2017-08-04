@extends('layouts.app')

@section('content')
@if (count($errors) > 0)
    <!-- Form Error List -->
    <div class="alert alert-danger" style="margin:20px;">
        <strong>出错</strong>

        <br><br>

        <ul>
            @foreach ($errors as $error)
                <li>{!!  $error !!}  </li>
            @endforeach
        </ul>
    </div>
@endif
@endsection

