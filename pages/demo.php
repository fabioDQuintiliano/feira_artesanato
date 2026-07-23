<!--[CONTAINER-padrao-simples]-->
<?php

$extrator = new \Backend\v1\ExtratorEventosGemini();
$eventos = $extrator->extrair('https://www.viladionisio.com.br/ribeirao#agenda');

echo json_encode($eventos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);


/*
exit;
$para = '5516988758692';
$text = 'Oi. Tem um rapaz me olhando. Nao estou gostando. Onde posso pedir ajuda?';
$openAi = new \Sistema\OpenAiClass();



$respostas = $openAi->start($para,$text);
echo '<pre>';
print_r($respostas);
echo '</pre>';*/

/*
$respostas = $openAi->start($para,'ja tem um verto tempo');

echo '<pre>';
print_r($respostas);
echo '</pre>';
*/

/*
use GeminiAPI\Client;
use GeminiAPI\Resources\ModelName;
use GeminiAPI\Resources\Parts\TextPart;

$client = new Client((defined('GEMINI_API_KEY') ? GEMINI_API_KEY : '') ?: (getenv('GEMINI_API_KEY') ?: ''));
$response = $client->withV1BetaVersion()
    ->generativeModel(ModelName::GEMINI_1_5_FLASH)
    ->withSystemInstruction('Você é uma atendente mulher de um serviço que presta apoio para outras mulheres que sofreram algum tipo de abuso. Seja educada, use uma linguagem mais informa, ofereça respostas diretas, não ultrapassando 160 caracteres.')
    ->generateContent(
        new TextPart('Oi. Bom dia'),
    );

print $response->text();	*/
?>
