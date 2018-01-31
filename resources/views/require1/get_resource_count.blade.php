
@extends('layouts.app')
@section('content')
<section class='content'>
    <div> <!-- search ... -->
    </div>
    <hr/>
    <table class="common-table">
        <thead>
            <tr>
                <td>文件名</td>
                <td>科目</td>
                <td>年级</td>
                <td>教研员</td>
                <td>浏览次数</td>
                <td>使用次数</td>
            </tr>
        </thead>
        <tbody>
            @foreach($info as $var)
                <tr>
                    <td>{{@$var['file_title']}}</td>
                    <td>{{@$var['subject_str']}}</td>
                    <td>{{@$var['grade_str']}}</td>
                    <td>{{@$var['nick']}}</td>
                    <td>{{@$var['visit_num']}}</td>
                    <td>{{@$var['use_num']}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@include('layouts.page')
</section>
@endsection
