@extends('layouts.app')
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
    </script>

 <section class="content">
     <div class="book_filter">

         <div class="row">
             <div class="col-xs-12 col-md-4">
                 <div id="id_date_range"> </div>
             </div>

             <div class="col-xs-6 col-md-2">
                 <div class="input-group ">
                     <span >渠道:</span>
                     <input type="text" id="id_origin" class="opt-change"/>
                 </div>
             </div>
             <div class="col-xs-6 col-md-4">
                 <div class="input-group ">
                     <span >渠道EX:</span>
                     <input type="text" id="id_origin_ex" class="opt-change"/>
                 </div>
             </div>

             <div class="col-md-2 col-xs-6">
                 <div class="input-group">
                     <span class="input-group-addon" class="opt-change">负责人</span>
                     <input id="id_admin_revisiterid" />
                 </div>
             </div>

             <div class="col-xs-6 col-md-2">
                 <div class="input-group ">
                     <span class="input-group-addon">销售分组</span>
                     <select class="opt-change form-control" id="id_groupid" >
                         <option value="-1">全部</option>
                         @foreach ( $group_list as $var )
                             <option value="{{$var['groupid']}}">{{$var['group_name']}}</option>
                         @endforeach 
                     </select>
                 </div>
             </div>


         </div>
     </div>
     <hr />
     <div class="body">
         <table class="common-table ">
             <thead>
                 <tr>
                     <td >key1</td>
                     <td >key2</td>
                     <td >key3</td>
                     <td >渠道</td>
                     <td >预约总数</td>
                     <td >已分配销售</td>
                     <td >未回访</td>
                     <td >已回访</td>
                     <td >无效资源</td>
                     <td >未接通</td>
                     <td >有效意向(A)</td>
                     <td >有效意向(B)</td>
                     <td >有效意向(C)</td>
                     <td >试听-预约</td>
                     <td >试听-已排</td>
                     <td >已试听-待跟进</td>
                     <td >已试听-未签</td>
                     <td >已试听-已签</td>
                     <td >试听-时间待定</td>
                     <td >试听-时间确定</td>
                     <td >试听-无法排课</td>
                     <td >试听-驳回</td>

                     <td >首次付费</td>
                     <td >总付费</td>
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
                         <td >{{@$var["al_count"]}}</td>
                         <td >{{@$var["revisited_yi"]}}</td>
                         <td >{{@$var["revisited_wei"]}}</td>
                         <td >{{@$var["revisited_yhf"]}}</td>
                         <td >{{@$var["revisited_wuxiao"]}}</td>
                         <td >{{@$var["no_call"]}}</td>
                         <td >{{@$var["effective_a"]}}</td>
                         <td >{{@$var["effective_b"]}}</td>
                         <td >{{@$var["effective_c"]}}</td>
                         <td >{{@$var["reservation"]}}</td>
                         <td >{{@$var["revisited_yipai"]}}</td>
                         <td >{{@$var["listened_dai"]}}</td>
                         <td >{{@$var["listened_wei"]}}</td>
                         <td >{{@$var["listened_yi"]}}</td>
                         <td >{{@$var["listen_dai"]}}</td>
                         <td >{{@$var["listen_que"]}}</td>
                         <td >{{@$var["listen_cannot"]}}</td>
                         <td >{{@$var["listen_refuse"]}}</td>
                         <td >{{@$var["first_money"]}}</td>
                         <td >{{@$var["money_all"]}}</td>
                         <td>
                             <div></div>
                         </td>

                     </tr>
                 @endforeach
             </tbody>
         </table>
         @include("layouts.page")
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
        
     

 </section>

@endsection

