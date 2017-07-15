@extends('layouts.app')
@section('content')
    <section class="content">

        <div class="row">
            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <span class="input-group-addon">数据库</span>
                    <select class="opt-change form-control" id="id_db_name" >
                        <option value="db_weiyi">db_weiyi</option>
                        <option value="db_weiyi_admin">db_weiyi_admin</option>
                        <option value="db_tool">db_tool</option>
                        <option value="db_account">db_account</option>

                    </select>

                </div>
            </div>
            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <button id="id_query"  > 查询 </button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-12">
                <textarea id="id_sql" style="width:100%; height:100px;" > </textarea>
            </div>
        </div>



        <hr/>

        <table   class=" common-table "   >
            <thead>
            </thead>

            <tbody>
            </tbody>
        </table>
@endsection

