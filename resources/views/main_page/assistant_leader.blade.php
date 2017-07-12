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
                        <td>目标回访量</td>
                        <td>实际回访量</td>
                        <td>回访完成率</td>

                        <td>课时量</td>
                        <td>上课人数</td>
                        <td>在读学员</td>
                        <td>课时系数-在读</td>

                        <td>课时系数-上课</td>
                        <td>目标系数-月</td>
                        <td>系数完成率-月</td>
                        <td>续费量</td>
                        <td>续费人数</td>
                        <td>续费课时量</td>
                        <td>赠送课时量</td>
                        <td>结课人数</td>
                        <td>退费人数</td>

                        <td> 操作  </td> </tr>
                 </thead>
                <tbody>
                    @foreach ($table_data_list as $var)

                        <tr class="{{$var["level"]}}">
                            <td data-class_name="{{$var["main_type_class"]}}" class="main_type" >{{$var["main_type_str"]}}</td>
                            <td  data-class_name="{{$var["up_group_name_class"]}}" class=" up_group_name  {{$var["main_type_class"]}}  {{$var["up_group_name_class"]}} " >{{$var["up_group_name"]}}</td>
                            <td data-class_name="{{$var["group_name_class"]}}" class="group_name  {{$var["up_group_name_class"]}} {{$var["group_name_class"]}}  "  >{{$var["group_name"]}}</td>
                            <td data-class_name="{{$var["account_class"]}}" class="account   {{$var["group_name_class"]}} {{$var["account_class"]}}"  >{{$var["account"]}}</td>


                            <td> <a href="javascript:;" class="td-assign_count" >{{@$var["except_revisit_count"]}}</a></td>
                            <td> <a href="javascript:;" class="td-get_new_count" >{{@$var["revisit_count"]}}</a></td>
                            <td> <a href="javascript:;" class="td-get_histroy_count" >{{@$var["revisit_per"]}}%</a></td>

                            <td> <a href="javascript:;" class="td-all_count" >{{@$var["lesson_count"]/100}}</a></td>
                            <td> <a href="javascript:;" class="td-all_count_0" >{{@$var["user_count"]}}</a></td>
                            <td> <a href="javascript:;" class="td-all_count_0" >{{@$var["stu_is_read"]}}</a></td>
                            <td> <a href="javascript:;" class="td-all_count_1" >{{@$var["read_xs"]/100}}</a></td>
                            <td> <a href="javascript:;" class="td-global_tq_no_call " >{{@$var["lesson_xs"]/100}}</a></td>
                            <td> <a href="javascript:;" class="td-no_call" >{{@$var["lesson_target"]}}</a></td>
                            <td> <a href="javascript:;" class="td-no_call_0" >{{@$var["target_per"]}}</a></td>
                            <td> <a href="javascript:;" class="td-no_call_1" >{{@$var["all_price"]/100}}</a></td>
                            <td> {{@$var["all_student"]}}</td>
                            <td> {{@$var["buy_total"]/100}}</td>
                            <td> {{@$var["give_total"]/100}}</td>
                            <td> <a href="javascript:;" class="td-require_test_count" >{{@$var["jk_num"]}}</a></td>
                            <td> <a href="javascript:;" class="td-test_lesson_count" ></a></td>


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
