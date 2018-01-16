@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-6 col-md-4"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" data-always_hide="1">
                    <div class="input-group ">
                        <input id="id_name"  class="opt-change" placeholder="下单人,回车搜索" />
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>助教</td>
                    <td>属性</td>

                     {!!\App\Helper\Utils::th_order_gen([
                        ["近1年退费率","one_year_per" ],
                        ["近6月退费率","half_year_per" ],
                        ["近3月退费率","three_month_per" ],
                        ["当月退费率","one_month_per"],
                        ["当月签约量","one_month_num"],
                        ["当月退费量","one_month_refund_num"],
                        ["当月退费申请量","apply_num"],
                       ])  !!}
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td >
                        <a class="detail_info" data-userid="{{@$var['assistantid']}}" data-group_name="{{@$var['group_name']}}" data-name="{{@$var['name']}}">{!! @$var["nick"] !!}</a></td>
                        <td>{{@$var["group"]}} </td>
                        <td>
                            {{@$var["one_year_per"]}}@if($var["one_year_per"] >0)%@endif
                            ( {{@$var['one_year_refund_num']}} /{{@$var['one_year_num']}}  )
                        </td>
                        <td>{{@$var["half_year_per"]}}@if($var["half_year_per"] > 0)%@endif
                            ( {{@$var['half_year_refund_num']}} /{{@$var['half_year_num']}}  )
                        </td>
                        <td>{{@$var["three_month_per"]}}@if(@$var["three_month_per"] > 0)%@endif
                            ( {{@$var['three_month_refund_num']}} /{{@$var['three_month_num']}}  )
                        </td>
                        <td >
                            @if($var['one_month_per'] == 0)
                                {{@$var["one_month_per"]}}@if(@$var["one_month_per"]> 0)%@endif
                            @else
                                <a class="one_month" data-id="{{@$var['assistantid']}}">{{@$var["one_month_per"]}}</a> 
                            @endif
                            ( {{@$var['one_month_refund_num']}} /{{@$var['one_month_num']}}  )
                        </td>
                        <td>{{@$var["one_month_num"]}} </td>
                        <td>{{@$var["one_month_refund_num"]}} </td>
                        <td  >
                            @if($var['apply_num'] == 0)
                                {{@$var["apply_num"]}}
                            @else
                                <a class="apply_num" data-nick="{{@$var['nick']}}" > {{@$var["apply_num"]}}</a>
                            @endif
                        </td>

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

