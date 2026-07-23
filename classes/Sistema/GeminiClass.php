<?php 
namespace Sistema;
use \DAO;
use GeminiAPI\Gemini;
use GeminiAPI\Resources\Parts\TextPart;
class GeminiClass {
	
	
	public static function start($code){


		$gemini = new Gemini('GEMINI_API_KEY');

		return true;
		
	}
	


	

}