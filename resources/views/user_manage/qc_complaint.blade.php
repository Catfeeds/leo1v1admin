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
                        <span class="input-group-addon">处理状态</span>
                        <select id="id_is_complaint_state" class="opt-change" >
                        </select>

                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">投诉人类型</span>
                        <select class="opt-change form-control" id="id_account_type" />
                        </select>
                    </div>
                </div>


            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>id </td>
                    <td>投诉类型</td>
                    <td>投诉人身份</td>
                    <td>投诉人姓名/电话</td>
                    <td>投诉内容</td>
                    <td>被投诉人/身份</td>
                    <td>投诉时间</td>
                    <td>跟进状态</td>
                    <td>当前处理人</td>
                    <td>最新分配时间</td>
                    <td>处理状态</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $key=>$var )
                    <tr>
                        <td>{{@$key}} </td>
                        <td>{{@$var["complaint_type_str"]}} </td>
                        <td>{{@$var["account_type_str"]}} </td>
                        <td>{{@$var["user_nick"]}}/{{@$var["phone"]}} </td>
                        <td >{{mb_substr($var["complaint_info"],0,50 )}}...</td>

                        <td>{{@$var["complained_adminid_nick"]}}/{{@$var["complained_adminid_type_str"]}} </td>
                        <td>{{@$var["complaint_date"]}} </td>
                        <td>{!!@$var["follow_state_str"]!!} </td>
                        <td>{{@$var["current_account"]}} </td>
                        <td>{{@$var["current_admin_assign_time_date"]}} </td>
                        <td>{!! @$var["complaint_state_str"]!!} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="opt-assign btn fa" title="分配处理人">分</a>
                                <a class="opt-assign_remark btn fa" title="分配记录">备</a>
                                <a class="fa fa-list-alt opt-complaint-all btn" title="投诉信息汇总"></a>
                                <!-- <a class="fa fa-edit opt-edit"  title="编辑"> </a> -->
                                <a class="fa fa-times opt-del" title="删除"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")


        <div style="display:none;" >
            <div id="id_assign_log">
                <table   class="table table-bordered "   >
                    <tr>  <th> 分配时间 <th>分配人 <th>接受人 <th>分配备注  </tr>
                        <tbody class="data-body">
                        </tbody>
                </table>
            </div>
        </div>


    </section>

@endsection
