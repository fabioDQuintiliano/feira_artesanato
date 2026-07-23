<!--[CONTAINER-padrao-simples]-->
<script src="https://cdn.jsdelivr.net/npm/vue@3.5.13/dist/vue.global.js"></script>

<script src="<?php echo ROOT; ?>script/vue3-bridge.js"></script>

<?php
$lista = array();
$daoLojas = DAO::System_admin()
			->_perfil(2)
			->_where("instagram_id <> ''")
			->_ativo(1)
			//->_where("id IN(21)")
			->_loadAll("last_sinc ASC LIMIT 5");
if($daoLojas->size()){
	do{
		$lista[] = array(
				'id'=>$daoLojas->id,
				'instagram'=>$daoLojas->instagram_id,
				'nome'=>$daoLojas->nome
			);
	}while($daoLojas->next());
}

?>



<div id="publicacoes_aprovar">

	<div class="botoes-acao" >
		
		<button type="button" class="btn btn-sm btn-primary" @click="acao()">Sincronizar</button>

	</div>
	
</div>
<script>
var app = new Vue({
    el: '#publicacoes_aprovar',
    
    data: {

        lista: <?=json_encode($lista)?>
       
    },
    mounted: function () {
    	setTimeout(()=>{
    	
    		console.log(this.lista);
    		//this.acao();
    	},1000)
    },
    methods:{
    	buscaImagens(item){

    		return new Promise((resolve,reject)=>{

    			let instagram = item.instagram;
    			let url = 'https://instagram.com/graphql/query/?query_id=17888483320059182&variables={"id":"'+instagram+'","first":20,"after":null}';
    			console.log(url);
    			$.get(url,(o)=>{
    				console.log(o)
    			})
    			/*$.ajax({
				    url: url,
				    type: "GET",
				    dataType: "json",
				    success: function (data) {
				        console.log(data);
				    }
				});*/

    		});

    	},
    	
        acao: async function(){

        	this.buscaImagens(this.lista[0]);
        },
        get(){

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