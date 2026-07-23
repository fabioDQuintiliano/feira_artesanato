<?php
include("functions/nusoap/nusoap.php");

$client=new SoapClient('http://localhost/oca/ws.php?wsdl',array('trace'=>true));
$param= new  SoapVar(array('username' => 'fabio','password'=>'test'), SOAP_ENC_OBJECT); 
$header = new SoapHeader('http://localhost/oca/ws.php', 'AuthenticationInfo', $param,false);
$client->__setSoapHeaders($header);
try{
  	$data=$client->__soapCall('getData',array('id'=>'1'));
	print_r($data);
}
catch (SoapFault  $exception)
{
echo $exception->faultstring;
}
?>