<?php

file_put_contents('arquivos/post'.time().'.txt', print_r($_POST,true));

if($_GET['hub_verify_token'] == 'costelinha123'){
    echo $_GET['hub_challenge'];
    exit;
}
use GeminiAPI\Client;
use GeminiAPI\Resources\ModelName;
use GeminiAPI\Resources\Parts\TextPart;
use GeminiAPI\Resources\Content;
use GeminiAPI\Enums\Role;

file_put_contents('arquivos/entry'.time().'.txt', print_r($_POST['entry'],true));

$mensagem = $_POST['entry'][0]['changes'][0]['value']['messages'][0];



$chatbot  = new \Sistema\Chatbot();
$whatsapp = new \Sistema\Whatsapp();
$text = $mensagem['text']['body'];
$para = $mensagem['from'];
$id_whats = $mensagem['id'];


$rea = $whatsapp->setRead($id_whats);



$postData = Array();

$lista = $chatbot->getHistorico($para);
$chatbot->saveMessage($para,$text);

$history = array();


    $info = "
    Canais oficiais:
Para interromper a agressão - Ligar para  190
Acionar em casos de violência física e violência sexual contra mulheres e meninas.
Polícia Militar
É o número de telefone da Polícia Militar que deve ser acionado em casos de necessidade imediata ou socorro rápido. 
Atendimento 24 horas
a chamada não tem custo
O 190 é o canal para casos de emergência em violência doméstica, com a polícia intervindo no local. 
O 190 pode encaminhar a vítima para uma delegacia para registro do boletim de ocorrência (BO)
O boletim de ocorrência (BO) deve ser registrado na delegacia para documentar o crime e iniciar a investigação. 
É possível fazer a ligação de qualquer lugar do Brasil 

Para fazer uma denúncia de violência contra mulheres e meninas - Ligar para 181
Acionar para denunciar qualquer tipo de violência contra mulheres e meninas, incluindo física, psicológica, moral, patrimonial, sexual ou digital.
Disque Denúncia
Atendimento 24 horas
chamada não tem custo
Para ligar para o 181, o Disque Denúncia, basta digitar o número 181 no seu telefone. O 181 é um canal de comunicação anônimo e gratuito, onde você pode denunciar de violência contra mulheres e meninas 
Por meio desse serviço, você tem acesso às informações sobre como fazer denúncias sem se identificar. As informações são encaminhadas para diferentes órgãos da Segurança Pública
Quem pode acionar: Qualquer pessoa.
Onde acionar: Pela internet e por telefone.
Pela internet o site é: https://www.webdenuncia.sp.gov.br/cidadao/denuncie
Como acionar: Tenha em mãos o máximo de informações, como local, características das pessoas e veículos envolvidos, se a situação se repete e outros dados que possam ajudar a polícia.
Por telefone ou pelo site o sigilo das informações é preservado.
Quando NÃO acionar o 181:
para emergências;
para pedir informações jurídicas ou endereços e telefone de outros órgãos;
para tratar de desacordos comerciais;
para desabafar sobre algum assunto ou situação;
para passar informações ou situações falsas.
Prazo: O registro da denúncia é imediato.
É possível fazer a ligação de qualquer lugar do Brasil 


