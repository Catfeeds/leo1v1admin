
@extends('layouts.app')
@section('content')

    <link href="/ztree/zTreeStyle.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="/ztree/jquery.ztree.all.min.js"></script>
    <script type="text/javascript">
     var ext = <?php echo json_encode($ext);?>;
     var zNodes = <?php echo json_encode($info)?>;
	  </script>
<section class='content'>
    <div> <!-- search ... -->
        <div class='row ' >
            <div class="col-xs-12 col-md-2">
                <div class="input-group ">
                    <button class="btn btn-primary" id="id_flush_data">刷新数据</button>
                </div>
            </div>
        </div>
    </div>
    <hr/>
    <div class="zTreeDemoBackground left" style="weight: 400px'">
		    <ul id="treeDemo" class="ztree"></ul>
	  </div>
    <table class="common-table">
    </table>
    <div id="log-msg" style="width:700px; height:600px; float:right;  overflow:scroll;">

    </div>

@include('layouts.page')
</section>
@endsection
