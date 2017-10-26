
@extends('layouts.app')
@section('content')
<section class='content'>
    <div> <!-- search ... -->
        <div class='row  row-query-list' >
            <div class='col-xs-12 col-md-5'>
                <div id='id_date_range' >
                </div>
            </div>
        </div>
    </div>
    <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>id </td>
                    <td>添加时间 </td>
                    <td>操作人 </td>
                    <td>被修改人 </td>
                    <td>操作 </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["id"]}} </td>
                        <td>{{$var["add_time"]}} </td>
                        <td>{{$var['admin_name']}}</td>
                        <td>{{$var['stu_name']}}</td>
                        <td>{{$var["msg"]}} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
@include('layouts.page')
</section>
@endsection
