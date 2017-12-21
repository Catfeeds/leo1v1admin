<html>
<head>
<meta charset="utf-8">
</head>
<body>

<script src="http://cdn.static.runoob.com/libs/jquery/1.10.2/jquery.min.js">
</script>
<script>

function GetQueryString(name)
{
     var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     var r = window.location.search.substr(1).match(reg);
     if(r!=null)return  unescape(r[2]); return null;
}

$(document).ready(function(){
  var file = GetQueryString("file");
  $("#file").val(file);	
  var arr = new Array();	
  $("a span").each(function(){
	  var level = $(this).parents('li').length;
	  var obj = {'level':level,'name':$(this).text()};
	  //console.log(obj);
          arr.push(obj);
  })
  var data = JSON.stringify(arr);
  $("#data").val(data);
  $('.download').submit(function(e){
	alert("Submitted");
  });	
  console.log(data);


});
</script>
<form method="post" enctype="multipart/form-data">
<input id="data" name="data" value="" type="hidden">
<input id="file" name="file" value="" type="hidden">
<button type="submit" value="xiazai">xiazai</button>
</form>

