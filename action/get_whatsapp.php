<?php
if($_GET['hub_verify_token'] == 'costelinha123'){
    echo $_GET['hub_challenge'];
    exit;
}


$chatbot  = new \Sistema\Chatbot();
$whatsapp = new \Sistema\Whatsapp();
$openAi = new \Sistema\OpenAiClass();



$mensagem = $_POST['entry'][0]['changes'][0]['value']['messages'][0];
$text = $mensagem['text']['body'];
$para = $mensagem['from'];
$id_whats = $mensagem['id'];

if($_GET['teste']){
    $para = '5516988758692';
    $text = $_GET['text'];

}else{
    //marca mensagem como lido;
    $rea = $whatsapp->setRead($id_whats);
}

$respostas = $openAi->start($para,$text);

foreach ($respostas as $key => $resposta) {

    $whatsapp->sendMessage($para,$resposta); 

}
echo 1;
exit;
//---------------------------------------------
//---------------------------------------------
//---------------------------------------------
//---------------------------------------------


$lista = $chatbot->getHistorico($para);
$chatbot->saveMessage($para,$text);

$history = array();


$info = "";


if(sizeof($lista) > 0){
    foreach ($lista as $key => $value) {
        if($value['mensagem'] && $value['mensagem'] != ''){
            $history[] = Content::text($value['mensagem'], ($value['origem'] == 1?Role::Model:Role::User));
        }
    }
}



if(sizeof($lista) <= 0){

$mensgem_resposta = "
Oi! Eu sou a *Flor*, assistente virtual do Banheiro Feminista. Esta é uma conversa segura e sigilosa. Estou aqui para oferecer apoio psicológico, orientar sobre relacionamentos, violência de gênero, saúde, direitos jurídicos, onde buscar ajuda em serviços públicos de proteção e, acima de tudo, para te escutar.

_*Aviso: Esta simulação antecipa o funcionamento da assistente Flor. A primeira versão será lançada a partir de 22/07/2025._

As informações trocadas aqui são protegidas pela Lei Geral de Proteção de Dados (LGPD) e tratadas com sigilo.

As orientações fornecidas não substituem apoio psicológico, médico ou jurídico especializado. *Em casos de emergência, ligue 190.*

Se for o seu caso, também posso te ajudar com informações sobre como fazer uma denúncia.

Ah! Lembre-se: o *consumo responsável de álcool* é uma das causas que apoiamos. E, se beber, não dirija!

Pode me contar o que está acontecendo? Estou aqui para ajudar.

";
}else{

    $text = "Leia a seguinte mensagem e ofereça suporte com base no contexto da conversa. Seja sensivel e direta em suas respostas. Essa é a mensagem:".$text;





    $client = new Client((defined('GEMINI_API_KEY') ? GEMINI_API_KEY : '') ?: (getenv('GEMINI_API_KEY') ?: ''));
    $response = $client->withV1BetaVersion()
        ->generativeModel(ModelName::GEMINI_1_5_FLASH)
        ->withSystemInstruction('Seu nome é Flor. Você é uma psicóloca em um serviço de apoio contra abuso de mulheres e oferece orientação para pessoas que possuem dúvidas, podem ter sofrido algum abuso ou precisam de ajuda psicológica para entender o que podem estar passando. Antes de sugerir os canais oficiais, sugia a rede local de apoio. Não seja repetitiva em suas respostas. Use girias e formas informais de comunicação. Você atende a cidade de Ribeirão preto, todas as informações que vc tem são dessa cidade. Ofereça respostas extremamente curtas, se possível menos de 50 caracteres, sem detalhes extensos. Utilize essas informações sobre a rede de apoio de Ribeirão preto em suas respostas: '.$info)
        ->startChat()
        ->withHistory($history)
        ->sendMessage(new TextPart($text))
        ;

    $resposta = $response->text();
    
    $mensgem_resposta = $resposta;


    

}


$rc = $chatbot->saveMessage($para,$mensgem_resposta,1);



$postData['messaging_product'] = "whatsapp";
$postData['to'] = $para;
$postData['recipient_type'] = "individual";
$postData['type'] = "text";
$postData['text'] = array("body"=>$mensgem_resposta);



$whatsapp->sendMessage($para,$mensgem_resposta);    

echo 1;
exit;
?>