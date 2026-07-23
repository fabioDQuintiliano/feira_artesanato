<?
$app = new \Slim\Slim();
$semacao = function(){};


define('DEVELOPMENT_MODE', true);
//AUTICACAO PARA REST
$app->options('/rest/token',$semacao);
$app->get(
    '/rest/token',
    function (){  
		if($_GET['v']*1 < 5){
			return json_result(array('t'=>time(), 'm'=>'Atenção: Há uma nova versão disponível...<br/><a href="http://www.update.com">Atualizar</a>'));       
		}

		return json_result(array('t'=>time()));        
    }
);



/****************/


/***AUTO_LOAD_FUNCOES**/
if ($extradir = @dir("rest/")) {
  $listalib=Array();
  while ($zv_file = $extradir->read()) {
	if (preg_match('/^auto_.*\.php$/', $zv_file) > 0) {
		$listalib[]=	"rest/".$zv_file;
	}
  }
  sort($listalib);
  foreach($listalib as $libno){



	  include_once($libno);
  }
}  



/*


if ($extradir2 = @dir($path_base."pack/")) {
  while ($zv_fileP = $extradir2->read()) {
  if($zv_fileP=="." || $zv_fileP=="..") continue; 

		if ($extradir = @dir($path_base."pack/".$zv_fileP."/rest/")) {
		  $listalib=Array();
		  while ($zv_file = $extradir->read()) {
			if (preg_match('/^auto_.*\.php$/', $zv_file) > 0) {
				$listalib[]=	$path_base."pack/".$zv_fileP."/rest/".$zv_file;
			}
		  }
		  sort($listalib);
		  foreach($listalib as $libno)
			  include_once($libno);
		}  

  }
}


*/


/************************************************************************************************************************
API - END
*************************************************************************************************************************/
$app->run();