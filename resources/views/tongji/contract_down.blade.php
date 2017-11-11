@extends('layouts.app')
@section('content')
    <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.js"></script>
    
  <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.categories.js"></script>
  <script language="javascript" type="text/javascript" src="/page_js/lib/select_date_range.js"></script>
    <script type="text/javascript" >

     var g_data_ex_list= <?php  echo json_encode ($data_ex_list); ?> ;
    </script>

    <section class="content ">
        <div class="row" style="display:none">
            <div class="col-xs-12 col-md-6">
                <div id="id_date_range"> </div>
            </div>

            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">新签业绩</span>
                    <select class="opt-change form-control" id="id_contract_type" >
                    </select>
                </div>
            </div>



            <div style="display:none;" class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">详细分类</span>
                    <select class="opt-change form-control " id="id_stu_from_type" >
                    </select>
                </div>
            </div>

        </div>


        <div class="row" id="">
            <div  class="col-md-3">
                新签业绩
            </div>
            <div  class="col-md-3">
                11月11日
            </div>
            <div  class="col-md-3">
                总业绩：15257042
            </div>

        </div>
        <hr/>
        <div style="font-size:22px"> 新签业绩</div>
        <div id="id_contract_money"> </div>
        <hr/>
        <div style="font-size:22px" style="display:none"> </div>
        <div id="id_contract_user" style="display:none"> </div>

        <table     class="common-table" style="display:none" >
            <thead>
                <tr> <td>时间 </td> <td>合同金额</td> <td> 合同个数</td>

                        <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td> {{$var["title"]}} </td>
                        <td> {{@$var["money"]}} </td>
                        <td> {{@$var["order_count"]}} </td>
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
