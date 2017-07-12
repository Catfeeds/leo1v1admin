@extends('layouts.app')
@section('content')
    <hr/>
    <table class="common-table">
        <thead>
            <tr>
                <td class="remove-for-not-xs"></td>
                <td>userid</td>
                <td>真实姓名</td>
                <td>电话</td>
                <td>年级</td> 
                <td>操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $var)
				<tr>
                    @include('layouts.td_xs_opt')
                    <td>{{$var["userid"]}} </td>
                    <td>{{$var["realname"]}} </td>
                    <td>{{$var["phone"]}} </td>
                    <td>{{$var["grade"]}} </td>
                    <td class="remove-for-xs">
                        <div class="btn-group">
                        </div>
                    </td>
				</tr>
            @endforeach
        </tbody>
    </table>

@endsection
