<?php
function getInfoGeral(){
    $lista = array();
    $dao = DAO::Informacao()->_loadAll();
    if($dao->size()){
        do{

            $lista[] = array(

                'duvida' => $dao->pergunta,
                'resposta_esperada' => $dao->resposta,
                'tom_esperado' => $dao->tom,
                'tags'=>$dao->tags,
                'instituicao_pertinente'=>$dao->instituicao_pertinente,
                'instituicao_pertinente_em_sao_paulo'=>$dao->instituicao_pertinente_sp,
            );


        }while($dao->next());
    }

    return $lista;
}

function buscaDadosRede($obj=null){
$info = "
GUIA DE SERVIÇOS DA REDE DE ENFRENTAMENTO À VIOLÊNCIA CONTRA AS MULHERES DA PREFEITURA DE SÃO PAULO 1

SECRETARIA MUNICIPAL DE DIREITOS HUMANOS E CIDADANIA (SMDHC) 2

A Secretaria Municipal de Direitos Humanos e Cidadania (SMDHC) visa aprimorar a articulação e a gestão transversal das políticas de direitos humanos e estimular a participação social3. A Coordenação de Políticas Para Mulheres da secretaria assessora, coordena e articula a formulação, proposição, acompanhamento, coordenação e implementação de ações governamentais para promover a igualdade entre mulheres e homens, a cidadania e a participação política das mulheres, e auxiliar no combate à violência contra as mulheres4. A rede de enfrentamento à violência contra a mulher da secretaria é composta por 3 postos avançados de apoio à mulher no metrô Santa Cecília, Estação da Luz e Terminal de Ônibus Sacomã, uma unidade móvel de atendimento e 12 equipamentos, incluindo os Centros de Cidadania da Mulher (CCMs) e os Centros de Referência da Mulher (CRMs)5. A rede também inclui os Centros de Defesa e de Convivência da Mulher, geridos pela Secretaria Municipal de Assistência e Desenvolvimento Social, as Delegacias de Defesa da Mulher, o Núcleo Especializado de Promoção e Defesa dos Direitos da Mulher da Defensoria Pública do Estado de São Paulo e as Promotorias de Justiça de Enfrentamento à Violência Doméstica (GEVID) do Ministério Público do Estado de São Paulo6.

LEI MARIA DA PENHA 7

A Lei nº 11.340, conhecida como Lei Maria da Penha, é considerada a terceira melhor lei do mundo em proteção, cuidado e acolhimento às mulheres pela Organização das Nações Unidas8. A lei obriga o poder público e a sociedade a protegerem as mulheres da violência doméstica e familiar9. Ela pune quem comete violências física, psicológica, moral, sexual e patrimonial10. A lei também prevê medidas protetivas de urgência, que exigem que o agressor se afaste da vítima, seus familiares e testemunhas11. Mulheres transexuais e travestis também são protegidas pela Lei Maria da Penha12.

TIPOS DE VIOLÊNCIAS PREVISTAS NA LEI MARIA DA PENHA 13

●   MORAL: calúnia (acusar falsamente alguém de crime) 14, injúria (ofensa à dignidade) 15, difamação (ofender a reputação) 16e chantagens17.

●   PATRIMONIAL: controlar o dinheiro 18, não deixar a vítima escolher o que comprar 19, destruir objetos 20, não deixar trabalhar 21e ocultar bens e propriedades22.

●   PSICOLÓGICA: humilhar 23, insultar 24, intimidar 25, ameaçar 26e perseguir27.

●   SEXUAL: pressionar 28, exigir práticas que a vítima não gosta 29, negar-se a usar preservativo 30e negar o direito a métodos contraceptivos31.

●   FÍSICA: empurrar 32, chutar 33, amarrar 34e bater35.

LEI DE IMPORTUNAÇÃO SEXUAL 36

A Lei nº 13.718, de 24 de setembro de 2018, conhecida como Lei de Importunação Sexual, altera o Código Penal Brasileiro para tipificar os crimes de importunação sexual e de divulgação de cena de estupro37. A lei também altera a concepção penal de crimes contra a liberdade sexual e crimes sexuais contra vulnerável, e estabelece causas de aumento de pena para esses crimes, bem como para os crimes de estupro coletivo e estupro corretivo38.

PARA QUEM SOFRE VIOLÊNCIA 39

●   A culpa não é sua40!

●   Procure sua rede de apoio, como amigos e familiares41.

●   Ligue 156 ou 180 para obter informações42.

●   Procure ajuda especializada nos equipamentos da Rede Municipal43.

●   Denuncie quando se sentir preparada, vá até a delegacia mais próxima, se possível acompanhada44.

PARA QUEM CONVIVE COM MULHERES QUE SOFREM VIOLÊNCIAS 45

●   Esteja presente e escute sem julgar46.

●   Conheça os sinais e o ciclo da violência para ajudar melhor47.

●   Mostre que ela não está sozinha e ofereça apoio48.

●   A decisão de denunciar é da mulher. Se ela quiser, ofereça-se para ir junto49.

CONHEÇA A REDE DE ENFRENTAMENTO 50

CENTRO DE REFERÊNCIA DA MULHER (CRMs) 51

Oferecem orientação e atendimento social, psicológico e jurídico para mulheres em situação de violência doméstica52. Realizam encaminhamentos, como para abrigos sigilosos em casos de risco de morte53. O atendimento é de segunda a sexta, das 8h às 18h54.

●   Casa Eliane de Grammont: Rua Dr. Bacelar, 20, Vila Clementino. (11) 5549-933955.

●   CRM 25 de março: Rua Líbero Badaró, 137, 4º andar, Centro. (11) 3106-110056.

●   CRM Casa Brasilândia: Rua Sílvio Bueno Peruche, 538, Brasilândia. (11) 3983-429457.

●   CRM Maria de Lourdes Rodrigues: Rua Dr. Luiz Fonseca Galvão, 145, Capão Redondo. (11) 5524-478258.

CENTRO DE REFERÊNCIA DE ATENDIMENTO INTEGRAL ÀS MULHERES EM SITUAÇÃO DE VIOLÊNCIA COM ATENDIMENTO 24H 59

●   Casa da Mulher Brasileira: Rua Vieira Ravasco, 26, Cambuci. (11) 3275-800060.

CENTROS DE CIDADANIA DA MULHER (CCMs) 61

São espaços para qualificação e cidadania ativa, defesa de direitos sociais, econômicos e culturais62. Realizam atendimento inicial para mulheres em situação de violência doméstica e de gênero63. O funcionamento é de segunda a sexta, das 8h às 17h64.

●   CCM Parelheiros: Rua Terezinha do Prado Oliveira, 119, Parelheiros. (11) 5921-366565.

●   CCM Capela do Socorro: Rua Professor Oscar Barreto Filho, 350, Grajaú. (11) 5927-310266.

●   CCM Itaquera: Rua Ibiajara, 495, Itaquera. (11) 2073-486367.

●   CCM Perus: Rua Aurora Boreal, 43, Vila Perus. (11) 3917-595568.

●   CCM Santo Amaro: Praça Salim Farah Maluf, s/n. (11) 5521-662669.

SERVIÇOS DE ACOLHIMENTO 70

●   Casa de Acolhimento Provisório de Curta Duração (Rosângela Rigo): Acolhimento provisório emergencial para mulheres e seus filhos em risco por violência doméstica ou vítimas de tráfico sob grave risco de ameaça e/ou morte71.

●   Casa Abrigo Sigiloso: Acolhimento temporário, em endereço sigiloso, que oferece proteção e atendimento integral a mulheres em situação de violência doméstica72.

O encaminhamento para os serviços de acolhimento é feito por meio dos outros serviços da rede de enfrentamento à violência contra a mulher do município de São Paulo73.

POSTO AVANÇADO DE APOIO À MULHER 74

É um espaço onde mulheres vítimas de violência podem buscar orientações sobre a rede de enfrentamento disponível na cidade75. Os postos avançados oferecem atendimento e possíveis encaminhamentos76. Funcionam de segunda a sexta, das 8h às 17h77.

●   Estação Santa Cecília (Linha 3-Vermelha)78.

●   Estação da Luz (Linha 1-Azul)79.

●   Terminal de Ônibus Sacomã - Zona Sul80.

UNIDADE MÓVEL DE ATENDIMENTO ÀS MULHERES 81

Oferece serviço de atendimento descentralizado e itinerante da rede de enfrentamento à violência contra a Mulher da Cidade82.

CENTRO DE DEFESA E DE CONVIVÊNCIA DA MULHER (CDCMS) 83

●   CDCM Mariás: Rua José Antônio Moreira, 546, Sobreloja - Pq. Novo Mundo. (11) 3294-006684.

●   CDCM Espaço Francisca Franco: Rua Conselheiro Ramalho, 93, Liberdade. (11) 3106-101385.

●   CDCM Mulheres Vivas: Rua Marinho Vaz de Barros, 257, Campo Limpo. (11) 5842-646286.

●   CDCM Helena Vitória Fernandes: Rua Cel. Carlos Dourado, 07, Vila Marilena - Guaianases. (11) 2016-904187.

●   CDCM Casa Viviane dos Santos: Rua Planície dos Goitacases, 456, Guaianases. (11) 2553-242488.

●   CDCM Casa Sofia: Rua Dr. Luiz Fernando Ferreira, 06, M'Boi Mirim. (11) 5891-3483 ou 5891-363289.

●   CDCM Casa Cidinha Kopcak: Rua Margarida Cardoso dos Santos, 500, São Mateus. (11) 2282-470690.

●   CDCM Casa Anastácia: Rua Areia das Ampulhetas, 101, Castro Alves - Cidade Tiradentes. (11) 2282-470691.

●   CDCM Margarida Maria Alves: Rua Sabbado d'Ângelo, 2085, 2º andar - Itaquera. (11) 2524-732492.

●   CDCM Casa Márcia Martins: Rua Ministro Laudo Ferreira de Camargo, 320, Jardim Peri Peri. (11) 3507-585693.

●   CDCM Sônia Maria Batista: Rua Ribeiro do Amaral, 136, Ipiranga. (11) 2272-042394.

●   CDCM Casa Zizi: Rua Teotônio de Oliveira, 101, Vila Ema. (11) 2216-734695.



";


return $info;

}
function buscaDadosRede_Ribeirao($obj=null){
	
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

return $info;
}

?>