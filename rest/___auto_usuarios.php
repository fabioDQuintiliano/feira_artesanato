<?
/************************************************************************************************************************
API - LOGIN
*************************************************************************************************************************/



//MAPEAMENTO
$app->options('/rest/userauth',$semacao);
$app->options('/rest/userauth_fb',$semacao);
//$app->options('/rest/assinatura',$semacao);

//POST
$app->post(
    '/rest/userauth',
    function () { 
		$body      = app_body();
        //echo $body->email;
        $ERROR     = false;
        $ERROR_MSG = false;
        $INFO      = array();
        $MSG       = '';
        $MSG_TYPE  = 0;
        //--------------
        $user  = $body->email;
        $senha = $body->senha;
        if($user != '' && $senha != ''){
            $aux = DB_Class::make("system_admin")->_login($user)->_senha($senha)->_loadAll();

            if($aux->size()>0){
                $INFO = array(
                        'id'=>$aux->id,
                        'nome'=>$aux->nome,        
                        'perfil'=>$aux->perfil           
                    );
            }else{
                $ERROR = true;
                $ERROR_MSG = 'Usuário ou senha incorretos. Por favor, verifique seus dados de acesso e tente novamente.';
            }
        }else{
            $ERROR = true;
            $ERROR_MSG = 'Por favor, insira seu endereço de e-mail e sua senha.';
        }

        //retorno ------
        $return = array(
                'error'=>$ERROR,
                'error_msg'=>$ERROR_MSG,
                'msg'=>$MSG,
                'dados'=>$INFO,
                'msg_type'=>$MSG_TYPE
            );
		return json_result($return);
		//--------------
	}
);
//POST
$app->post(
    '/rest/userauth_fb',
    function () { 
        $body      = app_body();
        //echo $body->email;
        $ERROR     = false;
        $ERROR_MSG = false;
        $INFO      = array();
        $MSG       = '';
        $MSG_TYPE  = 0;
        //--------------
        $user  = $body->email;
        $tokenFb = $body->tokenFb;
        $facebookId = $body->fb_id;
        $image = $body->foto;
        
        


        $aux = DB_Class::make("system_admin")->_loadAll("","login = '".$user."' OR (facebook_id = '".$facebookId."' AND facebook_id IS NOT NULL AND facebook_id > 0 AND facebook_id <> '')");
        if($aux->size() > 0){
            if(validaTokenFB($facebookId,$tokenFb)){
                if($aux->facebook_id != ''){

                    $aux->facebook_id = $facebookId;
                    $aux->update();
                    $INFO = array(
                        'id'=>$aux->id,
                        'nome'=>$aux->nome,        
                        'perfil'=>$aux->perfil           
                    );
                }else{
                    $INFO = array(
                        'id'=>$aux->id,
                        'nome'=>$aux->nome,        
                        'perfil'=>$aux->perfil           
                    );
                }
            }else{
                $ERROR = true;
                $ERROR_MSG = 'Facebook não autorizado';
            }
        }else{
            $ERROR = true;
            $ERROR_MSG = 'Usuário não encontrado';
        }

        

        //retorno ------
        $return = array(
                'error'=>$ERROR,
                'error_msg'=>$ERROR_MSG,
                'msg'=>$MSG,
                'dados'=>$INFO,
                'msg_type'=>$MSG_TYPE
            );
        return json_result($return);
        //--------------
    }
);
/************************************************************************************************************************
API - USUARIOS
*************************************************************************************************************************/

//MAPEAMENTO
$app->options('/rest/cadastro',$semacao);

