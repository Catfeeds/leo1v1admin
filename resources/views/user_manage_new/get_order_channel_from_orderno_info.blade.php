@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">合同类型</span>
                        <select class="form-control opt-change" id="id_contract_type">
                            <option value="-2">正式1v1课程</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">付款渠道</span>
                        <select class="form-control opt-change" id="id_channel_origin">
                            <option value="-1">全部</option>
                            <option value="1">支付宝</option>
                            <option value="2">微信</option>
                            <option value="3">建行</option>
                            <option value="4">百度</option>
                            <option value="100">其他</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">支付方式</span>
                        <select class="form-control opt-change" id="id_channel">
                            <option value="-1">全部</option>
                            <option value="1">支付宝扫码付款</option>
                            <option value="2">支付宝升学帮付款</option>
                            <option value="3">微信扫码付款</option>
                            <option value="4">微信升学帮付款</option>
                            <option value="5">建行分期付款</option>
                            <option value="6">建行全款</option>
                            <option value="7">百度分期</option>
                            <option value="100">其他</option>
                        </select>
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <input id="id_name_str"  class="opt-change" placeholder="学生/家长姓名,id,手机" />
                    </div>
                </div>



               
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>用户ID </td>
                    <td>学员姓名 </td>
                    <td style="display:none">家长姓名 </td>
                    <td>年级 </td>
                    <td>科目 </td>
                    <td>合同类型 </td>
                    <td>总课时 </td>
                    <td>实付金额 </td>
                    <td>付款渠道 </td>
                    <td>支付方式 </td>
                    <td>第三方支付订单号 </td>
                    <td>支付时间 </td>
                    <td>下单时间 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["userid"]}} </td>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["parent_name"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["contract_type_str"]}} </td>
                        <td>{{@$var["lesson_total"]}} </td>
                        <td>{{@$var["price"]/100}} </td>
                        <td>{{@$var["channel_origin"]}} </td>
                        <td>{{@$var["channel"]}} </td>
                        <td>{{@$var["from_orderno"]}} </td>
                        <td>{{@$var["pay_time_str"]}} </td>
                        <td>{{@$var["order_time_str"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                               
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection
