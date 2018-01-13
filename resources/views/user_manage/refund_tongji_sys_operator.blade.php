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
                        <input id="id_sys_operator"  class="opt-change" placeholder="下单人,回车搜索" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">下单人类型</span>
                        <select class="opt-change form-control" id="id_account_role">
                            <option value="-1" >全部</option>
                            <option value="1" >助教</option>
                            <option value="2" >销售</option>
                            <option value="3" >其他</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>下单人</td>
                    <td>类型</td>
                    <td>近1年退费率</td>
                    <td>近6月退费率</td>
                    <td>近3月退费率</td>

                    <td>当月退费率</td>
                    <td>当月签约量</td>
                    <td>当月退费量</td>
                    <td>当月退费申请量</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["sys_operator"]}} </td>
                        <td>{{@$var["type_str"]}} </td>
                        <td>{{@$var["one_year_per"]}}% 
                            ( {{@$var['one_year_refund_num']}} /{{@$var['one_year_num']}}  )
                        </td>
                        <td>{{@$var["half_year_per"]}}% 
                            ( {{@$var['half_year_refund_num']}} /{{@$var['half_year_num']}}  )
                        </td>
                        <td>{{@$var["three_month_per"]}}% 
                            ( {{@$var['three_month_refund_num']}} /{{@$var['three_month_num']}}  )
                        </td>

                        <td>{{@$var["one_month_per"]}}% 
                            ( {{@$var['one_month_refund_num']}} /{{@$var['one_month_num']}}  )
                        </td>
                        <td>{{@$var["one_month_num"]}} </td>
                        <td>{{@$var["one_month_refund_num"]}} </td>
                        <td>{{@$var["apply_num"]}} </td>

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

