@extends('layouts.app')
@section('content')
    <section class="content ">

        <div>
            <div class="row  row-query-list" >

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">parentid</span>
                        <input class="opt-change form-control" id="id_parentid" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">userid</span>
                        <input class="opt-change form-control" id="id_userid" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">年级</span>
                        <select class="opt-change form-control" id="id_grade" />
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" data-always_show="1">
                    <div class="input-group ">
                        <input type="text" class=" form-control click_on put_name opt-change"  data-field="phone" id="id_phone"  placeholder="手机号 回车查找" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">微信openid</span>
                        <input class="opt-change form-control" id="id_wx_openid" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <button id="id_add"> 增加</button>
                </div>
            </div>
        </div>
        <hr/>

        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>id </td>
                    <td>上级id</td>
                    <td>userid</td>
                    <td>手机号</td>
                    <td>wx_openid</td>
                    <td>银行卡号</td>
                    <td>身份证号码</td>
                    <td>开户行和支行</td>
                    <td>持卡人姓名</td>
                    <td>银行预留手机号</td>
                    <td>银行开户省</td>
                    <td>银行开户市</td>
                    <td>银行卡类型</td>
                    <td>支付宝姓名</td>
                    <td>支付宝账号</td>
                    <td>创建时间</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["id"]}} </td>
                        <td>{{@$var["parentid"]}} </td>
                        <td>{{@$var["userid"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["wx_openid"]}} </td>
                        <td>{{@$var["bankcard"]}} </td>
                        <td>{{@$var["idcard"]}} </td>
                        <td>{{@$var["bank_address"]}} </td>
                        <td>{{@$var["bank_account"]}} </td>
                        <td>{{@$var["bank_phone"]}} </td>
                        <td>{{@$var["bank_province"]}} </td>
                        <td>{{@$var["bank_city"]}} </td>
                        <td>{{@$var["bank_type"]}} </td>
                        <td>{{@$var["zfb_name"]}} </td>
                        <td>{{@$var["zfb_account"]}} </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit aaa"  title="编辑"> </a>
                                <a class="fa fa-times opt-del" title="删除"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
