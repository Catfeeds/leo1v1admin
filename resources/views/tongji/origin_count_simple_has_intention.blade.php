@extends('layouts.app_old2')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/page_js/seller_student/common.js"></script>
    <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>
    <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.pie.min.js"></script>
    <script type="text/javascript" >
     var g_grade_map= <?php  echo json_encode ($grade_map); ?> ;
     var g_subject_map= <?php  echo json_encode ($subject_map); ?> ;
     var g_has_pad_map= <?php  echo json_encode ($has_pad_map); ?> ;
     var g_area_map= <?php  echo json_encode ($area_map); ?> ;
     var g_origin_level_map= <?php  echo json_encode ($origin_level_map); ?> ;
     var g_order_area_map= <?php  echo json_encode ($order_area_map); ?> ;
     var g_order_grade_map= <?php  echo json_encode ($order_grade_map); ?> ;
     var g_order_subject_map= <?php  echo json_encode ($order_subject_map); ?> ;
     var g_test_area_map= <?php  echo json_encode ($test_area_map); ?> ;
     var g_test_grade_map= <?php  echo json_encode ($test_grade_map); ?> ;
     var g_test_subject_map= <?php  echo json_encode ($test_subject_map); ?> ;

    </script>
    <section class="content">
        <div class="book_filter">
            <div class="row">
                <div class="col-xs-12 col-md-2">
                    <button id="id_query" class="btn btn-warning" >点击查询 </button>
                    <button id="id_align" class="btn btn-primary" >对齐</button>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div id="id_date_range"> </div>
                </div>

                <div class="col-xs-12 col-md-2"  >
                    <div class="input-group ">
                        <span class="input-group-addon" style="color:red;" >统计项</span>
                        <select class="opt-change form-control" id="id_check_field_id" >
                            <option value="1">渠道</option>
                            <option value="2">年级</option>
                            <option value="3">科目</option>
                            <option value="4">tmk人员</option>
                            <option value="5">销售</option>
                            <option value="6">渠道等级</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >渠道:</span>
                        <input type="text" id="id_origin" class="opt-change"/>
                    </div>
                </div>
                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span class="input-group-addon">微信运营</span>
                        <input class="opt-change form-control" id="id_tmk_adminid" />
                    </div>
                </div>



                <div class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <span class="input-group-addon">渠道选择</span>
                        <input class="opt-change form-control" id="id_origin_ex" />
                    </div>
                </div>

                <div style="display:none;" class="col-md-2 col-xs-6">
                    <div class="input-group">
                        <span class="input-group-addon" class="opt-change">负责人</span>
                        <input id="id_admin_revisiterid" />
                    </div>
                </div>




                <div style="display:none;" class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">销售分组</span>
                        <select class="opt-change form-control" id="id_groupid" >
                            <option value="-1">全部</option>
                            @foreach ( $group_list as $var )
                                <option value="{{@$var['groupid']}}">{{@$var['group_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <span class="input-group-addon">销售选择</span>
                        <input class="opt-change form-control" id="id_seller_groupid_ex" />
                    </div>
                </div>




            </div>
        </div>
        <hr />
        <div class="body">

            @if ($field_name !="origin")

                <table class="common-table   ">
                    <thead>
                        <tr>
                            <td >分类</td>
                            <td >例子总数</td>
                            <td>已分配销售</td>
                            <td>TMK有效</td>

                            <td >首次拨打平均</td>
                            <td >未拨打</td>
                            <td >已拨打 </td>

                            <td >未接通</td>
                            <td >已拨通-有效</td>
                            <td >已拨通-无效</td>

                            <td >未接通-无效</td>


                            <td >有效意向(A)</td>
                            <td >有效意向(B)</td>
                            <td >有效意向(C)</td>
                            <td >预约数</td>
                            <td >上课人数</td>
                            <td >上课成功数</td>
                            <td >合同个数</td>
                            <td >合同人数</td>
                            <td >合同金额</td>
                            <td > 操作</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($table_data_list as $var)
                            <tr >
                                <td >{{@$var["title"]}}</td>
                                <td >{{@$var["all_count"]}}</td>
                                <td >{{@$var["assigned_count"]}}</td>
                                <td >{{@$var["tmk_assigned_count"]}}</td>
                                <td >{{intval(@$var["avg_first_time"]/60)}}</td>
                                <td >{{@$var["tq_no_call_count"]}}</td>
                                <td >{{@$var["tq_called_count"]}}</td>
                                <td >{{@$var["tq_call_fail_count"]}}</td>
                                <td >{{@$var["tq_call_succ_valid_count"]}}</td>
                                <td >{{@$var["tq_call_succ_invalid_count"]}}</td>
                                <td >{{@$var["tq_call_fail_invalid_count"]}}</td>
                                <td>{{@$var["have_intention_a_count"]}}</td>
                                <td>{{@$var["have_intention_b_count"]}}</td>
                                <td>{{@$var["have_intention_c_count"]}}</td>
                                <td>{{@$var["require_count"]}}</td>
                                <td>{{@$var["test_lesson_count"]}}</td>
                                <td>{{@$var["succ_test_lesson_count"]}}</td>
                                <td>{{@$var["order_count"]}}</td>
                                <td>{{@$var["user_count"]}}</td>
                                <td>{{@$var["order_all_money"]}}</td>
                                <td>
                                    <div></div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <table class="common-table   ">
                    <thead>
                        <tr>
                            <td >key0</td>
                            <td >key1</td>
                            <td >key2</td>
                            <td >key3</td>

                            <td >渠道</td>

                            <td >例子总数</td>
                            <td>已分配销售</td>
                            <td>TMK有效</td>

                            <td >首次拨打平均</td>
                            <td >未拨打</td>
                            <td >已拨打 </td>

                            <td >未接通</td>
                            <td >已拨通-有效</td>
                            <td >已拨通-无效</td>

                            <td >未接通-无效</td>

                            <td >有效意向(A)</td>
                            <td >有效意向(B)</td>
                            <td >有效意向(C)</td>
                            <td >预约数</td>
                            <td >上课数</td>
                            <td >上课成功数</td>
                            <td class="text-danger">上课数(去重)</td>
                            <td class="text-danger">上课成功数(去重)</td>
                            <td >合同个数</td>
                            <td >合同人数</td>
                            <td >合同金额</td>
                            <td > 操作</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($table_data_list as $var)
                            <tr class="{{@$var["level"]}}">
                                <td data-class_name="{{@$var["key0_class"]}}" class="key0" >{{@$var["key0"]}}</td>
                                <td data-class_name="{{@$var["key1_class"]}}" class="key1 {{@$var["key0_class"]}}  {{@$var["key1_class"]}}" >{{@$var["key1"]}}</td>
                                <td  data-class_name="{{@$var["key2_class"]}}" class=" key2  {{@$var["key1_class"]}}  {{@$var["key2_class"]}} " >{{@$var["key2"]}}</td>
                                <td data-class_name="{{@$var["key3_class"]}}" class="key3  {{@$var["key2_class"]}} {{@$var["key3_class"]}}  "  >{{@$var["key3"]}}</td>
                                <td data-class_name="{{@$var["key4_class"]}}" class="key4   {{@$var["key3_class"]}} {{@$var["key4_class"]}}"  >{{@$var["key4"]}}</td>
                                @if($origin_type)
                                    <td ><a target="_blank" href="http://admin.leo1v1.com/agent/agent_list_new?start_time={{@$start_time}}&end_time={{@$end_time}}">{{@$var["all_count"]}}</a></td>
                                    <td ><a target="_blank" href="http://admin.leo1v1.com/agent/agent_list_new?type=2&start_time={{@$start_time}}&end_time={{@$end_time}}">{{@$var["assigned_count"]}}</a></td>
                                    <td ><a target="_blank" href="http://admin.leo1v1.com/agent/agent_list_new?type=3&start_time={{@$start_time}}&end_time={{@$end_time}}">{{@$var["tmk_assigned_count"]}}</a></td>

                                    <td >{{intval(@$var["avg_first_time"]/60)}}</td>
                                    <td ><a target="_blank" href="http://admin.leo1v1.com/agent/agent_list_new?type=5&start_time={{@$start_time}}&end_time={{@$end_time}}">{{@$var["tq_no_call_count"]}}</a></td>
                                    <td ><a target="_blank" href="http://admin.leo1v1.com/agent/agent_list_new?type=6&start_time={{@$start_time}}&end_time={{@$end_time}}">{{@$var["tq_called_count"]}}</a></td>

                                    <td ><a target="_blank" href="http://admin.leo1v1.com/agent/agent_list_new?type=7&start_time={{@$start_time}}&end_time={{@$end_time}}">{{@$var["tq_call_fail_count"]}}</a></td>
                                    <td ><a target="_blank" href="http://admin.leo1v1.com/agent/agent_list_new?type=8&start_time={{@$start_time}}&end_time={{@$end_time}}">{{@$var["tq_call_succ_valid_count"]}}</a></td>
                                    <td ><a target="_blank" href="http://admin.leo1v1.com/agent/agent_list_new?type=9&start_time={{@$start_time}}&end_time={{@$end_time}}">{{@$var["tq_call_succ_invalid_count"]}}</a></td>
                                    <td ><a target="_blank" href="http://admin.leo1v1.com/agent/agent_list_new?type=10&start_time={{@$start_time}}&end_time={{@$end_time}}">{{@$var["tq_call_fail_invalid_count"]}}</a></td>

                                    <td><a target="_blank" href="http://admin.leo1v1.com/agent/agent_list_new?type=11&start_time={{@$start_time}}&end_time={{@$end_time}}">{{@$var["have_intention_a_count"]}}</a></td>
                                    <td><a target="_blank" href="http://admin.leo1v1.com/agent/agent_list_new?type=12&start_time={{@$start_time}}&end_time={{@$end_time}}">{{@$var["have_intention_b_count"]}}</a></td>
                                    <td><a target="_blank" href="http://admin.leo1v1.com/agent/agent_list_new?type=13&start_time={{@$start_time}}&end_time={{@$end_time}}">{{@$var["have_intention_c_count"]}}</a></td>
                                    <td><a target="_blank" href="http://admin.leo1v1.com/agent/agent_list_new?type=14&start_time={{@$start_time}}&end_time={{@$end_time}}">{{@$var["require_count"]}}</a></td>
                                    <td><a target="_blank" href="http://admin.leo1v1.com/agent/agent_list_new?type=15&start_time={{@$start_time}}&end_time={{@$end_time}}">{{@$var["test_lesson_count"]}}</a></td>
                                    <td><a target="_blank" href="http://admin.leo1v1.com/agent/agent_list_new?type=16&start_time={{@$start_time}}&end_time={{@$end_time}}">{{@$var["succ_test_lesson_count"]}}</a></td>
                                    <td class="text-danger"></td>
                                    <td class="text-danger"></td>
                                    <td><a target="_blank" href="http://admin.leo1v1.com/agent/agent_order_list?start_time={{@$start_time}}&end_time={{@$end_time}}">{{@$var["order_count"]}}</a></td>
                                    <td><a target="_blank" href="http://admin.leo1v1.com/agent/agent_order_list?start_time={{@$start_time}}&end_time={{@$end_time}}">{{@$var["user_count"]}}</a></td>
                                    <td><a target="_blank" href="http://admin.leo1v1.com/agent/agent_order_list?start_time={{@$start_time}}&end_time={{@$end_time}}">{{@$var["order_all_money"]}}</a></td>
                                @else
                                    <td >{{@$var["all_count"]}}</td>
                                    <td >{{@$var["assigned_count"]}}</td>
                                    <td >{{@$var["tmk_assigned_count"]}}</td>

                                    <td >{{intval(@$var["avg_first_time"]/60)}}</td>
                                    <td >{{@$var["tq_no_call_count"]}}</td>
                                    <td >{{@$var["tq_called_count"]}}</td>

                                    <td >{{@$var["tq_call_fail_count"]}}</td>
                                    <td >{{@$var["tq_call_succ_valid_count"]}}</td>
                                    <td >{{@$var["tq_call_succ_invalid_count"]}}</td>
                                    <td >{{@$var["tq_call_fail_invalid_count"]}}</td>


                                    <td>{{@$var["have_intention_a_count"]}}</td>
                                    <td>{{@$var["have_intention_b_count"]}}</td>
                                    <td>{{@$var["have_intention_c_count"]}}</td>
                                    <td>{{@$var["require_count"]}}</td>
                                    <td>
                                        <a href="javascript:;" class="opt-go-info" data-opt="test_lesson" data-val="{{@$var["key4"]}}">
                                            {{@$var["test_lesson_count"]}}
                                        </a>
                                    </td>
                                    <td>{{@$var["succ_test_lesson_count"]}}</td>
                                    <td class="text-danger">{{@$var["distinct_test_count"]}}</td>
                                    <td class="text-danger">{{@$var["distinct_succ_count"]}}</td>

                                    <td>
                                        <a href="javascript:;"  class="opt-go-info" data-opt="order" data-val="{{@$var["key4"]}}">
                                            {{@$var["order_count"]}}
                                        </a>
                                    </td>
                                    <td>{{@$var["user_count"]}}</td>
                                    <td>{{@$var["order_all_money"]}}</td>
                                @endif
                                <td>
                                    <div></div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="row">
            <div class="col-xs-6 col-md-3">
                <div> 年级 </div>
                <div id="id_grade_pic" class="demo-placeholder" style="height:400;"></div>
                <table   class="table table-bordered table-striped"   >
                    <thead> <tr> <td> 编号 </td> <td> 名称  </td><td> 个数</td> </tr> </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-6 col-md-3">
                <div> 科目</div>
                <div id="id_subject_pic" class="demo-placeholder" style="height:400;"></div>
                <table   class="table table-bordered table-striped"   >
                    <thead> <tr> <td> 编号 </td> <td> 名称  </td><td> 个数</td> </tr> </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>
            <div class="col-xs-6 col-md-3">
                <div> pad</div>
                <div id="id_has_pad_pic" class="demo-placeholder" style="height:400;"></div>
                <table   class="table table-bordered table-striped"   >
                    <thead> <tr> <td> 编号 </td> <td> 名称  </td><td> 个数</td> </tr> </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-6 col-md-3">
                <div> 省份</div>
                <div id="id_area_pic" class="demo-placeholder" style="height:400;"></div>
                <table   class="table table-bordered table-striped"   >
                    <thead> <tr> <td> 编号 </td> <td> 名称  </td><td> 个数</td> </tr> </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-md-3">
                <div> 渠道等级</div>
                <div id="id_origin_level_pic" class="demo-placeholder" style="height:400;"></div>
                <table   class="table table-bordered table-striped"   >
                    <thead> <tr> <td> 编号 </td> <td> 名称  </td><td> 个数</td> </tr> </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <div class="col-xs-6 col-md-3">
                <div>签单省份</div>
                <div id="id_order_area_pic" class="demo-placeholder" style="height:400;"></div>
                <table   class="table table-bordered table-striped"   >
                    <thead> <tr> <td> 编号 </td> <td> 名称  </td><td> 个数</td> </tr> </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-6 col-md-3">
                <div>签单年级</div>
                <div id="id_order_grade_pic" class="demo-placeholder" style="height:400;"></div>
                <table   class="table table-bordered table-striped"   >
                    <thead> <tr> <td> 编号 </td> <td> 名称  </td><td> 个数</td> </tr> </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <div class="col-xs-6 col-md-3">
                <div>签单科目</div>
                <div id="id_order_subject_pic" class="demo-placeholder" style="height:400;"></div>
                <table   class="table table-bordered table-striped"   >
                    <thead> <tr> <td> 编号 </td> <td> 名称  </td><td> 个数</td> </tr> </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-md-3">
                <div>试听省份</div>
                <div id="id_test_area_pic" class="demo-placeholder" style="height:400;"></div>
                <table   class="table table-bordered table-striped"   >
                    <thead> <tr> <td> 编号 </td> <td> 名称  </td><td> 个数</td> </tr> </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-6 col-md-3">
                <div>试听年级</div>
                <div id="id_test_grade_pic" class="demo-placeholder" style="height:400;"></div>
                <table   class="table table-bordered table-striped"   >
                    <thead> <tr> <td> 编号 </td> <td> 名称  </td><td> 个数</td> </tr> </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <div class="col-xs-6 col-md-3">
                <div>试听科目</div>
                <div id="id_test_subject_pic" class="demo-placeholder" style="height:400;"></div>
                <table   class="table table-bordered table-striped"   >
                    <thead> <tr> <td> 编号 </td> <td> 名称  </td><td> 个数</td> </tr> </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>


        </div>



    </section>

@endsection
