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
                <div class="col-xs-12 col-md-4">
                    <div class="input-group ">
                        <span >时间:</span>
                        <input type="text" id="id_start_time" class="opt-change"/>
                        <span >-</span>
                        <input type="text" id="id_end_time" class="opt-change"/>
                    </div>
                </div>

            </div>
        </div>
        <hr/>

        <div id="id_pic_user_count" > </div>

        <hr/>
    </section>

@endsection
