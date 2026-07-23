<!--[CONTAINER-padrao-simples]-->
<?php

file_put_contents('arquivos/a'.time().'.txt', print_r($_REQUEST,true))
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
