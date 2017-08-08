@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content">
        <hr/>
        <table class="common-table">
            <tr>
                <td>老师</td>
                <td>{{$bank_info['realname']}}</td>
            </tr>
				    <tr>
                <td >持卡人</td>
                <td >{{$var["bank_account"]}}</td>
				    </tr>
				    <tr>
                <td >银行卡号</td>
                <td >{{$var["bankcard"]}}</td>
				    </tr>
				    <tr>
                <td >银行类型</td>
                <td >{{$var["bank_type"]}}</td>
				    </tr>
				    <tr>
                <td >开户行地址</td>
                <td >{{$var["bank_address"]}}</td>
				    </tr>
				    <tr>
                <td >开户省</td>
                <td >{{$var["bank_province"]}}</td>
				    </tr>
				    <tr>
                <td >开户市</td>
                <td >{{$var["bank_city"]}}</td>
				    </tr>
				    <tr>
                <td >身份证号</td>
                <td >{{$var["idcard"]}}</td>
				    </tr>
				    <tr>
                <td >身份证号</td>
                <td >{{$var["idcard"]}}</td>
				    </tr>
        </table>
    </section>
@endsection

