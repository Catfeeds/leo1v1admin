@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/page_js/select_user.js"></script>
 <script type="text/javascript" src="//g.alicdn.com/sj/aliphone-sdk/aliphone.min.js" charset="utf-8"></script>

    <script type="text/javascript" src="/page_js/seller_student/common.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js?v={{@$_publish_version}}"></script>
    <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>

  <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.categories.js"></script>
  <script language="javascript" type="text/javascript" src="/page_js/lib/select_date_range.js"></script>
    <script type="text/javascript" >

     var g_data_ex_list= <?php  echo json_encode($table_data_list); ?> ;
    </script>

    <section class="content">
        <div class="book_filter">


            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div id="id_date_range"> </div>
                </div>
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span class="input-group-addon">历史分组</span>
                        <select class="opt-change form-control" id="id_monthtime_flag" >                           
                            <option value="1">否</option>
                            <option value="2">是</option>
                        </select>
                    </div>
                </div>

            </div>




        </div>
        <hr />
        <div class="body">
            <table class="common-table  ">
                <thead>
                    <tr>
                        <td>类型 </td>
                        <td>主管 </td>
                        <td>小组 </td>


                        <td>负责人 </td>
                        <td>老师已下载</td>
                        <td>老师未下载</td>
                        <td>无试卷</td>

                        <td> 操作  </td> </tr>

                 </thead>
                <tbody>
                    @foreach ($table_data_list as $var)

                        <tr class="{{$var["level"]}}">
                            <td data-class_name="{{$var["main_type_class"]}}" class="main_type" >{{$var["main_type_str"]}}</td>
                            <td  data-class_name="{{$var["up_group_name_class"]}}" class=" up_group_name  {{$var["main_type_class"]}}  {{$var["up_group_name_class"]}} " >{{$var["up_group_name"]}}</td>
                            <td data-class_name="{{$var["group_name_class"]}}" class="group_name  {{$var["up_group_name_class"]}} {{$var["group_name_class"]}}  "  >{{$var["group_name"]}}</td>
                            <td data-class_name="{{$var["account_class"]}}" class="account   {{$var["group_name_class"]}} {{$var["account_class"]}}"  >{{$var["account"]}}</td>


                            <td> {{@$var["have_download_per"]}}%</td>
                            <td> {{@$var["no_download_per"]}}%</td>
                            <td> {{@$var["no_paper_per"]}}%</td>


                            <td><div class=" row-data"
                                    {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >

                            </div></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include("layouts.page")
        </div>

    <script src="/js/qiniu/plupload/plupload.full.min.js"></script>

@endsection
