@extends('layouts.app')
@section('content')
<section class="content">
    <div class="row row-query-list">
        <div class="col-xs-6 col-md-2">
            <div class="input-group">
                <span class="input-group-addon">状态</span>
                <select class="opt-change form-control" id="id_type" >
                    <option value="-1">[全部]</option> 
                    <option value="0">首页不显示</option> 
                    <option value="1">首页显示</option> 
                </select>
            </div>
        </div>
    </div>
    <hr/>
    <table class="common-table ">
        <thead>
            <tr>
                <td class="remove-for-xs">名称</td>
                <td class="remove-for-xs">首页</td>
                <td style="min-width:200px">操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
                <tr>
                    <td >{{$var['name']}}</td>
                    <td >{{$var['type_str']}}</td>
                    <td class="remove-for-xs">
                        <div class="opt"
                             {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                        >
                            <a href="javascript:;" class="btn fa opt-change_type">
                                @if($var['type']==0)
                                    设为首页
                                @else
                                    取消首页
                                @endif
                            </a>
                                
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @include("layouts.page")
</section>
@endsection
