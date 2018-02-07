
@extends('layouts.app')
@section('content')
    <script type="text/javascript">
     var g_data = <?php echo json_encode(['info' => $table_data_list ]);?>;
    </script>
    <section class='content'>
        <div> <!-- search ... -->
            <div class="row">
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span>是否有银行卡</span>
                        <select id="id_is_bank" class="opt-change">
                            <option value="-1">全部</option>
                            <option value="1" selected>有银行卡</option>
                            <option value="2">没有银行卡</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span >老师</span>
                        <input id="id_teacherid"/>
                    </div>
                </div>
                @if(in_array($_account,["ricky","sunny","孙瞿"]))
                    <div class="col-xs-6 col-md-2">
                        <div><a href="javascript:;" id="download_data" class="fa fa-download">下载</a></div>
                    </div>
                @endif
            </div>
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <td>老师id</td>
                <td>老师姓名</td>
                <td>科目</td>
                <td>手机号</td>
                <td>持卡人</td>
                <td>卡号</td>
                <td>银行类型</td>
                <td>开户省</td>
                <td>开户市</td>
                <td>支行</td>
                <td>手机预留号</td>
                <td>身份证</td>
                <td>绑卡时间</td>
            </thead>
            <tbody>
                @foreach($table_data_list as $var)
                    <tr>
                        <td>{{$var['teacherid']}}</td>
                        <td>{{$var['nick']}}</td>
                        <td>{{$var['subject_str']}}</td>
                        <td>{{$var['phone']}}</td>
                        <td>{{$var['bank_account']}}</td>
                        <td>{{$var['bankcard']}}</td>
                        <td>{{$var['bank_type']}}</td>
                        <td>{{$var['bank_province']}}</td>
                        <td>{{$var['bank_city']}}</td>
                        <td>{{$var['bank_address']}}</td>
                        <td>{{$var['bank_phone']}}</td>
                        <td>{{$var['idcard']}}</td>
                        <td>{{$var['bind_bankcard_time_str']}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include('layouts.page')
    </section>
@endsection
