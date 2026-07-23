<?
namespace Backend\v1;
use \DAO, \Mailjet\Resources;

class PhpLogin extends \Backend\v0\PhpLogin{ 

	function _enviaEmailDeSenha($pes,$resetcod){
		//SE A CONTA FOR COM PROVIDER INFORMAR O USUARIO E NAO ENVIAR EMAIL	


		$dao = DAO::recuperar_senha()->_pessoa($pes->id)->_loadAll();
		if($dao->size()){
			do{
				$dao->delete();
			}while($dao->next());
		}
		$s = DAO::recuperar_senha();
		$s->pessoa =$pes->id;
		$s->validade = addMinuto(date("Y-m-d H:i:s"),15);
		$s->codigo = $resetcod;
		$s->save();

		$link = ROOT.'recoverypass/'.$resetcod;

		$mensagem = "Você solicitou a recuperação de sua senha. Acesse o link a seguir e cadastre uma nova senha. <br />Caso não tenha realizado essa solicitação, por favor, ignore este e-mail. <br /> <br /><a style=\"color:#3498db;\" href='".$link."'>Clique aqui para recuperar sua senha.</a>";

		$html = file_get_contents('email/recuperar_senha.htm');
		$html = str_replace('[basedir]', ROOT, $html);
		$html = str_replace('[titulo]', 'Recuperação de senha', $html);
		$html = str_replace('[mensagem]', $mensagem, $html);



		$email = $pes->login;
		$nome = $pes->nome;
		$mj = new \Mailjet\Client(MAILJET_API_KEY, MAILJET_SECREDT,true,['version' => 'v3.1']);
		$mj->setConnectionTimeout(0);
		$body = [
		    'Messages' => [
		        [
		            'From' => [
		                'Email' => "noreply@trico.app",
		                'Name' => "Suporte Tricô"
		            ],
		            'To' => [
		                [
		                    'Email' => $email,
		                    'Name' => $nome
		                ]
		            ],
		            'Subject' => "Recuperação de senha",
		            'TextPart' => "Você solicitou a recuperação de seu senha.",
		            'HTMLPart' => $html
		        ]
		    ]
		];
		$response = $mj->post(Resources::$Email, ['body' => $body]);
		//$response->success() && var_dump($response->getData());

		return 'sent ';
	}

}