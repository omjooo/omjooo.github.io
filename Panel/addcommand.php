<?php
  if (file_exists("reg.php")) {
    include("reg.php");
	if ($validDomain == "false") {
	  die("域名未授权，请联系管理员");
	}
  } else {
    die("文件丢失");
  }
  
include("functions.php");

if($_GET['uid'] == ""){
	echo "请至少选择一台主机";
	die();
}
if(!isset($_GET['command'])){
	echo "你不应该得到这个错误";
	die();
}

$UID = explode(",", $_GET['uid']);
$Command = $_GET['command'];
$Arg1 = $_GET['arg1'];
$Arg2 = $_GET['arg2'];

if($Arg1 == "undefined") {
  $Arg1 = "";
}
if($Arg2 == "undefined") {
  $Arg2 = "";
}

foreach ($UID as $currentUID) {
  $statement = $connect->prepare("INSERT INTO `commands` (`uid`, `command`, `arg1`, `arg2`) VALUES ('$currentUID', '$Command', '$Arg1', '$Arg2')");
  $statement->execute();
}

echo "成功！";
?>