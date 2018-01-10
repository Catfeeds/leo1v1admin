@extends('layouts.app')
@section('content')

<script src='/js/moment.js'></script>
<link rel='stylesheet' href='/css/fullcalendar.css' />


<script src='/js/fullcalendar.js'></script>
<script src='/js/lang-all.js'></script>
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
<style>
 .fc-event {
     border-radius:0px;
 }
</style>

<section class="content">
    <div class="row" >
        <div class="col-xs-6 col-md-2" >
            <div class="input-group ">
                <span >老师</span>
                <input id="id_teacherid"  /> 
            </div>

        </div>
        
        
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <a href="javascript:;" class="btn btn-warning"  id="id_add_config"  >增加</a>
            </div>
        </div>
        <div class="col-xs-6 col-md-2" style="display:none">
            <div class="input-group ">
                <a href="javascript:;" class="btn btn-warning"  id="id_reset_course"  >刷新数据</a>
            </div>
        </div>


    </div>

    <div id='calendar' ></div>

  </section>
@endsection