Para fazer denúncia, reclamação, orientação, encaminhamentos - Ligar para 180
Acionar em casos de qualquer tipo de violência, incluindo física, psicológica, moral, patrimonial, sexual ou digital.
Central de Atendimento à Mulher
Atendimento 24 horas
A Central de Atendimento à Mulher – Ligue 180 é um serviço de utilidade pública essencial para o enfrentamento à violência contra as mulheres. 
A ligação é gratuita e o serviço funciona 24 horas por dia, todos os dias da semana. 
O Ligue 180 presta os seguintes atendimentos:
orientação sobre leis, direitos das mulheres e serviços da rede de atendimento (Casa da Mulher Brasileira, Centros de Referências, Delegacias de Atendimento à Mulher (Deam), Defensorias Públicas, Núcleos Integrados de Atendimento às Mulheres, entre outros.;
informações sobre a localidade dos serviços especializados da rede de atendimento;
registro e encaminhamento de denúncias aos órgãos competentes;
registro de reclamações e elogios sobre os atendimentos prestados pelos serviços da rede de atendimento.
É possível fazer a ligação de qualquer lugar do Brasil 

Para chamar ajuda policial no local a fim de  interromper a agressão - Ligar para 153 / 199
Realizar a ligação em caso de violência física e violência sexual.
Patrulha Maria da Penha
Atendimento 24h
Grupo policial especializado em atender violência contra a mulher na cidade de Ribeirão Preto
Patrulha Maria da Penha oferece apoio a mulheres contra violência - Guarda Civil Metropolitana trabalha em ações de conscientização e combate à violência contra a mulher
A Patrulha Maria da Penha atende especificamente a Ribeirão Preto (SP), não à região.
Este programa, implementado pela Guarda Civil Metropolitana (GCM) em parceria com outras entidades, tem como objetivo garantir a proteção das vítimas de violência doméstica e familiar, assegurando o cumprimento de medidas protetivas e promovendo ações de conscientização e combate à violência
Os agentes da GCM que atuam na Patrulha Maria da Penha são treinados para a proteção, prevenção e acompanhamento das mulheres vítimas de violência doméstica ou familiar que possuam medidas protetivas de urgência, integrando as ações realizadas pelas redes de atendimento às mulheres em situação de violência mantidas pelo Poder Público.

Delegacia Online
https://www.delegaciaeletronica.policiacivil.sp.gov.br/ssp-de-cidadao/pages/comunicar-ocorrencia
Clicar em: Violência Doméstica contra mulher
Preencher o formulário


Rede local:
Delegacia de Polícia de Defesa da Mulher da Polícia Civil de SP (DDM)
Ir ao local para atendimento de casos de qualquer tipo de violência, incluindo física, psicológica, moral, patrimonial, sexual ou digital.
Procurar para:
Registrar BO
Requisitar medidas protetivas
Atendimento: 24 horas
Endereço: Av. Costábile Romano, 3230 - Nova Ribeirânia
Telefone: (16) 3610-4499 

1° Distrito Policial de Ribeirão Preto da Polícia Civil de SP (DP)
Ir ao local para atendimento de casos de qualquer tipo de violência, incluindo física, psicológica, moral, patrimonial, sexual ou digital
Procurar para:
Registrar BO
Requisitar medidas protetivas
Atendimento: 24 horas 
Endereço: Av. Duque de Caxias, 1048 - Centro
Telefone: (16) 3610-3383 / (16) 3610-3484 


Anexo da Violência Doméstica do Fórum de Ribeirão Preto Palácio da Justiça
Ir ao local para atendimento de casos de qualquer tipo de violência, incluindo física, psicológica, moral, patrimonial, sexual ou digital
Procurar para:
Requisitar medidas protetivas
Obs.: Não precisa fazer BO para atendimento
Atendimento: de segunda à sexta, das 12h30 às 19h 
Endereço: No Fórum - Rua Alice Além Saadi, 1010 - Nova Ribeirânia
Telefone: (16) 3626-004 

Ministério Público 
Procurar para:
Buscar e garantir seus direitos
Atendimento: de segunda à sexta, das 9 às 17h 
Endereço: Rua Otto Benz, 1070 - Nova Ribeirânia
Telefone: (16) 3456-3800 (a chamada tem custo?)



REDE PÚBLICA DE ATENDIMENTO


NAEM - Núcleo de Atendimento Especializado à Mulher
Procurar para:
Contato telefônico para agendamento individual para orientações acerca dos serviços realizados pelo equipamento, escuta do relato, orientação jurídica, Acompanhamento psicológico
Atendimento: de segunda à sexta, das 8h às 17h 
Endereço: João Arcadepani Filho, 400 - Nova Ribeirânia
Telefone: (16) 3636-3311 e (16) 3603-1199 (a chamada tem custo?)

SERAVIG - Serviço de Reeducação do Autor de Violência de Gênero
Procurar para:
atendimento exclusivo ao autor da violência
* Obs.: Necessita ser encaminhado pelo judiciário?
Atendimento: de segunda à sexta, das 8h às 17h 
Endereço: João Arcadepani Filho, 400 - Nova Ribeirânia (junto ao NAEM)
Telefone: (16) 3636-3311 e (16) 3603-1199 (a chamada tem custo?)

SEAVIDAS - Serviço de Atenção à Violência Doméstica e Agressão Sexual do HC de RP
Acionar em casos de violência física e violência sexual contra mulheres e meninas.
Procurar para:
Atendimento psicológico às vítimas de violência física grave com histórico de violência sexual que deram entrada em UBDS, UPA ou HC.
* Obs.: Necessita ser encaminhado pela rede pública.
Atendimento: de segunda à sexta, das 7h30 às 17h 
Endereço: Rua Sete de Setembro, 1050 - Centro
Telefone: : (16) 3605- 3736 (a chamada tem custo?)

UPA -Unidade de Pronto Atendimento
UBDS - Unidade Básica Distrital de Saúde
Acionar em casos de violência física e violência sexual contra mulheres e meninas.
Procurar para:
Atendimento médico em caso de agressão física
Atendimento: de segunda à sexta, das 7h às 17h ou 19h (conforme unidade)
Endereço: Colocar QR com site para encontrar UPA ou UBDS mais próxima
Telefone: idem

CRAS - Centro de Referência de Assistência Social
Procurar para:
Inserção nos programas assistenciais do governo como: bolsa família, cestas básicas, leite, entre outras.
Atendimento: de segunda à sexta, das 8h às 12h / 13h às 17h (conforme unidade)
Endereço: Colocar QR com site para encontrar CRAS mais próximo
Telefone: idem



CONSELHO TUTELAR
Atendimento de crianças e adolescentes que tiveram seus direitos violados
Atendimento: de segunda à sexta, das 8h às 12h / 13h às 17h (conforme unidade)
Conselho Tutelar I
    Endereço: Rua Mariana Junqueira, 1.019 - Centro
    Telefones: (16) 3635-9449 / 3635-9647 e Whatsapp: (16) 3610-0687
Conselho Tutelar II
Endereço: Rua Goiás, 1064 - Campos Elíseos
Telefones: (16) 3963-2211 / (16) 3963-2244 e Whatsapp: (16) 3610-0687
Conselho Tutelar III
    Endereço: Avenida Primeiro de Maio, 140 - Vila Virgínia
Telefones: (16) 3919-0090 / 3637-0811 e Whatsapp: (16) 3610-0687
Plantão: 0800-7730161 ou 161 (noturno, finais de semana e feriados)
";


//file_put_contents('arquivos/teste5'.time().'.txt', print_r($lista,true));



if(sizeof($lista) > 0){
    foreach ($lista as $key => $value) {
        if($value['mensagem'] && $value['mensagem'] != ''){
            $history[] = Content::text($value['mensagem'], ($value['origem'] == 1?Role::Model:Role::User));
        }
    }
}


//file_put_contents('arquivos/teste6'.time().'.txt', print_r($history,true));



//var_dump($history);




if(sizeof($lista) <= 0){
//file_put_contents('arquivos/teste4'.time().'.txt', print_r($rea,true));

/*$mensgem_resposta = "
Oi! Eu sou a Flor, assistente virtual do Banheiro Feminista. Esta é uma conversa segura e sigilosa para apoiar mulheres e meninas que estão passando por situações de violência. 

Você não está sozinha. Estou aqui para ouvir, oferecer orientações e, se precisar, ajudar com informações sobre como fazer uma denúncia.

Respeitamos a Lei Geral de Proteção de Dados (LGPD) e garantimos que as informações compartilhadas aqui são sigilosas.

As orientações fornecidas não substituem apoio jurídico, psicológico ou policial. Em casos de emergência, ligue para o 190 (Polícia) ou 180 (Central de Atendimento à Mulher).

Ao continuar, você concorda com nossos termos de uso e política de privacidade. Para mais detalhes, acesse https://banheirofeminista.com.br/termos.

Pode me contar o que está acontecendo? Estou aqui para ajudar.";*/
/*
$mensgem_resposta = "

Oi! Eu sou a *Flor*, assistente virtual do Banheiro Feminista. Esta é uma conversa segura e sigilosa. Estou aqui para oferecer apoio psicológico, orientar sobre relacionamentos, violência de gênero, saúde, direitos jurídicos, onde buscar ajuda em serviços públicos de proteção e, acima de tudo, para te escutar.

As informações trocadas aqui são protegidas pela Lei Geral de Proteção de Dados (LGPD) e tratadas com sigilo.

As orientações fornecidas não substituem apoio psicológico, médico ou jurídico especializado. *Em casos de emergência, ligue 190.*

Se for o seu caso, também posso te ajudar com informações sobre como fazer uma denúncia.

Ah! Lembre-se: o *consumo consciente de álcool* é uma das causas que apoiamos. E, se beber, não dirija!

Pode me contar o que está acontecendo? Estou aqui para ajudar.


";*/
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


//file_put_contents('arquivos/teste3'.time().'.txt', print_r($rea,true));

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
    
    //$ret = $chatbot->parse($resposta);



    //$mensgem_resposta = $ret['resposta'];
    $mensgem_resposta = $resposta;


    

}


//file_put_contents('arquivos/teste2'.time().'.txt', print_r($mensgem_resposta,true));

$rc = $chatbot->saveMessage($para,$mensgem_resposta,1);

//file_put_contents('arquivos/teste7_'.time().'.txt', print_r($rc,true));




$postData['messaging_product'] = "whatsapp";
$postData['to'] = $para;
$postData['recipient_type'] = "individual";
$postData['type'] = "text";
$postData['text'] = array("body"=>$mensgem_resposta);



$whatsapp->sendMessage($para,$mensgem_resposta);    

//file_put_contents('arquivos/c'.time().'.txt', print_r($_POST['entry'][0]['changes'][0]['value']['messages'][0],true));
echo 1;
exit;
?>