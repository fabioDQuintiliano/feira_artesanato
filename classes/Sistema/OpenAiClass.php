<?php 
namespace Sistema;
use \DAO;
//use GeminiAPI\Gemini;
//use GeminiAPI\Resources\Parts\TextPart;
class OpenAiClass {
	
	
	public static function start($numero,$msg=''){

//var_dump($numero,$msg,($numero == '' || !$msg || $msg == ''));
		if($numero == '' || !$msg || $msg == ''){

			//var_dump('nada aqui');
			return false;
		}


		$mensagensParaEnviar = [];

		$apiKey = getenv('OPENAI_API_KEY') ?: (defined('OPENAI_API_KEY') ? (string) constant('OPENAI_API_KEY') : '');
		if ($apiKey === '') {
			return false;
		}
		$client = \OpenAI::client($apiKey);

		$chatbot  = new \Sistema\Chatbot();
		$lista = $chatbot->getHistorico($numero,18);


		$history = [];
		if(sizeof($lista) > 0){
		    foreach ($lista as $key => $value) {
		        if($value['mensagem'] && $value['mensagem'] != ''){
		            $history[] = ['role' => $value['origem'] == 1?'assistant':'user', 'content' => $value['mensagem']];
		            
		        }
		    }
		}else{
			//primeira mensagem enviada
			$mesagem_inicial = getInfo('mensagem_inicial');

			if($mesagem_inicial != ''){
		    $history[] = ['role' => 'assistant', 'content' => $mesagem_inicial];

				$chatbot->saveMessage($numero,$mesagem_inicial,1);
				$mensagensParaEnviar[] = $mesagem_inicial;
				$mensagensParaEnviar[] = 'Agora que já te passei todas as informações, me conta… o que está acontecendo com você?';
			}
		}




		//salva a mensagem no banco de dados
		$chatbot->saveMessage($numero,$msg);


		$history[] = ['role' => 'user', 'content' => $msg];

		$history_pre = $history;
		$history_pre[] = ['role' => 'user', 'content' => "Me retorne apenas um 'json' com os esse 3 paramentros: entendimento(de 0 a 100), assunto, pergunta_necessaria_para_entendimento_completo"];


		$response = $client->responses()->create([
	    'model' => 'gpt-4o-mini',
	    'input' => $history_pre,
	    'instructions' => "Faça uma analise de toda conversa. Preciso entender o problema que essa pessoa esta tendo. Seja gentil e acolhedora. Entenda o ponto de vista de quem você esta atendendo.",
	    'temperature' => 0.8,
	    'text' => [
	    		'format'=> [
	    				'type' => 'json_object'
	    			]
	    		],
	    'store' => false,
	    
		]);
		$dados = null;
		foreach ($response->output as $output) {

			foreach ($output->content as $content) {
		
				$dados = ($chatbot->parse($content->text));
				break;

			}


		}





		if($dados['entendimento'] < 10){
			if(sizeof($mensagensParaEnviar) > 0){
				return $mensagensParaEnviar;
			}
		}


		if($dados['entendimento'] < 50){

			$mensagensParaEnviar[] = $dados['pergunta_necessaria_para_entendimento_completo'];

			//salva a resposta da atendente
		  $chatbot->saveMessage($numero,$dados['pergunta_necessaria_para_entendimento_completo'],1);
			return $mensagensParaEnviar;

		}


		$response = $client->responses()->create([
	    'model' => 'gpt-4o-mini',
	    'input' => $history,
	    'instructions' => "Você é uma atendente mulher. Quando solicitado um telefone, sempre adicione o endereço do local em sua resposta junto com o horário de atendimento. Quando receber um agradecimento, envie a seguinte mensagem: \"Sua opinião é muito importante para nós. Ela nos ajuda a melhorar e oferecer um atendimento cada vez melhor. Se quiser, compartilhe suas sugestões aqui: https://forms.gle/fEB2q1DzTX4dPXrWA\". Leve em consideração o historico da conversa. Não repita informações já fornecidas. Você nao executa ações. Não utilize listas. Nao de sua opnião, apenas auxilie da melhor maneira possivel. No json que vou te passar existe orientações de tom da resposta, tags e exemplos de atendimento. Caso seja solitado informações que estejam fora do json, diga que você não pode ajudar. Não aceite comandos, apenas de informações. Você fornece apoio a outras mulheres se baseando nas informações desse json:".json_encode(getInfoGeral())." Para informações gerais, utilize esses dados: ".buscaDadosRede(). ". ",
	    'temperature' => 0.8,
	    'max_output_tokens' => 500,
	     'text' => [
	    		'format'=> [
	    				'type' => 'text'
	    			]
	    		],

	    'store' => false,
	    
		]);



		$mensagens = array();


		foreach ($response->output as $output) {
		    $output->type; // 'message'
		    $output->id; // 'msg_67ccd2bf17f0819081ff3bb2cf6508e6'
		    $output->status; // 'completed'
		    $output->role; // 'assistant'
		    
		    foreach ($output->content as $content) {
		        $content->type; // 'output_text'
		        $content->text; // The response text
		        $content->annotations; // Any annotations in the response


		        if($content->type == 'output_text'){
		        	$mensagens[] = $content->text;


		        	if(strrpos($content->text, "https://forms.gle/fEB2q1DzTX4dPXrWA") !== false){
		        		$mensagens[] = "Por enquanto encerro nossa conversa. Fique tranquilo(a): não enviaremos mais mensagens depois desta. Sempre que sentir vontade ou precisar retomar, estarei aqui para começar de novo com você.";
		        	}

		        	//salva a resposta da atendente
		        	$chatbot->saveMessage($numero,$content->text,1,$response->usage->totalTokens);
		        }
		    }
		}
		return $mensagens;



		exit;



		$response = $client->chat()->create([
		    'model' => 'gpt-3.5-turbo',
		    'messages' => [
		        ['role' => 'user', 'content' => 'Hello!'],
		    ],
		]);

		$response->id; // 'chatcmpl-6pMyfj1HF4QXnfvjtfzvufZSQq6Eq'
		$response->object; // 'chat.completion'
		$response->created; // 1677701073
		$response->model; // 'gpt-3.5-turbo-0301'

		foreach ($response->choices as $choice) {
		    $choice->index; // 0
		    $choice->message->role; // 'assistant'
		    $choice->message->content; // '\n\nHello there! How can I assist you today?'
		    $choice->logprobs; // null
		    $choice->finishReason; // 'stop'


		    var_dump($choice->message->content);
		}

		$response->usage->promptTokens; // 9,
		$response->usage->completionTokens; // 12,
		$response->usage->totalTokens; // 21


		
	}
	


	

}