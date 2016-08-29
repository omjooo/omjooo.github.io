<?php
  $url = "http://127.0.0.1/";

  if (file_exists("config.php")) {
    include("config.php");
  } else {
    die();
  }
  
  include("functions.php");
  
  $uid = $_GET['uid'];
  
  $path = realpath('dlfiles/' . $uid);
  
  if (is_dir($path . '/')) {
       $files = array_diff(scandir($path), array('.', '..'));
        foreach ($files as $file)
        {
            unlink(realpath($path) . '/' . $file);
        }

    rmdir($path . '/');
	echo "图片已删除";
  } else {
    echo "出现了一个错误";
    die();
  }
?>