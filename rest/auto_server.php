<?
$app->options('/rest/v1/rfc',$semacao);
$app->post(
    '/rest/v1/rfc',
    function () { 
		$body = app_body();

		//var_dump($body->path);
		$path = '';
		if($body->path) $path = $body->path;
		echo \Backend\Base::process('rfc',$path, null,$body);
       
		//--------------
	}
);


?>