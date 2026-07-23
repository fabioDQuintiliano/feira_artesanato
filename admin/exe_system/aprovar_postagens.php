<!--[CONTAINER-padrao-simples]-->
<script src="https://cdn.jsdelivr.net/npm/vue@3.5.13/dist/vue.global.js"></script>



<script src="<?php echo ROOT; ?>script/vue3-bridge.js"></script>



<?php
$dados = array();
$dados['lista'] = array();
$auxTotal = DAO::Postagem()
		->_where("(tem_valor = 1 OR EXISTS(SELECT 1 FROM system_admin WHERE system_admin.id = postagem.pessoa AND system_admin.permitir_sem_valor = 1)) AND IFNULL(inativo,0) = 0 AND exibir = 0")
		->_loadAll();		

$dados['total'] = $auxTotal->size();

$aux = DAO::Postagem()
		->_where("(tem_valor = 1 OR EXISTS(SELECT 1 FROM system_admin WHERE system_admin.id = postagem.pessoa AND system_admin.permitir_sem_valor = 1)) AND IFNULL(inativo,0) = 0 AND exibir = 0")
		->_loadAll("id LIMIT 50");
if($aux->size()){


	do{
		$daoLoja = DAO::System_admin()->_id($aux->pessoa)->_loadAll();
		$dados['lista'][] = array(
			'id' => $aux->id,
			'instagram_code' => $aux->instagram_code,
			'aprovado' => 0,
			'descricao' => nl2br($aux->descricao),
			'imagem' => ROOT.'images/upload/thumb_'.$aux->imagem,
			'data' => banco2date($aux->created_on),
			'loja' => array(
				'id'=> $daoLoja->id,
				'nome' => $daoLoja->nome,
				'imagem' => ROOT.'images/upload/'.$daoLoja->foto,
			),
		);
	}while($aux->next());
}

$dados = json_encode($dados);



?>
<div id="publicacoes_aprovar">

	<div class="botoes-acao" v-if="dados.lista && dados.lista.length">
		<button type="button" class="btn btn-sm btn-info" @click="selecionarTodos">Selecionar todos</button>
		<button type="button" class="btn btn-sm btn-primary" @click="acao()">Salvar</button>

	</div>
	<div class="container">
		<br />
		<h5 v-if="!dados.lista.length">Nenhuma publicação aguardando liberação</h5>
		<h5 v-if="dados.lista.length">Total dessa lista: {{dados.lista.length}}</h5>
		<h6 v-if="dados.lista.length">Total de publicações aguardando liberação: {{dados.total}}</h6>
		<br />
		<br />
		

		<div class="row item-postagem" v-for="item in dados.lista">
    		<div class="col-sm " @click="selecionarUm(item)">

    			<div class="alert d-flex flex-row " v-bind:class="{'alert-success':item.aprovado,'alert-light ':!item.aprovado}">
    				<div><img v-bind:src="item.imagem" class="rounded imagem-postagem"></div>

    				<div>
    					
    					<p v-html="item.descricao" class="descricao-postagem"></p>
    					<div class="d-flex flex-row linha-loja">
    						<img style="width: 30px;" v-bind:src="item.loja.imagem" alt="Avatar" class="rounded-circle">
    						&nbsp;	<b> {{item.loja.nome}}</b>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>


	</div>
</div>
<script>
var app = new Vue({
    el: '#publicacoes_aprovar',
    
    data: {

        dados: <?=$dados?>
       
    },
    mounted: function () {

    },
    methods:{
    	selecionarTodos(){
    		let instagram_codes = [];
    		this.dados.lista.map((obj)=>{
    			console.log(obj.instagram_code)
    			let tem = instagram_codes.filter((obj_item)=>{ 
    			//	console.log(obj_item.instagram_code,obj.instagram_code);
    				if (obj_item.instagram_code == obj.instagram_code){
    					return true;
    				}else{
    					return false;
    				}
    			});
    			//console.log(tem);
    			if(tem.length){
    				obj.aprovado = 0;
    			}else{
    				instagram_codes.push(obj);
    				obj.aprovado = 1;
    			}
    		})

    	},
    	selecionarUm(obj){
    		obj.aprovado = obj.aprovado?0:1;
    	},
       
        acao: async function(){

        	try{
	        	await confirma('Deseja aprovar as publicações marcadas? As publicações não marcadas serão reprovadas automaticamente.')
        		loadShow()
	          	let obj = this.dados.lista;
	        	let info = await loadFuncao('acao_publicacoes',{p1:obj});
	        	console.log(info)
	        	loadHide();
	        	if(info){

	        		document.location.reload(true);
	        	}

        	}catch(e){

	        	loadHide();
        		console.log('nao deu')
        	}
        }	
       
    }
})
</script>

<style>
	.imagem-postagem{width:100px;}
	.descricao-postagem{line-height: 14px; margin-left: 10px;}
	.item-postagem{cursor: pointer;  }

	.botoes-acao{

		 
		  position: fixed;
		  bottom: 0px;
		  left: 0px;
		  z-index: 9;
		  width: 100%;
		  background-color: #fff;
		  padding:5px;
		  text-align: center;
		  box-shadow: 0px 0px 6px -1px;

	}
	.linha-loja{
		margin: 10px;
		align-items: center;

	}

</style>