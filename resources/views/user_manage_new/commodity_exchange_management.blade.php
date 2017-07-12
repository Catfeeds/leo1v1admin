@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">商品类型</span>
                        <select class="opt-change form-control" id="id_gift_type" >
                            <option value="-1">全部 </option>
                            <option value="1">实物</option>
                            <option value="2">虚拟物品(phone)</option>
                            <option value="3">虚拟物品(qq)</option>

                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">礼品状态</span>
                        <select class="opt-change form-control" id="id_status" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span >助教</span>
                        <input id="id_assistantid"  /> 
                    </div>
                </div>
             </div>
        </div>
        <hr/>
        <table class="common-table"  > 
            <thead>
                <tr>
                    <td>兑换id </td>
                    <td> 昵称  </td>
                    <td> 手机号 </td>
                    <td> 兑换时间 </td>
                    <td> 物品名称  </td>
                    <td> 兑换账号  </td>
                    <td> 收货地址 </td>
                    <td> 收货人姓名  </td>
                    <td> 收货人联系方式 </td>
                    <td> 物品状态 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["exchangeid"]}} </td>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["exchange_time"]}} </td>
                        <td>{{@$var["gift_name"]}} </td>
                        <td>{{@$var["account"]}} </td>
                        <td>{{@$var["address"]}} </td>
                        <td>{{@$var["consignee"]}} </td>
                        <td>{{@$var["consignee_phone"]}} </td>
                        <td>{{@$var["status_str"]}} </td>
                        <td>
                            <div class="opt-all-div"
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-truck opt-send"  title="发放礼品"> </a>
                                <a class="fa fa-hand-o-up opt-confirm" title="确认收货"> </a>
                                <a class="fa fa-mobile-phone opt-exchange" title="确认兑换"> </a>
                                <a class="opt-set_status" >更改状态 </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

