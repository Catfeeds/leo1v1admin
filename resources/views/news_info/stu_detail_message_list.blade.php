@extends('layouts.app') @section('content')
<section class="content">
    <div class="row">
        <div class="col-xs-12 col-md-4">
            <div class="col-xs-12 col-md-4" data-title="时间段">
                <div id="id_date_range"> </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-2">
            <div class="input-group ">
                <span >学生:</span>
                <input type="text" id="id_studentid" class="opt-change"/>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <button class="btn btn-primary" id="add_message_info"> 新增信息 </button>
        </div>                
    </div>
    <hr/>
    <table class="common-table">
        <thead>
            <tr>
                <td class="remove-for-not-xs"></td>
                <td style="display:none">messageid</td>
                <td>内容</td> 
                <td>消息类型</td> 
                <td>操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
				<tr>
                    @include('layouts.td_xs_opt')
                    <td>{{$var["messageid"]}} </td>
                    <td>{{$var["content"]}}</td>
                    <td>{{$var["message_type_str"]}}</td>
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
