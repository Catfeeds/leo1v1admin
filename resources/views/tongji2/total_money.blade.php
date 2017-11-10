@extends('layouts.app')
@section('content')
    <script type="text/javascript" >
     var g_data_ex_list= <?php  echo json_encode($table_data_list); ?> ;
    </script>


    <section class="content ">
	      <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>
	      <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.categories.js"></script>
    <script type="text/javascript" >
     var g_data_ex_list= <?php  echo json_encode($table_data_list); ?> ;
    </script>


        <div>
            <div class="row  row-query-list" >
               
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-12 col-md-2" class="input-group ">
                    <span class="input-group-addon">总金额:13621133</span>
                    
                </div>
                
            </div>
        </div>
        <hr/>

        <div id="id_pic_user_count" > </div>

        <hr/>
    </section>

@endsection
