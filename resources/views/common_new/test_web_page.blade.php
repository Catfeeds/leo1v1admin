@extends('layouts.base')
@section('content')

    <section class="content ">
        <div>
            <div class="row  row-query-list" >
                OK :>
            </div>
        </div>
        <hr/>
    </section>
    <script>

     $(function(){
         $.do_ajax("/common_new/web_page_log",{
             "web_page_id" : g_args.web_page_id,
             "from_adminid" : g_args.from_adminid,
         });
     });
    </script>

@endsection
