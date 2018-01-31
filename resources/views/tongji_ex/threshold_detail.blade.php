@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >xx</span>
                        <input type="text" value=""  class="opt-change"  id="id_"  placeholder=""  />
                    </div>
                </div>
            </div>
        </div>
        <hr/>

        <table     class="common-table"  >
            警报统计 &nbsp&nbsp&nbsp&nbsp 截止{{@$end_time}}
            <thead>
                <tr>
                    <td>警报序号 </td>
                    <td>警报类型 </td>
                    <td>警报时间 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $ret_report as $var )
                    <tr>
                        <td>{{@$var["num"]}} </td>
                        <td>{{@$var["type"]}} </td>
                        <td>{{@$var["time"]}} </td>
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

        <table     class="common-table"  >
            <thead>
            拨通率统计 &nbsp&nbsp&nbsp&nbsp 截止{{@$end_time}}
                <tr>
                    <td>峰值类型 </td>
                    <td>拨通率 </td>
                    <td>时间 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $ret_rate as $var )
                    <tr>
                        <td>{{@$var["type"]}} </td>
                        <td>{{@$var["rate"]}} </td>
                        <td>{{@$var["time"]}} </td>
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


        <table     class="common-table"  >
            <thead>
                渠道统计 &nbsp&nbsp&nbsp&nbsp 截止{{@$end_time}}
                <tr>
                    <td>渠道等级 </td>
                    <td>拨打量 </td>
                    <td>拨通量 </td>
                    <td>拨通率 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $ret_origin_info as $var )
                    <tr>
                        <td>{{@$var["origin_level"]}} </td>
                        <td>{{@$var["call_count"]}} </td>
                        <td>{{@$var["called_count"]}} </td>
                        <td>{{@$var["rate"]}} </td>
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
