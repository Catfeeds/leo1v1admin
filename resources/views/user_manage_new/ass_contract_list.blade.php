@extends('layouts.app_new2')
@section('content')
    <section class="content">
        <div class="row  row-query-list" >
            <div class="col-xs-12 col-md-5"  data-title="时间段">
                <div  id="id_date_range" >
                </div>
            </div>


            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <span class="input-group-addon">助教</span>
                    <input class="opt-change form-control" id="id_assistantid"/>
                </div>
            </div>


            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span >学生</span>
                    <input id="id_studentid"  />
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">类型</span>
                    <select class="opt-change form-control " id="id_contract_type" >
                        <option value="-2">正式1v1课程</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">财务确认</span>
                    <select class="opt-change form-control " id="id_check_money_flag" >
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">交接单</span>
                    <select class="opt-change form-control " id="id_have_init" >
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">分配组长</span>
                    <select class="opt-change form-control " id="id_have_master" >
                    </select>
                </div>
            </div>



        </div>
        <hr/>
        <table   class="common-table"   >
            <thead>
                <tr>
                    <td  >学员姓名</td>
                    <td style="display:none;" >家长姓名</td>
                    <td style="display:none;" >是否新增 </td>
                    <td style="display:none;" >年级</td>
                    <td class=" remove-for-xs  ">联系电话</td>
                    <td >合同状态</td>
                    <td >合同类型</td>
                    <td class=" remove-for-xs  ">生效日期</td>
                    <td >课时数</td>
                    <td style="display:none;" >包类型</td>
                    <td >下单人</td>
                    <td >财务确认</td>
                    <td >交接单</td>

                    <td >交接单状态</td>
                    <td>驳回(次数)/修改(次数)</td>

                    <td >助教助长</td>
                    <td style="display:none;" >分配助教助长时间</td>
                    <td >助教</td>
                    <td >分配助教时间</td>
                    <td >转介绍学生</td>
                    <td >转介绍学生对应助教</td>
                    <td  >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
            <tr>
                        <td class="stu_nick" >{{$var["stu_nick"]}} </td>
                        <td >{{$var["parent_nick"]}}</td>
                        <td >{{$var["is_new_stu"]}}</td>
                        <td >{{$var["grade"]}}</td>
                        <td >{{$var["phone"]}}</td>
                        <td >{{$var["contract_status"]}}</td>
                        <td >{{$var["contract_type_str"]}}</td>
                        <td class="contract_starttime"  >{{$var["contract_starttime"]}}</td>
                        <td >{{$var["lesson_total"] * $var["default_lesson_count"]/100}}</td>
                        <td >{{$var["from_type_str"]}}</td>
                        <td >{{$var["sys_operator"]}}</td>
                        <td >{{$var["check_money_flag_str"]}}</td>
                        <td >{{$var["init_info_pdf_url_str"]}}</td>


                        <td >{!!@$var["is_submit_str"]!!}</td>
                        <td  class="opt-reject_list" data-orderid="{{$var['orderid']}}" ><span style="color:#3c8dbc;" >驳回({{@$var["reject_num"]}})</span>/修改({{$var["modify_num"]}})</td>


                        <td >{{$var["master_nick"]}}</td>
                        <td >{{$var["master_assign_time_str"]}}</td>
                        <td >{{$var["assistant_nick"]}}</td>
                        <td >{{$var["ass_assign_time"]}}</td>
                        <td >{{@$var["origin_user_nick"]}}</td>
                        <td >{{@$var["origin_ass_nick"]}}</td>
                        <td >
                            <div class="btn-group"
                                 {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa-user opt-user " title="个人信息" ></a>
                                <a class="fa-list opt-init_info " title="销售交接单" ></a>
                                <a class="fa-user-md  opt-set_ass " title="分配助教" ></a>
                                @if($account_id==349 || $account_id==60)
                                    <a class=" opt-set_ass_master " title="分配助教组长" >分配组长</a>
                                @endif
                                @if($var["init_info_pdf_url_str"]=="有")
                                    <a class="fa-edit  opt-edit " title="修改交接单课时" ></a>
                                @endif
                            </div>
                        </td>
            </tr>
                @endforeach
            </tbody>
        </table>

        <div style="display:none;" >
            <div id="id_assign_log">
                <table   class="table table-bordered "   >
                    <tr>  <th> 合同id <th>驳回人 <th>驳回原因 <th>驳回时间  </tr>
                        <tbody class="data-body">
                        </tbody>
                </table>
            </div>
        </div>



        @include("layouts.page")
    </section>

    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>


@endsection
