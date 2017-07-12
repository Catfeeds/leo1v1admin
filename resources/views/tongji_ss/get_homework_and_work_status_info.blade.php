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
                        <td>科目</td>
                        <td>有讲义(试听课)</td>
                        <td>有讲义(签单数)</td>
                        <td>有讲义(签单率)</td>
                        <td>无讲义(试听课)</td>
                        <td>无讲义(签单数)</td>
                        <td>无讲义(签单率)</td>
                        <td>有作业(试听课)</td>
                        <td>有作业(签单数)</td>
                        <td>有作业(签单率)</td>
                        <td>无作业(试听课)</td>
                        <td>无作业(签单数)</td>
                        <td>无作业(签单率)</td>
                        <td> 操作  </td>
                    </tr>

                 </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr>
                            <td>{{@$var["subject_str"]}}</td>
                            <td>{{@$var["have_tea_cw"]}}</td>
                            <td>{{@$var["have_tea_cw_order"]}}</td>
                            <td>{{@$var["have_tea_cw_per"]}}%</td>
                            <td>{{@$var["no_tea_cw"]}}</td>
                            <td>{{@$var["no_tea_cw_order"]}}</td>
                            <td>{{@$var["no_tea_cw_per"]}}%</td>
                            <td> {{@$var["have_homework"]}}</td>
                            <td> {{@$var["have_homework_order"]}}</td>
                            <td> {{@$var["have_homework_per"]}}%</td>
                            <td> {{@$var["no_homework"]}}</td>
                            <td> {{@$var["no_homework_order"]}}</td>
                            <td> {{@$var["no_homework_per"]}}%</td>
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
