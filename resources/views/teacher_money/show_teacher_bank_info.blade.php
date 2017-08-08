@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content">
        <table >
            <tr>
                <td width="200px">老师</td>
                <td>{{$bank_info['realname']}}</td>
            </tr>
				    <tr>
                <td >持卡人</td>
                <td >{{$bank_info["bank_account"]}}</td>
				    </tr>
				    <tr>
                <td >银行卡号</td>
                <td >{{$bank_info["bankcard"]}}</td>
				    </tr>
				    <tr>
                <td >银行类型</td>
                <td >{{$bank_info["bank_type"]}}</td>
				    </tr>
				    <tr>
                <td >开户行地址</td>
                <td >{{$bank_info["bank_address"]}}</td>
				    </tr>
				    <tr>
                <td >开户省</td>
                <td >{{$bank_info["bank_province"]}}</td>
				    </tr>
				    <tr>
                <td >开户市</td>
                <td >{{$bank_info["bank_city"]}}</td>
				    </tr>
				    <tr>
                <td >身份证号</td>
                <td >{{$bank_info["idcard"]}}</td>
				    </tr>
				    <tr>
                <td >银行预留手机号</td>
                <td >{{$bank_info["bank_phone"]}}</td>
				    </tr>
        </table>
    </section>
@endsection

