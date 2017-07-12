@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row " >
                <div class="col-xs-12 col-md-4"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span class="input-group-addon">更改方式</span>
                        <select class="opt-change form-control" id="id_change_type" >                           
                            <option value="1">人工</option>
                            <option value="2">系统</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span class="input-group-addon">限课类型</span>
                        <select class="opt-change form-control" id="id_record_type" >                           
                            <option value="-1">全部</option>
                            <option value="3">教研限课</option>
                            <option value="7">系统限课</option>
                        </select>
                    </div>
                </div>

               
                <div class="col-xs-6 col-md-4">
                    <button class="btn" id="id_have_lesson" data-value="{{$total["num"]}}" >{{$total["num"]}}</button>
                    <button class="btn" id="id_no_lesson" data-value="{{$total["lesson"]}}" >{{$total["lesson"]}}</button> 
                    <button class="btn" id="id_all_tea" data-value="{{$total["order"]}}" >{{$total["order"]}}</button> 
                    <button class="btn" id="id_all_lesson" data-value="{{$total["per"]}}%" >{{$total["per"]}}%</button> 
                </div>
                

            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>老师</td>
                    <td>CC</td>
                    @if($change_type==1)
                    <td>调整前</td>
                    <td>调整后</td>
                    @else
                    <td>解限理由</td>
                    @endif
                    <td>操作人</td>
                    <td>操作时间</td>
                    <td>试听成功</td>
                    <td>签单成功</td>                   
                    <td> 操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["seller_account"]}} </td>
                        @if($change_type==1)
                            <td>{{@$var["limit_plan_lesson_type_old_str"]}} </td>
                            <td>{{@$var["limit_plan_lesson_type_str"]}} </td>
                        @else
                            <td>{{@$var["limit_require_reason"]}}</td>
                        @endif
                        <td>{{@$var["acc"]}} </td>
                        <td>{{@$var["add_time_str"]}} </td>
                        <td class="lesson_info" data-value="{{$var["lesson_flag"]}}">{{@$var["lesson_info"]}} </td>
                        <td class="order_info" data-value="{{$var["order_flag"]}}">{{@$var["order_info"]}} </td>
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

