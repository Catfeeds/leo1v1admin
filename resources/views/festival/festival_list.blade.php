@extends('layouts.app')
@section('content')
<section class="content">
    <div class="row">
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">点击状态</span>
                <select id="id_ad_status" class="opt-change form-control status" >
                </select>
            </div>
        </div>
        <div class="col-xs-2">
            <div class="input-group input-group-btn">
                <button class="btn btn-primary form-control add_new_ad_info" >添加数据</button>
            </div>
        </div>
    </div>
    <hr/>
    <table class="common-table">
        <thead>
            <tr>
                <td class="remove-for-not-xs"></td>
                <td>id</td>
                <td>日期</td>
                <td>节日</td> 
                <td>操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
				<tr>
                    @include('layouts.td_xs_opt')
                    <td>{{$var["id"]}} </td>
                    <td>{{$var["time_str"]}}</td>
                    <td>{{$var["status_str"]}}</td>
                    <td class="remove-for-xs">
                        <div class="opt"
                             {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                        >
                            <a class="fa-edit opt-update" title="更改"></a>
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
