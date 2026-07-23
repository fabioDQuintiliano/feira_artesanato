<?php
	$iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
	$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
	$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
	$Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
	$webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");


if( $iPod || $iPhone || $iPad){
  
	myHeader("location:https://itunes.apple.com/us/app/tric%C3%B4/id1425463826?l=pt&ls=1&mt=8");
}else {
	myHeader("location:https://play.google.com/store/apps/details?id=com.tricoapp");
}

?>