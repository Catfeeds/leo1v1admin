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
                        <td>老师</td>
                        {!!\App\Helper\Utils::th_order_gen([                        
                            ["下载率","download_per" ],
                            ["老师已下载(试听课数)","have_download_num"],
                            ["老师已下载(签单数)","have_download_order" ],
                            ["老师已下载(签单率)","have_download_per" ],
                            ["老师未下载(试听课数)","no_download_num" ],
                            ["老师未下载(签单数)","no_download_order" ],
                            ["老师未下载(签单率)","no_download_per" ],
                            ["无试卷(试听课数)","no_paper_num" ],
                            ["无试卷(签单数)","no_paper_order" ],
                            ["无试卷(签单率)","no_paper_per" ],
                           ])  !!}


                        <td> 操作  </td> </tr>

                 </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr>
                            <td>{{@$var["realname"]}}</td>
                            <td>{{@$var["download_per"]}}%</td>
                            <td> {{@$var["have_download_num"]}}</td>
                            <td> {{@$var["have_download_order"]}}</td>
                            <td> {{@$var["have_download_per"]}}%</td>
                            <td> {{@$var["no_download_num"]}}</td>
                            <td> {{@$var["no_download_order"]}}</td>
                            <td> {{@$var["no_download_per"]}}%</td>
                            <td> {{@$var["no_paper_num"]}}</td>
                            <td> {{@$var["no_paper_order"]}}</td>
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
