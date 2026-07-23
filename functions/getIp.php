<?php
class UserInfo
{
 public function getIp()
 {
 
 if ( !empty($_SERVER['REMOTE_ADDR']) )
 {
 $ip = $_SERVER['REMOTE_ADDR'];
 }
 elseif( !empty($_SERVER['HTTP_CLIENT_IP']) )
 {
 $ip = $_SERVER['HTTP_CLIENT_IP'];
 }
 elseif ( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) )
 {
 $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
 }
 elseif ( !empty($_SERVER['HTTP_X_FORWARDED']) )
 {
 $ip = $_SERVER['HTTP_X_FORWARDED'];
 }
 elseif ( !empty($_SERVER['HTTP_FORWARDED']) )
 {
 $ip = $_SERVER['HTTP_FORWARDED'];
 }
 elseif ( !empty($_SERVER['HTTP_X_COMING_FROM']) )
 {
 $ip = $_SERVER['HTTP_X_COMING_FROM'];
 }
 elseif ( !empty($_SERVER['HTTP_COMING_FROM']) )
 {
 $ip = $_SERVER['HTTP_COMING_FROM'];
 }
 else
 {
 $ip = null;
 }
 return $ip;
 }
}
 ?>