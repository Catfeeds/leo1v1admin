@extends('layouts.app')
@section('content')
    <section class="content ">
        <div class="row">
            <div class="col-xs-12 col-md-4" data-title="时间段">
                <div id="id_date_range"> </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span>匹配数</span>
                    <input value="{{$all_num}}">
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span>匹配正确数</span>
                    <input value="{{$match_num}}">
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span>匹配率</span>
                    <input value="{{$match_rate}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span>去重总人数</span>
                    <input value="{{$all_stu}}">
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span>去重匹配-下单人数</span>
                    <input value="{{$match_stu}}">
                </div>
            </div>

            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span>去重非匹配-下单人数</span>
                    <input value="{{$no_match_stu}}">
                </div>
            </div>

            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span>匹配转化率</span>
                    <input value="{{$match_succ_rate}}">
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span>非匹配转化率</span>
                    <input value="{{$no_match_succ_rate}}">
                </div>
            </div>
        </div>
        <hr/>
    </section>
@endsection
