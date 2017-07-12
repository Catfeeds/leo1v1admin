@extends('layouts.app')
@section('content')
    <script type="text/javascript" >
     var g_adminid_right= <?php  echo json_encode($adminid_right); ?> ;
    </script>

    <section class="content ">
        
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div  class="col-xs-6 col-md-4">
                <div class="input-group ">
                    <span class="input-group-addon">销售选择</span>
                    <input class="opt-change form-control" id="id_seller_groupid_ex" />
                </div>
            </div>

                <div class="col-xs-6 col-md-2"  >
                    <button class="btn btn-primary" id="id_level_show_all"> 张开所有 </button>
                </div>

            </div>
        </div>
        <hr/>

        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>类型 </td>
                    <td>主管 </td>
                    <td>小组 </td>
                    <td>成员 </td>
                    <td>申请数 </td>
                    <td>排课数</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    
                    <tr class="{{$var["level"]}}">
                        <td data-class_name="{{$var["main_type_class"]}}" class="main_type" >{{$var["main_type_str"]}}</td>
                        <td  data-class_name="{{$var["up_group_name_class"]}}" class=" up_group_name  {{$var["main_type_class"]}}  {{$var["up_group_name_class"]}} " >{{$var["up_group_name"]}}</td>
                        <td data-class_name="{{$var["group_name_class"]}}" class="group_name  {{$var["up_group_name_class"]}} {{$var["group_name_class"]}}  "  >{{$var["group_name"]}}</td>
                        <td data-class_name="{{$var["account_class"]}}" class="account   {{$var["group_name_class"]}} {{$var["account_class"]}}"  >{{$var["account"]}}</td>
                        <td >{{@$var["require_count"]}}</td> 
                        <td >{{@$var["set_lesson_count"]}}</td> 
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
    </section>
    
@endsection


