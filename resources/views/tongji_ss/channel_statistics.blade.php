@extends('layouts.app_old2')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/page_js/seller_student/common.js"></script>
    <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>
    <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.pie.min.js"></script>
    @if($is_show_pie_flag ==1)
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
         var g_test_has_pad_map= <?php  echo json_encode ($test_has_pad_map); ?> ;
         var g_test_origin_level_map= <?php  echo json_encode ($test_origin_level_map); ?> ;
         var g_order_has_pad_map= <?php  echo json_encode ($order_has_pad_map); ?> ;
         var g_order_origin_level_map= <?php  echo json_encode ($order_origin_level_map); ?> ;
        </script>
    @endif
    <section class="content">
        <div class="book_filter">
            <div class="row row-query-list">
                <div class="col-xs-12 col-md-3" >
                    <button id="id_query" class="btn btn-warning" >点击查询 </button>
                    <button id="id_update" class="btn btn-primary" >更新数据</button>
                </div>
                <div class="col-xs-12 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon" >数据类型</span>
                        <select class="opt-change form-control" id="id_sta_data_type" >
                            <option value="1">漏斗型</option>
                            <option value="2">节点型</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-12 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon" >历史数据</span>
                        <select class="opt-change form-control" id="id_is_history" >
                            <option value="1">显示历史数据</option>
                            <option value="2">隐藏历史数据</option>
                        </select>
                    </div>
                </div>


                <div class="col-xs-12 col-md-4" >
                    <div id="id_date_range"> </div>
                </div>

                <div style="display:none;" class="col-xs-12 col-md-2">
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
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">微信运营</span>
                        <input class="opt-change form-control" id="id_tmk_adminid" />
                    </div>
                </div>



                <div style="display:none;" class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span class="input-group-addon">渠道选择</span>
                        <input class="opt-change form-control" id="id_origin_ex" />
                    </div>
                </div>

                <div style="display:none;"  class="col-md-2 col-xs-2">
                    <div class="input-group">
                        <span class="input-group-addon" class="opt-change">负责人</span>
                        <input id="id_admin_revisiterid" />
                    </div>
                </div>

                <div style="display:none;"  class="col-xs-6 col-md-2">
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
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">销售选择</span>
                        <input class="opt-change form-control" id="id_seller_groupid_ex" />
                    </div>
                </div>
            </div>
        </div>
        <hr />
        <div class="body">
            @if ($field_name !="origin" && $is_history != 1)
                <table class="common-table   ">
                    <thead>
                        <tr>
                            <td >分类</td>
                            <td >例子进入量</td>
                            <td >例子总数(去重)</td>
                            <td>已分配销售</td>
                            <td>TMK有效</td>

                            <td >首次拨打平均</td>
                            <td >未拨打</td>
                            <td >已拨通</td>
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
                                <td >{{@$var["heavy_count"]}}</td>
                                <td >{{@$var["assigned_count"]}}</td>
                                <td >{{@$var["tmk_assigned_count"]}}</td>
                                <td >{{intval(@$var["avg_first_time"]/60)}}</td>
                                <td >{{@$var["tq_no_call_count"]}}</td>
                                <td >{{@$var["called_num"]}}</td>
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

                            <td >例子进入量</td>
                            <td >例子总数(去重)</td>
                            <td>已分配销售</td>
                            <td>TMK有效</td>

                            <td >首次拨打平均</td>
                            <td >未拨打</td>
                            <td >已拨通</td>
                            <td style="display:none;" >消耗率</td>
                            <td >已拨打</td>

                            <td >未接通</td>
                            <td >已拨通-有效</td>
                            <td >已拨通-无效</td>
                            <td style="display:none;" >已拨通率</td>
                            <td style="display:none;" >有效率</td>

                            <td >未接通-无效</td>

                            <td style="display:none;" >有效意向(A)</td>
                            <td style="display:none;" >有效意向(B)</td>
                            <td style="display:none;" >有效意向(C)</td>
                            <td >预约数</td>
                            <td style="display:none;" >上课数</td>
                            <td >上课成功数</td>
                            <td class="text-danger">上课数(去重)</td>
                            <td class="text-danger">上课成功数(去重)</td>
                            <td style="display:none;" >试听率</td>
                            <td >合同个数</td>
                            <td >合同人数</td>
                            <td >合同金额</td>
                            <td > 操作</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($table_data_list as $var)
                            <tr class="{{$var["level"]}}">
                                <td data-class_name="{{$var["key0_class"]}}" class="key0" >{{$var["key0"]}}</td>
                                <td data-class_name="{{$var["key1_class"]}}" class="key1 {{$var["key0_class"]}}  {{$var["key1_class"]}}" >{{$var["key1"]}}</td>
                                <td  data-class_name="{{$var["key2_class"]}}" class=" key2  {{$var["key1_class"]}}  {{$var["key2_class"]}} " >{{$var["key2"]}}</td>
                                <td data-class_name="{{$var["key3_class"]}}" class="key3  {{$var["key2_class"]}} {{$var["key3_class"]}}  "  >{{$var["key3"]}}</td>
                                <td data-class_name="{{$var["key4_class"]}}" class="key4   {{$var["key3_class"]}} {{$var["key4_class"]}}"  >{{$var["key4"]}}</td>
                                <td >
                                    <a href="javascript:;"  class="opt-go-info" data-opt="example" data-val="{{@$var["key4"]}}">
                                        {{@$var["all_count"]}}
                                    </a>
                                </td>
                                <td>{{@$var["heavy_count"]}}</td>
                                <td >
                                    <a href="javascript:;"  class="opt-go-info" data-cond="admin_revisiterid" data-opt="example" data-val="{{@$var["key4"]}}">
                                        {{@$var["assigned_count"]}}
                                    </a>
                                </td>
                                <td >
                                    <a href="javascript:;"  class="opt-go-info" data-cond="tmk" data-opt="example" data-val="{{@$var["key4"]}}">
                                        {{@$var["tmk_assigned_count"]}}
                                    </a>
                                </td>
                                <td >{{intval(@$var["avg_first_time"]/60)}}</td>
                                <td >
                                    <a href="javascript:;"  class="opt-go-info" data-cond="tq_no_call" data-opt="example" data-val="{{@$var["key4"]}}">
                                        {{@$var["tq_no_call_count"]}}
                                    </a>
                                </td>
                                <td >
                                    <a href="javascript:;"  class="opt-go-info" data-cond="called" data-opt="example" data-val="{{@$var["key4"]}}">
                                        {{@$var["called_num"]}}
                                    </a>
                                </td>

                                <td style="display:none;" >{{@$var["consumption_rate"]}}%</td>
                                <td >
                                    <a href="javascript:;"  class="opt-go-info" data-cond="tq_called" data-opt="example" data-val="{{@$var["key4"]}}">
                                        {{@$var["tq_called_count"]}}
                                    </a>
                                </td>

                                <td >
                                    <a href="javascript:;"  class="opt-go-info" data-cond="tq_call_fail" data-opt="example" data-val="{{@$var["key4"]}}">
                                        {{@$var["tq_call_fail_count"]}}
                                    </a>
                                </td>
                                <td >
                                    <a href="javascript:;"  class="opt-go-info" data-cond="tq_call_succ_vaild" data-opt="example" data-val="{{@$var["key4"]}}">
                                        {{@$var["tq_call_succ_valid_count"]}}
                                    </a>
                                </td>
                                <td >
                                    <a href="javascript:;"  class="opt-go-info" data-cond="tq_call_succ_invaild" data-opt="example" data-val="{{@$var["key4"]}}">
                                        {{@$var["tq_call_succ_invalid_count"]}}

                                    </a>
                                </td>

                                <td style="display:none;" >{{@$var['called_rate']}}%</td>
                                <td style="display:none;" >{{@$var['effect_rate ']}}%</td>

                                <td >
                                    <a href="javascript:;"  class="opt-go-info" data-cond="tq_call_fail_invaild" data-opt="example" data-val="{{@$var["key4"]}}">
                                        {{@$var["tq_call_fail_invalid_count"]}}
                                    </a>
                                </td>
                                <td style="display:none;" >{{@$var["have_intention_a_count"]}}</td>
                                <td style="display:none;" >{{@$var["have_intention_b_count"]}}</td>
                                <td style="display:none;" >{{@$var["have_intention_c_count"]}}</td>
                                <td>
                                    <a href="javascript:;" class="opt-go-info" data-cond="require_count" data-opt="test_lesson" data-val="{{@$var["key4"]}}">
                                        {{@$var["require_count"]}}
                                    </a>
                                </td>
                                <td style="display:none;" >
                                    <a href="javascript:;" class="opt-go-info" data-opt="test_lesson" data-val="{{@$var["key4"]}}">
                                        {{@$var["test_lesson_count"]}}
                                    </a>
                                </td>
                                <td>
                                    <a href="javascript:;" class="opt-go-info" data-cond="test_lesson_succ" data-opt="test_lesson" data-val="{{@$var["key4"]}}">
                                        {{@$var["succ_test_lesson_count"]}}
                                    </a>
                                </td>
                                <td class="text-danger">{{@$var["distinct_test_count"]}}</td>
                                <td class="text-danger">{{@$var["distinct_succ_count"]}}</td>

                                <td style="display:none;" >{{@$var['audition_rate']}}%</td>

                                <td >
                                    <a href="javascript:;"  class="opt-go-info" data-opt="order" data-val="{{@$var["key4"]}}">
                                        {{@$var["order_count"]}}
                                    </a>
                                </td>
                                <td>{{@$var["user_count"]}}</td>
                                <td>{{@$var["order_all_money"]}}</td>
                                <td>
                                    <div></div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        @if($is_show_pie_flag ==1)
        <!-- 统计饼图   begin -->
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
                <div> 签单设备</div>
                <div id="id_order_has_pad_pic" class="demo-placeholder" style="height:400;"></div>
                <table   class="table table-bordered table-striped"   >
                    <thead> <tr> <td> 编号 </td> <td> 名称  </td><td> 个数</td> </tr> </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-6 col-md-3">
                <div> 签单渠道等级</div>
                <div id="id_order_origin_level_pic" class="demo-placeholder" style="height:400;"></div>
                <table   class="table table-bordered table-striped"   >
                    <thead> <tr> <td> 编号 </td> <td> 名称  </td><td> 个数</td> </tr> </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>


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
        </div>
        <div class="row">

            <div class="col-xs-6 col-md-3">
                <div>试听科目</div>
                <div id="id_test_subject_pic" class="demo-placeholder" style="height:400;"></div>
                <table   class="table table-bordered table-striped"   >
                    <thead> <tr> <td> 编号 </td> <td> 名称  </td><td> 个数</td> </tr> </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-6 col-md-3">
                <div> 试听设备</div>
                <div id="id_test_has_pad_pic" class="demo-placeholder" style="height:400;"></div>
                <table   class="table table-bordered table-striped"   >
                    <thead> <tr> <td> 编号 </td> <td> 名称  </td><td> 个数</td> </tr> </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-6 col-md-3">
                <div> 试听渠道等级</div>
                <div id="id_test_origin_level_pic" class="demo-placeholder" style="height:400;"></div>
                <table   class="table table-bordered table-striped"   >
                    <thead> <tr> <td> 编号 </td> <td> 名称  </td><td> 个数</td> </tr> </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>


     </div>
     <!-- 统计饼图   end -->
     @endif


 </section>

@endsection
