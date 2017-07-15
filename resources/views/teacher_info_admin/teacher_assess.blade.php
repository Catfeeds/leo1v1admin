@extends('layouts.tea_header')
@section('content')
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>

<section class="content">
    
    <div>
        <div class="row">
            <div class="col-xs-6 col-md-2">
                <div class="input-group " >
                    <button id="opt-add-assess" class="btn btn-warning fa fa-plus fa-lg form-control " >新增考核信息</button>
                </div>
            </div>
            
            <div class="col-xs-6 col-md-2">
                <div class="input-group " >
                    <span  >考核次数:{{$assess_num}}</span>
                </div>
            </div>
        </div>
    </div>
    <hr/>
    <table class="common-table ">
        <thead>
            <tr>
                <td >考核时间</td>
                <td>审核人员</td>
                <td >考核内容</td>
                <td >考核结果</td>
                <td >建议或原因</td>          
                <td  style="min-width:200px" >操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
                <tr>
                    
                    <td >{{$var["assess_time_str"]}}</td>
                    <td >{{$var["assess_nick"]}}</td>
                    <td >{{$var["content"]}}</td>
                    <td >{{$var["assess_res_str"]}}</td>
                    <td >{{$var["advise_reason"]}}</td>

                    <td class="remove-for-xs">
                        <div class="opt"
                             {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                        >
                            <a href="javascript:;" class="btn fa fa-edit opt-edit" title="编辑"></a>
                            <a href="javascript:;" class="btn fa fa-trash-o opt-del" title="删除"></a>
                            
                        </div>
                    </td>

                </tr>
            @endforeach
        </tbody>

    </table>
    @include("layouts.page")

   

</section>
  
@endsection


