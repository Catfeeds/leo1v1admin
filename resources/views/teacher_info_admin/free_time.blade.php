@extends('layouts.tea_header')
@section('content')

<script src='/js/moment.js'></script>
<link rel='stylesheet' href='/css/fullcalendar.css' />

<script src='/js/fullcalendar.js'></script>
<script src='/js/lang-all.js'></script>
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
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
 #cal_week .have_lesson {
     background-color : red;
 }

</style>

<section class="content">
    <div class="row" id="id_week_info" data-week_start_time="{{$week_start_time}}" >
        <div class="col-xs-12 col-md-6">
            <table class="table table-bordered table-striped" id="cal_week">
                <tr id="th_list_1">
                    <th width="120px">时段</th>
                    <th>周一 </th>
                    <th>周二 </th>
                    <th>周三 </th>
                    <th>周四 </th>
                    <th>周五 </th>
                    <th>周六 </th>
                    <th>周日 </th>
                </tr>
                <tbody id="id_time_body_1" >
                    <tr data-timeid="07"><td>07:00-07:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="08"><td>08:00-08:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="09"><td>09:00-09:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="10"><td>10:00-10:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="11"><td>11:00-11:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="12"><td>12:00-12:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="13"><td>13:00-13:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="14"><td>14:00-14:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="15"><td>15:00-15:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="16"><td>16:00-16:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="17"><td>17:00-17:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="18"><td>18:00-18:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="19"><td>19:00-19:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="20"><td>20:00-20:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="21"><td>21:00-21:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="22"><td>22:00-22:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                </tbody>

            </table>
        </div>

        <div class="col-xs-12 col-md-6">
            <table   class="table table-bordered table-striped"   id="cal_week"  >
                <tr id="th_list_2">
                    <th width="120px">时段 </th>
                    <th>周一 </th>
                    <th>周二 </th>
                    <th>周三 </th>
                    <th>周四 </th>
                    <th>周五 </th>
                    <th>周六 </th>
                    <th>周日 </th>
                </tr>
                <tbody id="id_time_body_2" >
                    <tr data-timeid="07"><td>07:00-07:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="08"><td>08:00-08:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="09"><td>09:00-09:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="10"><td>10:00-10:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="11"><td>11:00-11:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="12"><td>12:00-12:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="13"><td>13:00-13:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="14"><td>14:00-14:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="15"><td>15:00-15:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="16"><td>16:00-16:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="17"><td>17:00-17:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="18"><td>18:00-18:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="19"><td>19:00-19:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="20"><td>20:00-20:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="21"><td>21:00-21:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="22"><td>22:00-22:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                </tbody>

            </table>
        </div>

        <div class="col-xs-12 col-md-6">
            <table   class="table table-bordered table-striped"   id="cal_week"  >
                <tr id="th_list_3">
                    <th width="120px">时段 </th>
                    <th>周一 </th>
                    <th>周二 </th>
                    <th>周三 </th>
                    <th>周四 </th>
                    <th>周五 </th>
                    <th>周六 </th>
                    <th>周日 </th>
                </tr>
                <tbody id="id_time_body_3" >
                    <tr data-timeid="07"><td>07:00-07:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="08"><td>08:00-08:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="09"><td>09:00-09:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="10"><td>10:00-10:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="11"><td>11:00-11:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="12"><td>12:00-12:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="13"><td>13:00-13:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="14"><td>14:00-14:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="15"><td>15:00-15:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="16"><td>16:00-16:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="17"><td>17:00-17:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="18"><td>18:00-18:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="19"><td>19:00-19:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="20"><td>20:00-20:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="21"><td>21:00-21:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
                    <tr data-timeid="22"><td>22:00-22:59</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>
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

