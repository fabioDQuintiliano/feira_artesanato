<?php
/*
require("classes/pagarme/Pagarme.php");
class Pagamento{
    private $producao = false;
    private $key_producao = 'ak_live_h4QiHIHJhgM9YnGlLU1mVQ3u59allH';
    private $key_desenvolvimento = 'ak_test_ATh485QqgUMeaxJdMWcwCYGdXdVsk5';
    private $apikey;

    public function __construct(){
        
        if($this->producao == true){
            $this->apikey = $this->key_producao;
        }else{
            $this->apikey = $this->key_desenvolvimento;
        }
    }

    public function assinarPlano($pessoa,$card_hash){
        $pes = DB_Class::make("system_admin")->_id($pessoa)->_loadAll();
        $plano = DB_Class::make("planos")->_loadAll("id DESC LIMIT 1","ativo = 1");

        Pagarme::setApiKey($this->apikey);
        $plan = PagarMe_Plan::findById($plano->id_pagarme);

        
        //0 - aguardando pagamento
       // 1 - ativo
       // 2 - recusado
       // 3 - nao pago
        //4 - finalizado
       // 5 - cancelado

        

        $pagamento = DB_Class::make("pagamento_plano")->_status(1)->_pessoa($pes->id)->_loadAll();

        if($pagamento->size()==0){


            $salva = DB_Class::make("pagamento_plano");
            $salva->pessoa = $pes->id;
            $salva->created_on = date("Y-m-d H:i:s");
            $salva->status = 0;
            $idSalva = $salva->save();

            $subscription = new PagarMe_Subscription(array(
                "plan" => $plan,
                "postback_url" => ROOT."retorno_pagarme",
                "card_hash" => $card_hash,
                'customer' => array(
                    'email' => $pes->login,
                    'name' => substr($pes->nome,0,30)
                    ),
                "metadata" => array(
                    'pessoa' => $pes->id,
                    'pagamento_plano' => $idSalva
                    )
                ));

            $subscription->create();

            $status = $subscription->status;
            $id_pagarme = $subscription->id;
            $validade = $subscription->current_period_end;


            $atualiza = DB_Class::make("pagamento_plano")->_id($idSalva)->_loadAll();

            
            if($status == 'paid'){
                
                $atualiza->status = 1;
                $atualiza->id_pagarme = $id_pagarme;
                $atualiza->validade = $validade;
                $sucesso = 'pagamento_realizado';
            }else{
                $atualiza->status = 2;
                $sucesso = 'erro_no_pagamento';
            }

            $atualiza->update();
            return $sucesso;
        }else{
            return 'assinatura_existente';
        }
    }

    public function getAssinatura($id){
        Pagarme::setApiKey($this->apikey);
        $subscription = PagarMe_Subscription::findById($id);

        if($subscription)
            return $subscription;
    }

    public function statusAssinatura($status){
        if($status == 'paid'){
            return 1;
        }else if($status == 'pending_payment'){
            return 0;
        }else if($status == 'canceled'){
            return 5;
        }else if($status == 'unpaid'){
            return 3;
        }else if($status == 'ended'){
            return 4;
        }
    }
    public function postBackAssinatura($post){
        if($post['current_status'] != ''){
            $dados = self::getAssinatura($post['id']);
            echo '<pre>';
            print_r($dados['current_transaction']['status']);

            if($dados['object'] == 'subscription'){
                $idPagamento = $dados['current_transaction']['metadata']['pagamento_plano'];

                $pag_plano = DB_Class::make("pagamento_plano")->_id($idPagamento)->_loadAll();
                if($pag_plano->size()){
                    
                    $pag_plano->status = self::statusAssinatura($dados['current_transaction']['status']);
                    $pag_plano->update();

                }


            }
        }
    }
}*/