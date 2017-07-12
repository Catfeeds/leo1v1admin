@extends('layouts.app')
@section('content')
	<script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>

	<script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.categories.js"></script>
	<script language="javascript" type="text/javascript" src="/page_js/lib/select_date_range.js"></script>
    <script type="text/javascript" >

     var g_adminid_right= <?php  echo json_encode($adminid_right); ?> ;

    </script>

    <section class="content ">
        <div class="row">
            <div class="col-xs-12 col-md-5">
                <div id="id_date_range"> </div>
            </div>

            <div  class="col-xs-6 col-md-4">
                <div class="input-group ">
                    <span class="input-group-addon">申请人选择</span>
                    <input class="opt-change form-control" id="id_seller_groupid_ex" />
                </div>
            </div>

        </div>


        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>

                    <td>助教 </td>
                    <td>学情回访 </td>
                    <td>首度回访 </td>                 
                    <td> 操作  </td> </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["sys_operator"]}} </td>
                        <td>{{@$var["xq_count"]}} </td>
                        <td>{{@$var["sc_count"]}} </td>
                        <td>
                            <div class="row-data"
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