//POST
$app->post(
    '/rest/cadastro',
    function () { 
        $body      = app_body();
        $dados = (object)$body->form;
        //echo $body->email;
        $ERROR     = false;
        $ERROR_MSG = false;
        $INFO      = array();
        $MSG       = '';
        $MSG_TYPE  = 0;
        //--------------
      
        $aux = DB_Class::make("system_admin")->_login($dados->email)->_loadAll();

        if($aux->size()>0){
            $ERROR     = true;
            $ERROR_MSG = 'Este endereço de e-mail já esta em uso. Por favor, utilize outro endereço de e-mail.';
        }else{
            
            
            if(trim($dados->email) == ''){
                $ERROR     = true;
                $ERROR_MSG = "Preencha corretamente seu endereçco de e-mail.";
            }else if(trim($dados->nome) == ''){
                $ERROR     = true;
                $ERROR_MSG = "Insira seu nome";
            }else if(trim($dados->senha) == '' && $dados->facebook_id == ""){
                $ERROR     = true;
                $ERROR_MSG = "Insira uma senha.";
            }else{
                if($dados->cidade && $dados->cidade != ''){
                    $cidade = DB_Class::make("cidade")->_id($dados->cidade)->_loadAll();
                    $estado = $cidade->estado;
                }
                $perfil = '';
                if($dados->profissao == 1){
                    $perfil = 2;
                }else if($dados->profissao == 2){
                    $perfil = 3;
                }else if($dados->profissao == 3){
                    $perfil = 4;
                }else if($dados->profissao == 4){
                    $perfil = 5;
                }

                $nomeImagem = '';
                if($dados->imagem != ''){
                    $nomeImagem = md5(date('Y-m-d H:i:s').rand(0,999999)).".png";
                    if(strpos($dados->imagem,'http://') !== false || strpos($dados->imagem,'https://') !== false){
                        $url = str_replace('https://', 'http://',$dados->imagem);
                        $imgFafebook = file_get_contents($url);
                        file_put_contents("images/upload/".$nomeImagem, $imgFafebook);

                    }else{
                        
                        base64_to_jpeg($dados->imagem, "images/upload/".$nomeImagem);
                    }
                }

                $cad = DB_Class::make("system_admin");
                $cad->nome = $dados->nome;
                $cad->email = $dados->email;
                $cad->login = $dados->email;
                $cad->senha = $dados->senha;
                $cad->perfil = $perfil;
                $cad->foto = $nomeImagem;
                $cad->profissao = $dados->profissao;
                $cad->cidade = $dados->cidade;
                $cad->estado = $estado;
                $cad->device = $body->device;
                $cad->telefone = $dados->telefone;
                $cad->whatsapp = $dados->whatsapp;
                $cad->plataforma = $body->platform;
                $cad->facebook_id = $dados->facebook_id?$dados->facebook_id:0;
                $cad->oab = $dados->oab;
                $cad->notificacoes = 1;
                $cad->ativo = 1;
                $cad->created_on = date("Y-m-d H:i:s");
                $info = $cad->save();

                if($info){
                    //$del = DB_Class::make("pessoa_areas_atuacao")->_pessoa($info)->_delete();
                    if($dados->areas != ''){
                        
                        $areas = explode(',', $dados->areas);
                        foreach($areas as $area){
                            if($area != ''){
                                $insere = DB_Class::make("pessoa_areas_atuacao");
                                $insere->area = $area;
                                $insere->pessoa = $info;
                                $insere->save();
                            }
                        }
                    }
                    $INFO = array(
                            'id'=>$info,
                            'nome'=>$dados->nome,
                            'perfil'=>$perfil
                        );
                }else{
                    $ERROR = true;
                    $ERROR_MSG = "Ocorreu um erro, por favor tente novamente em alguns minutos";
                }

            }
        }
        

        //retorno ------
        $return = array(
                'error'=>$ERROR,
                'error_msg'=>$ERROR_MSG,
                'msg'=>$MSG,
                'dados'=>$INFO,
                'msg_type'=>$MSG_TYPE
            );
        return json_result($return);
        //--------------
    }
);


/************************************************************************************************************************
API - EDITAR PERFIL
*************************************************************************************************************************/

//MAPEAMENTO
$app->options('/rest/edita_perfil',$semacao);

