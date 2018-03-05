@extends('layouts.app')
@section('content')

    <section class="content ">
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-12 col-md-2">
                    <div class="input-group">
                        <span >省份</span>
                        <select id="id_phone_province" class="opt-change">
                            <option value="">[全部]</option>
                            @foreach($phone_province as $val)
                                <option value="{{$val}}">{{$val}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-xs-12 col-md-2">
                    <div class="input-group">
                        <span >渠道等级</span>
                        <select id="id_origin_level_list" class="opt-change">
                            <option value="-1">[全部]</option>
                            @foreach($origin_level_list as $key => $val)
                                <option value="{{$key}}">{{$val[$key]}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-xs-12 col-md-2">
                    <div class="input-group">
                        <span >key0</span>
                        <select id="id_key0_list" class="opt-change">
                            <option value="">[全部]</option>
                            @foreach($key0_list as $val)
                                <option value="{{$val}}">{{$val}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-2">
                    <div class="input-group">
                        <span >key1</span>
                        <select id="id_key1_list" class="opt-change">
                            <option value="">[全部]</option>
                            @foreach($key1_list as $val)
                                <option value="{{$val}}">{{$val}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-2">
                    <div class="input-group">
                        <span >key2</span>
                        <select id="id_key2_list" class="opt-change">
                            <option value="">[全部]</option>
                            @foreach($key2_list as $val)
                                <option value="{{$val}}">{{$val}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-2">
                    <div class="input-group">
                        <span >key3</span>
                        <select id="id_key3_list" class="opt-change">
                            <option value="">[全部]</option>
                            @foreach($key3_list as $val)
                                <option value="{{$val}}">{{$val}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-xs-12 col-md-2">
                    <div class="input-group">
                        <span >渠道</span>
                        <select id="id_value_list" class="opt-change">
                            <option value="">[全部]</option>
                            @foreach($value_list as $val)
                                <option value="{{$val}}">{{$val}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>userid </td>
                    <td>例子进入时间 </td>
                    <td>省份</td>
                    <td>渠道等级</td>
                    <td>key0</td>
                    <td>key1</td>
                    <td>key2</td>
                    <td>key3</td>
                    <td>渠道</td>
                    <td>回访状态 </td>
                    <td>全局tq状态 </td>
                    <td>负责人 </td>
                    <td>首个拨打人 </td>
                    <td>首个认领人 </td>
                    <td>是否试听 </td>
                    <td>是否试听成功 </td>
                    <td>是否签单 </td>
                    <td>签单金额 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["userid"]}} </td>
                        <td>{{@$var["add_time"]}} </td>
                        <td>{{@$var['phone_province']}}</td>
                        <td>{{@$var["origin_level_str"]}}</td>
                        <td>{{@$var["key0"]}} </td>
                        <td>{{@$var["key1"]}} </td>
                        <td>{{@$var["key2"]}} </td>
                        <td>{{@$var["key3"]}} </td>
                        <td>{{@$var['value']}}</td>
                        <td>{!! @$var["seller_student_status_str"] !!}</td>
                        <td>{{@$var["global_tq_called_flag_str"]}} </td>
                        <td>{{@$var["cc_nick"]}} </td>
                        <td>{{@$var["first_called_cc"]}} </td>
                        <td>{{@$var["first_get_cc"]}} </td>
                        <td>{!! @$var["test_lesson_flag"] !!} </td>
                        <td>{!! @$var["suc_test_flag"] !!} </td>
                        <td>{!! @$var["order_flag"] !!} </td>
                        <td>{{@$var["price"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
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

