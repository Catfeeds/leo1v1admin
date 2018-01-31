@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript">
        var g_data = <?php echo json_encode(['teacherid' => $teacherid]);?>
    </script>
    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-4" data-title="时间段">
                    <div id="id_date_range"> </div>
                </div>
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span >老师</span>
                        <input id="id_teacherid"/>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span >奖金类型</span>
                        <select id="id_reward_type" class="opt-change">
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span >是否有课</span>
                        <select id="id_has_lesson" class="opt-change">
                        </select>
                    </div>
                </div>
                <div class=" col-xs-6  col-md-2">
                    <div class="input-group col-sm-12">
                        <input type="text" class="opt-change " id="id_lessonid" placeholder="试听课程ID" />
                    </div>
                </div>
                <div class="col-xs-12 col-md-2">
                    <div class="input-group ">
                        <button class="btn btn-primary" id="id_add_teacher_money">添加</button>
                    </div>
                </div>
                @if($_account_role==12)
                <div class="col-xs-12 col-md-2">
                    <div class="input-group ">
                        <button class="btn btn-primary" id="id_add_teacher_money_2018_01">添加_2018_1</button>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td style="display:none">老师id</td>
                    <td>姓名</td>
                    <td style="display:none">银行卡</td>
                    <td style="display:none">开户行</td>
                    <td style="display:none">持卡人</td>
                    <td style="display:none">银行手机</td>
                    <td style="display:none">银行类型</td>
                    <td>奖励类型</td>
                    <td>添加时间</td>
                    <td>金额</td>
                    <td>奖励备注</td>
                    <td>添加人</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $key => $var )
                    <tr>
                        <td>{{@$var["teacherid"]}}</td>
                        <td>{{@$var["tea_nick"]}}</td>
                        <td>银行卡:{{@$var["bankcard"]}}</td>
                        <td>{{@$var["bank_address"]}}</td>
                        <td>{{@$var["bank_account"]}}</td>
                        <td>{{@$var["bank_phone"]}}</td>
                        <td>{{@$var["bank_type"]}}</td>
                        <td>{{@$var["type_str"]}}</td>
                        <td>{{@$var["add_time_str"]}}</td>
                        <td>{{@$var["money"]}}</td>
                        <td>
                            {{@$var["money_info"]}}
                            <br>
                            @if($var['money_info_extra']!='')
                                {{$var['money_info_extra']}}
                            @endif
                        </td>
                        <td>{{@$var["acc"]}}</td>
                        <td>
                            <div {!!  \App\Helper\Utils::gen_jquery_data($var)  !!} >
                                <a class="fa-edit opt-edit" title="编辑"> </a>
                                <a class="fa-trash-o opt-delete" title="删除"> </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