//POST
$app->post(
    '/rest/edita_perfil',
    function () { 
        $body      = app_body();
        $dados = (object)$body->form;
        //echo $body->email;
        $ERROR     = 0;
        $ERROR_MSG = false;
        $INFO      = array();
        $MSG       = '';
        $MSG_TYPE  = 0;
        //--------------
        $user   = $body->userid;
        $device = $body->device;

        if($user != '' && validatoken($body) == true){

            $dados = (object)$body->form;
            //var_dump($dados->nome);
            $aux = DB_Class::make("system_admin")->_id($user)->_loadAll();

            if($aux->size()>0){
                
                if(trim($dados->nome) == ''){

                    $ERROR     = true;
                    $ERROR_MSG = "Insira seu nome";

                }else{
                    if($dados->cidade && $dados->cidade != ''){
                        $cidade = DB_Class::make("cidade")->_id($dados->cidade)->_loadAll();
                        $estado = $cidade->estado;
                    }

                    $perfil = '0';
                    if($dados->profissao == 1){
                        $perfil = 2;
                    }else if($dados->profissao == 2){
                        $perfil = 3;
                    }else if($dados->profissao == 3){
                        $perfil = 4;
                    }else if($dados->profissao == 4){
                        $perfil = 5;
                    }

                    $nomeImagem = '';
                    if($dados->imagem != '' && $dados->imagem != $aux->foto){
                        $nomeImagem = md5(date('Y-m-d H:i:s').rand(0,999999)).".png";
                        if(strpos($dados->imagem,'http://') !== false || strpos($dados->imagem,'https://') !== false){
                            
                            $url = str_replace('https://', 'http://',$dados->imagem);
                            $imgFafebook = file_get_contents($url);
                            file_put_contents("images/upload/".$nomeImagem, $imgFafebook);

                        }else{
                            base64_to_jpeg($dados->imagem, "images/upload/".$nomeImagem);
                        }
                    }

                    $cad = DB_Class::make("system_admin")->_id($user)->_loadAll();
                    $cad->nome = $dados->nome;
                    
                    if($dados->senha != '')
                        $cad->senha = $dados->senha;

                    if($dados->site != '')
                        $cad->site = $dados->site;
                    
                    //$cad->perfil = $perfil;
                    if($nomeImagem != '')
                        $cad->foto = $nomeImagem;

                    $cad->profissao = $dados->profissao * 1;

                    $cad->cidade = $dados->cidade * 1;
                    $cad->estado = $estado * 1;
                    
                    $cad->whatsapp = $dados->whatsapp;

                    $cad->telefone = $dados->telefone;
                   
                    $cad->oab = $dados->oab;
                    $cad->edited_on = date("Y-m-d H:i:s");

                    $info = $cad->update();

                    if($info){
                        $del = DB_Class::make("pessoa_areas_atuacao")->_pessoa($aux->id)->_delete();
                        if($dados->areas != ''){
                            
                            $areas = explode(',', $dados->areas);
                            foreach($areas as $area){
                                $insere = DB_Class::make("pessoa_areas_atuacao");
                                $insere->area = $area;
                                $insere->pessoa = $aux->id;
                                $insere->save();
                            }
                        }
                        $INFO = array(
                                'id'=>$aux->id,
                                'nome'=>$dados->nome,
                                'perfil'=>$perfil
                            );
                    }else{
                        $ERROR = true;
                        $ERROR_MSG = "Ocorreu um erro, por favor tente novamente em alguns minutos";
                    }

                }
            }
        }else{
            $ERROR = true;
            $ERROR_MSG = "Ocorreu um erro inesperado. Caso este erro persista, feche o aplicativo e tente novamente.";
        }
        

        //retorno ------
        $return = array(
                'error'=>$ERROR,
                'error_msg'=>$ERROR_MSG,
                'msg'=>$MSG,
                'dados'=>$INFO,
                'msg_type'=>$MSG_TYPE
            );
        return json_result($return);
        //--------------
    }
);





/************************************************************************************************************
API - USUARIOS
************************************************************************************************************/

//MAPEAMENTO
$app->options('/rest/getdados',$semacao);

//POST
$app->post(
    '/rest/getdados',
    function () { 
        $body      = app_body();

        $ERROR     = false;
        $ERROR_MSG = false;
        $INFO      = array();
        $MSG       = '';
        $MSG_TYPE  = 0;
        //--------------

        $user   = $body->userid;
        $device = $body->device;
        $pessoa = $body->pessoa;
      
        

        if($user != '' && validatoken($body) == true){
            $aux = DB_Class::make("system_admin")->_id($pessoa)->_loadAll();
            if($aux->size()>0){
                $arr = array();
                $areas = DB_Class::make("areas_direito")->_loadAll("","id IN(SELECT area from pessoa_areas_atuacao WHERE pessoa = '".$aux->id."')");
                if($areas->size()>0){
                    do{
                        $arr[] = array(
                                'id'=>$areas->id,
                                'nome'=>$areas->nome,
                                'checked'=>true
                            );
                    }while($areas->next());
                }
                $cidade = DB_Class::make("cidade")->_id($aux->cidade)->_loadAll();
                $imagem = '';
                if($aux->foto != '' && is_file("images/upload/".$aux->foto)){
                    $imagem = $aux->foto;    
                }
                $INFO = array(
                        'id'=>$aux->id,
                        'nome'=>$aux->nome,
                        'email'=>$aux->email,
                        'imagem'=>$imagem,
                        'oab'=>$aux->oab,
                        'perfil'=>$aux->perfil,
                        'profissao'=>$aux->profissao.'',
                        'profissional'=>($aux->profissao == 1 || $aux->profissao == 2 || $aux->profissao == 3?true:false),
                        'areas'=>$arr,
                        'cidade'=>$cidade->id,
                        'estado'=>$aux->estado,
                        'cidade_nome'=>$cidade->nome,
                        'estado_nome'=>$cidade->uf,
                        'notificacoes'=>$aux->notificacoes,
                        'facebook'=>($aux->facebook_id != '' && $aux->facebook_id > 0?true:false),
                        'telefone'=>$aux->telefone,
                        'whatsapp'=>$aux->whatsapp,
                        'nota'=>getNota($aux->id),
                        'site'=>$aux->site != ''?$aux->site:''
                    );

            }
            $ERROR_MSG = "nao sei";

        }else{
            $ERROR_MSG = "Token incorreto";
        }

        //retorno ------
        $return = array(
                'error'=>$ERROR,
                'error_msg'=>$ERROR_MSG,
                'msg'=>$MSG,
                'dados'=>$INFO,
                'msg_type'=>$MSG_TYPE
            );
        return json_result($return);
        //--------------
    }
);


