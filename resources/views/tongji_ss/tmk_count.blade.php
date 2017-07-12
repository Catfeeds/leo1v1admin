@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/page_js/seller_student/common.js"></script>
    <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>
    <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.pie.min.js"></script>
    <section class="content">
        <div class="book_filter">
            <div class="row">
                <!-- <div class="col-xs-12 col-md-2">
                     <button id="id_query" class="btn btn-warning" >点击查询 </button>
                     </div>
                   -->
             <div class="col-xs-12 col-md-6">
                 <div id="id_date_range"> </div>
             </div>

        <div class="col-xs-6 col-md-3">
            <div class="input-group ">
                <span class="input-group-addon">渠道等级</span>
                <input class="opt-change form-control" id="id_origin_level" />
            </div>
        </div>




             <div class="col-xs-12 col-md-2" style="display:none;" >
                 <div class="input-group ">
                     <span class="input-group-addon" style="color:red;" >统计项</span>
                     <select class="opt-change form-control" id="id_check_field_id" >
                         <option value="1">渠道</option>
                         <option value="2">年级</option>
                         <option value="3">科目</option>
                         <option value="4">tmk人员</option>
                         <option value="5">销售</option>
                     </select>
                 </div>
             </div>

             <div class="col-xs-6 col-md-2" style="display:none;">
                 <div class="input-group ">
                     <span >渠道:</span>
                     <input type="text" id="id_origin" class="opt-change"/>
                 </div>
             </div>
             <div class="col-xs-6 col-md-3" style="display:none;">
                 <div class="input-group ">
                     <span class="input-group-addon">微信运营</span>
                     <input class="opt-change form-control" id="id_tmk_adminid" />
                 </div>
             </div>



             <div class="col-xs-6 col-md-4" style="display:none;">
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

             <div class="col-xs-6 col-md-4" style="display:none;">
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
                     <td >新例子数</td>
                     <td >例子总数</td>
                     <td>TMK有效</td>
                     <td >TMK-上课数</td>
                     <td >TMK-上课成功数</td>

                     <td >已回访</td>
                     <td >TQ未回访</td>
                     <td >TQ&销售未回访</td>
                     <td >无效资源</td>
                     <td style="display:none" >未接通</td>
                     <td >有效意向(A)</td>
                     <td >有效意向(B)</td>
                     <td >有效意向(C)</td>

                     <td >合同个数</td>
                     <td >合同金额</td>
                     <td > 操作</td>
                 </tr>
             </thead>
             <tbody>
                 @foreach ($table_data_list as $var)
                     <tr >
                         <td >{{@$var["title"]}}</td>
                         <td >{{@$var["new_user_count"]}}</td>
                         <td >{{@$var["all_count"]}}</td>
                         <td >{{@$var["tmk_assigned_count"]}}</td>
                         <td>{{@$var["tmk_count"]}}</td>
                         <!-- //succ_test_lesson_count
                            -->
                         <td>{{@$var["succ_test_lesson_count"]}}</td>

                         <td >{{@$var["tq_called_count"]}}</td>
                         <td >{{@$var["tq_no_call_count"]}}</td>
                         <td >{{@$var["no_call_count"]}}</td>
                         <td >{{@$var["invalid_count"]}}</td>
                         <td style="display:none;" >{{@$var["tq_call_fail_count"]}}</td>
                         <td>{{@$var["have_intention_a_count"]}}</td>
                         <td>{{@$var["have_intention_b_count"]}}</td>
                         <td>{{@$var["have_intention_c_count"]}}</td>

                         <td><a data-tmk_adminid="{{@$var['check_value']}}" class="opt_order_tmk">{{@$var["order_count"]}}</a></td>
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
                     <td >key1</td>
                     <td >key2</td>
                     <td >key3</td>
                     <td >渠道</td>
                     <td >例子总数</td>
                     <!-- <td>已分配销售</td> -->
                     <td>TMK有效</td>
                     <td>TMK-已上课</td>
                     <!-- <td >已回访</td>
                          <td >TQ未回访</td>
                          <td >TQ&销售未回访</td>
                          <td >无效资源</td>
                          <td >未接通</td>
                          <td >有效意向(A)</td> -->
                     <td style="display:none;">有效意向(B)</td>
                     <td style="display:none;">有效意向(C)</td>
                     <td style="display:none;">预约数</td>
                     <td style="display:none;">上课数</td>
                     <td style="display:none;">上课成功数</td>
                     <td style="display:none;" >合同个数</td>
                     <td style="display:none;" >合同人数</td>
                     <td style="display:none;" >合同金额</td>
                     <td > 操作</td>
                 </tr>
             </thead>
             <tbody>
                 @foreach ($table_data_list as $var)
                     <tr class="{{$var["level"]}}">
                         <td data-class_name="{{$var["key1_class"]}}" class="key1" >{{$var["key1"]}}</td>
                         <td  data-class_name="{{$var["key2_class"]}}" class=" key2  {{$var["key1_class"]}}  {{$var["key2_class"]}} " >{{$var["key2"]}}</td>
                         <td data-class_name="{{$var["key3_class"]}}" class="key3  {{$var["key2_class"]}} {{$var["key3_class"]}}  "  >{{$var["key3"]}}</td>
                         <td data-class_name="{{$var["key4_class"]}}" class="key4   {{$var["key3_class"]}} {{$var["key4_class"]}}"  >{{$var["key4"]}}</td>

                         <td >{{@$var["all_count"]}}</td>
                         <!-- <td >{{@$var["assigned_count"]}}</td> -->
                         <td >{{@$var["tmk_assigned_count"]}}</td>
                         <td >{{@$var["tmk_count"]}}</td>
                         <!-- <td >{{@$var["tq_called_count"]}}</td>
                              <td >{{@$var["tq_no_call_count"]}}</td>
                              <td >{{@$var["no_call_count"]}}</td>
                              <td >{{@$var["invalid_count"]}}</td>
                              <td >{{@$var["tq_call_fail_count"]}}</td>
                              <td>{{@$var["have_intention_a_count"]}}</td>
                              <td>{{@$var["have_intention_b_count"]}}</td>
                              <td>{{@$var["have_intention_c_count"]}}</td>
                              <td>{{@$var["require_count"]}}</td>
                              <td>{{@$var["test_lesson_count"]}}</td>
                              <td>{{@$var["succ_test_lesson_count"]}}</td>
                              <td>{{@$var["order_count"]}}</td>
                              <td>{{@$var["user_count"]}}</td>
                              <td>{{@$var["order_all_money"]}}</td> -->
                         <td>
                             <div></div>
                         </td>

                     </tr>
                 @endforeach
             </tbody>
         </table>
        @endif
     </div>

     <div class="row" style="display:none;" >
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

         <div class="col-xs-6 col-md-3">
             <div> 渠道等级</div>
             <div id="id_origin_level_pic" class="demo-placeholder" style="height:400;"></div>
             <table   class="table table-bordered table-striped"   >
                 <thead> <tr> <td> 编号 </td> <td> 名称  </td><td> 个数</td> </tr> </thead>
                 <tbody>
                 </tbody>
             </table>
         </div>



     </div>



 </section>

@endsection
