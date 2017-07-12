@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/page_js/seller_student/common.js"></script>
  <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>
  <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.pie.min.js"></script>
    <script type="text/javascript" >

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
                     <span class="input-group-addon">渠道选择</span>
                     <input class="opt-change form-control" id="id_origin_ex" />
                 </div>
             </div>

             <div class="col-xs-6 col-md-4" >
                 <div class="input-group ">
                     <span class="input-group-addon">销售选择</span>
                     <input class="opt-change form-control" id="id_seller_groupid_ex" />
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
                     <td >渠道生成时间</td>
                     <td >例子总数</td>
                     <td >未通数</td>
                     <td >接通数</td>
                     <td >已排课</td>
                     <td >试听成功</td>
                     <td >签约数</td>
                     <td >操作</td>
                 </tr>
             </thead>
             <tbody>
                 @foreach ($table_data_list as $var)
                     <tr class="{{$var["level"]}}">
                         <td data-class_name="{{$var["key1_class"]}}" class="key1" >{{$var["key1"]}}</td>
                         <td  data-class_name="{{$var["key2_class"]}}" class=" key2  {{$var["key1_class"]}}  {{$var["key2_class"]}} " >{{$var["key2"]}}</td>
                         <td data-class_name="{{$var["key3_class"]}}" class="key3  {{$var["key2_class"]}} {{$var["key3_class"]}}  "  >{{$var["key3"]}}</td>
                         <td data-class_name="{{$var["key4_class"]}}" class="key4   {{$var["key3_class"]}} {{$var["key4_class"]}}"  >{{$var["key4"]}}</td>

                         <td >{{@$var["create_time"]}}</td>
                         <td >{{@$var["all_count"]}}</td>
                         <td >{{@$var["tq_flag_1_count"]}}</td>
                         <td >{{@$var["tq_flag_2_count"]}}</td>
                         <td >{{@$var["lesson_count"]}}</td>
                         <td >{{@$var["success_count"]}}</td>
                         <td >{{@$var["order_count"]}}</td>
                         <td>
                             <div></div>
                         </td>

                     </tr>
                 @endforeach
             </tbody>
         </table>
         @include("layouts.page")
     </div>

 </section>

@endsection
