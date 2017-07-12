@extends('layouts.app') @section('content')
<section class="content">
    <div class="row">
        <div class="col-xs-2">
            <div class="input-group input-group-btn">
                <button class="btn btn-primary form-control add_message_info" >添加数据</button>
            </div>
        </div>
    </div>
    <hr/>
    <table class="common-table">
        <thead>
            <tr>
                <td class="remove-for-not-xs"></td>
                <td>messageid</td>
                <td>内容</td> 
                <td>发送给</td> 
                <td>操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
				<tr>
                    @include('layouts.td_xs_opt')
                    <td>{{$var["messageid"]}} </td>
                    <td>{{$var["message_content"]}}</td>
                    <td>{{$var["message_type"]}}</td>
                    <td class="remove-for-xs">
                        <div class="btn-group" data-id="{{$var["messageid"]}}">
                            <a class="fa-trash-o opt-del" title="删除"></a>
                        </div>
                    </td>
				</tr>
            @endforeach
        </tbody>
    </table>
    @include("layouts.page")
</section>
@endsection
