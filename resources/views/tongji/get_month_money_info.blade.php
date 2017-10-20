@extends('layouts.app')
@section('content')
    <section class="content ">
          <div class="row">

            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">年</span>
                    <select type="text" class="opt-change " id="id_year" >
                        <option value="2014" >2014</option>
                        <option value="2015"  >2015</option>
                        <option value="2016"  >2016</option>
                        <option value="2017"  >2017</option>
                    </select>
                </div>
            </div>
           
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td>月份 </td>
                    <td>收入</td>
                    <td  >签单课时</td>
                    <td  >签单数</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["order_month"]}} </td>
                        <td>{{$var["all_money"]}} </td>
                        <td >{{(float)$var["order_total"]/100}} </td>
                        <td >{{@$var["count"]}} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection

