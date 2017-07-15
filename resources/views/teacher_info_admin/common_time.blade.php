@extends('layouts.tea_header')
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
    
    <div id='calendar' ></div>

  </section>
  
@endsection