/************************************************************************************************************
API - USUARIOS
************************************************************************************************************/

//MAPEAMENTO
$app->options('/rest/recupera_senha',$semacao);

//POST
$app->post(
    '/rest/recupera_senha',
    function () { 
        $body      = app_body();

        $ERROR     = false;
        $ERROR_MSG = false;
        $INFO      = array();
        $MSG       = '';
        $MSG_TYPE  = 0;
        //--------------

        $email  = $body->email;

      
        

        if(validatoken($body) == true){
            
            $aux = DB_Class::make("system_admin")->_login($email)->_loadAll();
            if($aux->size()){

                $nsenha = substr(md5(rand(0,99999)),5,6);
                $aux->senha = encriptPassSystem(addslashes($nsenha));
                $aux->update();

                $msg = "Você solicitou a recuperação de sua senha.<br />";
                $msg .= "Essas é sua nova senha:<b>".$nsenha."</b><br />";
                $msg .= "Após efetuar o login, você poderá alterar sua senha novamente.";

                $html = file_get_contents('email/contato.htm');
             
                $html = str_replace('[titulo]', 'Recuperação de senha', $html);
                $html = str_replace('[mensagem]', $msg, $html);

                sendMail($aux->email,('Recuperando sua senha'),($html));
                
            }else{
                $ERROR = true;
                $ERROR_MSG = "Usuário não encontrado.";
            }

        }else{
            $ERROR_MSG = "Token incorreto";
        }

        //retorno ------
        $return = array(
                'error'=>$ERROR,
                'error_msg'=>$ERROR_MSG,
                'msg'=>$MSG,
                'dados'=>$INFO,
                'msg_type'=>$MSG_TYPE
            );
        return json_result($return);
        //--------------
    }
);
//--------------------------------------------------------
//--------------------------------------------------------
/*
$app->post(
    '/rest/assinatura',
    function () { 
        $body      = app_body();

        $ERROR     = false;
        $ERROR_MSG = false;
        $INFO      = array();
        $MSG       = '';
        $MSG_TYPE  = 0;
        //--------------

        $user   = $body->userid;
        $device = $body->device;
        
      
        

        if($user != '' && validatoken($body) == true){
            $aux = DB_Class::make("system_admin")->_id($pessoa)->_loadAll();
            if($aux->size()>0){
                
                if(substr($aux->tria,0,10) >= date("Y-m-d")){
                    $INFO = array(
                        'trial' => true,
                        'fim_trial' => substr($aux->tria,0,10)
                        );
                }   
            }

        }else{
            $ERROR_MSG = "Token incorreto";
        }

        //retorno ------
        $return = array(
                'error'=>$ERROR,
                'error_msg'=>$ERROR_MSG,
                'msg'=>$MSG,
                'dados'=>$INFO,
                'msg_type'=>$MSG_TYPE
            );
        return json_result($return);
        //--------------
    }
);
*/

/************************************************************************************************************************
API - USUARIOS
*************************************************************************************************************************/


//MAPEAMENTO
$app->options('/rest/users(/:id)',$semacao);


$app->get(
    '/rest/users/:id',
    function ($id) {  


       // $no = DB_Class::open("pessoa",$id, "pes_id")->asJson();
        
		return json_result($no[0]);
    }
);


$app->get(
    '/rest/users',
    function () {


       // $result = DB_Class::make("pessoa")->_loadAll()->asJson();
        return json_result(array('data'=>$result)); 
    }
);


$app->put(
    '/rest/users/:id',
    function ($id) {
		$body = app_body();
		/*
		$user = DB_Class::open("pessoa",$id,'pes_id');
		if($user->size()==0) return json_result(array('error'=>1, 'errorMsg'=>'NOT FOUND')); 

		$user->pes_nome = $body->nome;
		$user->update();			
*/
        return json_result(array('error'=>0)); 
    }
);



$app->delete(
    '/rest/users/:id',
    function ($id) {
		/*
		$user = DB_Class::open("pessoa",$id,'pes_id');
		if($user->size()==0) return json_result(array('error'=>1, 'errorMsg'=>'NOT FOUND')); 

		$user->delete();			
*/
        return json_result(array('error'=>0)); 
    }
);

