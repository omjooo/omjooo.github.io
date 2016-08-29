<?php
$allowedDomains = array("http://127.0.0.1/", "127.0.0.1");//服务器域名或者IP地址，支持多域名或IP

if (in_array($_SERVER['HTTP_HOST'], $allowedDomains)) {
	$validDomain = "true";
} else {
	$validDomain = "false";
}
?>