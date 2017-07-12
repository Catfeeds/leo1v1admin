@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
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
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td>姓名</td>
                    <td>奖励类型</td>
                    <td>添加时间</td>
                    <td>金额</td>
                    <td>奖励备注</td>
                    <td>添加人</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["tea_nick"]}}</td>
                        <td>{{@$var["type_str"]}}</td>
                        <td>{{@$var["add_time_str"]}}</td>
                        <td>{{@$var["money"]}}</td>
                        <td>
                            {{@$var["money_info"]}}
                            <br>
                            @if($var['stu_nick']!='')
                                学生：{{$var['stu_nick']}}
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
