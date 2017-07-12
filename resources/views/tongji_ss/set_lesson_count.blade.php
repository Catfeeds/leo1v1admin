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
                    <div class="input-group ">
                        <span class="input-group-addon">角色</span>
                        <select class="opt-change form-control" id="id_account_role" >
                        </select>
                    </div>
                </div>

        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>科目 </td>
                    <td>小学</td>
                    <td>初中</td>
                    <td>高中</td>
                    <td>总共(排课数/学生到课数/到课率/合同数 )</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>
                            {{@$var["l_1_count"]*1}}
                            / {{@$var["l_1_stu_in_count"]*1}}
                            / {{@$var["l_1_count"]? intval( @$var["l_1_stu_in_count"]*100/@$var["l_1_count"]):0}}%
                            / {{@$var["l_1_order_count"]*1}}

                        </td>
                        <td>
                            {{@$var["l_2_count"]*1}}
                            / {{@$var["l_2_stu_in_count"]*1}}
                            / {{@$var["l_2_count"]? intval( @$var["l_2_stu_in_count"]*100/@$var["l_2_count"]):0}}%
                            /
                            {{@$var["l_2_order_count"]*1}}



                        </td>
                        <td>
                            {{@$var["l_3_count"]*1}}
                            / {{@$var["l_3_stu_in_count"]*1}}
                            / {{@$var["l_3_count"]? intval( @$var["l_3_stu_in_count"]*100/@$var["l_3_count"]):0}}%
                            / {{@$var["l_3_order_count"]*1}}
                        </td>

                        <td>
                            {{@$var["l_all_count"]*1}}
                            / {{@$var["l_all_stu_in_count"]*1}}
                            / {{@$var["l_all_count"]?intval( @$var["l_all_stu_in_count"]*100/@$var["l_all_count"]):0}}%
                            / {{@$var["l_all_order_count"]*1}}
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
