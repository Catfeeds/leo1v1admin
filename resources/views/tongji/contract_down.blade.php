@extends('layouts.app')
@section('content')
    <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.js"></script>

  <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.categories.js"></script>
  <script language="javascript" type="text/javascript" src="/page_js/lib/select_date_range.js"></script>
    <script type="text/javascript" >

     var g_data_ex_list= <?php  echo json_encode ($data_ex_list); ?> ;
    </script>

    <section class="content ">
        <div class="row">
            <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-12 col-md-2" class="input-group ">
                    <span class="input-group-addon">总金额:15257042</span>

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
