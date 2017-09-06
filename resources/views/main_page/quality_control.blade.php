@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/page_js/select_user.js"></script>
 <script type="text/javascript" src="//g.alicdn.com/sj/aliphone-sdk/aliphone.min.js" charset="utf-8"></script>

    <script type="text/javascript" src="/page_js/seller_student/common.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js?v={{@$_publish_version}}"></script>
    <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>

  <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.categories.js"></script>
  <script language="javascript" type="text/javascript" src="/page_js/lib/select_date_range.js"></script>
   
    <section class="content">
        <div class="book_filter">


            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div id="id_date_range"> </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">科目</span>
                        <select class="opt-change form-control" id="id_subject" >
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
                        <td>老师 </td>
                        <td>面试通过人数 </td>
                        <td>模拟试听审核(一审)</td>
                        <td>模拟试听审核(二审) </td>
                        <td>第一次试听课</td>
                        <td>第一次试听课平均听课时长</td>

                        <td>第五次试听课</td>
                        <td>第五次试听课平均听课时长</td>
                        <td>第一次常规课</td>
                        <td>第一次常规课平均听课时长</td>
                        <td>第五次常规课</td>
                        <td>第五次常规课平均听课时长</td>
                        <td>总数</td>

                        <td>完成率</td>
                       

                        <td> 操作  </td> </tr>
                 </thead>
                <tbody>
                    @foreach ($table_data_list as $var)

                        <tr>
                           
                            <td> {{@$var["name"]}}</td>
                            <td> {{@$var["real_num"]}}/{{@$var["suc_count"]}}</td>
                            <td> {{@$var["train_first_all"]}}/{{@$var["train_first_pass"]}}</td>
                            <td> {{@$var["train_second_all"]}}</td>
                            <td> {{@$var["test_first"]}}</td>
                            <td> {{@$var["test_first_per_str"]}}</td>

                            <td> {{@$var["test_five"]}}</td>
                            <td> {{@$var["test_five_per_str"]}}</td>

                            <td> {{@$var["regular_first"]}}</td>
                            <td> {{@$var["regular_first_per_str"]}}</td>
                            <td> {{@$var["regular_five"]}}</td>
                            <td> {{@$var["regular_five_per_str"]}}</td>
                            <td> {{@$var["all_num"]}}/ {{@$var["all_target_num"]}}</td>
                            <td> {{@$var["per"]}}%</td>
                          


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
