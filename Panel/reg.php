<?php
$allowedDomains = array("http://127.0.0.1/", "127.0.0.1","http://www.linf0.com/", "www.linf0.com");//服务器域名或者IP地址，支持多域名或IP

if (in_array($_SERVER['HTTP_HOST'], $allowedDomains)) {
	$validDomain = "true";
} else {
	$validDomain = "false";
}
?>