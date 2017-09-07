@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  " >
                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >联系状态</span>
                        <select id="id_qc_contact_status"  ></select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >提升状态</span>
                        <select id="id_qc_advances_status"  ></select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >是否自愿</span>
                        <select id="id_qc_voluntarily_status"  ></select>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>部门 </td>
                    <td>一级原因 </td>
                    <td>二级原因 </td>
                    <td>三级原因 </td>
                    <td>扣分值 </td>
                    <td colspan="3">原因分析（相关责任人分析） </td>
                    <td>操作 </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $refund_info as $var )
                    <tr>
                        <td>{{@$var["key1_str"]}} </td>
                        <td>{{@$var["key2_str"]}}</td>
                        <td>{{@$var["key3_str"]}}</td>
                        <td>{{@$var["key4_str"]}}</td>
                        <td>{{@$var["score"]}}</td>
                        <td colspan="3">{{@$var["reason"]}}</td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <!-- <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                                     <a class="fa fa-times opt-del" title="删除"> </a>
                                   -->
                            </div>
                        </td>
                    </tr>
                @endforeach
        </table>

        <table  class="common-table" >
            <tr>
                <td colspan="9">责任鉴定</td>
            </tr>
            <tr>
                @foreach($all_percent as $key => $val)
                    <td>{{$val['value']}}</td>
                @endforeach
            </tr>
            <tr>
                @foreach($all_percent as $key => $val)
                    <td>{{$val}}</td>
                @endforeach
            </tr>
            </tbody>
        </table>


        <table  class="common-table" >
            <tr>
                <td style="width:20%;">其他原因</td>
                <td colspan="15" id="id_qc_other_reason_show">
                    <textarea style="width:100%;" id="id_qc_other_reason">{{$qc_anaysis['qc_other_reason']}}</textarea>

                </td>
            </tr>
            <tr>
                <td>QC整体分析</td>
                <td colspan="15" id="id_qc_analysia_show">
                    <textarea style="width:100%;" id="id_qc_analysia">{{$qc_anaysis['qc_analysia']}}</textarea>
                </td>
            </tr>
            <tr>
                <td>后期应对措施及工作调整方案</td>
                <td colspan="15" id="id_qc_reply_show">
                    <textarea style="width:100%;" id="id_qc_reply">{{$qc_anaysis['qc_reply']}}</textarea>
                </td>
            </tr>

            <tr>
                <td  colspan="15"  style="vertical-align:middle; text-align:center;width:10%;">
                    <button type="button" class="btn btn-success btn-lg" id="id_qc_msg">提交</button>
                </td> 
            </tr>
        </table>
        <a style="display:none;" id="adminid" data-adminid="{{$adminid}}"></a>
        <a style="display:none;" id="opt_qc_contact_status" data-val="{{$qc_anaysis['qc_contact_status']}}"></a>
        <a style="display:none;" id="opt_qc_advances_status" data-val="{{$qc_anaysis['qc_advances_status']}}"></a>
        <a style="display:none;" id="opt_qc_voluntarily_status" data-val="{{$qc_anaysis['qc_voluntarily_status']}}"></a>
    </section>

@endsection
