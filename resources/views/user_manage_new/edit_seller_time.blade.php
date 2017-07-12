@extends('layouts.app')
@section('content')

<script src='/js/moment.js'></script>
<link rel='stylesheet' href='/css/fullcalendar.css' />


<script src='/js/fullcalendar.js'></script>
<script src='/js/lang-all.js'></script>
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
<script type="text/javascript" >
 var g_adminid = "{{@$adminid}}";
 var g_groupid = "{{@$groupid}}";
 var g_month = "{{@$month}}";
</script>
<style>
 #cal_week th  {
     text-align:center;  
 }

 #cal_week td  {
     text-align:center;  
 }

 #cal_week .select_free_time {
     background-color : #17a6e8;
 }
</style>

<section class="content">
    <div>
        <div class="row">
            <div class="col-xs-12 col-md-4"  data-title="时间段">
                <div  id="id_date_range" >
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row" id="id_week_info" data-start_time="{{@$start_time}}" >
        <div class="text-center" style="font-size:40px">{{@$month_time_str}}</div>
        <div class="col-xs-12 col-md-12" style="margin-top:20px">
            <table   class="table table-bordered table-striped"   id="cal_week"  >
                <tr>
                    <th>周一 </th>
                    <th>周二 </th>
                    <th>周三 </th>
                    <th>周四 </th>
                    <th>周五 </th>
                    <th>周六 </th>
                    <th>周日 </th>
                </tr>
                <tbody id="id_time_body_3" >
                    <tr id="tr_list_1"><td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr id="tr_list_2"><td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr id="tr_list_3"> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr id="tr_list_4"> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr id="tr_list_5"> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr id="tr_list_6"> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                </tbody>

            </table>
        </div>

    </div>

    <hr/>
    <div class="row">
        <div class="col-xs-6 col-md-6">
        </div>

        <div class="col-xs-6 col-md-2">
            <button id="id_update" class="btn btn-primary">保存 </button>
        </div>
    </div>

</section>
@endsection

