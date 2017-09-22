@extends('layouts.app')
@section('content')
    <style>
     #id_content .panel-heading {
         font-size:20px;
         text-align:center;
     }
     .font_thead{
         font-size:17px;
         color:#3c8dbc;
     }
    </style>
    <script type="text/javascript" >
    </script>

    <section class="content " id="id_content">

        <div  id="id_query_row">
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">历史数据</span>
                        <select class="opt-change form-control" id="id_history_data">
                            <option value="0">是</option>
                            <option value="1">否</option>
                        </select>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div>
                    <span>目标</span>
                </div>
            </div>

            <!-- <div class="col-xs-12 col-md-4">
                 <div class="panel panel-warning"  >
                 <div class="panel-heading">
                 本月-我的数据
                 </div>
                 <div class="input-group " id="id_seller_new">
                 <span class="input-group-addon">销售</span>
                 <input id="id_test_seller_id" style="width:100px" class="opt-change" />
                 </div>

                 <div class="panel-body">
                 <table   class="table table-bordered "   >
                 <thead>
                 <tr>
                 <td style="width:140px"><strong><font class="font_thead">项目</font><strong></td>
                 <td><strong><font class="font_thead">数值</font><strong></td>
                 <td><strong><font class="font_thead">公司排名</font><strong></td>
                 </tr>
                 </thead>
                 <tbody id="id_self_body">
                 </tbody>
                 </table>
                 </div>
                 </div>

                 </div>
               -->

        </div>




    </section>

@endsection
