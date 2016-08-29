<?php
  if (file_exists("../reg.php")) {
    include("../reg.php");
	if ($validDomain == "false") {
	  die("Unauthorized Domain: Please contact support.");
	}
  } else {
    die("Missing Files: Please contact support.");
  }
  
if (file_exists("../config.php")) {
  header('Location: ../control.php');
  die();
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
    <title>Setup</title>

    <link href="../assets/css/bootstrap.css" rel="stylesheet" media="screen">
	<link href="../assets/css/bootstrap-glyphicons.css" rel="stylesheet" media="screen">
	
	<script src="http://code.jquery.com/jquery.js"></script>
	<script src="../assets/js/jquery.tablesorter.min.js"></script>
	<script src="../assets/js/jquery.tablesorter.widgets.min.js"></script>
    <style type="text/css">
	  body {
	    background-image:url('../assets/img/login_bg.png');
	  }
	  .main {
        width: 1000px;
        height: 315px;
		
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
	  .center {
	    margin: 0 auto 0 auto;
		width: 50%;
		text-align: center;
	  }
	</style>
  <body>
    <div class="main">
	  <h2 class="center">第一次安装</h2>
	  <div class="well"><p>欢迎第一次安装，这个过程会引导你配置你的帐户、数据库以及其他的一些设置。 <br /><br /> 本程序需要 PHP 5.2 或者以上的版本. 你当前的版本: <?php echo phpversion(); ?>. 并且还需要一个MySql数据库. 最后你需要一个支持HTML5的浏览器. 本程序在<a href="www.google.com/chrome/">Google Chrome</a> 下开发调试通过，在此浏览器下可以保证正常运作。 <br /><br /> 如果你准备好了，请点击下面的按钮:<br /><br /></p><button onclick="location.href='step1.php'" type="button" class="btn btn-success btn-block">开始安装</button></div>
	</div>
  </body>
</html>