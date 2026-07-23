<?php
class Push{
    //var $API_KEY = "";
    static function send($msg,$registrationIds){
       
        $fields = array
        (
            'registration_ids'  => $registrationIds,
            'data'              => $msg
        );
        
        $headers = array
        (
            'Authorization: key=' . "AIzaSyAGfp6VgAkCqOr040hGTXi-MULkmBJ_3hQ",
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );

        return $result;

    }
}

function execFila(){
    $aux = DB_Class::make('push_fila')->_enviado(0)->_loadAll("id ASC LIMIT 500");
    if($aux->size()){
        do{

            enviaPush($au->pessoa,$aux->mensagem,$aux->extra);

            $aux->enviado = 1;
            $aux->enviado_em = date("Y-m-d H:i:s");
            $aux->update();

        }while($aux->next());
    }

}
function addFila($para,$mensagem,$extra='',$de='',$device=''){
    $aux = DB_Class::make('push_fila');
    $aux->pessoa = $para;
    $aux->mensagem = $mensagem;
    $aux->extra = $extra;
    $aux->enviado = 0;
    $aux->created_on = date("Y-m-d H:i:s");
    $aux->save();
}
function enviaPush($para,$mensagem,$extra='',$de=0,$device=''){
    if(!is_array($para)){
        $para = array($para);
    }

    if(count($para)>0){

        $aux = DB_Class::make("push_reg")->_plataforma(1)->_loadAll("","userid IN(".implode(',',$para).")");
        $registrationIds = array();
        if($aux->size() > 0){
            do{

                $registrationIds[] = $aux->regid;

                $salva = DB_Class::make("push_msg");
                $salva->para = $aux->userid;
                $salva->mensagem = $mensagem;
                $salva->lido = 0;
                $salva->extra = $extra;
                $salva->de = $de;
                $salva->created_on = date("Y-m-d H:i:s");

                if($extra != ''){
                    $ex = explode('__', $extra);
                    if($ex[0] == 'caso'){
                        $salva->caso = $ex[1]*1;
                    }
                }

                $reg = $salva->save();

            }while($aux->next());

            $mensagemCompleta = true;
            if(strlen($mensagem) > 200){
                $mensagemCompleta = false;
                $mensagem = substr($mensagem, 0,197)."...";
            }

            $msg = array(
                'message'       => $mensagem,
                'msgcnt'         =>"1",
                'extra'         => array(
                        'extra'=>$extra,
                        'de'   =>$de,
                        'id'   =>$reg,
                        'full' =>$mensagemCompleta,
                        'para' =>$para,
                        'data' =>strtotime(date("Y-m-d H:i:s"))*1000
                    ),
                'notId'         => 1,
                'title'         => 'iJuris',
                //'subtitle'      => 'This is a subtitle. subtitle',
                //'tickerText'    => 'Ticker text here...Ticker text here...Ticker text here',
                //'vibrate'   => 1,
                //'sound'     => 1,
                "color"     => '#33b5e7',
                "style"     => "inbox",
                "summaryText"=> "Você tem 1 nova notificação"
            );
            return $info = Push::send($msg,$registrationIds);
           // var_dump($info);
        }
    }
}

?>