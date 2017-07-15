@extends('layouts.tea_header')
@section('content')

    <script src='/js/moment.js'></script>
    <link rel='stylesheet' href='/css/fullcalendar.css' />
    <script type="text/javascript" src='/page_js/select_teacher_free_time.js'></script>
    <script type="text/javascript" src='/page_js/set_lesson_time.js'></script>


    <script src='/js/fullcalendar.js'></script>
    <script src='/js/lang-all.js'></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" >
     var g_teacherid = "{{$teacherid}}";
    </script>
    
    <style>
     .fc-event {
         border-radius:0px;
     }
    </style>

    <section class="content">
        
        <div id='id_calendar' ></div>

    </section>

     
    
@endsection



